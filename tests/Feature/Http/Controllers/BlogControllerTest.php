<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BlogControllerTest extends TestCase
{

    // use RefreshDatabase;

    /**
     * @test
     */
    public function ブログ情報が表示されているか()
    {

        User::factory()->create();//試しにuser情報挿入できるかをやってみたがデータはいらず。

        // $this->seed();
        // DB::table('blogs')->insert([
        //     'user_id' => 1,
        //     'category_id' => 1,
        //     'title' => "title. 1",
        //     'price' => 200,
        //     'content' => "content . 1"
        // ]);

        $this->get('/api/blog_list')
        ->assertOK()
            ->assertJsonFragment([
                'user_id' => 1,
                'category_id' => 1,
                'title' => "title. 1",
                'price' => 200,
                'content' => "content . 1"
            ]);
    }

    /**
     * @test
     */
    public function idごとにブログ情報が表示できるか()
    {
        $this->seed(BlogsTableSeeder::class);
        $this->get('/api/blog_list/3')
        ->assertOK()
            ->assertJson([
                'user_id' => 3,
                'category_id' => 3,
                'title' => "title. 3",
                'price' => 200,
                'content' => "content . 3",
            ]);
    }
}
