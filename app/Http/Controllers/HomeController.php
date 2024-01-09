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

        $photos = Cache::rememberForever('photos'.$currentPage, function() {
            return Photo::with('album.user')->orderByDesc('created_at')->paginate();
        });

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
