<?php

namespace Tests\Feature\Transporter;

use App\Models\Transporter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UpdateTransporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $transporter = Transporter::factory()->create();

        $response = $this->get(route('transporters.edit', $transporter->id));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $transporter = Transporter::factory()->create();

        $response = $this->get(route('transporters.edit', $transporter->id));
        $response->assertForbidden();

        $response = $this->put(route('transporters.update', $transporter->id));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());

        $transporter = Transporter::factory()->create();
        $response = $this->get(route('transporters.edit', $transporter->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Transporter/Form')->has('transporter')
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());
        $transporter = Transporter::factory()->create();

        $response = $this->put(route('transporters.update', $transporter->id), [
            'name' => '',
        ]);

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
        ]);
    }

    public function test_updates_transporter()
    {
        Auth::login(User::factory()->create());
        $transporter = Transporter::factory()->create();

        $response = $this->put(route('transporters.update', $transporter->id), [
            'name' => 'Locadora',
        ]);

        $transporter->refresh();
        $this->assertEquals('Locadora', $transporter->name);

        $response->assertRedirect(route('transporters.index'));
        $response->assertSessionHas('flash', 'Dados atualizados');
    }
}
