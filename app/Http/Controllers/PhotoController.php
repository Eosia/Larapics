<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PhotoRequest;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


use App\Models\{
    Album,
    Photo,
    User,
    Source,
    Tag,
};

use DB, Storage, Str, Mail;
use Dotenv\Exception\ValidationException;

$manager = new ImageManager(new Driver());
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

    public function store(PhotoRequest $request, Album $album) {
        abort_if($album->user_id !== auth()->id(), 403);
        DB::beginTransaction();
        //$manager = new ImageManager(new Driver());

        try {
            $photo = $album->photos()->create($request->validated());

            $tags = explode(',', $request->tags);

            $tags = collect($tags)->filter(function($value, $key){
                return $value != ' ';
            })->all();

            foreach($tags as $t){
                $tag = Tag::firstOrCreate(['name' => trim($t)]);
                $photo->tags()->attach($tag->id);
            }
            if ($request->file('photo')->isValid()) {
                $ext = $request->file('photo')->extension();
                $filename = Str::uuid() . '.' . $ext;

                $originalPath = $request->file('photo')->storeAs('photos/' . $photo->album_id, $filename);
                $originalWidth = (int) \Image::read($request->file('photo'))->width();
                $originalHeight = (int) \Image::read($request->file('photo'))->height();
                

                $originalSource = $photo->sources()->create([
                    'path' => $originalPath,
                    'url' => Storage::url($originalPath),
                    'size' => Storage::size($originalPath),
                    'width' => $originalWidth,
                    'height' => $originalHeight,
                ]);
                
            }

        } 
        catch(ValidationException $e) {
            DB::rollBack();
            dd($e->getErrors());
        }

        DB::commit();

        $success = 'Photo ajoutée';
        $redirect = route('photos.create', [$album->slug]);

        return redirect($redirect)->withSuccess($success);
    }

}