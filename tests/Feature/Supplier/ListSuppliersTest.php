<?php

namespace Tests\Feature\Supplier;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ListSuppliersTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('suppliers.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $response = $this->get(route('suppliers.index'));

        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('suppliers.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Supplier/List')
        );
    }

    public function test_paginates_results()
    {
        Auth::login(User::factory()->create());
        Supplier::factory()->count(100)->create();

        $response = $this->get(route('suppliers.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Supplier/List')->has('suppliers.data', 15)
        );
    }

    public function test_filters_results_by_name()
    {
        Auth::login(User::factory()->create());

        Supplier::factory()->count(9)->create();
        Supplier::factory()->create(['social_name' => 'Coca-Cola']);

        $response = $this->get(route('suppliers.index', ['name' => 'coca']));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Supplier/List')->has('suppliers.data', 1)
        );
    }

    public function test_filters_results_by_cnpj()
    {
        Auth::login(User::factory()->create());

        Supplier::factory()->count(9)->create();
        Supplier::factory()->create(['cnpj' => '00.333.178/0001-54']);

        $response = $this->get(route('suppliers.index', ['cnpj' => '333']));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Supplier/List')->has('suppliers.data', 1)
        );
    }

    public function test_filters_results_by_both_name_and_cnpj()
    {
        Auth::login(User::factory()->create());
        Supplier::factory()->count(8)->create();

        Supplier::factory()->create(['social_name' => 'Coca-Cola']);
        Supplier::factory()->create(['cnpj' => '00.333.178/0001-54']);

        $response = $this->get(route('suppliers.index', ['name' => 'coca', 'cnpj' => '333']));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Supplier/List')->has('suppliers.data', 0)
        );
    }
}
