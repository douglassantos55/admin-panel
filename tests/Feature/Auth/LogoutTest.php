<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logs_out()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertFalse(Auth::check());
    }
}
