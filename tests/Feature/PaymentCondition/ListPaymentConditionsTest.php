<?php

namespace Tests\Feature\PaymentCondition;

use App\Models\PaymentCondition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ListPaymentConditionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('payment_conditions.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        $response = $this->get(route('payment_conditions.index'));
        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('payment_conditions.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentCondition/List')
        );
    }

    public function test_paginates_results()
    {
        Auth::login(User::factory()->create());
        PaymentCondition::factory(100)->forPaymentType()->create();

        $response = $this->get(route('payment_conditions.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentCondition/List')
                ->has('payment_conditions.data', 15)
        );
    }
}
