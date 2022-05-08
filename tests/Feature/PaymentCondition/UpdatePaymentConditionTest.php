<?php

namespace Tests\Feature\PaymentCondition;

use App\Models\PaymentCondition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UpdatePaymentConditionTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $response = $this->get(route('payment_conditions.edit', $condition->id));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $response = $this->get(route('payment_conditions.edit', $condition->id));
        $response->assertForbidden();

        $response = $this->put(route('payment_conditions.update', $condition->id));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());

        $condition = PaymentCondition::factory()->forPaymentType()->create();
        $response = $this->get(route('payment_conditions.edit', $condition->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('PaymentCondition/Form')
                ->has('payment_types')
                ->where(
                    'payment_condition',
                    $condition->refresh()
                )
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $response = $this->put(route('payment_conditions.update', $condition->id), [
            'name' => '',
            'increment' => 'ten',
            'payment_type_id' => '2',
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

    public function test_updates_payment_condition()
    {
        Auth::login(User::factory()->create());
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $this->put(route('payment_conditions.update', $condition->id), [
            'name' => 'A vista 7 dias',
            'payment_type_id' => '1',
            'title' => '7 DD',
            'increment' => '5',
            'installments' => ['7'],
        ]);

        $condition->refresh();

        $this->assertEquals('7 DD', $condition->title);
        $this->assertEquals('A vista 7 dias', $condition->name);
        $this->assertEquals([7], $condition->installments);
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $response = $this->put(route('payment_conditions.update', $condition->id), [
            'name' => 'A vista 7 dias',
            'title' => '7 DD',
            'payment_type_id' => '1',
        ]);

        $response->assertRedirect(route('payment_conditions.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());
        $condition = PaymentCondition::factory()->forPaymentType()->create();

        $response = $this->put(route('payment_conditions.update', $condition->id), [
            'name' => 'A vista 7 dias',
            'title' => '7 DD',
            'payment_type_id' => '1',
        ]);

        $response->assertSessionHas('flash', 'Dados atualizados');
    }
}
