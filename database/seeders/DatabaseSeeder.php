<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Create Magasinier ───
        User::create([
            'name'     => 'Ahmed Magasinier',
            'email'    => 'magasinier@cmc.ma',
            'password' => Hash::make('password'),
            'role'     => 'magasinier',
        ]);

        // ─── Create Chefs de Pôle ───
        User::create([
            'name'     => 'Fatima Chef Pôle',
            'email'    => 'chef1@cmc.ma',
            'password' => Hash::make('password'),
            'role'     => 'chef_pole',
        ]);

        User::create([
            'name'     => 'Youssef Chef Pôle',
            'email'    => 'chef2@cmc.ma',
            'password' => Hash::make('password'),
            'role'     => 'chef_pole',
        ]);

        // ─── Create Products ───
        Product::create([
            'name' => 'Stylo Bleu',
            'quantity' => 50,
        ]);

        Product::create([
            'name' => 'Papier A4',
            'quantity' => 200,
        ]);

        Product::create([
            'name' => 'Marqueur',
            'quantity' => 3, // low stock!
        ]);

        Product::create([
            'name' => 'Cahier 96p',
            'quantity' => 30,
        ]);

        Product::create([
            'name' => 'Gomme',
            'quantity' => 2, // low stock!
        ]);
    }
}
