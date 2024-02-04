<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Photo,
    Album,
};
use Cache;

class HomeController extends Controller
{

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //

        $currentPage = request()->query('page', 1);

        $sort = request()->query('sort', null);

        switch ($sort) {
            case 'newest' :
                $photos = Photo::with('album.user.photos')->orderByDesc('created_at')->paginate();
                break;
            case 'oldest' :
                $photos = Photo::with('album.user.photos')->orderBy('created_at')->paginate();
                break;

            default:
                $photos = Photo::with('album.user.photos')->orderByDesc('created_at')->paginate();
                break;

        }

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
