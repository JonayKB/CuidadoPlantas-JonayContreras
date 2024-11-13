<?php

namespace App\Http\Controllers;

use App\Repository\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($page = 1){
        $postRepository = new PostRepository();
        $posts = $postRepository->findByPage($page,10);

        return view('welcome', compact('posts'));
    }

    public function show($id){
        $postRepository = new PostRepository();
        $post = $postRepository->findById($id);

        return view('showPost', compact('post'));
    }
}
