<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_create_a_user_with_an_existing_email()
    {
        $frank = User::factory()->create(['email' => 'frank@sinatra.com']);
        $data = [
            'name' => 'Elis Regina',
            'email' => $frank->email,
            'password' => '!123456Elis',
            'password_confirmation' => '!123456Elis',
            'document_number' => '89862935014',
        ];

        $this->postJson('api/users', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'The email has already been taken.',
            ]);

        $this->assertDatabaseMissing('users', ['id' => 2]);
    }

    /** @test */
    public function it_cant_create_a_user_with_an_existing_document_number()
    {
        $marie = User::factory()->create();
        $data = [
            'name' => 'João Gilberto',
            'email' => 'joao@bossa.com',
            'password' => '!123456João',
            'password_confirmation' => '!123456João',
            'document_number' => $marie->document_number,
        ];

        $this->postJson('api/users', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'document_number' => 'The document number has already been taken.',
            ]);

        $this->assertDatabaseMissing('users', ['id' => 2]);
    }

    /** @test */
    public function it_can_create_a_physical_person_user()
    {
        $data = [
            'name' => 'Cartola',
            'email' => 'cartola@samba.com',
            'password' => '!123456Cartola',
            'password_confirmation' => '!123456Cartola',
            'document_number' => '89862935014',
        ];

        $this->postJson('api/users', $data)
            ->assertCreated()
            ->assertJsonPath('id', 1)
            ->assertDontSee('password');

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => 'Cartola',
            'email' => 'cartola@samba.com',
            'document_number' => '89862935014',
        ]);
    }

    /** @test */
    public function it_can_create_a_legal_person_user()
    {
        $data = [
            'name' => 'Umbrella Corporation',
            'email' => 'me@umbrellacorp.com',
            'password' => '!123456Evil',
            'password_confirmation' => '!123456Evil',
            'document_number' => '04461907000109',
        ];

        $this->postJson('api/users', $data)
            ->assertCreated()
            ->assertJsonPath('id', 1)
            ->assertDontSee('password');

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => 'Umbrella Corporation',
            'email' => 'me@umbrellacorp.com',
            'document_number' => '04461907000109',
        ]);
    }

    /** @test */
    public function it_opens_a_wallet_when_an_user_is_created()
    {
        $data = [
            'name' => 'Tim Maia',
            'email' => 'sindico@seroma.com',
            'password' => '!123456Tim',
            'password_confirmation' => '!123456Tim',
            'document_number' => '99935757048',
        ];

        $this->postJson('api/users', $data)
            ->assertCreated();

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => 'Tim Maia',
        ]);
        $this->assertDatabaseHas('wallets', [
            'id' => 1,
            'balance' => 0,
            'user_id' => 1,
        ]);
    }

    /** @test */
    public function it_securely_persists_the_password_in_the_database()
    {
        $data = [
            'name' => 'Jorge Ben Jor',
            'email' => 'jacarezinho@aviao.com',
            'password' => '!123456Ben',
            'password_confirmation' => '!123456Ben',
            'document_number' => '96111728008',
        ];

        $this->postJson('api/users', $data)
            ->assertCreated();

        $passwordPersisted = User::first()->password;
        $this->assertTrue(Hash::check('!123456Ben', $passwordPersisted));
    }
}
