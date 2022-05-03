<?php

namespace Tests\Feature\Period;

use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeletePeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $period = Period::factory()->create();
        $response = $this->delete(route('periods.destroy', $period->id));

        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $period = Period::factory()->create();
        $response = $this->delete(route('periods.destroy', $period->id));

        $response->assertForbidden();
    }

    public function test_soft_deletes()
    {
        Auth::login(User::factory()->create());

        $period = Period::factory()->create();
        $this->delete(route('periods.destroy', $period->id));

        $this->assertSoftDeleted($period);
    }

    public function test_redirects()
    {
        Auth::login(User::factory()->create());

        $period = Period::factory()->create();
        $response = $this->delete(route('periods.destroy', $period->id));

        $response->assertRedirect(route('periods.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $period = Period::factory()->create();
        $response = $this->delete(route('periods.destroy', $period->id));

        $response->assertSessionHas('flash', 'Periodo excluido');
    }
}
