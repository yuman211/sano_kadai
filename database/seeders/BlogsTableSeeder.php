<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            DB::table('blogs')->insert([
                'user_id' => $i,
                'category_id' => $i,
                'title' => "title. $i",
                'price' => 200,
                'content' => "content . $i",
            ]);
        }
    }
}
