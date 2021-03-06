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

class UpdateEquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $equipment = Equipment::factory()->create();
        $response = $this->get(route('equipments.edit', $equipment->id));

        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $equipment = Equipment::factory()->create();

        $response = $this->get(route('equipments.edit', $equipment->id));
        $response->assertForbidden();

        $response = $this->put(route('equipments.update', $equipment->id));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());

        Period::factory()->count(5)->create();
        Supplier::factory()->count(10)->create();

        $equipment = Equipment::factory()->create();
        $response = $this->get(route('equipments.edit', $equipment->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Equipment/Form')
                ->has('periods', 5)
                ->has('suppliers', 10)
                ->where('equipment', $equipment->refresh()->load(['supplier', 'values'])));
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());

        $equipment = Equipment::factory()->create();
        $response = $this->put(route('equipments.update', $equipment->id), [
            'in_stock' => 'a',
            'effective_qty' => 'a',
            'weight' => 'a',
            'unit_value' => 'a',
            'purchase_value' => 'a',
            'replace_value' => 'a',
            'min_qty' => 'a',
            'supplier_id' => 999,
        ]);

        $response->assertInvalid([
            'description' => 'O campo Descri????o ?? obrigat??rio.',
            'in_stock' => 'O campo Em estoque deve conter um n??mero inteiro.',
            'effective_qty' => 'O campo Qtd efetiva deve conter um n??mero inteiro.',
            'weight' => 'O campo Peso deve ser um n??mero.',
            'unit_value' => 'O campo Valor unit??rio deve ser um n??mero.',
            'purchase_value' => 'O campo Valor compra deve ser um n??mero.',
            'min_qty' => 'O campo Qtd m??nima deve conter um n??mero inteiro.',
            'supplier_id' => 'O campo Fornecedor n??o ?? v??lido.',
        ]);
    }

    public function test_updates_equipment()
    {
        Auth::login(User::factory()->create());
        $equipment = Equipment::factory()->create();

        $this->put(route('equipments.update', $equipment->id), [
            'description' => 'escora',
            'unit' => 'm/l',
        ]);

        $equipment->refresh();

        $this->assertEquals('m/l', $equipment->unit);
        $this->assertEquals('escora', $equipment->description);
    }

    public function test_redirects()
    {
        Auth::login(User::factory()->create());
        $equipment = Equipment::factory()->create();

        $response = $this->put(route('equipments.update', $equipment->id), [
            'description' => 'escora',
            'unit' => 'm/l',
        ]);

        $response->assertRedirect(route('equipments.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());
        $equipment = Equipment::factory()->create();

        $response = $this->put(route('equipments.update', $equipment->id), [
            'description' => 'escora',
            'unit' => 'm/l',
        ]);

        $response->assertSessionHas('flash', 'Dados atualizados');
    }

    public function test_updates_values()
    {
        Auth::login(User::factory()->create());
        $equipment = Equipment::factory()->create();

        Period::factory()->count(3)->create();

        $equipment->values()->createMany([
            ['period_id' => 1, 'value' => 100],
            ['period_id' => 2, 'value' => 50],
            ['period_id' => 3, 'value' => 10],
        ]);

        $this->put(route('equipments.update', $equipment->id), [
            'description' => 'escora',
            'unit' => 'm/l',
            'values' => [
                ['period_id' => 1, 'value' => 10],
                ['period_id' => 2, 'value' => 50],
                ['period_id' => 3, 'value' => 100],
            ],
        ]);

        $equipment->refresh()->load('values');

        $this->assertEquals(10, $equipment->values[0]->value);
        $this->assertEquals(50, $equipment->values[1]->value);
        $this->assertEquals(100, $equipment->values[2]->value);
    }

    public function test_removes_null_values()
    {
        Auth::login(User::factory()->create());

        Period::factory()->count(3)->create();
        $equipment = Equipment::factory()->create();

        $equipment->values()->createMany([
            ['period_id' => 1, 'value' => 100],
            ['period_id' => 2, 'value' => 50],
            ['period_id' => 3, 'value' => 10],
        ]);

        $this->put(route('equipments.update', $equipment->id), [
            'description' => 'Escora',
            'values' => [
                ['period_id' => 1, 'value' => null],
                ['period_id' => 2, 'value' => null],
                ['period_id' => 3, 'value' => null],
            ],
        ]);

        $equipment->refresh();
        $this->assertCount(0, $equipment->values);
    }
}
