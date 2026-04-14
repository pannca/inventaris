<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category; 

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Data Users (Akun)
        // Password disamakan dengan aturan: 4 huruf awal email + ID
        User::insert([
            [
                'name' => 'Admin Wikrama', 
                'email' => 'admin@gmail.com', 
                'role' => 'admin', 
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Operator Wikrama', 
                'email' => 'operator@gmail.com', 
                'role' => 'staff', 
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Panca (Admin)', 
                'email' => 'panca@gmail.com', 
                'role' => 'admin', 
                'password' => Hash::make('password')
            ],
        ]);

        // 2. Data Categories (Kategori Barang)
        Category::insert([
            [
                'name' => 'Elektronik', 
                'division_pj' => 'Tefa'
            ],
            [
                'name' => 'Alat Dapur', 
                'division_pj' => 'Sarpras'
            ],
            [
                'name' => 'Alat Tulis Kantor', 
                'division_pj' => 'Tata Usaha'
            ],
            [
                'name' => 'Mebel / Furniture', 
                'division_pj' => 'Sarpras'
            ],
        ]);
    }
}
