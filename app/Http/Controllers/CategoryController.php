<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class CategoryController extends Controller
{
    public function searchBlogsByCategory(Request $request,Category $category)
    {
        $input_category = $request->input('category');
        return $category->searchBlogsByCategory($input_category);

    }
}
