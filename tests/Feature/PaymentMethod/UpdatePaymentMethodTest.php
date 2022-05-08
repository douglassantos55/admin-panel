<?php

namespace Tests\Feature\PaymentMethod;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UpdatePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $method = PaymentMethod::factory()->create();

        $response = $this->get(route('payment_methods.edit', $method->id));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));
        $method = PaymentMethod::factory()->create();

        $response = $this->get(route('payment_methods.edit', $method->id));
        $response->assertForbidden();

        $response = $this->put(route('payment_methods.update', $method->id));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());

        $method = PaymentMethod::factory()->create();
        $response = $this->get(route('payment_methods.edit', $method->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentMethod/Form')
                ->where(
                    'payment_method',
                    $method->refresh()
                )
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());
        $method = PaymentMethod::factory()->create();

        $response = $this->put(route('payment_methods.update', $method->id), [
            'name' => '',
        ]);

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
        ]);
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());
        $method = PaymentMethod::factory()->create();

        $response = $this->put(route('payment_methods.update', $method->id), [
            'name' => 'Dinheiro',
        ]);

        $response->assertRedirect(route('payment_methods.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());
        $method = PaymentMethod::factory()->create();

        $response = $this->put(route('payment_methods.update', $method->id), [
            'name' => 'Dinheiro',
        ]);

        $response->assertSessionHas('flash', 'Dados atualizados');
    }

    public function test_updates_payment_method()
    {
        Auth::login(User::factory()->create());
        $method = PaymentMethod::factory()->create();

        $this->put(route('payment_methods.update', $method->id), [
            'name' => 'Dinheiro',
        ]);

        $method->refresh();
        $this->assertEquals('Dinheiro', $method->name);
    }
}
