<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_component()
    {
        $response = $this->get(route('login'));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component('Auth/Login')
        );
    }

    public function test_redirects_if_already_authenticated()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('login'));
        $response->assertRedirect(route('dashboard'));

        $response = $this->post(route('authenticate'), [
            'email' => 'email@email.com',
            'password' => 'password',
        ]);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_invalid_email()
    {
        $response = $this->post(route('authenticate'), [
            'email' => 'email#email.com',
            'password' => '123456',
        ]);

        $response->assertInvalid([
            'email' => 'O campo email deve ser um endereco de email valido.'
        ]);
    }

    public function test_wrong_email()
    {
        $response = $this->post(route('authenticate'), [
            'email' => 'email@email.com',
            'password' => '123456',
        ]);

        $response->assertInvalid([
            'email' => 'Nenhum usuario encontrado para o e-mail e senha informados.'
        ]);
    }

    public function test_wrong_password()
    {
        $user = User::factory()->create();

        $response = $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => '123456',
        ]);

        $response->assertInvalid([
            'email' => 'Nenhum usuario encontrado para o e-mail e senha informados.'
        ]);
    }

    public function test_authentication()
    {
        $user = User::factory()->create();

        $response = $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }
}
