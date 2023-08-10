<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'controller' => '',
                'created_at' => '2017-11-21 16:23:22',
                'description' => '',
                'details' => NULL,
                'display_name_plural' => 'Posts',
                'display_name_singular' => 'Post',
                'generate_permissions' => 1,
                'icon' => 'voyager-news',
                'id' => 1,
                'model_name' => 'TCG\\Voyager\\Models\\Post',
                'name' => 'posts',
                'policy_name' => 'TCG\\Voyager\\Policies\\PostPolicy',
                'server_side' => 0,
                'slug' => 'posts',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            1 => 
            array (
                'controller' => '',
                'created_at' => '2017-11-21 16:23:22',
                'description' => '',
                'details' => NULL,
                'display_name_plural' => 'Pages',
                'display_name_singular' => 'Page',
                'generate_permissions' => 1,
                'icon' => 'voyager-file-text',
                'id' => 2,
                'model_name' => 'TCG\\Voyager\\Models\\Page',
                'name' => 'pages',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'pages',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            2 => 
            array (
                'controller' => NULL,
                'created_at' => '2017-11-21 16:23:22',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null}',
                'display_name_plural' => 'Users',
                'display_name_singular' => 'User',
                'generate_permissions' => 1,
                'icon' => 'voyager-person',
                'id' => 3,
                'model_name' => 'TCG\\Voyager\\Models\\User',
                'name' => 'users',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'server_side' => 0,
                'slug' => 'users',
                'updated_at' => '2018-06-22 20:29:47',
            ),
            3 => 
            array (
                'controller' => '',
                'created_at' => '2017-11-21 16:23:22',
                'description' => '',
                'details' => NULL,
                'display_name_plural' => 'Categories',
                'display_name_singular' => 'Category',
                'generate_permissions' => 1,
                'icon' => 'voyager-categories',
                'id' => 4,
                'model_name' => 'TCG\\Voyager\\Models\\Category',
                'name' => 'categories',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'categories',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            4 => 
            array (
                'controller' => '',
                'created_at' => '2017-11-21 16:23:22',
                'description' => '',
                'details' => NULL,
                'display_name_plural' => 'Menus',
                'display_name_singular' => 'Menu',
                'generate_permissions' => 1,
                'icon' => 'voyager-list',
                'id' => 5,
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'name' => 'menus',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'menus',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            5 => 
            array (
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'created_at' => '2017-11-21 16:23:22',
                'description' => '',
                'details' => NULL,
                'display_name_plural' => 'Roles',
                'display_name_singular' => 'Role',
                'generate_permissions' => 1,
                'icon' => 'voyager-lock',
                'id' => 6,
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'name' => 'roles',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'roles',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            6 => 
            array (
                'controller' => NULL,
                'created_at' => '2018-05-20 21:08:14',
                'description' => NULL,
                'details' => NULL,
                'display_name_plural' => 'Announcements',
                'display_name_singular' => 'Announcement',
                'generate_permissions' => 1,
                'icon' => 'voyager-megaphone',
                'id' => 7,
                'model_name' => 'Wave\\Announcement',
                'name' => 'announcements',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'announcements',
                'updated_at' => '2018-05-20 21:08:14',
            ),
            7 => 
            array (
                'controller' => NULL,
                'created_at' => '2018-07-03 04:50:28',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null}',
                'display_name_plural' => 'Plans',
                'display_name_singular' => 'Plan',
                'generate_permissions' => 1,
                'icon' => 'voyager-logbook',
                'id' => 8,
                'model_name' => 'Wave\\Plan',
                'name' => 'plans',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'plans',
                'updated_at' => '2018-07-03 04:50:28',
            ),
            8 => 
            array (
                'controller' => NULL,
                'created_at' => '2018-07-03 04:50:28',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'display_name_plural' => 'Payments',
                'display_name_singular' => 'Payment',
                'generate_permissions' => 1,
                'icon' => NULL,
                'id' => 9,
                'model_name' => 'App\\Models\\Payment',
                'name' => 'payments',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'payments',
                'updated_at' => '2023-08-10 06:56:40',
            ),
            9 => 
            array (
                'controller' => NULL,
                'created_at' => '2018-07-03 04:50:28',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'display_name_plural' => 'Products',
                'display_name_singular' => 'Product',
                'generate_permissions' => 1,
                'icon' => NULL,
                'id' => 10,
                'model_name' => 'App\\Product',
                'name' => 'products',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'products',
                'updated_at' => '2023-08-09 02:46:00',
            ),
            10 => 
            array (
                'controller' => NULL,
                'created_at' => '2018-07-03 04:50:28',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":"currentUser"}',
                'display_name_plural' => 'Orders',
                'display_name_singular' => 'Order',
                'generate_permissions' => 1,
                'icon' => NULL,
                'id' => 11,
                'model_name' => 'App\\Order',
                'name' => 'orders',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'orders',
                'updated_at' => '2023-08-10 02:28:16',
            ),
        ));
        
        
    }
}