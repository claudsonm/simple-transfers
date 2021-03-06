<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use App\Enums\UserPersonTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(UserPersonTypes::toArray());
        $document = UserPersonTypes::LEGAL_PERSON()->getValue() === $type
            ? $this->faker->cnpj(false)
            : $this->faker->cpf(false);

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'person_type' => $type,
            'document_number' => $document,
        ];
    }

    /**
     * Indicate that the user is a physical person.
     *
     * @return static
     */
    public function physical()
    {
        return $this->state(function (array $attributes) {
            return [
                'person_type' => UserPersonTypes::PHYSICAL_PERSON()->getValue(),
            ];
        });
    }

    /**
     * Indicate that the user is a legal person.
     *
     * @return static
     */
    public function legal()
    {
        return $this->state(function (array $attributes) {
            return [
                'person_type' => UserPersonTypes::LEGAL_PERSON()->getValue(),
            ];
        });
    }

    /**
     * @return static
     */
    public function withWallet()
    {
        return $this->has(Wallet::factory());
    }

    /**
     * @return static
     */
    public function withBalance(int $amountInCents = 10000)
    {
        return $this->has(Wallet::factory()->state(function () use ($amountInCents) {
            return ['balance' => $amountInCents];
        }));
    }
}
