<?php

namespace Tests\Feature\PaymentMethod;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreatePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('payment_methods.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        $response = $this->get(route('payment_methods.create'));
        $response->assertForbidden();

        $response = $this->post(route('payment_methods.store'));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('payment_methods.create'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentMethod/Form')
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('payment_methods.store'), [
            'name' => '',
        ]);

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
        ]);
    }

    public function test_saves_payment_method()
    {
        Auth::login(User::factory()->create());

        $this->post(route('payment_methods.store'), [
            'name' => 'Cartão de Crédito',
        ]);

        $this->assertModelExists(PaymentMethod::where('name', 'Cartão de Crédito')->first());
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('payment_methods.store'), [
            'name' => 'Cartão de Crédito',
        ]);

        $response->assertRedirect(route('payment_methods.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('payment_methods.store'), [
            'name' => 'Cartão de Crédito',
        ]);

        $response->assertSessionHas('flash', 'Forma de pagamento cadastrada');
    }
}
