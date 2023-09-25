<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Usuário administrador',
            'email' => 'admin@empresa.com',
            'password' => bcrypt('123123'),
            'is_admin' => true,
        ]);

        $this->call([
            // SellerSeeder::class,
            SaleSeeder::class,
        ]);
    }
}
