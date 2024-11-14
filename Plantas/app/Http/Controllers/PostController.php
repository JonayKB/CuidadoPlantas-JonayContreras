<?php

namespace App\Http\Controllers;

use App\Repository\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $postRepository = new PostRepository();
        $posts = $postRepository->getPagination(6);

        return view('welcome', compact('posts'));
    }

    public function show($id){
        $postRepository = new PostRepository();
        $post = $postRepository->findById($id);

        return view('showPost', compact('post'));
    }

    public function filterBy($filterType, $filter){
        $postRepository = new PostRepository();
        $posts = $postRepository->filterBy($filterType, $filter);

        return view('welcome', compact('posts'));
    }
}
