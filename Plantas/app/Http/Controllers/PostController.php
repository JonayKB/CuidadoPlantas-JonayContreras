<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\PlantRepository;
use App\Repository\PlantTypeRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Returns posts dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexDashboard()
    {
        $userRepository = new UserRepository();
        $userNeedsVerification = $userRepository->getNotVerified()->total();
        $postRepository = new PostRepository();
        $postsReporteds = $postRepository->getReportedPosts()->total();
        $trash = false;
        $posts = $postRepository->getPagination();
        return view('dashboardPosts', compact('posts', 'trash','userNeedsVerification','postsReporteds'));
    }
    /**
     * Returns all the information requiered to main page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $postRepository = new PostRepository();
        $posts = $postRepository->getPagination();
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->findAll();
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();
        $plantTypeRepository = new PlantTypeRepository();
        $plantTypes = $plantTypeRepository->findAll();

        return view('welcome', compact('posts', 'plants', 'categories','plantTypes'));
    }

    /**
     * Show a post
     * @param mixed $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $postRepository = new PostRepository();
        $post = $postRepository->findById($id);

        return view('showPost', compact('post'));
    }

    /**
     * Filter posts
     * @param \Illuminate\Http\Request $request to filter by
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function filter(Request $request)
    {
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
        $posts = $query->orderByDesc('created_at')->paginate(PostRepository::AMOUNT_PER_PAGE);

        return view('welcome', compact('posts', 'plants', 'plantTypes', 'categories'));
    }
    /**
     * Returns form to create a new post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getView()
    {
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->findAll();
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();
        return view('createPost', compact('plants',  'categories'));
    }
    /**
     * Creates a new post
     * @param Request $request to receive data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {

        $postRepository = new PostRepository();
        $imageRepository = new ImageRepository();

        $user_id = $request->user_id;
        $plant_id = $request->plant_id;
        $category_id = $request->category_id;
        $title = $request->title;
        $description = $request->description;
        $images = $request->file('images');

        if ($category_id == null)
            $category_id = 16;

        if ($plant_id == null)
            $plant_id = 91;


        $post = new Post([
            'user_id' => $user_id,
            'plant_id' => $plant_id,
            'category_id' => $category_id,
            'title' => $title,
            'description' => $description,
        ]);
        $post = $postRepository->save($post);
        foreach ($images as $image) {
            $imagename = time() . '_' . $image->getClientOriginalName();
            $image->move('storage', $imagename);
            $imageModel = new Image(['path' => $imagename, 'post_id' => $post->post_id]);
            $imageRepository->save($imageModel);
        }
        return redirect()->route('posts.show', $post->post_id);
    }


    /**
     * Reports a post
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function report(Request $request)
    {
        $postRepository = new PostRepository();
        $post = $postRepository->findById($request->post_id);
        $post->reports =  $post->reports + 1;
        $postRepository->save($post);
        return redirect()->route('posts.show', $post->post_id);
    }
    /**
     * Returns the view to edit a post
     * @param mixed $post_id to edit
     * @return mixed|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($post_id)
    {
        $postRepository = new PostRepository();
        $post = $postRepository->findById($post_id);
        if (!auth()->user()->id != $post->user_id && !auth()->user()->roles->contains('name', 'admin')) {
            return redirect('/')->with('error', 'Unathorized edition');
        }
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->findAll();
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();
        return view('editPost', compact('post','plants','categories'));
    }
    /**
     * Returns the deleted posts
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getTrash()
    {
        $trash = true;
        $postRepository = new PostRepository();
        $posts = $postRepository->getOnlyTrash();
        $userRepository = new UserRepository();
        $userNeedsVerification = $userRepository->getNotVerified()->total();
        $postsReporteds = $postRepository->getReportedPosts()->total();
        return view('dashboardPosts', compact('posts', 'trash','userNeedsVerification','postsReporteds'));
    }
    /**
     * Restore a deleted post
     * @param mixed $post_id to restore
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function restore($post_id)
    {
        $postRepository = new PostRepository();
        $postRepository->restore($post_id);
        return redirect('postTrash')->with('message', 'Post restored correctly');
    }
    /**
     * Deletes a post
     * @param mixed $post_id to delete
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function delete($post_id)
    {
        $postRepository = new PostRepository();
        $postRepository->delete($post_id);
        return redirect()->route('dashboardPosts')->with('message', 'Post deleted correctly');
    }
    /**
     * Delete a post (by user)
     * @param mixed $post_id to delete
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function deleteUser($post_id){
        $postRepository = new PostRepository();
        $post = $postRepository->findById($post_id);
        if (!auth()->user()->id != $post->user_id && !auth()->user()->roles->contains('name', 'admin')) {
            return redirect('/')->with('error', 'Unathorized edition');
        }
        $postRepository->delete($post_id);
        return redirect('/')->with('message', 'Post deleted correctly');
    }
    /**
     * Get reporteds post and returns to view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getReportedPosts()
    {

        $trash = false;
        $postRepository = new PostRepository();
        $userRepository = new UserRepository();
        $posts = $postRepository->getReportedPosts();
        $userNeedsVerification = $userRepository->getNotVerified()->total();
        $postsReporteds = $posts->total();
        return view('dashboardPosts', compact('posts', 'trash','userRepository','userNeedsVerification','postsReporteds'));
    }
    /**
     * Update a post
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request){
        $postRepository = new PostRepository();
        $post = $postRepository->findById($request->post_id);
        if (!auth()->user()->id != $post->user_id && !auth()->user()->roles->contains('name', 'admin')) {
            return redirect('/')->with('error', 'Unathorized edition');
        }
        $post->title = $request->title;
        $post->description = $request->description;
        $post->category_id = $request->category_id;
        $post->plant_id = $request->plant_id;
        $postRepository->update($post);
        return redirect()->route('posts.show', $post->post_id)->with('message', 'Post updated correctly');
    }
    /**
     * Clear reports from a post
     * @param mixed $id post id
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function clearReports($id){
        $postRepository = new PostRepository();
        $post = $postRepository->findById($id);
        $post->reports = 0;
        $postRepository->update($post);
        return redirect()->route('posts.reported')->with('message', 'Reports cleared correctly');
    }

}
