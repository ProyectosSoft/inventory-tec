<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'       => $this->faker->company(),
            'legal_name' => $this->faker->companySuffix().' '.$this->faker->company(),
            'tax_id'     => 'NIT-'.$this->faker->unique()->numerify('#########'),
            'email'      => $this->faker->companyEmail(),
            'phone'      => $this->faker->e164PhoneNumber(),
            'website'    => $this->faker->domainName(),
            'address'    => $this->faker->streetAddress(),
            'city'       => $this->faker->city(),
            'country'    => strtoupper($this->faker->countryCode()),
            'status'     => $this->faker->randomElement(['active','inactive']),
        ];
    }

    // Ejemplo de estado (state)
    public function active(): static
    {
        return $this->state(fn() => ['status' => 'active']);
    }
}
