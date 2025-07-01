<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
            'gender' => fake()->randomElement(['Masculino', 'Feminino', 'Outro']),
            'marital_status' => fake()->optional()->randomElement(['Solteiro', 'Casado', 'Divorciado', 'Viúvo']),
            'birth_date' => fake()->date(),
            'baptism_date' => fake()->optional()->date(),
            'admission_date' => fake()->optional()->date(),
            'wedding_date' => fake()->optional()->date(),
            'zip_code' => fake()->postcode(),
            'street' => fake()->optional()->streetName(),
            'neighborhood' => fake()->optional()->citySuffix(),
            'city' => fake()->optional()->city(),
            'state' => fake()->optional()->stateAbbr(),
            'number' => fake()->optional()->buildingNumber(),
            'complement' => fake()->optional()->secondaryAddress(),
        ];
    }

    public function male(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'Masculino',
        ]);
    }

    public function female(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'Feminino',
        ]);
    }

    public function married(): static
    {
        return $this->state(fn (array $attributes) => [
            'marital_status' => 'Casado',
        ]);
    }

    public function single(): static
    {
        return $this->state(fn (array $attributes) => [
            'marital_status' => 'Solteiro',
        ]);
    }
}
