<?php

namespace App\Observers;

use App\Models\ArticleImage;
use Illuminate\Support\Facades\Storage;

class ArticleImageObserver
{

    public function saved(ArticleImage $gallery): void
    {
        if ($gallery->isDirty('image_name') && $gallery->image_name) {
            // Re-use logic: Move to {id} folder and make thumb
            $this->processGalleryImage($gallery);
        }
    }

    private function processGalleryImage($model)
    {
        $originalPath = $model->image_name;

        // Safety: Ensure we have a parent news_id
        if (!$model->news_id || strpos($originalPath, "uploads/news/{$model->news_id}/") !== false) {
            return;
        }

        $disk = Storage::disk('public');
        $fileName = basename($originalPath);

        // Destination: uploads/news/{id}/
        $newDir = "uploads/news/{$model->news_id}/";
        $newPath = $newDir . $fileName;

        // Thumb: uploads/news/{id}/thumb/
        $thumbDir = $newDir . "thumb/";
        $thumbName = pathinfo($fileName, PATHINFO_FILENAME) . "_thumb.jpg";
        $thumbPath = $thumbDir . $thumbName;

        if (!$disk->exists($newDir)) $disk->makeDirectory($newDir);
        if (!$disk->exists($thumbDir)) $disk->makeDirectory($thumbDir);

        if ($disk->exists($originalPath)) {
            $disk->move($originalPath, $newPath);

            // Save JUST the filename to DB (Legacy format)
            $model->image_name = $fileName;
        }

        // Generate Thumb
        $this->createThumbnail($disk->path($newPath), $disk->path($thumbPath));

        $model->thumb_name = $thumbName;
        $model->saveQuietly();
    }

    private function createThumbnail($src, $dest)
    {
        // Simple GD Thumbnailer (150x150)
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
    }

    /**
     * Handle the ArticleImage "created" event.
     */
    public function created(ArticleImage $articleImage): void
    {
        //
    }

    /**
     * Handle the ArticleImage "updated" event.
     */
    public function updated(ArticleImage $articleImage): void
    {
        //
    }

    /**
     * Handle the ArticleImage "deleted" event.
     */
    public function deleted(ArticleImage $articleImage): void
    {
        //
    }

    /**
     * Handle the ArticleImage "restored" event.
     */
    public function restored(ArticleImage $articleImage): void
    {
        //
    }

    /**
     * Handle the ArticleImage "force deleted" event.
     */
    public function forceDeleted(ArticleImage $articleImage): void
    {
        //
    }
}
