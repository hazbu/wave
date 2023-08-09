<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('orders')->delete();
        
        \DB::table('orders')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => '1',
                'product_id' => '1',
                'order_id' => '64d2f763b88c9',
                'created_at' => '2023-08-08 04:12:23',
                'updated_at' => '2023-08-08 04:12:23',
            ),
        ));
        
        
    }
}