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

    public function test_lists_users()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        Customer::factory()->count(10)->create();
        $response = $this->get(route('customers.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
                $page->component('Customer/List')->has('customers', 10)
        );
    }
}
