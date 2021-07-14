<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_can_register_by_hitting_the_endpoint()
    {
        $password = $this->faker->lexify('??????????');
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        $this->assertDatabaseMissing('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }
}
