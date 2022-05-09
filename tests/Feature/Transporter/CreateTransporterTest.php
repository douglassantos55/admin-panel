<?php

namespace Tests\Feature\Transporter;

use App\Models\Transporter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreateTransporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('transporters.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('transporters.create'));
        $response->assertForbidden();

        $response = $this->post(route('transporters.store'));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());

        $response = $this->get(route('transporters.create'));
        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Transporter/Form'));
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('transporters.store'), [
            'name' => '',
        ]);

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
        ]);
    }

    public function test_creates_transporter()
    {
        Auth::login(User::factory()->create());

        $this->post(route('transporters.store'), [
            'name' => 'Locadora',
        ]);

        $this->assertModelExists(Transporter::where('name', 'Locadora')->first());
    }

    public function test_redirects()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('transporters.store'), [
            'name' => 'Locadora',
        ]);

        $response->assertRedirect(route('transporters.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('transporters.store'), [
            'name' => 'Locadora',
        ]);

        $response->assertSessionHas('flash', 'Transportador cadastrado');
    }
}
