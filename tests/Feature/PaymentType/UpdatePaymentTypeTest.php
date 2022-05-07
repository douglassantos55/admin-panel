<?php

namespace Tests\Feature\PaymentType;

use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UpdatePaymentTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $paymentType = PaymentType::factory()->create();

        $response = $this->get(route('payment_types.edit', $paymentType->id));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));
        $paymentType = PaymentType::factory()->create();

        $response = $this->get(route('payment_types.edit', $paymentType->id));
        $response->assertForbidden();

        $response = $this->put(route('payment_types.update', $paymentType->id));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());
        $paymentType = PaymentType::factory()->create();

        $response = $this->get(route('payment_types.edit', $paymentType->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentType/Form')
                ->where('payment_type', $paymentType->refresh())
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());
        $paymentType = PaymentType::factory()->create();

        $response = $this->put(route('payment_types.update', $paymentType->id));

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
        ]);
    }

    public function test_updates_payment_type()
    {
        Auth::login(User::factory()->create());
        $paymentType = PaymentType::factory()->create();

        $this->put(route('payment_types.update', $paymentType->id), [
            'name' => 'Faturado',
        ]);

        $paymentType->refresh();
        $this->assertEquals('Faturado', $paymentType->name);
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());
        $paymentType = PaymentType::factory()->create();

        $response = $this->put(route('payment_types.update', $paymentType->id), [
            'name' => 'Faturado',
        ]);

        $response->assertRedirect(route('payment_types.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());
        $paymentType = PaymentType::factory()->create();

        $response = $this->put(route('payment_types.update', $paymentType->id), [
            'name' => 'Faturado',
        ]);

        $response->assertSessionHas('flash', 'Dados atualizados');
    }
}
