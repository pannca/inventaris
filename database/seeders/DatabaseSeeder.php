<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Users
        $users = [
            ['name' => 'Admin Wikrama',   'email' => 'admin@gmail.com',    'role' => 'admin'],
            ['name' => 'Operator Wikrama','email' => 'operator@gmail.com', 'role' => 'staff'],
            ['name' => 'Panca',           'email' => 'panca@gmail.com',    'role' => 'admin'],
        ];

        foreach ($users as $data) {
            User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'role'     => $data['role'],
                'password' => Hash::make('password'),
            ]);
        }

        // 2. Categories
        $categories = [
            ['name' => 'Elektronik',        'division_pj' => 'Tefa'],
            ['name' => 'Alat Dapur',        'division_pj' => 'Sarpras'],
            ['name' => 'Alat Tulis Kantor', 'division_pj' => 'Tata Usaha'],
            ['name' => 'Mebel / Furniture', 'division_pj' => 'Sarpras'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $elektronik  = Category::where('name', 'Elektronik')->first()->id;
        $dapur       = Category::where('name', 'Alat Dapur')->first()->id;
        $atk         = Category::where('name', 'Alat Tulis Kantor')->first()->id;
        $mebel       = Category::where('name', 'Mebel / Furniture')->first()->id;

        // 3. Items
        $items = [
            // Elektronik
            ['category_id' => $elektronik, 'name' => 'Laptop',          'total' => 20],
            ['category_id' => $elektronik, 'name' => 'Proyektor',        'total' => 10],
            ['category_id' => $elektronik, 'name' => 'Komputer PC',      'total' => 30],
            ['category_id' => $elektronik, 'name' => 'Kamera DSLR',      'total' => 5],
            ['category_id' => $elektronik, 'name' => 'Speaker Aktif',    'total' => 8],
            ['category_id' => $elektronik, 'name' => 'Mikrofon',         'total' => 6],
            ['category_id' => $elektronik, 'name' => 'Printer',          'total' => 7],
            ['category_id' => $elektronik, 'name' => 'Scanner',          'total' => 4],

            // Alat Dapur
            ['category_id' => $dapur, 'name' => 'Kompor Gas',            'total' => 5],
            ['category_id' => $dapur, 'name' => 'Panci Besar',           'total' => 10],
            ['category_id' => $dapur, 'name' => 'Wajan',                 'total' => 8],
            ['category_id' => $dapur, 'name' => 'Pisau Dapur',           'total' => 15],
            ['category_id' => $dapur, 'name' => 'Talenan',               'total' => 10],

            // ATK
            ['category_id' => $atk, 'name' => 'Spidol Whiteboard',       'total' => 50],
            ['category_id' => $atk, 'name' => 'Penghapus Papan',         'total' => 30],
            ['category_id' => $atk, 'name' => 'Gunting',                 'total' => 20],
            ['category_id' => $atk, 'name' => 'Stapler',                 'total' => 15],
            ['category_id' => $atk, 'name' => 'Penggaris Besi',          'total' => 25],

            // Mebel
            ['category_id' => $mebel, 'name' => 'Kursi Lipat',           'total' => 100],
            ['category_id' => $mebel, 'name' => 'Meja Belajar',          'total' => 40],
            ['category_id' => $mebel, 'name' => 'Papan Tulis',           'total' => 15],
            ['category_id' => $mebel, 'name' => 'Lemari Arsip',          'total' => 8],
        ];

        foreach ($items as $item) {
            Item::create([
                'category_id'   => $item['category_id'],
                'name'          => $item['name'],
                'total'         => $item['total'],
                'available'     => $item['total'],
                'repair'        => 0,
                'lending_total' => 0,
            ]);
        }
    }
}
