<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::get('/{page?}', [PostController::class, 'index'])->name('home')
    ->where('page', '[0-9]+');



Route::middleware(['auth', 'admin', 'verify'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'indexDashboard'])->name('dashboard');
    Route::get('/dashboardPosts', [PostController::class, 'indexDashboard'])->name('dashboardPosts');


    Route::get('/editUser/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/editUser/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/removeUser/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::get('/userTrash', [UserController::class, 'getTrash'])->name('users.trash');
    Route::get('/userRestore/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::get('/dashboardVerification', [UserController::class, 'getNotVerified'])->name('users.verify');
    Route::get('/userVerify/{id}', [UserController::class, 'verifyUser'])->name('users.verify');

    Route::get('/postTrash', [PostController::class, 'getTrash'])->name('posts.trash');
    Route::get('/postRestore/{id}', [PostController::class, 'restore'])->name('posts.restore');
    Route::delete('/removePost/{id}', [PostController::class, 'delete'])->name('posts.delete');
    Route::get('/dashboardReports', [PostController::class, 'getReportedPosts'])->name('posts.reported');
    Route::get('/clearReports/{id}', [PostController::class, 'clearReports'])->name('posts.clear');

});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('postCreate', [PostController::class, 'getView'])->name('posts.create');

});

Route::get('post/{id}', [PostController::class, 'show'])->name(name: 'posts.show');
Route::post('filter', [PostController::class, 'filter'])->name('posts.filter');

Route::middleware(['auth', 'user', 'verify'])->group(function () {
    Route::post('post/addComment/{post_id}', [CommentController::class, 'addComment'])->name('comment.add');
    Route::post('postCreate', [PostController::class, 'create'])->name('posts.create');
    Route::post('reportPost', [PostController::class, 'report'])->name('posts.report');
    Route::delete('postDelete/{id}', [PostController::class, 'deleteUser'])->name('postsUser.remove');
    Route::get('/editPost/{id}', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/editPost/{id}', [PostController::class, 'update'])->name('posts.update');
});


require __DIR__ . '/auth.php';
