<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name'=>'admin']);
        User::create([
            'name' => 'admin',
            'email' => 'a@1',
            'password' => bcrypt('12345678'),
        ]);
        
        $admin = User::where('name', 'admin')->first();
        $admin->assignRole('admin');
    }
}
