<?php

namespace Tests\Feature\Equipment;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ListEquipmentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('equipments.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('equipments.index'));
        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('equipments.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Equipment/List')->has('suppliers')
        );
    }

    public function test_paginates_results()
    {
        Auth::login(User::factory()->create());
        Equipment::factory()->count(100)->create();

        $response = $this->get(route('equipments.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Equipment/List')->has('equipments.data', 15)
        );
    }

    public function test_filters_results_by_description()
    {
        Auth::login(User::factory()->create());

        Equipment::factory()->count(98)->create();
        Equipment::factory()->create(['description' => 'escora']);
        Equipment::factory()->create(['description' => 'andaime']);

        $response = $this->get(route('equipments.index', [
            'description' => 'escora',
        ]));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Equipment/List')->has('equipments.data', 1)
        );
    }

    public function test_filters_results_by_supplier()
    {
        Auth::login(User::factory()->create());

        Equipment::factory()->count(98)->create();
        Equipment::factory()->create(['supplier_id' => 1]);
        Equipment::factory()->create(['supplier_id' => 1]);

        $response = $this->get(route('equipments.index', [
            'supplier' => 1,
        ]));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Equipment/List')->has('equipments.data', 2)
        );
    }
}
