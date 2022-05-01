<?php

namespace Tests\Feature\Supplier;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UpdateSupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $supplier = Supplier::factory()->create();
        $response = $this->get(route('suppliers.edit', $supplier->id));

        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $supplier = Supplier::factory()->create();

        $response = $this->get(route('suppliers.edit', $supplier->id));
        $response->assertForbidden();

        $response = $this->put(route('suppliers.update', $supplier->id));
        $response->assertForbidden();
    }

    public function test_renders_form()
    {
        Auth::login(User::factory()->create());

        $supplier = Supplier::factory()->create();
        $response = $this->get(route('suppliers.edit', $supplier->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Supplier/Form')->where(
                'supplier',
                $supplier->refresh()
            )
        );
    }

    public function test_duplicate_cnpj()
    {
        Auth::login(User::factory()->create());

        $supplier = Supplier::factory()->create();
        Supplier::factory()->create(['cnpj' => '71.640.879/0001-84']);

        $response = $this->put(route('suppliers.update', $supplier->id), [
            'social_name' => 'Coca-Cola',
            'cnpj' => '71.640.879/0001-84',
            'legal_name' => '',
            'email' => '',
            'website' => '',
            'insc_est' => '',
            'phone' => '',
            'observations' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
            ],
        ]);

        $response->assertInvalid([
            'cnpj' => 'Este CNPJ já foi cadastrado.'
        ]);
    }

    public function test_updates_successfully()
    {
        Auth::login(User::factory()->create());
        $supplier = Supplier::factory()->create();

        $response = $this->put(route('suppliers.update', $supplier->id), [
            'social_name' => 'Coca-Cola',
            'cnpj' => '71.640.879/0001-84',
            'legal_name' => '',
            'email' => '',
            'website' => '',
            'insc_est' => '',
            'phone' => '(19) 98570-3538',
            'observations' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
            ],
        ]);

        $response->assertRedirect(route('suppliers.index'));
        $this->assertEquals('Coca-Cola', $supplier->refresh()->social_name);
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());
        $supplier = Supplier::factory()->create();

        $response = $this->put(route('suppliers.update', $supplier->id), [
            'social_name' => 'Coca-Cola',
            'cnpj' => '71.640.879/0001-84',
            'legal_name' => '',
            'email' => '',
            'website' => '',
            'insc_est' => '',
            'phone' => '(19) 98570-3538',
            'observations' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
            ],
        ]);

        $response->assertSessionHas('flash', 'Dados atualizados');
    }
}
