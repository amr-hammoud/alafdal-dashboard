<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // 1. LEGACY MAPPING
    protected $table = 'categories';
    // Primary key is standard 'id', so no need to define it
    public $timestamps = false;

    protected $fillable = [
        'name',
        'active',
        'parent_id',
        'is_parent',
        'addBy',
        'addDate'
    ];

    protected $casts = [
        'active' => 'boolean', // Maps enum('active', 'inactive') or similar
        'is_parent' => 'boolean',
    ];

    // 2. RELATIONSHIPS

    // A category belongs to a parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // A category has many child categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // A category has many articles (mapped via 'id_cat')
    public function articles()
    {
        return $this->hasMany(Article::class, 'id_cat', 'id');
    }
}
