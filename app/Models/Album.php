<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Album extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = ['categories', 'tags'];

    protected $perPage = 9;

    public function getRouteKeyName() {
        return 'slug';
    }

    public function getSlugOptions() : SlugOptions {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function categories() {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function photos() {
        return $this->hasMany(Photo::class);
    }

}
