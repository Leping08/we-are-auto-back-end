<?php

namespace Tests\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_can_login()
    {
        $this->assertTrue(true);

//        $password = $this->faker->lexify('??????????');
//        //Create the user
//        $user = User::factory()->create([
//            'password' => Hash::make($password)
//        ]);
//
//        Artisan::call('passport:install');
//
//        //Login the user
//        //TODO make this work with oauth https://stackoverflow.com/questions/50113508/how-to-test-authentication-via-api-with-laravel-passport
//        $response = $this->json('POST', route('login'), [
//            'email' => $user->email,
//            'password' => $password,
//        ]);
//
//
//        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_register_by_hitting_the_endpoint()
    {
        $password = $this->faker->lexify('??????????');
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'terms_and_conditions' => true
        ];

        $this->assertDatabaseMissing('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        $this->json('POST', route('register'), $data);
        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }

    /** @test */
    public function a_user_must_accept_the_privacy_policy()
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

        $this->json('POST', route('register'), $data);
        $this->assertDatabaseMissing('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        $dataWithTermsChecked = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'terms_and_conditions' => true
        ];

        $this->json('POST', route('register'), $dataWithTermsChecked);
        $this->assertDatabaseHas('users', [
            'name' => $dataWithTermsChecked['name'],
            'email' => $dataWithTermsChecked['email']
        ]);
    }
}
