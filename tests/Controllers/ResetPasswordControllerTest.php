<?php

namespace Tests\Controllers;

use App\Models\User;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    /** @test */
    public function it_can_success_reset_password()
    {
        /** @var User $user */
        $user = create(User::class);

        Notification::fake();

        Password::broker()->sendResetLink(['email' => $user->email]);

        /** @var DatabaseTokenRepository $databaseTokenRepo */
        $databaseTokenRepo = Password::getRepository();
        $token = $databaseTokenRepo->create($user);

        $this->postJson(route('password.reset'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => '333333',
            'password_confirmation' => '333333'
        ])->assertStatus(200);

        $this->assertEquals(\DB::table('password_resets')->count(), 0);
    }

    /** @test */
    public function it_can_not_change_password_if_incorrect_token()
    {
        /** @var User $user */
        $user = create(User::class);

        Notification::fake();

        Password::broker()->sendResetLink(['email' => $user->email]);

        $this->postJson(route('password.reset'), [
            'token'                 => str_random(),
            'email'                 => $user->email,
            'password'              => '333333',
            'password_confirmation' => '333333'
        ])->assertStatus(400);

        $this->assertEquals(\DB::table('password_resets')->count(), 1);
    }

    public function it_can_not_change_password_credentials_incorrect()
    {
        /** @var User $user */
        $user = create(User::class);

        Notification::fake();

        Password::broker()->sendResetLink(['email' => $user->email]);

        $this->postJson(route('password.reset'), [
            'token'                 => str_random(),
            'email'                 => $user->email,
            'password'              => '333333',
            'password_confirmation' => '111111'
        ])->assertStatus(422);
    }
}
