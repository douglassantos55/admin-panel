<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('customers.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $response = $this->get(route('customers.index'));

        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));
        $response = $this->get(route('customers.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component('Customer/List')
        );
    }

    public function test_paginates_results()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        Customer::factory()->count(10)->create();
        $response = $this->get(route('customers.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Customer/List')->has('customers.data', 10)
        );
    }

    public function test_filters_results_by_name()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        Customer::factory()->count(9)->create();
        Customer::factory()->create(['name' => 'John Doe']);

        $response = $this->get(route('customers.index', ['name' => 'john']));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Customer/List')->has('customers.data', 1)
        );
    }

    public function test_filters_results_by_cpf()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        Customer::factory()->count(9)->create();
        Customer::factory()->create(['cpf_cnpj' => '380.443.710-95']);

        $response = $this->get(route('customers.index', ['cpf_cnpj' => '710-']));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Customer/List')->has('customers.data', 1)
        );
    }
}
