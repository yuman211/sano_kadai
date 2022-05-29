<?php

namespace Tests\Feature\Models;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\BlogsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;

class BlogTest extends TestCase
{
    use DatabaseMigrations;

    public function setup(): void
    {
        parent::setUp();
        $this->seed(BlogsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
    }

    /**
     * @test
     */
    public function blogのインスタンスにuserモデルのインスタンスが紐づいているか()
    {
        // $this->seed(BlogsTableSeeder::class);
        User::factory()->create();
        $user = Blog::find(1)->user;
        $this->assertInstanceOf(User::class,$user);
    }

    /**
     * @test
     */
    public function blogのインスタンスにcategoryモデルのインスタンスが紐づいているか()
    {
        // $this->seed(CategoriesTableSeeder::class);
        $blog = Blog::find(1)->category;
        $this->assertInstanceOf(Category::class,$blog);
    }
}
