<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $datas =[
            ['name'=>'凤凰天问'],
            ['name'=>'社团活动'],
        ];
        DB::table('categories')->insert($datas);
    }
}
