<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class JWTControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function ユーザー登録ができているか()
    {
        $this->postJson('api/register', [
            'name' => 'ゆうき',
            'email' => 'hoge@gmail.com',
            'password' => 'hogehoge',
            'password_confirmation' => 'hogehoge',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'ゆうき',
            'email' => 'hoge@gmail.com',
        ]);
    }

    /**
     * @test
     */
    public function ログインできるか()
    {

        //照合するユーザー情報
        $postData = [
            'email' => 'hoge@gmail.com',
            'password' => 'hogehoge',
        ];

        //照合先のユーザー情報
        $dbData = [
            'email' => 'hoge@gmail.com',
            'password' => bcrypt('hogehoge'),
        ];

        $user = User::factory()->create($dbData);

        $this->postJson('api/login', $postData)
            ->assertOk();

        //$userとして認証されているかのチェック
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function プロフィールを確認できるか()
    {
        $user = $this->login();

        $this->post('api/profile')
            ->assertOk()
            ->assertJson($user->toArray());
        //     ->withHeader([
        //     'Authorization' => 'Value',
        // ]);
    }

    /**
     * @test
     */
    public function ログアウトできるか()
    {
        $this->login();
        $this->post('api/logout');
        $this->assertGuest();
    }
}
