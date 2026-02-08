<?php

namespace App\Observers;

use App\Models\ArticleImage;
use Illuminate\Support\Facades\Storage;

class ArticleImageObserver
{

    public function saved(ArticleImage $gallery): void
    {
        // Get the raw image_name value from attributes (bypasses accessor)
        $rawImageName = $gallery->getAttributes()['image_name'] ?? null;
        
        if ($gallery->isDirty('image_name') && $rawImageName) {
            $this->processGalleryImage($gallery, $rawImageName);
        }
    }

    /**
     * Process gallery image: Move from temp to article folder and create thumbnail
     */
    private function processGalleryImage(ArticleImage $gallery, string $imagePath): void
    {
        $disk = Storage::disk('public');
        
        // Safety: Ensure we have a parent news_id
        if (!$gallery->news_id) {
            return;
        }
        
        // If it's already in the correct folder, just store filename
        if (str_contains($imagePath, "uploads/news/{$gallery->news_id}/")) {
            $fileName = basename($imagePath);
            $gallery->setRawAttributes(array_merge(
                $gallery->getAttributes(),
                ['image_name' => $fileName]
            ));
            $gallery->saveQuietly();
            return;
        }

        // If it's just a filename (no path), it's already processed
        if (!str_contains($imagePath, '/')) {
            return;
        }

        // It's a temp file, need to move it
        $fileName = basename($imagePath);
        $newDir = "uploads/news/{$gallery->news_id}/";
        $newPath = $newDir . $fileName;
        $thumbDir = $newDir . "thumb/";
        $thumbName = pathinfo($fileName, PATHINFO_FILENAME) . "_thumb.jpg";
        $thumbPath = $thumbDir . $thumbName;

        // Create directories
        if (!$disk->exists($newDir)) $disk->makeDirectory($newDir);
        if (!$disk->exists($thumbDir)) $disk->makeDirectory($thumbDir);

        // Move the main file
        if ($disk->exists($imagePath)) {
            $disk->move($imagePath, $newPath);

            // Generate thumbnail
            $this->createThumbnail($disk->path($newPath), $disk->path($thumbPath));

            // Update model with just the filename (legacy format)
            $gallery->setRawAttributes(array_merge(
                $gallery->getAttributes(),
                [
                    'image_name' => $fileName,
                    'thumb_name' => $thumbName,
                ]
            ));
            $gallery->saveQuietly();
        }
    }

    private function createThumbnail($src, $dest)
    {
        $info = @getimagesize($src);
        if (!$info) return;

        $img = match ($info['mime']) {
            'image/jpeg' => imagecreatefromjpeg($src),
            'image/png' => imagecreatefrompng($src),
            default => null,
        };
        if (!$img) return;

        $tmp = imagecreatetruecolor(150, 150);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, 150, 150, imagesx($img), imagesy($img));
        imagejpeg($tmp, $dest, 85);
        imagedestroy($img);
        imagedestroy($tmp);
    }
}
