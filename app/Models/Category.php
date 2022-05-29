<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Blog;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function blogs()
    {
        return $this->hasMany(Blog::class,'category_id', 'id');
    }


    public function searchBlogsByCategory($input_category)
    {
        return $this->where('name',$input_category)->with('blogs')->get();
    }
}
