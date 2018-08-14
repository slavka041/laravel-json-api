<?php

namespace Tests\Middlewares;

use App\Models\User;
use Tests\TestCase;

class ThrottleRequestsTest extends TestCase
{
    /** @test */
    public function dont_throw_429_exception_if_many_request_to_api_in_testing_mode()
    {
        $user = create(User::class);

        for ($i = 0; $i <= 100; $i++) {
            $this->postJson(route('login'), [
                'email'    => $user->email,
                'password' => '111111',
            ])->assertStatus(200);
        }
    }
}
