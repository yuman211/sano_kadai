<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Database\Seeders\BlogsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class BlogControllerTest extends TestCase
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
    public function ブログ情報が表示されているか()
    {
        // $this->seed(BlogsTableSeeder::class);

        $this->get('/api/blog_list')
            ->assertOK()
            ->assertJsonCount(20)
            ->assertJsonStructure(
                ['*' => [
                    'id', 'user_id', 'category_id', 'title', 'price', 'content'
                ]]
            );
    }

    /**
     * @test
     */
    public function idごとにブログ情報が表示できるか()
    {
        $this->get('/api/blog_list/3')
            ->assertOK()
            ->assertJsonFragment([
                'user_id' => 3,
                'category_id' => 3,
                'title' => "title. 3",
                'price' => 200,
                'content' => "content . 3",
            ]);
    }

    /**
     * @test
     */
    public function blogを表示したときにuserの情報が表示されているか()
    {
        User::factory()->create();
        $user = User::find(1);
        $this->get('/api/blog_list')
            ->assertSee($user->name); //nameはuser固有の情報
    }


    /**
     * @test
     */
    public function blogを表示したときにcategoryの情報が表示されているか()
    {
        $category = Category::find(1);
        Log::info($category->name);
        $this->get('api/blog_category_list')->assertSee($category->name);
    }

    /**
     * @test
     */
    public function ブログが登録できるか()
    {
        $this->login();

        $postData = [
            // 'user_id'=>1,
            'category_id' => 2,
            'title' => 'ららららら',
            'price' => 2000,
            'content' => 'リリリリリリり',
        ];

        $this->post('api/blog/create', $postData)
            ->assertOk();
        $this->assertDatabaseHas('blogs', $postData);
    }

    /**
     * @test
     */
    public function 自分のブログは編集できる()
    {
        $this->login();

        $postData = [
            'id' => 1,
            'category_id' => 1,
            'title' => 'ららららら',
            'price' => 2000,
            'content' => 'リリリリリリり',
        ];

        $this->post('api/blog/edit', $postData)
            ->assertOk()
            ->assertSee('更新しました');
        $this->assertDatabaseHas('blogs', $postData);
    }
    /**
     * @test
     */
    public function 自分のブログは削除できる()
    {
        $this->login();
        $postData = [
            'id' => 1
        ];
        $blog = Blog::find(1);
        $this->post('api/blog/delete', $postData)
            ->assertOk()
            ->assertSee('削除しました');
        $this->assertSoftDeleted($blog);
    }
    /**
     * @test
     */
    public function 他人のブログは編集できない()
    {

        $blog = Blog::create([
            'user_id' =>100,
            'category_id'=>1,
            'title'=>'らららららら',
            'price'=>3000,
            'content'=>'らららららららら',
        ]);

        $postData = [
            'id' =>$blog->id,
            'category_id' => 1,
            'title' => 'ららららら',
            'price' => 2000,
            'content' => 'リリリリリリり',
        ];

        $user = $this->login();//user_id=1になる
        $this->post('api/blog/edit',$postData)
        ->assertStatus(403);
    }

    /**
     * @test
     */
    public function 他人のブログは削除できない()
    {
        $override = [
            'user_id' => 100,
            'category_id' => 1,
            'title' => 'らららららら',
            'price' => 3000,
            'content' => 'らららららららら',
        ];
        $blog = Blog::create($override);

        $postData = [
            'id' => $blog->id,
        ];
        $user = $this->login(); //user_id=1になる
        $this->post('api/blog/delete', $postData)
        ->assertStatus(403);
        $this->assertDatabaseHas('blogs',$override);
    }
}
