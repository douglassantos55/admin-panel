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
}
