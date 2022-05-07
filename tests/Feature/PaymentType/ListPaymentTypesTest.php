<?php

namespace Tests\Feature\PaymentType;

use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ListPaymentTypesTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('payment_types.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        $response = $this->get(route('payment_types.index'));
        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('payment_types.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentType/List')
        );
    }

    public function test_paginates_results()
    {
        Auth::login(User::factory()->create());
        PaymentType::factory()->count(100)->create();

        $response = $this->get(route('payment_types.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentType/List')->has('payment_types.data', 15)
        );
    }
}
