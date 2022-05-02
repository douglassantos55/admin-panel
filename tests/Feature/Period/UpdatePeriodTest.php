<?php

namespace Tests\Feature\Period;

use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UpdatePeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $period = Period::factory()->create();
        $response = $this->get(route('periods.edit', $period->id));

        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $period = Period::factory()->create();

        $response = $this->get(route('periods.edit', $period->id));
        $response->assertForbidden();

        $response = $this->put(route('periods.update', $period->id));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());

        $period = Period::factory()->create();
        $response = $this->get(route('periods.edit', $period->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Period/Form')->where('period', $period->refresh())
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());
        $period = Period::factory()->create();

        $response = $this->put(route('periods.update', $period->id), [
            'name' => '',
            'qty_days' => 'seven',
        ]);

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
            'qty_days' => 'O campo Qtd dias deve conter um número inteiro.',
        ]);
    }

    public function test_redirects()
    {
        Auth::login(User::factory()->create());
        $period = Period::factory()->create();

        $response = $this->put(route('periods.update', $period->id), [
            'name' => 'Semanal',
            'qty_days' => 7,
        ]);

        $response->assertRedirect(route('periods.index'));
    }

    public function test_updates_period()
    {
        Auth::login(User::factory()->create());
        $period = Period::factory()->create(['name' => 'Diario', 'qty_days' => 1]);

        $this->put(route('periods.update', $period->id), [
            'name' => 'Semanal',
            'qty_days' => 7,
        ]);

        $this->assertEquals('Semanal', $period->refresh()->name);
        $this->assertEquals(7, $period->refresh()->qty_days);
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());
        $period = Period::factory()->create(['name' => 'Diario', 'qty_days' => 1]);

        $response = $this->put(route('periods.update', $period->id), [
            'name' => 'Semanal',
            'qty_days' => 7,
        ]);

        $response->assertSessionHas('flash', 'Dados atualizados');
    }
}
