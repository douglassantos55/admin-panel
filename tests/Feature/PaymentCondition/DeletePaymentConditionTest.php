<?php

namespace Tests\Feature\PaymentCondition;

use App\Models\PaymentCondition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeletePaymentConditionTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $response = $this->delete(route('payment_conditions.destroy', $condition->id));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $response = $this->delete(route('payment_conditions.destroy', $condition->id));
        $response->assertForbidden();
    }

    public function test_soft_deletes()
    {
        Auth::login(User::factory()->create());
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $this->delete(route('payment_conditions.destroy', $condition->id));
        $this->assertSoftDeleted($condition, [], null, 'deletedAt');
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $response = $this->delete(route('payment_conditions.destroy', $condition->id));
        $response->assertRedirect(route('payment_conditions.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $response = $this->delete(route('payment_conditions.destroy', $condition->id));
        $response->assertSessionHas('flash', 'Condição cadastrada');
    }
}
