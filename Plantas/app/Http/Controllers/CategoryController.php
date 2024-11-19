<?php

namespace App\Http\Controllers;

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
}
