<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(10)->create();
        Product::factory(10)->create();
         \App\Models\User::factory()->create([
             'last_name_doc' => 'ADMIN',
             'first_name_doc' => 'ADMIN',
             'phone_number' => '123456789',
             'address' =>'12345678',
             'email' => 'admin@admin.com',
             'password'=>'$2y$10$sWRADT1FX7IACEdAbsfa2O05nJgqd88bA/lfJuS.8DFiRcXaPv2eO',
             'role'=>'admin'
         ]);
        \App\Models\User::factory()->create([
            'last_name_doc' => 'Moderator',
            'first_name_doc' => 'Moderator',
            'phone_number' => '123456789',
            'address' =>'12345678',
            'email' => 'mod@mod.com',
            'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'role'=>'moderator'
        ]);
        User::factory(8)->create();
    }
}
