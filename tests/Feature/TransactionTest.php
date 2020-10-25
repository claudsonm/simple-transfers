<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
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

    /** @test */
    public function it_doesnt_allow_transactions_to_non_existent_users()
    {
        Sanctum::actingAs(User::factory()->physical()->create());

        $this->postJson('/api/transactions', ['payee' => 10])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'payee' => 'The selected payee is invalid.',
            ]);
    }

    /** @test */
    public function it_doesnt_allow_transactions_for_the_current_authenticated_user()
    {
        $galCosta = User::factory()->physical()->create();
        Sanctum::actingAs($galCosta);

        $this->postJson('/api/transactions', ['payee' => $galCosta->id])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'payee' => 'The selected payee is invalid.',
            ]);
    }

    /** @test */
    public function it_doesnt_allow_an_payer_to_be_an_user_other_than_the_authenticated_user()
    {
        $dominguinhos = User::factory()->physical()->create();
        $luizGonzaga = User::factory()->physical()->create();
        Sanctum::actingAs($luizGonzaga);

        $this->postJson('/api/transactions', ['payer' => $dominguinhos->id])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'payer' => 'The selected payer is invalid.',
            ]);
    }

    /** @test */
    public function it_assures_the_user_has_enough_funds_to_perform_the_transaction()
    {
        $alceuValenca = null;
        User::withoutEvents(function () use (&$alceuValenca) {
            $alceuValenca = User::factory()
                ->physical()
                ->withBalance(2500)
                ->create();
            Sanctum::actingAs($alceuValenca);
        });

        $data = [
            'payer' => $alceuValenca->id,
            'value' => 50.25,
        ];
        $this->postJson('/api/transactions', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'value' => 'The value is higher than the balance available.',
            ]);
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
