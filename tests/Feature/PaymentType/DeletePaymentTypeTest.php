<?php

namespace Tests\Feature\PaymentType;

use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeletePaymentTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $paymentType = PaymentType::factory()->create();

        $response = $this->delete(route('payment_types.destroy', $paymentType->id));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));
        $paymentType = PaymentType::factory()->create();

        $response = $this->delete(route('payment_types.destroy', $paymentType->id));
        $response->assertForbidden();
    }

    public function test_soft_deletes()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $paymentType = PaymentType::factory()->create();
        $this->delete(route('payment_types.destroy', $paymentType->id));

        $this->assertSoftDeleted($paymentType, [], null, 'deletedAt');
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $paymentType = PaymentType::factory()->create();

        $response = $this->delete(route('payment_types.destroy', $paymentType->id));

        $response->assertRedirect(route('payment_types.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $paymentType = PaymentType::factory()->create();

        $response = $this->delete(route('payment_types.destroy', $paymentType->id));

        $response->assertSessionHas('flash', 'Tipo excluido');
    }
}
