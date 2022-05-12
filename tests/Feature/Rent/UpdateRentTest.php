<?php

namespace Tests\Feature\Rent;

use App\Models\Customer;
use App\Models\Equipment;
use App\Models\PaymentCondition;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Period;
use App\Models\Rent;
use App\Models\Transporter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UpdateRentTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $rent = Rent::factory()->create();
        $response = $this->get(route('rents.edit', $rent->id));

        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $rent = Rent::factory()->create();
        $response = $this->get(route('rents.edit', $rent->id));

        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());

        $rent = Rent::factory()->create();
        $response = $this->get(route('rents.edit', $rent->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Rent/Form')
                ->has('periods')
                ->has('customers')
                ->has('equipments')
                ->has('payment_types')
                ->has('payment_conditions')
                ->has('payment_methods')
                ->has('transporters')
                ->where('rent', $rent->refresh())
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());
        $rent = Rent::factory()->create();

        $response = $this->put(route('rents.update', $rent->id), [
            'customer_id' => '1',
            'period_id' => '1',
            'start_date' => '2021-12-20 23:30',
            'end_date' => '2021-12-10 23:10',
            'payment_type_id' => '1',
            'payment_condition_id' => '1',
            'qty_days' => 'ten',
            'transporter_id' => '1',
            'discount' => 'five',
            'payment_method_id' => '1',
            'items' => [],
        ]);

        $response->assertInvalid([
            'customer_id' => 'O campo Cliente não é válido.',
            'period_id' => 'O campo Período não é válido.',
            'start_date' => 'O campo Data de início deve ser uma data maior ou igual a hoje.',
            'end_date' => 'O campo Data de término deve ser uma data maior ou igual a Data de início.',
            'items' => 'O campo items é obrigatório.',
            'payment_type_id' => 'O campo Tipo pagamento não é válido.',
            'payment_condition_id' => 'O campo Condição pagamento não é válido.',
            'qty_days' => 'O campo Qtd dias deve conter um número inteiro.',
            'transporter_id' => 'O campo Transportador não é válido.',
            'discount' => 'O campo Desconto deve ser um número.',
            'payment_method_id' => 'O campo Forma pagamento não é válido.',
        ]);
    }

    public function test_updates_rent()
    {
        Auth::login(User::factory()->create());
        $rent = Rent::factory()->create(['period_id' => 1]);

        Customer::factory()->create();
        Period::factory()->create();
        $type = PaymentType::factory()->create();
        PaymentCondition::factory()->for($type)->create();
        PaymentMethod::factory()->create();
        Transporter::factory()->create();

        $equipments = Equipment::factory()->count(3)->create(['unit_value' => 1000]);
        foreach ($equipments as $key => $equipment) {
            $equipment->values()->create([
                'period_id' => '1',
                'value' => $key + 0.6,
            ]);
        }

        $rent->items()->create(['equipment_id' => 1, 'qty' => 1]);

        $response = $this->put(route('rents.update', $rent->id), [
            'customer_id' => '1',
            'period_id' => '1',
            'start_date' => '2025-12-20 23:30',
            'end_date' => '2026-02-10 23:30',
            'payment_type_id' => '1',
            'payment_condition_id' => '1',
            'qty_days' => '10',
            'transporter_id' => '1',
            'discount' => '5',
            'paid_value' => '2',
            'bill' => '3',
            'payment_method_id' => '1',
            'items' => [
                ['equipment_id' => '1', 'qty' => '2'],
                ['equipment_id' => 2, 'qty' => '3'],
                ['equipment_id' => '3', 'qty' => '5'],
            ],
        ]);

        $response->assertRedirect(route('rents.index'));
        $response->assertSessionHas('flash', 'Dados atualizados');

        $rent->refresh();
        $this->assertCount(3, $rent->items);
        $this->assertEquals(5, $rent->discount);
        $this->assertEquals(1, $rent->change);
        $this->assertEquals(19, $rent->total_rent_value);
        $this->assertEquals(10000, $rent->total_unit_value);
        $this->assertEquals(14, $rent->total);
    }
}
