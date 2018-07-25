<?php

namespace Tests\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_success_register()
    {
        $request                          = make(User::class)->toArray();
        $request['password']              = '111111';
        $request['password_confirmation'] = '111111';

        $this->postJson(route('register'), $request)->assertStatus(201)
             ->assertJsonStructure([
                 'token',
                 'token_type',
                 'expires_in',
                 'user',
             ])->assertJsonFragment(['email' => $request['email']]);

        $this->assertTrue(User::query()->where(['email' => $request['email']])->exists());
    }

    /** @test */
    public function it_throw_exception_if_data_incorrect()
    {
        $request = make(User::class)->toArray();

        $this->postJson(route('register'), $request)
             ->assertStatus(422);
    }
}
