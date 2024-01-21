<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Album,
    Photo,
};

class PhotoController extends Controller
{
    // ajout de photo à un album
    public function create(Album $album) {
        abort_if($album->user_id !== auth()->id(), 403);

        $data = [
            'title' =>  $description = 'Ajouter des photos à ' . $album->title,
            'description' => $description,
            'album' => $album,
            'heading' => $album->title,
        ];
        return view('photo.create', $data);

    }

    public function store(Album $album) {

    }

}
