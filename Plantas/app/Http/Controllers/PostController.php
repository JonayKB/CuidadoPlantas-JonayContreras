<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repository\CategoryRepository;
use App\Repository\PlantRepository;
use App\Repository\PlantTypeRepository;
use App\Repository\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $postRepository = new PostRepository();
        $posts = $postRepository->getPagination();
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->findAll();
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();
        $plantTypeRepository = new PlantTypeRepository();
        $plantTypes = $plantTypeRepository->findAll();

        return view('welcome', compact('posts','plants','plantTypes','categories'));
    }

    public function show($id){
        $postRepository = new PostRepository();
        $post = $postRepository->findById($id);

        return view('showPost', compact('post'));
    }

    public function filter(Request $request) {
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->findAll();
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();
        $plantTypeRepository = new PlantTypeRepository();
        $plantTypes = $plantTypeRepository->findAll();

        $query = Post::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->input('category') != 'any') {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->input('plant') != 'any') {
            $query->where('plant_id', $request->input('plant'));
        }

        if ($request->input('plantType') != 'any') {
            $query->whereHas('plant', function ($q) use ($request) {
                $q->where('type_id', $request->input('plantType'));
            });
        }
        $posts = $query->paginate(PostRepository::AMOUNT_PER_PAGE);

        return view('welcome', compact('posts', 'plants', 'plantTypes', 'categories'));
    }

}
