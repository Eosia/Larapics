<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    Photo,
    Tag
};

use App\Services\PhotoService;

use Cache;

class TagController extends Controller
{
    //
    public function photos(Tag $tag)
    {
        $tag->load('photos');
        $sort = request()->query('sort', null);
        $query = Photo::query()->whereHas('tags', fn ($query) => $query->where('slug', $tag->slug))
            ->orWhereHas('album.tags', fn ($query) => $query->where('slug', $tag->slug))
            ->with('album.user.photos');

        $currentPage = http_build_query(request()->query());

        $photos = Cache::rememberForever('photos_tag_' . $tag->id . '_' . $currentPage, fn () => (new PhotoService())->getAll($query, $sort));

        $data = [
            'title' => 'Les photos avec le tag ' . $tag->name . ' - ' . config('app.name'),
            'description' => $tag->photos->count() . ' photo(s) avec le tag ' . $tag->name,
            'heading' => 'Photos taggées ' . $tag->name,
            'photos' => $photos,
        ];
        return view('tag.photos', $data);
    }
}
