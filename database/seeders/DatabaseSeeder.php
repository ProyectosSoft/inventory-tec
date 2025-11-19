<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1) Usuario(s) de prueba (opcional)
        // Usa la UserFactory para evitar problemas de hash de password
        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
            // password: por defecto la factory de Laravel usa "password"
        ]);

        // 2) Semilla de negocio (companies + employees)
        // $this->call([
        //     InventorySeeder::class
        // ]);

        $this->call([
            DeviceTypeSeeder::class,
        ]);
    }
}
