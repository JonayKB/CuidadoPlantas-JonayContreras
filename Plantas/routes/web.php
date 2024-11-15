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
Route::post('filter', [PostController::class, 'filter'])->name('posts.filter');

Route::middleware(['auth','user','verified'])->group(function (){
    Route::delete('post/{id}', [PostController::class, 'delete'])->name('posts.remove');
    Route::get('postEdit/{id}', [PostController::class, 'edit'])->name('posts.edit');
    Route::post('post/addComment/{post_id}',[CommentController::class,'addComment'])->name('comment.add');
    Route::get('postCreate', [PostController::class, 'getView'])->name('posts.create');
    Route::post('postCreate', [PostController::class, 'create'])->name('posts.create');
    Route::post('reportPost',[PostController::class,'report'])->name('posts.report');
});


require __DIR__.'/auth.php';
