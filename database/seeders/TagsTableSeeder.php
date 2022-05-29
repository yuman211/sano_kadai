<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = ['あ','い','う','え','お','か','き','く','け','こ'];

        for ($i = 1; $i <= 10; $i++) {
            DB::table('tags')->insert([
                'name' => Arr::random($tags),
                ]);
        }
    }
}
