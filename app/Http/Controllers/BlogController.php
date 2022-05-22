<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class BlogController extends Controller
{
    public function showBlogs(Blog $blog, $id = null)
    {
        try {
            return $blog->getBlogs($id);

        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            return $e;
        }
    }
}
