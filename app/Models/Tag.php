<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tag extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];

    public function scopePopular($query)
    {
        return $query->withCount('photos')->orderByDesc('photos_count')->take(5)->get();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function albums()
    {
        return $this->morphedByMany(Album::class, 'taggable');
    }

    public function photos()
    {
        return $this->morphedByMany(Photo::class, 'taggable');
    }
}
