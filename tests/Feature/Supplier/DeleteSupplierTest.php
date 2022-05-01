<?php

namespace Tests\Feature\Supplier;

use App\Models\Supplier;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class DeleteSupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $supplier = Supplier::factory()->create();
        $response = $this->delete(route('suppliers.destroy', $supplier->id));

        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $supplier = Supplier::factory()->create();
        $response = $this->delete(route('suppliers.destroy', $supplier->id));

        $response->assertForbidden();
    }

    public function test_redirects()
    {
        Auth::login(User::factory()->create());

        $supplier = Supplier::factory()->create();
        $response = $this->delete(route('suppliers.destroy', $supplier->id));

        $response->assertRedirect(route('suppliers.index'));
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $supplier = Supplier::factory()->create();
        $response = $this->delete(route('suppliers.destroy', $supplier->id));

        $response->assertSessionHas('flash', 'Fornecedor excluido');
    }

    public function test_soft_delete()
    {
        Auth::login(User::factory()->create());

        $supplier = Supplier::factory()->create();
        $this->delete(route('suppliers.destroy', $supplier->id));

        $this->assertSoftDeleted($supplier);
    }
}
