<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('products')->delete();
        
        \DB::table('products')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Wave Source Code',
                'price' => '100000',
                'description' => 'Wave Source Code for Developers',
                'created_at' => '2023-08-08 04:11:45',
                'updated_at' => '2023-08-09 01:29:10',
            ),
        ));
        
        
    }
}