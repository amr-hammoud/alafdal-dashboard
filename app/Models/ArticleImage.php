<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    protected $table = 'news_gallery';
    protected $primaryKey = 'gallery_id';
    public $timestamps = false;

    protected $fillable = [
        'news_id',
        'image_name', // Stores "news_img_2023...jpg"
        'thumb_name', // Stores "news_img_2023..._thumb.jpg"
        'coverpage',  // 1 if it's the cover, 0 otherwise
        'active',
    ];

    // Relationship back to Article
    public function article()
    {
        return $this->belongsTo(Article::class, 'news_id');
    }
}
