<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Article extends Model
{
    // --- 1. LEGACY CONFIGURATION ---
    protected $table = 'news_tbl';
    protected $primaryKey = 'news_id';
    public $timestamps = false; // Legacy table doesn't use standard timestamps

    // Fields we are allowed to modify
    protected $fillable = [
        'news_title',
        'news_desc',
        'active',
        'news_date',
        'id_cat',
        'important',
        'notification',
        'show_slider',
        'news_time',
        'addBy',
        'updateBy',
        'addDate',
        'updateDate',
        'views',
        'youtube_url',
        'voiceover_url',
        'author',
        'thumbnail_image',
        'image',
        'embedding',
        'user_id',
    ];

    // --- 2. DATA CASTING (Fixing Types) ---
    protected $casts = [
        'active' => 'boolean',     // Converts enum('0', '1') to true/false
        'important' => 'boolean',
        'views' => 'integer',      // Fixes the legacy varchar issue
        'notification' => 'boolean',
        'show_slider' => 'boolean',
        // 'news_date' => 'date',
        // 'addDate' => 'date',
        // 'updateDate' => 'date',
    ];

    // --- 3. ACCESSORS & MUTATORS (The "Clean Code" Layer) ---

    // Title: Maps 'title' <-> 'news_title'
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['news_title'],
            set: fn($value) => ['news_title' => $value],
        );
    }

    // Content: Maps 'content' <-> 'news_desc'
    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['news_desc'],
            set: fn($value) => ['news_desc' => $value],
        );
    }

    // Category ID: Maps 'category_id' <-> 'id_cat'
    protected function categoryId(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['id_cat'],
            set: fn($value) => ['id_cat' => $value],
        );
    }

    // --- 4. RELATIONSHIPS ---
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_cat', 'id');
    }

    // Relationship to the Gallery Table
    public function images()
    {
        return $this->hasMany(ArticleImage::class, 'news_id');
    }
}
