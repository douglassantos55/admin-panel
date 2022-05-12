<?php

namespace Tests\Feature\Rent;

use App\Models\Rent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ListRentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('rents.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('rents.index'));
        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('rents.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Rent/List')->has('rents')->has('customers')
        );
    }

    public function test_paginates_result()
    {
        Rent::factory()->count(100)->create();

        Auth::login(User::factory()->create());
        $response = $this->get(route('rents.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Rent/List')->has('rents.data', 15)
        );
    }

    public function test_filters_results_by_id()
    {
        Rent::factory()->count(100)->create();

        Auth::login(User::factory()->create());
        $response = $this->get(route('rents.index', [
            'number' => '10',
        ]));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Rent/List')->has('rents.data', 1)
        );
    }

    public function test_filters_results_by_customer()
    {
        Rent::factory()->count(100)->create();
        Rent::factory()->create(['customer_id' => '100']);

        Auth::login(User::factory()->create());
        $response = $this->get(route('rents.index', [
            'customer' => '100',
        ]));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Rent/List')->has('rents.data', 1)
        );
    }
}
