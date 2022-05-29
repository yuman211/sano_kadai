<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => '国語'
            ],
            [
                'name' => '数学'
            ],
            [
                'name' => '物理'
            ],
            [
                'name' => '化学'
            ],
            [
                'name' => '世界史'
            ],
        ]);
    }
}
