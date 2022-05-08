<?php

namespace Tests\Feature\PaymentMethod;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ListPaymentMethodsTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('payment_methods.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        $response = $this->get(route('payment_methods.index'));
        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('payment_methods.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentMethod/List')
        );
    }

    public function test_paginates_results()
    {
        Auth::login(User::factory()->create());
        PaymentMethod::factory()->count(100)->create();

        $response = $this->get(route('payment_methods.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentMethod/List')->has('payment_methods.data', 15)
        );
    }
}
