<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
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
    public function getView(){
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->findAll();
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();
        $plantTypeRepository = new PlantTypeRepository();
        $plantTypes = $plantTypeRepository->findAll();
        return view('createPost',compact('plants', 'plantTypes', 'categories'));
    }
    public function create(Request $request){

        $postRepository = new PostRepository();
        $imageRepository = new ImageRepository();

        $user_id = $request->user_id;
        $plant_id=$request->plant_id;
        $category_id=$request->category_id;
        $title=$request->title;
        $description=$request->description;
        $images = $request->file('images');



        $post = new Post([
            'user_id'=>$user_id,
            'plant_id'=>$plant_id,
            'category_id'=>$category_id,
            'title'=>$title,
            'description'=>$description,
        ]);
        $post = $postRepository->save($post);
        foreach ($images as $image) {
            $imagename = time() . '_' . $image->getClientOriginalName();
            $image->move('storage',$imagename);
            $imageModel = new Image(['path' => $imagename,'post_id'=>$post->post_id]);
            $imageRepository->save($imageModel);
        }
        return redirect()->route('posts.show',$post->post_id);

    }


}
