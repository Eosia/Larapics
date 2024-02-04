<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Photo,
    Album,
};
use Cache;
use App\Services\PhotoService; 

class HomeController extends Controller
{

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //
        $sort = request()->query('sort', null);
        $query = Photo::query()->with('album.user.photos');

        $currentPage = http_build_query(request()->query());

        $photos = Cache::rememberForever('photos_'.$currentPage, fn() => (new PhotoService())->getAll($query, $sort));

        // $photos = Cache::rememberForever('photos'.$currentPage, function() {
        //     return Photo::with('album.user')->orderByDesc('created_at')->paginate();
        // });

        // dd($photos);

        $data = [
            'title' => 'Photos libres de droit' . config('app.name'),
            'description' => '',
            'heading' => config('app.name'),
            'photos' => $photos,
        ];

        return view('home.index', $data);

    }
}
