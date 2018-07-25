<?php

namespace Tests\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_success_login()
    {
        $user = create(User::class);

        $this->postJson(route('login'), [
            'email'    => $user->email,
            'password' => '111111',
        ])->assertStatus(200)
             ->assertJsonStructure([
                 'token',
                 'token_type',
                 'expires_in',
                 'user',
             ])->assertJsonFragment(['email' => $user->email]);
    }

    /** @test */
    public function it_get_422_error_if_email_incorrect()
    {
        $this->postJson(route('login'), [
            'email'    => $this->faker->unique()->email,
            'password' => '111111',
        ])->assertStatus(422);
    }

    /** @test */
    public function it_get_422_if_password_incorrect()
    {
        $user = create(User::class);

        $this->postJson(route('login'), [
            'email'    => $user->email,
            'password' => str_random(),
        ])->assertStatus(422);
    }

    /** @test */
    public function it_can_success_logout()
    {
        $user = create(User::class);

        $token = $this->postJson(route('login'), [
            'email'    => $user->email,
            'password' => '111111',
        ])->json()['token'];

        $this->postJson(route('logout'), compact('token'))
             ->assertStatus(204);
    }
}
