<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Vhiepp\VNDataFaker\VNFaker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = VNFaker::firstname(rand(1, 2));
        $lastName = VNFaker::lastname();
        $fullName = $lastName . ' ' . $firstName;
        $dateOfBirth = fake()->date();
        return [
            'full_name' => $fullName,
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => VNFaker::email([], str($firstName)->slug('') . str($dateOfBirth)->slug('')),
            'date_of_birth' => $dateOfBirth,
            'gender' => VNFaker::gender(),
            'address' => VNFaker::address(rand(1, 2)),
            'role' => 'user',
            'maxim' => ''
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
