<?php


namespace Tests\Api;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_only_view_its_own_profile_data()
    {
        // make a user
        $user = User::factory()->create();
        // make a user with a different id
        $user2 = User::factory()->create();

        // make an api call to user.show
        $this->get(route('user.show'))
            ->assertStatus(302);

        // login via passport
        Passport::actingAs($user);

        // make an api call to user.show
        $this->get(route('user.show'))
            ->assertStatus(200)
            ->assertSee($user['email'])
            ->assertSee($user['name']);

        Passport::actingAs($user2);

        // make an api call to user.show
        $this->get(route('user.show'))
            ->assertStatus(200)
            ->assertSee($user2['email'])
            ->assertSee($user2['name']);
    }

    /** @test */
    public function a_user_update_its_name()
    {
        // make a user
        $user = User::factory()->create();
        // make a user with a different id
        $user2 = User::factory()->create();

        // login via passport
        Passport::actingAs($user);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user]), [
                'name' => 'new name 1'
            ])
            ->assertStatus(200)
            ->assertSee('new name 1');

        // login via passport
        Passport::actingAs($user2);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user2]), [
                'name' => 'new name 2'
            ])
            ->assertStatus(200)
            ->assertSee('new name 2');

        // login as the second user
        Passport::actingAs($user2);

        // make an api call to the first users profile as the second user
        $this->post(route('user.update', ['user' => $user]), [
                'name' => 'new name 2'
            ])
            ->assertStatus(403);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user2]), [
                'name' => 'new name again'
            ])
            ->assertStatus(200)
            ->assertSee('new name again');
    }

    /** @test */
    public function a_user_update_its_password()
    {
        $first = 'password1';
        $second = 'password2';
        // make a user
        $user = User::factory()->create([
            'password' => Hash::make($first)
        ]);

        // login via passport
        Passport::actingAs($user);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user]), [
                'old_password' => $first,
                'password' => $second,
                'password_confirmation' => $second
            ])
            ->assertStatus(200)
            ->assertSee($user['name'])
            ->assertSee($user['email']);

        $user->refresh();
        $this->assertTrue(Hash::check($second, $user->password));
    }

    /** @test */
    public function a_user_must_have_the_correct_old_password_to_update_to_a_new_password()
    {
        $first = 'password1';
        $second = 'password2';
        // make a user
        $user = User::factory()->create([
            'password' => Hash::make($first)
        ]);

        // login via passport
        Passport::actingAs($user);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user]), [
                'old_password' => $second,
                'password' => $second,
                'password_confirmation' => $second
            ])
            ->assertStatus(302);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user]), [
                'old_password' => $first,
                'password' => $second,
                'password_confirmation' => $second
            ])
            ->assertStatus(200)
            ->assertSee($user['name'])
            ->assertSee($user['email']);
    }

    /** @test */
    public function a_user_must_have_the_confirm_the_new_password_to_update_to_a_new_password()
    {
        $first = 'password1';
        $second = 'password2';
        // make a user
        $user = User::factory()->create([
            'password' => Hash::make($first)
        ]);

        // login via passport
        Passport::actingAs($user);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user]), [
                'old_password' => $first,
                'password' => $second,
                'password_confirmation' => $first
            ])
            ->assertStatus(302);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user]), [
                'old_password' => $first,
                'password' => $second,
                'password_confirmation' => $second
            ])
            ->assertStatus(200)
            ->assertSee($user['name'])
            ->assertSee($user['email']);
    }

    /** @test */
    public function a_user_can_change_its_email_address()
    {
        $first = 'test1@gmail.com';
        $second = 'test2@gmail.com';
        // make a user
        $user = User::factory()->create([
            'email' => $first
        ]);

        // login via passport
        Passport::actingAs($user);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user]), [
                'email' => $second,
            ])
            ->assertStatus(200)
            ->assertSee($user['name'])
            ->assertDontSee($first)
            ->assertSee($second);
    }

    /** @test */
    public function a_user_can_not_change_its_email_address_to_one_already_in_use()
    {
        $first = 'test1@gmail.com';
        $second = 'test2@gmail.com';
        // make a user
        $user1 = User::factory()->create([
            'email' => $first
        ]);

        $user2 = User::factory()->create([
            'email' => $second
        ]);

        // login via passport
        Passport::actingAs($user1);

        // make an api call to user.show
        $this->post(route('user.update', ['user' => $user1]), [
                'email' => $first,
            ])
            ->assertStatus(302);
    }
}
