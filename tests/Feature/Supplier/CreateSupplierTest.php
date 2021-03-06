<?php

namespace Tests\Feature\Supplier;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreateSupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('suppliers.create'));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('suppliers.store'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('suppliers.create'));
        $response->assertForbidden();

        $response = $this->post(route('suppliers.store'));
        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('suppliers.create'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Supplier/Form')
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('suppliers.store'), [
            'social_name' => ' ',
            'legal_name' => '',
            'email' => 'contact#coca-cola.com',
            'website' => 'https://www.coca-cola.com',
            'cnpj' => '06.787.759/0001-40',
            'insc_est' => '',
            'phone' => '(19) 3444-444',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => 'BRL',
                'postcode' => '13344-s55',
            ],
            'observations' => '',
        ]);

        $response->assertInvalid([
            'social_name' => 'O campo Nome Fantasia ?? obrigat??rio.',
            'email' => 'O campo E-mail deve conter um endere??o de e-mail v??lido.',
            'cnpj' => 'O campo CNPJ n??o ?? v??lido.',
            'phone' => 'O campo Telefone n??o ?? v??lido.',
            'address.state' => 'O campo UF deve conter 2 caracteres.',
            'address.postcode' => 'O campo CEP n??o ?? v??lido.',
        ]);
    }

    public function test_no_email()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => '',
            'email' => '',
            'website' => 'https://www.coca-cola.com',
            'cnpj' => '06.787.759/0001-42',
            'insc_est' => '',
            'phone' => '(19) 3444-4443',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => 'BR',
                'postcode' => '13344-555',
            ],
            'observations' => '',
        ]);

        $response->assertRedirect(route('suppliers.index'));
    }

    public function test_no_phone()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => '',
            'email' => 'contact@coca-cola.com',
            'website' => 'https://www.coca-cola.com',
            'cnpj' => '06.787.759/0001-42',
            'insc_est' => '',
            'phone' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => 'BR',
                'postcode' => '13344-555',
            ],
            'observations' => '',
        ]);

        $response->assertRedirect(route('suppliers.index'));
    }

    public function test_no_cnpj()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => '',
            'email' => 'contact@coca-cola.com',
            'website' => 'https://www.coca-cola.com',
            'cnpj' => '',
            'insc_est' => '',
            'phone' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => 'BR',
                'postcode' => '13344-555',
            ],
            'observations' => '',
        ]);

        $response->assertRedirect(route('suppliers.index'));
    }

    public function test_no_state()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => '',
            'email' => 'contact@coca-cola.com',
            'website' => 'https://www.coca-cola.com',
            'cnpj' => '06.787.759/0001-42',
            'insc_est' => '',
            'phone' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => '',
                'postcode' => '13344-555',
            ],
            'observations' => '',
        ]);

        $response->assertRedirect(route('suppliers.index'));
    }

    public function test_no_postcode()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => '',
            'email' => 'contact@coca-cola.com',
            'website' => 'https://www.coca-cola.com',
            'cnpj' => '06.787.759/0001-42',
            'insc_est' => '',
            'phone' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => 'SP',
                'postcode' => '',
            ],
            'observations' => '',
        ]);

        $response->assertRedirect(route('suppliers.index'));
    }

    public function test_redirects()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => '',
            'email' => '',
            'website' => '',
            'cnpj' => '',
            'insc_est' => '',
            'phone' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
            ],
            'observations' => '',
        ]);

        $response->assertRedirect(route('suppliers.index'));
    }

    public function test_creates_supplier()
    {
        Auth::login(User::factory()->create());

        $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => '',
            'email' => '',
            'website' => '',
            'cnpj' => '',
            'insc_est' => '',
            'phone' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
            ],
            'observations' => '',
        ]);

        $this->assertModelExists(Supplier::where('social_name', 'Coca-Cola')->first());
    }

    public function test_duplicate_cnpj()
    {
        Auth::login(User::factory()->create());
        Supplier::factory()->create(['cnpj' => '00.333.178/0001-54']);

        $response = $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => '',
            'email' => '',
            'website' => '',
            'cnpj' => '00.333.178/0001-54',
            'insc_est' => '',
            'phone' => '',
            'address' => [
                'street' => '',
                'number' => '',
                'neighborhood' => '',
                'complement' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
            ],
            'observations' => '',
        ]);

        $response->assertInvalid([
            'cnpj' => 'Este CNPJ j?? foi cadastrado.',
        ]);
    }

    public function test_cellphone()
    {
        Auth::login(User::factory()->create());

        $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => 'Coca Cola SA',
            'email' => 'contact@cocacola.com',
            'website' => 'https://cocacola.com',
            'cnpj' => '71.640.879/0001-84',
            'insc_est' => '',
            'phone' => '(19) 95880-3583',
            'address' => [
                'street' => 'Rua abc',
                'number' => '3458',
                'neighborhood' => 'Centro',
                'complement' => '',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'postcode' => '30348-355',
            ],
            'observations' => '',
        ]);

        $this->assertModelExists(Supplier::where('social_name', 'Coca-Cola')->first());
    }

    public function test_flash_message()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('suppliers.store'), [
            'social_name' => 'Coca-Cola',
            'legal_name' => 'Coca Cola SA',
            'email' => 'contact@cocacola.com',
            'website' => 'https://cocacola.com',
            'cnpj' => '71.640.879/0001-84',
            'insc_est' => '',
            'phone' => '(19) 95880-3583',
            'address' => [
                'street' => 'Rua abc',
                'number' => '3458',
                'neighborhood' => 'Centro',
                'complement' => '',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'postcode' => '30348-355',
            ],
            'observations' => '',
        ]);

        $response->assertSessionHas('flash', 'Fornecedor cadastrado');
    }
}
