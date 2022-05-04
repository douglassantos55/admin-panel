<?php

namespace Tests\Feature\Equipment;

use App\Models\Equipment;
use App\Models\Period;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreateEquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('equipments.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('equipments.create'));
        $response->assertForbidden();

        $response = $this->post(route('equipments.store'));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('equipments.create'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Equipment/Form')->has('periods')->has('suppliers')
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('equipments.store'), [
            'description' => '',
            'unit' => 'm/l',
            'profit_percentage' => 5,
            'weight' => 1.5,
            'in_stock' => 348.59,
            'effective_qty' => 300.44,
            'min_qty' => 100.33,
            'purchase_value' => 1500.00,
            'unit_value' => 143.5,
            'replace_value' => 314.00,
            'supplier_id' => null,
            'values' => [
                ['period_id' => 1, 'value' => '0,6'],
                ['period_id' => 2, 'value' => 1.2],
                ['period_id' => 3, 'value' => 1.8],
            ],
        ]);

        $response->assertInvalid([
            'description' => 'O campo Descrição é obrigatório.',
            'in_stock' => 'O campo Em estoque deve conter um número inteiro.',
            'effective_qty' => 'O campo Qtd efetiva deve conter um número inteiro.',
            'min_qty' => 'O campo Qtd minima deve conter um número inteiro.',
            'values.0.period_id' => 'O campo Periodo não é válido.',
            'values.0.value' => 'O campo Valor deve ser um número.',
            'values.1.period_id' => 'O campo Periodo não é válido.',
            'values.2.period_id' => 'O campo Periodo não é válido.',
        ]);
    }

    public function test_saves_equipment()
    {
        Auth::login(User::factory()->create());

        $this->post(route('equipments.store'), [
            'description' => 'Escora',
            'unit' => 'm/l',
            'profit_percentage' => 5,
            'weight' => 1.5,
            'in_stock' => 348,
            'effective_qty' => 300,
            'min_qty' => 100,
            'purchase_value' => 1500.00,
            'unit_value' => 143.5,
            'replace_value' => 314.00,
            'supplier_id' => null,
        ]);

        $this->assertModelExists(Equipment::where('description', 'Escora')->first());
    }

    public function test_redirects()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('equipments.store'), [
            'description' => 'Escora',
            'unit' => 'm/l',
            'profit_percentage' => 5,
            'weight' => 1.5,
            'in_stock' => 348,
            'effective_qty' => 300,
            'min_qty' => 100,
            'purchase_value' => 1500.00,
            'unit_value' => 143.5,
            'replace_value' => 314.00,
            'supplier_id' => null,
        ]);

        $response->assertRedirect(route('equipments.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('equipments.store'), [
            'description' => 'Escora',
            'unit' => 'm/l',
            'profit_percentage' => 5,
            'weight' => 1.5,
            'in_stock' => 348,
            'effective_qty' => 300,
            'min_qty' => 100,
            'purchase_value' => 1500.00,
            'unit_value' => 143.5,
            'replace_value' => 314.00,
            'supplier_id' => null,
        ]);

        $response->assertSessionHas('flash', 'Equipamento cadastrado');
    }

    public function test_saves_supplier()
    {
        Auth::login(User::factory()->create());
        $supplier = Supplier::factory()->create();

        $this->post(route('equipments.store'), [
            'description' => 'Escora',
            'unit' => 'm/l',
            'profit_percentage' => 5,
            'weight' => 1.5,
            'in_stock' => 348,
            'effective_qty' => 300,
            'min_qty' => 100,
            'purchase_value' => 1500.00,
            'unit_value' => 143.5,
            'replace_value' => 314.00,
            'supplier_id' => $supplier->id,
        ]);

        $equipment = Equipment::first();
        $this->assertEquals($supplier->name, $equipment->supplier->name);
    }

    public function test_saves_renting_values()
    {
        Supplier::factory()->create();
        Period::factory()->count(3)->create();

        Auth::login(User::factory()->create());

        $this->post(route('equipments.store'), [
            'description' => 'Escora',
            'unit' => 'm/l',
            'profit_percentage' => 5,
            'weight' => 1.5,
            'in_stock' => 348,
            'effective_qty' => 300,
            'min_qty' => 100,
            'purchase_value' => 1500.00,
            'unit_value' => 143.5,
            'replace_value' => 314.00,
            'supplier_id' => null,
            'values' => [
                ['period_id' => 1, 'value' => 0.5],
                ['period_id' => 2, 'value' => 0.8],
                ['period_id' => 3, 'value' => 1.2],
            ],
        ]);

        $equipment = Equipment::first();

        $this->assertCount(3, $equipment->values);
        $this->assertEquals(0.5, $equipment->values[0]->value);
        $this->assertEquals(0.8, $equipment->values[1]->value);
        $this->assertEquals(1.2, $equipment->values[2]->value);
    }

    public function test_saves_non_validated_data()
    {
        Supplier::factory()->create();
        Auth::login(User::factory()->create());

        $this->post(route('equipments.store'), [
            'description' => 'Escora',
            'unit' => 'm/l',
            'profit_percentage' => 5,
            'weight' => 1.5,
            'in_stock' => 348,
            'effective_qty' => 300,
            'min_qty' => 100,
            'purchase_value' => 1500.00,
            'unit_value' => 143.5,
            'replace_value' => 314.00,
        ]);

        $equipment = Equipment::first();
        $this->assertEquals('m/l', $equipment->unit);
    }
}
