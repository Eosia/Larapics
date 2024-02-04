<?php

namespace App\Http\Controllers;

use App\Models\{
    Album,
    Category,
    Tag,
    Photo
};
use Illuminate\Http\Request;
use App\Http\Requests\AlbumRequest;
use DB, Auth, Storage, Cache;
use Dotenv\Exception\ValidationException;
use App\Services\PhotoService;


class AlbumController extends Controller
{

    public function __construc()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Liste des albums de l'user connecté
        $albums = auth()->user()->albums()->with('photos', fn ($query) =>
        $query->withoutGlobalScope('active')->orderByDesc('created_at'))
            ->orderByDesc('updated_at')->paginate();

        $data = [
            'title' => $description = 'Mes albums',
            'description' => $description,
            'albums' => $albums,
            'heading' => $description,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = $description = $heading =  'Ajouter un nouvel album - ' . config('app.name');
        return view('album.create', compact('title', 'description', 'heading'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlbumRequest $request)
    {
        //
        DB::beginTransaction();

        try {
            $album = Auth::user()->albums()->create($request->validated());

            $categories = explode(',', $request->categories);

            $categories = collect($categories)->filter(function ($value, $key) {
                return $value != ' ';
            })->all();

            foreach ($categories as $cat) {
                $category = Category::firstOrCreate(['name' => trim($cat)]);
                $album->categories()->attach($category->id);
            }

            $tags = explode(',', $request->tags);

            $tags = collect($tags)->filter(function ($value, $key) {
                return $value != ' ';
            })->all();

            foreach ($tags as $t) {
                $tag = Tag::firstOrCreate(['name' => trim($t)]);
                $album->tags()->attach($tag->id);
            }

            //dd($categories);
        } catch (ValidationException $e) {
            DB::rollBack();
            dd($e->getErrors());
        }

        DB::commit();

        $success = 'Album ajouté.';
        $redirect  = route('photos.create', [$album->slug]);
        return $request->ajax()
            ? response()->json(['success' => $success, 'redirect' => $redirect])
            : redirect($redirect)->withSuccess($success);
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        //
        $album->load('photos');
        $sort = request()->query('sort', null);
        $query = Photo::query()->whereAlbumId($album->id)->with('album.user.photos');

        $currentPage = http_build_query(request()->query()); {
            //
        }
        $photos = Cache::rememberForever('photos_album_' . $album->id . '_' . $currentPage, fn () => (new PhotoService())->getAll($query, $sort));

        $data = [
            'title' => $album->title . ' - ' . config('app.name'),
            'description' => $album->photos->count() . ' photo(s) dans l\'album ' . $album->title,
            'heading' => $album->title,
            'photos' => $photos,
        ];
        return view('album.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album)
    {
        abort_if($album->user_id !== auth()->id(), 403);
        $album->load('categories:name,slug', 'tags:name,slug');
        $categories = $album->categories->implode('name', ', ');
        $tags = $album->tags->implode('name', ', ');

        $data = [
            'title' => $description = 'Editer ' . $album->title,
            'description' => $description,
            'album' => $album,
            'heading' => 'Mettre à jour ' . $album->title,
            'categories' => $categories,
            'tags' => $tags,
        ];
        return view('album.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlbumRequest $request, Album $album)
    {
        abort_if($album->user_id !== auth()->id(), 403);

        DB::beginTransaction();
        try {
            $album = Auth::user()->albums()->updateOrCreate(
                ['id' => $album->id],
                $request->validated()
            );

            $categories = explode(',', $request->categories);
            $categories = collect($categories)->filter(function ($value, $key) {
                return $value != ' ';
            })->all();

            $categoryIds = [];
            foreach ($categories as $cat) {
                $category = Category::firstOrCreate(['name' => trim($cat)]);
                array_push($categoryIds, $category->id);
            }

            $album->categories()->sync($categoryIds);


            $tags = explode(',', $request->tags);
            $tags = collect($tags)->filter(function ($value, $key) {
                return $value != ' ';
            })->all();

            $tagIds = [];
            foreach ($tags as $t) {
                $tag = Tag::firstOrCreate(['name' => trim($t)]);
                array_push($tagIds, $tag->id);
            }

            $album->tags()->sync($tagIds);
        } catch (ValidationException $e) {
            DB::rollBack();
            dd($e->getErrors());
        }
        DB::commit();

        $success = 'Album mis à jour.';
        $redirect = route('albums.edit', [$album->slug]);
        return $request->ajax() ?
            response()->json(['success' => $success, 'redirect' => $redirect])
            : redirect($redirect)->withSuccess($success);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        //
        abort_if(auth()->id() !== $album->user_id, 403);

        DB::beginTransaction();

        try {
            DB::afterCommit(function () use ($album) {
                Storage::deleteDirectory('photos/' . $album->id);
                Cache::flush();
            });

            $album->delete();
        } catch (ValidationException $e) {
            DB::rollBack();
            dd($e->getErrors());
        }

        DB::commit();

        $success = 'Album supprimé.';
        $redirect = route('albums.index');
        return request()->ajax()
            ? response()->json(['success' => $success, 'redirect' => $redirect])
            : redirect($redirect)->withSuccess($success);
    }
}
