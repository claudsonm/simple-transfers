<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_assures_legal_persons_cant_perform_transactions()
    {
        $evilCorp = User::factory()->legal()->create();
        Sanctum::actingAs($evilCorp);

        $this->postJson('/api/transactions')
            ->assertForbidden();
    }

    /**
     * @test
     * @dataProvider invalidCurrencyFormats
     */
    public function it_rejects_invalid_currency_formats($value)
    {
        Sanctum::actingAs(User::factory()->physical()->create());

        $this->postJson('/api/transactions', ['value' => $value])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'value' => 'The value format is invalid.',
            ]);
    }

    /**
     * @test
     * @dataProvider invalidCurrencyAmounts
     */
    public function it_rejects_invalid_currency_amounts($amount, $error)
    {
        Sanctum::actingAs(User::factory()->physical()->create());

        $this->postJson('/api/transactions', ['value' => $amount])
            ->dump()
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'value' => $error,
            ]);
    }

    public function invalidCurrencyFormats(): array
    {
        return [
            ['-1'],
            ['.85'],
            ['25.'],
            ['8.000'],
            ['2.000,75'],
            [',45'],
            ['9,'],
            ['10,75'],
            ['R$ 20'],
        ];
    }

    public function invalidCurrencyAmounts(): array
    {
        return [
            ['0', 'The value must be at least 1.'],
            ['0.99', 'The value must be at least 1.'],
        ];
    }
}
