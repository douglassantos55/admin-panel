<?php

namespace Tests\Feature\Period;

use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreatePeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('periods.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('periods.create'));
        $response->assertForbidden();

        $response = $this->post(route('periods.store'));
        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('periods.create'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Period/Form')
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('periods.store'), [
            'name' => '',
            'qty_days' => 'one',
        ]);

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
            'qty_days' => 'O campo Qtd dias deve conter um número inteiro.',
        ]);
    }

    public function test_no_qty_days()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('periods.store'), [
            'name' => 'Diario',
            'qty_days' => '',
        ]);

        $response->assertInvalid([
            'qty_days' => 'O campo Qtd dias é obrigatório.',
        ]);
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('periods.store'), [
            'name' => 'Diario',
            'qty_days' => '1',
        ]);

        $response->assertRedirect(route('periods.index'));
    }

    public function test_saves_period()
    {
        Auth::login(User::factory()->create());

        $this->post(route('periods.store'), [
            'name' => 'Diario',
            'qty_days' => '1',
        ]);

        $this->assertModelExists(Period::where('name', 'Diario')->first());
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('periods.store'), [
            'name' => 'Diario',
            'qty_days' => '1',
        ]);

        $response->assertSessionHas('flash', 'Período cadastrado');
    }
}
