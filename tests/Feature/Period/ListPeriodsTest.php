<?php

namespace Tests\Feature\Period;

use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ListPeriodsTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('periods.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $response = $this->get(route('periods.index'));

        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('periods.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Period/List')
        );
    }

    public function test_paginates_results()
    {
        Period::factory()->count(100)->create();

        Auth::login(User::factory()->create());
        $response = $this->get(route('periods.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Period/List')->has('periods.data', 15)
        );
    }
}
