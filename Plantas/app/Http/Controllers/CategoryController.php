<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repository\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function indexDashboard(){
        $trash = false;
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getPagination();
        return view('dashboardCategories', compact('categories','trash'));
    }
    public function edit($id){
        $categoryRepository = new CategoryRepository();
        $category = $categoryRepository->findById($id);
        return view('editCategory', compact('category'));
    }
    public function create(){
        return view('createCategory');
    }
    public function update(Request $request){
        $categoryRepository = new CategoryRepository();
        $category_id = $request->id;
        $category = $categoryRepository->findById($category_id);
        $category->name = $request->name;
        $categoryRepository->save($category);
        return redirect()->route('dashboardCategories')->with('message', 'Category updated correctly');
    }
    public function getTrash(){
        $trash = true;
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getOnlyTrash();
        return view('dashboardCategories', compact('categories','trash'));
    }
    public function restore($id){
        $categoryRepository = new CategoryRepository();
        $categoryRepository->restore($id);
        return redirect()->route('dashboardCategories')->with('message', 'Category restored correctly');
    }
    public function delete($id){
        $categoryRepository = new CategoryRepository();
        $categoryRepository->delete($id);
        return redirect()->route('dashboardCategories')->with('message', 'Category deleted correctly');
    }
    public function createCategory(Request $request){
        $categoryRepository = new CategoryRepository();
        $category = new Category();
        $category->name = $request->name;
        $categoryRepository->save($category);
        return redirect()->route('dashboardCategories')->with('message', 'Category created correctly');
    }
}
