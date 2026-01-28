<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Support\Facades\Storage;

class ArticleObserver
{

    public function saved(Article $article): void
    {
        // 1. Handle Cover Image (news_tbl)
        if ($article->isDirty('image') && $article->image) {
            $this->processImage($article, 'image', 'thumbnail_image');

            // Sync Cover to Gallery (Legacy Requirement: Cover is also in gallery with coverpage=1)
            // Check if we already have a cover entry
            $galleryEntry = ArticleImage::where('news_id', $article->news_id)
                ->where('coverpage', '1')
                ->first();

            if (!$galleryEntry) {
                $galleryEntry = new ArticleImage();
                $galleryEntry->news_id = $article->news_id;
                $galleryEntry->coverpage = '1';
                $galleryEntry->active = '1';
            }

            $galleryEntry->image_name = $article->image; // These will be updated with correct paths by the processor logic if needed
            $galleryEntry->thumb_name = $article->thumbnail_image;
            $galleryEntry->save();
        }

        // 2. Handle Gallery Images (The Repeater items)
        // Since the repeater saves related models, we might need a separate observer for ArticleImage
        // OR we just rely on the fact that Filament saves them to the temp folder, 
        // and we can fix them in the ArticleImageObserver (See Step 4b below).
    }

    /**
     * Helper to Move File to ID folder and Create Thumbnail
     */
    private function processImage($model, $imageCol, $thumbCol)
    {
        $originalPath = $model->$imageCol; // e.g., uploads/news/temp/abc.jpg

        // If it's already in the correct folder, just ensure we store only the filename
        if (strpos($originalPath, "uploads/news/{$model->news_id}/") !== false) {
            // Extract just the filename for DB storage (legacy format)
            $fileName = basename($originalPath);
            if ($model->$imageCol !== $fileName) {
                $model->$imageCol = $fileName;
                $model->saveQuietly();
            }
            return;
        }

        // If it's just a filename (no path), it's already processed, skip
        if (!str_contains($originalPath, '/')) {
            return;
        }

        $disk = Storage::disk('public');

        // Define new paths
        $fileName = basename($originalPath);
        $newDir = "uploads/news/{$model->news_id}/";
        $newPath = $newDir . $fileName;
        $thumbDir = $newDir . "thumb/";
        $thumbName = pathinfo($fileName, PATHINFO_FILENAME) . "_thumb.jpg";
        $thumbPath = $thumbDir . $thumbName;

        // Create directories
        if (!$disk->exists($newDir)) $disk->makeDirectory($newDir);
        if (!$disk->exists($thumbDir)) $disk->makeDirectory($thumbDir);

        // Move the main file
        if ($disk->exists($originalPath)) {
            $disk->move($originalPath, $newPath);

            // Update Model to point to new path (or just filename if legacy expects that)
            // Legacy seems to store just the filename in DB? 
            // Based on save.php: $finalName = build_target_name... $database->insert(..., $finalName)
            // So DB stores: "news_img_2023...jpg" (Just the name)

            // We update the model with JUST the filename to match legacy
            $model->$imageCol = $fileName;
        }

        // Generate Thumbnail (Using GD, same as legacy)
        $fullPath = $disk->path($newPath);
        $fullThumbPath = $disk->path($thumbPath);

        $this->createThumbnail($fullPath, $fullThumbPath, 150, 150);

        // Update Thumb Column
        if ($thumbCol) {
            $model->$thumbCol = $thumbName;
        }

        // Save quietly to avoid infinite loops
        $model->saveQuietly();
    }

    private function createThumbnail($src, $dest, $w, $h)
    {
        $info = getimagesize($src);
        if (!$info) return;

        $mime = $info['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg($src);
                break;
            case 'image/png':
                $img = imagecreatefrompng($src);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($src);
                break;
            default:
                return;
        }

        $width = imagesx($img);
        $height = imagesy($img);
        $tmp = imagecreatetruecolor($w, $h);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $w, $h, $width, $height);
        imagejpeg($tmp, $dest, 85);
        imagedestroy($img);
        imagedestroy($tmp);
    }

    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }
}
