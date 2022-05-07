<?php

namespace Tests\Feature\PaymentType;

use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreatePaymentTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('payment_types.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        $response = $this->get(route('payment_types.create'));
        $response->assertForbidden();

        $response = $this->post(route('payment_types.store'));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('payment_types.create'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentType/Form')
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('payment_types.store'), [
            'name' => '',
        ]);

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
        ]);
    }

    public function test_saves_payment_type()
    {
        Auth::login(User::factory()->create());

        $this->post(route('payment_types.store'), [
            'name' => 'Faturado',
        ]);

        $this->assertModelExists(PaymentType::where('name', 'Faturado')->first());
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('payment_types.store'), [
            'name' => 'Faturado',
        ]);

        $response->assertRedirect(route('payment_types.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('payment_types.store'), [
            'name' => 'Faturado',
        ]);

        $response->assertSessionHas('flash', 'Tipo cadastrado');
    }
}
