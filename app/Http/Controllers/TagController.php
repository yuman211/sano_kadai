<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Models\Tag;

class TagController extends Controller
{
    public function searchBlogsByTags(Request $request,Tag $tag)
    {
        $tags_array = Arr::except(explode('#', $request->tags), [0]);
        $searched_blogs = $tag->searchBlogsByTags($tags_array);

        if(empty($searched_blogs)){
            return 'Blogがありません';
        }

        return $searched_blogs;
    }
}
