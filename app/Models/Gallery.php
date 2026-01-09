<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    // 1. LEGACY MAPPING
    protected $table = 'news_gallery';
    protected $primaryKey = 'gallery_id'; // Non-standard Key
    public $timestamps = false;

    protected $fillable = [
        'news_id',
        'image_name',
        'thumb_name',
        'active',
        'coverpage'
    ];

    protected $casts = [
        'active' => 'boolean',
        'coverpage' => 'boolean',
    ];

    // 2. RELATIONSHIPS
    public function article()
    {
        return $this->belongsTo(Article::class, 'news_id', 'news_id');
    }
}
