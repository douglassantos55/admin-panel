<?php

namespace Tests\Feature\Equipment;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeleteEquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $equipment = Equipment::factory()->create();
        $response = $this->delete(route('equipments.destroy', $equipment->id));

        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $equipment = Equipment::factory()->create();
        $response = $this->delete(route('equipments.destroy', $equipment->id));

        $response->assertForbidden();
    }

    public function test_soft_delete()
    {
        Auth::login(User::factory()->create());

        $equipment = Equipment::factory()->create();
        $this->delete(route('equipments.destroy', $equipment->id));

        $this->assertSoftDeleted($equipment->refresh());
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $equipment = Equipment::factory()->create();
        $response = $this->delete(route('equipments.destroy', $equipment->id));

        $response->assertSessionHas('flash', 'Equipamento excluido');
    }

    public function test_redirects()
    {
        Auth::login(User::factory()->create());

        $equipment = Equipment::factory()->create();
        $response = $this->delete(route('equipments.destroy', $equipment->id));

        $response->assertRedirect(route('equipments.index'));
    }
}
