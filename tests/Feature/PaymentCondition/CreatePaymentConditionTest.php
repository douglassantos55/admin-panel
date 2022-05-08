<?php

namespace Tests\Feature\PaymentCondition;

use App\Models\PaymentCondition;
use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreatePaymentConditionTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('payment_conditions.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        $response = $this->get(route('payment_conditions.create'));
        $response->assertForbidden();

        $response = $this->post(route('payment_conditions.store'));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('payment_conditions.create'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentCondition/Form')->has('payment_types')
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('payment_conditions.store'), [
            'name' => '',
            'increment' => 'ten',
            'payment_type_id' => '1',
            'installments' => ['zero', '-31', '7'],
        ]);

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
            'title' => 'O campo Título é obrigatório.',
            'increment' => 'O campo Taxa deve ser um número.',
            'payment_type_id' => 'O campo Tipo pagamento não é válido.',
            'installments.0' => 'O campo Parcela deve ser um número.',
            'installments.1' => 'O campo Parcela deve ser maior ou igual a 0.',
        ]);
    }

    public function test_creates_payment_condition()
    {
        Auth::login(User::factory()->create());

        PaymentType::factory()->create();

        $this->post(route('payment_conditions.store'), [
            'name' => 'À vista 7 dias',
            'title' => '7 DD',
            'increment' => '10',
            'payment_type_id' => '1',
            'installments' => ['7'],
        ]);

        $this->assertModelExists(PaymentCondition::where('name', 'À vista 7 dias')->first());
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());

        PaymentType::factory()->create();

        $response = $this->post(route('payment_conditions.store'), [
            'name' => 'À vista 7 dias',
            'title' => '7 DD',
            'increment' => '10',
            'payment_type_id' => '1',
            'installments' => ['7'],
        ]);

        $response->assertRedirect(route('payment_conditions.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        PaymentType::factory()->create();

        $response = $this->post(route('payment_conditions.store'), [
            'name' => 'À vista 7 dias',
            'title' => '7 DD',
            'increment' => '10',
            'payment_type_id' => '1',
            'installments' => ['7'],
        ]);

        $response->assertSessionHas('flash', 'Condição cadastrada');
    }
}
