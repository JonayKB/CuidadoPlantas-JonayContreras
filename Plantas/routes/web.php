<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/{page?}', [PostController::class,'index'])->name('home')
->where('page','[0-9]+');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified','admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('post/{id}', [PostController::class, 'show'])->name(name: 'posts.show');

Route::middleware(['auth','user'])->group(function (){
    Route::delete('post/{id}', [PostController::class, 'delete'])->name('posts.remove');
    Route::get('postEdit/{id}', [PostController::class, 'edit'])->name('posts.edit');
    Route::post('/post/addComment/{post_id}',[CommentController::class,'addComment'])->name('comment.add');
});

require __DIR__.'/auth.php';
