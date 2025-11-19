<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Si no pasas company_id, tomará una company aleatoria (o creará una)
            'company_id'  => Company::inRandomOrder()->value('id') ?? Company::factory(),
            'code'        => strtoupper($this->faker->bothify('EMP-###??')),
            'first_name'  => $this->faker->firstName(),
            'last_name'   => $this->faker->lastName(),
            'document_id' => $this->faker->unique()->numerify('##########'),
            'email'       => $this->faker->unique()->safeEmail(),
            'phone'       => $this->faker->e164PhoneNumber(),
            'position'    => $this->faker->randomElement(['Developer','IT Support','SysAdmin','QA','PM']),
            'site'        => $this->faker->randomElement(['HQ','Remote','Sucursal Norte','Sucursal Sur']),
            'status'      => $this->faker->randomElement(['active','inactive','suspended']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn() => ['status' => 'active']);
    }
}
