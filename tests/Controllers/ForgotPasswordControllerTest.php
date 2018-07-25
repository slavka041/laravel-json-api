<?php

namespace Tests\Controllers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_success_send_email()
    {
        $user = create(User::class);

        Notification::fake();

        $this->postJson(route('password.email'), ['email' => $user->email])->assertStatus(200);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function it_not_sent_notification_if_email_incorrect()
    {
        Notification::fake();

        $this->postJson(route('password.email'), ['email' => $this->faker->unique()->email])
             ->assertStatus(422);

        Notification::assertNothingSent(ResetPassword::class);
    }
}
