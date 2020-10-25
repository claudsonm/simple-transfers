<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_physical_person_user()
    {
        $data = [
            'name' => 'Tom Jobim',
            'email' => 'tom@bossa.com',
            'password' => '!123456Tom',
            'password_confirmation' => '!123456Tom',
            'document_number' => '89862935014',
        ];
        $this->postJson('api/users', $data)
            ->assertCreated()
            ->assertJsonPath('id', 1)
            ->assertDontSee('password');

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => 'Tom Jobim',
            'email' => 'tom@bossa.com',
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
}
