<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    // public $timestamp = false;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'blogs_tags','blog_id','tag_id');
    }


    public function getBlogs($id)
    {
        try {
            $query = $this->query();

            if (isset($id)) {
                $query->where('id', '=', $id);
            }

            return $query->with('user')->get();
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            throw $e;
        }
    }

    public function getBlogsWithCategoryAndTags()
    {
        try {

            return $this->with('category')->with('tags')->get()->toJson(JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            throw $e;
        }
    }

    public function insertBlog($postData, $user_id)
    {
        try {
            $this->insert(
                [
                    'user_id' => $user_id,
                    'category_id' => $postData['category_id'],
                    'title' => $postData['title'],
                    'price' => $postData['price'],
                    'content' => $postData['content']
                ]
            );
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            throw $e;
        }
    }

    public function softDeleteBlog($id, $user_id)
    {
        try {
            $this->where('user_id', '=', $user_id)->where('id', '=', $id)->delete();
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            throw $e;
        }
    }

    public function updateBlog($blog_id,$postData,$user_id)
    {
        try {
            $this->where('user_id', '=', $user_id)->where('id', '=', $blog_id)->update($postData);
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            throw $e;
        }
    }

    public function findWho($user_id,$blog_id)
    {
        if($user_id === $blog_id)
        {
            return true;
        }else{
            return false;
        }
    }
}
