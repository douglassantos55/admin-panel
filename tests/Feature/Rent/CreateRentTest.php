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

class CreateRentTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('rents.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('rents.create'));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('rents.create'));

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
        );
    }

    public function test_general_validation()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('rents.store'), [
            'customer_id' => '1',
            'period_id' => '1',
            'start_date' => '2021-12-20',
            'start_hour' => '25:30',
            'end_date' => '2021-12-10',
            'end_hour' => '10:89',
            'payment_type_id' => '1',
            'payment_condition_id' => '1',
            'qty_days' => 'ten',
            'transporter_id' => '1',
            'discount' => 'five',
            'payment_method_id' => '1',
            'items' => [],
        ]);

        $response->assertInvalid([
            'customer_id' => 'O campo Cliente n??o ?? v??lido.',
            'period_id' => 'O campo Per??odo n??o ?? v??lido.',
            'start_hour' => 'O campo Hora in??cio n??o possui o formato H:i.',
            'end_date' => 'O campo Data de t??rmino deve ser uma data maior ou igual a Data de in??cio.',
            'end_hour' => 'O campo Hora t??rmino n??o possui o formato H:i.',
            'items' => 'O campo items ?? obrigat??rio.',
            'payment_type_id' => 'O campo Tipo pagamento n??o ?? v??lido.',
            'payment_condition_id' => 'O campo Condi????o pagamento n??o ?? v??lido.',
            'qty_days' => 'O campo Qtd dias deve conter um n??mero inteiro.',
            'transporter_id' => 'O campo Transportador n??o ?? v??lido.',
            'discount' => 'O campo Desconto deve ser um n??mero.',
            'payment_method_id' => 'O campo Forma pagamento n??o ?? v??lido.',
        ]);
    }

    public function test_required_bill()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('rents.store'), [
            'start_date' => '2025-12-31',
            'start_hour' => '20:00',
            'end_date' => '2026-01-10',
            'end_hour' => '20:00',
            'bill' => '',
            'paid_value' => '100,00',
        ]);

        $response->assertInvalid([
            'paid_value' => 'O campo Valor pago deve ser um n??mero.',
            'bill' => 'O campo C??dula ?? obrigat??rio.',
        ]);

        $response = $this->post(route('rents.store'), [
            'start_date' => '2025-12-31',
            'start_hour' => '20:00',
            'end_date' => '2026-01-10',
            'end_hour' => '20:00',
            'bill' => '',
            'paid_value' => '0.00'
        ]);

        $response->assertValid(['paid_value', 'bill']);
    }

    public function test_required_check_info()
    {
        Auth::login(User::factory()->create());
        PaymentMethod::factory()->create(['name' => 'Cheque']);

        $response = $this->post(route('rents.store'), [
            'start_date' => '2025-12-31',
            'start_hour' => '20:00',
            'end_date' => '2026-01-10',
            'end_hour' => '20:00',
            'check_info' => '',
            'payment_method_id' => '1',
        ]);

        $response->assertInvalid([
            'check_info' => 'O campo Dados cheque(s) ?? obrigat??rio.',
        ]);

        PaymentMethod::factory()->create(['name' => 'Boleto']);

        $response = $this->post(route('rents.store'), [
            'check_info' => '',
            'payment_method_id' => '2',
        ]);

        $response->assertValid('check_info');
    }

    public function test_validates_items()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('rents.store'), [
            'start_date' => '2025-12-31',
            'start_hour' => '20:00',
            'end_date' => '2026-01-10',
            'end_hour' => '20:00',
            'items' => [
                ['equipment_id' => '', 'qty' => ''],
                ['equipment_id' => null, 'qty' => 'five'],
                ['equipment_id' => '3', 'qty' => '2'],
            ],
        ]);

        $response->assertInvalid([
            'items.0.equipment_id' => 'O campo Equipamento ?? obrigat??rio.',
            'items.0.qty' => 'O campo Qtd ?? obrigat??rio.',
            'items.1.equipment_id' => 'O campo Equipamento ?? obrigat??rio.',
            'items.1.qty' => 'O campo Qtd deve ser um n??mero.',
            'items.2.equipment_id' => 'O campo Equipamento n??o ?? v??lido.',
        ]);
    }

    public function test_saves_rent()
    {
        Auth::login(User::factory()->create());

        Customer::factory()->create();
        Period::factory()->create();
        $type = PaymentType::factory()->create();
        PaymentCondition::factory()->for($type)->create();
        PaymentMethod::factory()->create();
        Transporter::factory()->create();

        Equipment::factory()->count(3)->create();

        $this->post(route('rents.store'), [
            'customer_id' => '1',
            'period_id' => '1',
            'start_date' => '2025-12-20',
            'start_hour' => '23:30',
            'end_date' => '2026-02-10',
            'end_hour' => '23:30',
            'payment_type_id' => '1',
            'payment_condition_id' => '1',
            'qty_days' => '10',
            'transporter_id' => '1',
            'discount' => '5',
            'payment_method_id' => '1',
            'items' => [
                ['equipment_id' => '1', 'qty' => '2'],
                ['equipment_id' => 2, 'qty' => '3'],
                ['equipment_id' => '3', 'qty' => '5'],
            ],
        ]);

        $this->assertModelExists(Rent::find(1));
    }

    public function test_saves_items()
    {
        Auth::login(User::factory()->create());

        Customer::factory()->create();
        Period::factory()->create();
        $type = PaymentType::factory()->create();
        PaymentCondition::factory()->for($type)->create(['increment' => 0]);
        PaymentMethod::factory()->create();
        Transporter::factory()->create();

        $equipments = Equipment::factory()->count(3)->create();
        foreach ($equipments as $key => $equipment) {
            $equipment->values()->create([
                'period_id' => '1',
                'value' => $key + 0.6,
            ]);
        }

        $this->post(route('rents.store'), [
            'customer_id' => '1',
            'period_id' => '1',
            'start_date' => '2025-12-20',
            'start_hour' => '23:30',
            'end_date' => '2026-02-10',
            'end_hour' => '23:30',
            'payment_type_id' => '1',
            'payment_condition_id' => '1',
            'qty_days' => '10',
            'transporter_id' => '1',
            'discount' => '5',
            'payment_method_id' => '1',
            'items' => [
                ['equipment_id' => '1', 'qty' => '2'],
                ['equipment_id' => 2, 'qty' => '3'],
                ['equipment_id' => '3', 'qty' => '5'],
            ],
        ]);

        $rent = Rent::find(1);

        $this->assertCount(3, $rent->items()->get());

        $this->assertEquals(2, $rent->items[0]->qty);
        $this->assertEquals(3, $rent->items[1]->qty);
        $this->assertEquals(5, $rent->items[2]->qty);

        $this->assertEquals(1, $rent->items[0]->equipment_id);
        $this->assertEquals(2, $rent->items[1]->equipment_id);
        $this->assertEquals(3, $rent->items[2]->equipment_id);

        $this->assertEquals(0.6, $rent->items[0]->rent_value);
        $this->assertEquals(1.6, $rent->items[1]->rent_value);
        $this->assertEquals(2.6, $rent->items[2]->rent_value);
    }

    public function test_redirect()
    {
        Auth::login(User::factory()->create());

        Customer::factory()->create();
        Period::factory()->create();
        $type = PaymentType::factory()->create();
        PaymentCondition::factory()->for($type)->create();
        PaymentMethod::factory()->create();
        Transporter::factory()->create();

        $equipments = Equipment::factory()->count(3)->create();
        foreach ($equipments as $key => $equipment) {
            $equipment->values()->create([
                'period_id' => '1',
                'value' => $key + 0.6,
            ]);
        }

        $response = $this->post(route('rents.store'), [
            'customer_id' => '1',
            'period_id' => '1',
            'start_date' => '2025-12-20',
            'start_hour' => '23:30',
            'end_date' => '2026-02-10',
            'end_hour' => '23:30',
            'payment_type_id' => '1',
            'payment_condition_id' => '1',
            'qty_days' => '10',
            'transporter_id' => '1',
            'discount' => '5',
            'payment_method_id' => '1',
            'items' => [
                ['equipment_id' => '1', 'qty' => '2'],
                ['equipment_id' => 2, 'qty' => '3'],
                ['equipment_id' => '3', 'qty' => '5'],
            ],
        ]);

        $response->assertRedirect(route('rents.view', 1));
        $response->assertSessionHas('flash', 'Loca????o cadastrada');
    }

    public function test_saves_hours()
    {
        Auth::login(User::factory()->create());

        Customer::factory()->create();
        Period::factory()->create();
        $type = PaymentType::factory()->create();
        PaymentCondition::factory()->for($type)->create();
        PaymentMethod::factory()->create();
        Transporter::factory()->create();

        $equipments = Equipment::factory()->count(3)->create();
        foreach ($equipments as $key => $equipment) {
            $equipment->values()->create([
                'period_id' => '1',
                'value' => $key + 0.6,
            ]);
        }

        $this->post(route('rents.store'), [
            'customer_id' => '1',
            'period_id' => '1',
            'start_date' => '2025-12-20',
            'start_hour' => '23:30',
            'end_date' => '2026-02-10',
            'end_hour' => '23:30',
            'payment_type_id' => '1',
            'payment_condition_id' => '1',
            'qty_days' => '10',
            'transporter_id' => '1',
            'discount' => '5',
            'payment_method_id' => '1',
            'items' => [
                ['equipment_id' => '1', 'qty' => '2'],
                ['equipment_id' => 2, 'qty' => '3'],
                ['equipment_id' => '3', 'qty' => '5'],
            ],
        ]);

        $rent = Rent::find(1);
        $this->assertEquals('2025-12-20', $rent->start_date->format('Y-m-d'));
        $this->assertEquals('2026-02-10', $rent->end_date->format('Y-m-d'));
        $this->assertEquals('23:30', $rent->start_hour);
        $this->assertEquals('23:30', $rent->end_hour);
    }
}
