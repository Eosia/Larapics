<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AlbumController,
    HomeController,
    PhotoController,
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

Route::resource('albums', AlbumController::class);

Route::get('photo/{photo}', [PhotoController::class, 'show'])->name('photos.show');

Route::post('download', [PhotoController::class, 'download'])->name('photos.download')->middleware('auth', 'verified');

Route::middleware(['auth', 'verified'])->group(function() {

    Route::get('photos/create/{album}', [PhotoController::class, 'create'])->name('photos.create');
    Route::post('photos/create/{album}', [PhotoController::class, 'store'])->name('photos.store');
    
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

