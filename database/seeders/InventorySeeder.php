<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Employee;
use Faker\Factory as FakerFactory;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // (Opcional) que el aleatorio sea reproducible en local
        if (app()->environment('local')) {
            $faker = FakerFactory::create();
            $faker->seed(12345);
        }

        // 1) Volumen base: 8–10 compañías
        $companies = Company::factory()->count(10)->create();

        // 2) Empleados por compañía: 6–12 c/u
        foreach ($companies as $company) {
            Employee::factory()
                ->count(random_int(6, 12))
                ->state(fn () => ['company_id' => $company->id])
                ->create();
        }

        // 3) Registros fijos (idempotentes)
        $demo = Company::firstOrCreate(
            ['tax_id' => 'NIT-900123456'], // clave candidata p/evitar duplicado
            [
                'name'    => 'Inventory Tech S.A.',
                'email'   => 'info@inventorytech.test',
                'phone'   => '+57 300 123 4567',
                'website' => 'inventorytech.test',
                'address' => 'Calle 123 #45-67',
                'city'    => 'Bogotá',
                'country' => 'CO',
                'status'  => 'active',
            ]
        );

        Employee::firstOrCreate(
            ['email' => 'maria.gonzalez@inventorytech.test'], // único
            [
                'company_id'  => $demo->id,
                'code'        => 'EMP-001AA',
                'first_name'  => 'María',
                'last_name'   => 'González',
                'document_id' => '1020304050',
                'phone'       => '+57 310 000 0000',
                'position'    => 'IT Support',
                'site'        => 'HQ',
                'status'      => 'active',
            ]
        );
    }
}
