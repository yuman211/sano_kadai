<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\CreateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

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

    public function showBlogsWithCategory(Blog $blog, $id = null)
    {
        try {

            return $blog->getBlogsWithCategory();
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            return $e;
        }
    }

    public function createBlogs(CreateBlogRequest $request, Blog $blog)
    {
        try {
            $user_id = Auth::id();
            $postData = $request->only(['category_id', 'title', 'price', 'content']);
            $blog->insertBlog($postData, $user_id);
            return '登録しました';
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            return $e;
        }
    }

    public function deleteBlogs(Request $request, Blog $blog)
    {
        try {
            $blog_id = $request->input('id');
            $blog_user_id = $blog->find($blog_id)->user_id;
            
            $user_id = Auth::id();

            $findWho = $blog->findWho($user_id, $blog_user_id);
            if (!$findWho) {
                return response()->json(['error' => 'エラーです'], 403);
            }
            $blog->softDeleteBlog($blog_id, $user_id);
            return '削除しました';
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            return $e;
        }
    }

    public function editBlogs(CreateBlogRequest $request, Blog $blog)
    {
        try {
            $blog_id = $request->input('id');

            $postData = $request->only(['category_id', 'title', 'price', 'content']);
            $user_id = Auth::id();

            $findWho = $blog->findWho($user_id, $blog_id);
            if (!$findWho) {
                return response()->json(['error' => 'エラーです'],403);
            }
            $blog->updateBlog($blog_id, $postData, $user_id);
            return '更新しました';
        } catch (Exception $e) {
            Log::emergency($e->getCode());
            return $e;
        }
    }
}
