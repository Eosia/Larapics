<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PhotoRequest;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\{ Album, Photo, User, Source, Tag };
use DB, Storage, Str, Mail;
use Dotenv\Exception\ValidationException;

class PhotoController extends Controller
{
    // Ajout de photo à un album
    public function create(Album $album) {
        abort_if($album->user_id !== auth()->id(), 403);

        $data = [
            'title' => $description = 'Ajouter des photos à ' . $album->title,
            'description' => $description,
            'album' => $album,
            'heading' => $album->title,
        ];
        return view('photo.create', $data);
    }

    public function store(PhotoRequest $request, Album $album) {
        abort_if($album->user_id !== auth()->id(), 403);
        DB::beginTransaction();

        try {
            $photo = $album->photos()->create($request->validated());

            $tags = explode(',', $request->tags);
            $tags = collect($tags)->filter(function($value, $key) {
                return $value != ' ';
            })->all();

            foreach ($tags as $t) {
                $tag = Tag::firstOrCreate(['name' => trim($t)]);
                $photo->tags()->attach($tag->id);
            }

            if ($request->file('photo')->isValid()) {
                $ext = $request->file('photo')->extension();
                $filename = Str::uuid() . '.' . $ext;

                $originalPath = $request->file('photo')->storeAs('photos/' . $album->album_id, $filename);
                $manager = new ImageManager(new Driver());
                $image = $manager->read($request->file('photo')->getRealPath());
                $originalWidth = (int) $image->width();
                $originalHeight = (int) $image->height();

                $originalSource = $photo->sources()->create([
                    'path' => $originalPath,
                    'url' => Storage::url($originalPath),
                    'size' => Storage::size($originalPath),
                    'width' => $originalWidth,
                    'height' => $originalHeight,
                ]);

                $thumbnailImage = $image->resize(350, 233, function($constraint) {
                    $constraint->aspectRatioCrop();
                    $constraint->upsize();
                });

                // Choisissez la méthode d'encodage en fonction de l'extension
                $encodedThumbnail = match ($ext) {
                    'png' => $thumbnailImage->toPng(),
                    'gif' => $thumbnailImage->toGif(),
                    'bmp' => $thumbnailImage->toBitmap(),
                    'webp' => $thumbnailImage->toWebp(),
                    'avif' => $thumbnailImage->toAvif(),
                    default => $thumbnailImage->toJpeg(),
                };

                $thumbnailPath = 'photos/'.$album->album_id.'/thumbnails/'.$filename;
                Storage::put($thumbnailPath, (string) $encodedThumbnail);

                $photo->thumbnail_path = $thumbnailPath;
                $photo->thumbnail_url = Storage::url($thumbnailPath);
                $photo->save();
            }
        } catch(ValidationException $e) {
            DB::rollBack();
            dd($e->getErrors());
        }

        DB::commit();

        $success = 'Photo ajoutée';
        $redirect = route('photos.create', [$album->slug]);

        return redirect($redirect)->withSuccess($success);
    }
}
