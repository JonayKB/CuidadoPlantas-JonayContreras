<?php

namespace App\Http\Controllers;

use App\Repository\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $postRepository = new PostRepository();
        $posts = $postRepository->findAll();

        return view('welcome', compact('posts'));
    }
}
