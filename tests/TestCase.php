<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function login()
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

        $this->postJson('api/login', $postData);

        return $user;
    }
}
