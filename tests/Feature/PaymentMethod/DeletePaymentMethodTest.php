<?php

namespace Tests\Feature\PaymentMethod;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeletePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $method = PaymentMethod::factory()->create();

        $response = $this->delete(route('payment_methods.destroy', $method->id));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        $method = PaymentMethod::factory()->create();

        $response = $this->delete(route('payment_methods.destroy', $method->id));
        $response->assertForbidden();
    }

    public function test_soft_deletes()
    {
        Auth::login(User::factory()->create());

        $method = PaymentMethod::factory()->create();
        $this->delete(route('payment_methods.destroy', $method->id));

        $this->assertSoftDeleted($method, [], null, 'deletedAt');
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());

        $method = PaymentMethod::factory()->create();
        $response = $this->delete(route('payment_methods.destroy', $method->id));

        $response->assertRedirect(route('payment_methods.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $method = PaymentMethod::factory()->create();
        $response = $this->delete(route('payment_methods.destroy', $method->id));

        $response->assertSessionHas('flash', 'Forma de pagamento excluida');
    }
}
