<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Blog;
use Illuminate\Support\Facades\Log;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blogs_tags', 'tag_id', 'blog_id');
    }


    public function searchBlogsByTags($tags_array)
    {
        Log::info($tags_array);
        $query = $this->query();

        foreach($tags_array as $tag)
        {
            $query->where('name', 'like', '%' . $tag . '%');
        }

        return $query->with('blogs')->get();

    }
}
