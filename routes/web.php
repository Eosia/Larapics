<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AlbumController,
    HomeController,
    PhotoController,
    UserController,
    TagController,
    CategoryController,
};
use App\Models\Album;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomeController::class)->name('home');

Route::get('user/{user}', [UserController::class, 'photos'])->name('user.photos');

Route::get('tag/{tag}', [TagController::class, 'photos'])->name('tag.photos');

Route::get('category/{category}', [CategoryController::class, 'photos'])->name('category.photos');

Route::resource('albums', AlbumController::class);

Route::get('photo/{photo}', [PhotoController::class, 'show'])->name('photos.show');

Route::post('download', [PhotoController::class, 'download'])->name('photos.download')->middleware('auth', 'verified');

Route::get('read-all', [PhotoController::class, 'readAll'])->name('notifications.read')->middleware('auth', 'verified');

Route::get('vote/{photo}/{vote}/{token}', [PhotoController::class, 'vote'])->name('photo.vote');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('photos/create/{album}', [PhotoController::class, 'create'])->name('photos.create');
    Route::post('photos/create/{album}', [PhotoController::class, 'store'])->name('photos.store');

    // suppresion de la photo
    Route::delete('delete-photo/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
