<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    User,
    Photo,
};

use App\Services\PhotoService;

use Cache;

class UserController extends Controller
{
    public function photos(User $user)
    {
        $user->load('albums');

        $sort = request()->query('sort', null);

        $query = Photo::query()->whereIn('album_id', $user->albums->pluck('id'))
            ->with('album.user.photos');

        $currentPage = http_build_query(request()->query());

        $photos = Cache::rememberForever('photos_user_' . $user->id . '_' . $currentPage, fn () => (new PhotoService())->getAll($query, $sort));

        $data = [
            'title' => 'Les photos de ' . $user->name,
            'description' => '',
            'heading' => 'Photos de ' . $user->name,
            'photos' => $photos,
        ];
        return view('user.photos', $data);
    }
}
