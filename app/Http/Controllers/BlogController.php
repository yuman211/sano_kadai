<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\CreateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

use Illuminate\Notifications\Notifiable;
use App\Notifications\SlackNotification;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    use Notifiable;
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

            return $blog->getBlogsWithCategoryAndTags();
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            return $e;
        }
    }

    public function createBlogs(CreateBlogRequest $request, Blog $blog)
    {
        try {
            DB::beginTransaction();

            $user_id = Auth::id();
            $postData = $request->only(['category_id', 'title', 'price', 'content']);
            $input_tag = $request->input('tags');

            $created_blog = $blog->createBlog($postData, $user_id);
            $blog_id = $created_blog->id;
            if (isset($input_tag)) {
                $tag_ids = $blog->createTags($input_tag);

                //中間テーブルに挿入
                $blog->insertBlogsTagsTable($blog_id,$tag_ids);
            }

            DB::commit();
            //slack通知
            $title = $request->input('title');
            $this->notify(new SlackNotification($title));

            return '登録しました';
        } catch (Exception $e) {
            DB::rollback();
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
                return response()->json(['error' => 'エラーです'], 403);
            }
            $blog->updateBlog($blog_id, $postData, $user_id);
            return '更新しました';
        } catch (Exception $e) {
            Log::emergency($e->getCode());
            return $e;
        }

    }

    public function routeNotificationForSlack($notification)
    {
        return config('app.slack_url');
    }
}
