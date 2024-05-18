<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
      $this->call([
        PermissionTableSeeder::class,
        CreateAdminUserSeeder::class,
        GlobalSeeder::class,
      ]);
//        \App\Models\Branch::factory(10)->create();
//        \App\Models\Product::factory(10)->create();
//      \App\Models\User::factory(100)->create();
    
    }
}
