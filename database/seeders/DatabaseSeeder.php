<?php

namespace Database\Seeders;

use App\Models\Space;
use App\Models\Category;
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
        // ─── Create Spaces ───
        $poleGS = Space::create(['name_espace' => 'Pôle GS']);
        $poleDigital = Space::create(['name_espace' => 'Pôle Digital']);
        $poleIndustriel = Space::create(['name_espace' => 'Pôle Industriel']);

        // ─── Create Magasinier ───
        User::create([
            'name'     => 'Ahmed Magasinier',
            'email'    => 'magasinier@cmc.ma',
            'password' => Hash::make('password'),
            'role'     => 'magasinier',
            'phone'    => '0600112233',
            'space_id' => null,
        ]);

        // ─── Create Chefs d'espace ───
        User::create([
            'name'     => 'Fatima Chef Espace',
            'email'    => 'chef1@cmc.ma',
            'password' => Hash::make('password'),
            'role'     => 'chef_espace',
            'phone'    => '0612345678',
            'space_id' => $poleGS->id,
        ]);

        User::create([
            'name'     => 'Youssef Chef Espace',
            'email'    => 'chef2@cmc.ma',
            'password' => Hash::make('password'),
            'role'     => 'chef_espace',
            'phone'    => '0687654321',
            'space_id' => $poleDigital->id,
        ]);

        // ─── Create Categories ───
        $catPapeterie = Category::create([
            'title' => 'Papeterie',
            'description' => 'Stylos, feuilles, classeurs et consommables de bureau'
        ]);

        $catInformatique = Category::create([
            'title' => 'Informatique',
            'description' => 'Souris, claviers, câbles et petits équipements informatiques'
        ]);

        $catDidactique = Category::create([
            'title' => 'Matériel Didactique',
            'description' => 'Livres, marqueurs pour tableaux, tableaux et supports de cours'
        ]);

        // ─── Create Products ───
        // Under Papeterie
        Product::create([
            'title' => 'Papier A4 Rame',
            'description' => 'Rame de 500 feuilles 80g/m²',
            'quantity' => 50,
            'category_id' => $catPapeterie->id
        ]);
        Product::create([
            'title' => 'Stylo Bleu Bic',
            'description' => 'Stylos à bille bleu à pointe moyenne',
            'quantity' => 120,
            'category_id' => $catPapeterie->id
        ]);
        Product::create([
            'title' => 'Classeur Carton',
            'description' => 'Classeur grand format à levier',
            'quantity' => 25,
            'category_id' => $catPapeterie->id
        ]);

        // Under Informatique
        Product::create([
            'title' => 'Souris Optique USB',
            'description' => 'Souris filaire standard USB',
            'quantity' => 15,
            'category_id' => $catInformatique->id
        ]);
        Product::create([
            'title' => 'Câble HDMI 1.5m',
            'description' => 'Câble de liaison vidéo haute définition',
            'quantity' => 8,
            'category_id' => $catInformatique->id
        ]);
        Product::create([
            'title' => 'Clavier AZERTY USB',
            'description' => 'Clavier filaire standard',
            'quantity' => 3, // Low stock!
            'category_id' => $catInformatique->id
        ]);

        // Under Matériel Didactique
        Product::create([
            'title' => 'Marqueur Tableau Noir',
            'description' => 'Marqueur effaçable à sec',
            'quantity' => 45,
            'category_id' => $catDidactique->id
        ]);
        Product::create([
            'title' => 'Effaceur Feutre',
            'description' => 'Effaceur magnétique pour tableaux blancs',
            'quantity' => 12,
            'category_id' => $catDidactique->id
        ]);
    }
}
