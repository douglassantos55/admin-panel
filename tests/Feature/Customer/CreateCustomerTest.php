<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('customers.create'));
        $response->assertForbidden();

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => 'email@domain.com',
            'birthdate' => '2000-12-23',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '(19) 3333-3333',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => array(
                'street' => 'Av 9 de Abril',
                'number' => '2346',
                'complement' => '',
                'neighborhood' => 'Centro',
                'city' => 'Mogi Guacu',
                'state' => 'SP',
                'postcode' => '13840-000',
            ),
            'observations' => 'observation',
        ]);

        $response->assertForbidden();
    }

    public function test_displays_customer_form()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('customers.create'));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component('Customer/Form')
        );
    }

    public function test_validates_input()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('customers.store'), [
            'name' => ' ',
            'email' => 'email#domain.com',
            'birthdate' => '21/13/2000',
            'cpf_cnpj' => '123.123.123-99',
            'rg_insc_est' => '',
            'phone' => '(19) 9074e0009',
            'cellphone' => '(19) 9000-4433',
            'ocupation' => '',
            'address' => array(
                'street' => '',
                'number' => '',
                'complement' => '',
                'neighborhood' => '',
                'city' => '',
                'state' => 'BRA',
                'postcode' => '13889-0090',
            ),
            'observations' => 'testing observation',
        ]);

        $response->assertInvalid([
            'name' => 'O campo Nome é obrigatório.',
            'email' => 'O campo E-mail deve conter um endereço de e-mail válido.',
            'birthdate' => 'O campo Data de nascimento não é uma data válida.',
            'cpf_cnpj' => 'O campo CPF/CNPJ não é válido.',
            'phone' => 'O campo Telefone não é válido.',
            'cellphone' => 'O campo Celular não é válido.',
            'address.state' => 'O campo UF deve conter 2 caracteres.',
            'address.postcode' => 'O campo CEP não é válido.',
        ]);
    }

    public function test_no_email()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '2000-12-23',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '(19) 3333-3333',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => array(
                'street' => 'Av 9 de Abril',
                'number' => '2346',
                'complement' => '',
                'neighborhood' => 'Centro',
                'city' => 'Mogi Guacu',
                'state' => 'SP',
                'postcode' => '13840-000',
            ),
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_birthdate()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '(19) 3333-3333',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => array(
                'street' => 'Av 9 de Abril',
                'number' => '2346',
                'complement' => '',
                'neighborhood' => 'Centro',
                'city' => 'Mogi Guacu',
                'state' => 'SP',
                'postcode' => '13840-000',
            ),
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_cellphone()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '',
            'ocupation' => 'Developer',
            'address' => array(
                'street' => 'Av 9 de Abril',
                'number' => '2346',
                'complement' => '',
                'neighborhood' => 'Centro',
                'city' => 'Mogi Guacu',
                'state' => 'SP',
                'postcode' => '13840-000',
            ),
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_state()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => array(
                'street' => 'Av 9 de Abril',
                'number' => '2346',
                'complement' => '',
                'neighborhood' => 'Centro',
                'city' => 'Mogi Guacu',
                'state' => '',
                'postcode' => '13840-000',
            ),
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_phone()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => array(
                'street' => 'Av 9 de Abril',
                'number' => '2346',
                'complement' => '',
                'neighborhood' => 'Centro',
                'city' => 'Mogi Guacu',
                'state' => 'SP',
                'postcode' => '13840-000',
            ),
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_postcode()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => array(
                'street' => 'Av 9 de Abril',
                'number' => '2346',
                'complement' => '',
                'neighborhood' => 'Centro',
                'city' => 'Mogi Guacu',
                'state' => 'SP',
                'postcode' => '',
            ),
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_creates_customer()
    {
        Auth::login(User::factory()->create());

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => 'email@domain.com',
            'birthdate' => '2000-12-23',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '(19) 3333-3333',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => array(
                'street' => 'Av 9 de Abril',
                'number' => '2346',
                'complement' => '',
                'neighborhood' => 'Centro',
                'city' => 'Mogi Guacu',
                'state' => 'SP',
                'postcode' => '13840-000',
            ),
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_unique_cpf()
    {
        Auth::login(User::factory()->create());
        $customer = Customer::factory()->create();

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => 'email@domain.com',
            'birthdate' => '2000-12-23',
            'cpf_cnpj' => $customer->cpf_cnpj,
            'rg_insc_est' => '',
            'phone' => '(19) 3333-3333',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => array(
                'street' => 'Av 9 de Abril',
                'number' => '2346',
                'complement' => '',
                'neighborhood' => 'Centro',
                'city' => 'Mogi Guacu',
                'state' => 'SP',
                'postcode' => '13840-000',
            ),
            'observations' => 'observation',
        ]);

        $response->assertInvalid([
            'cpf_cnpj' => 'Este CPF/CNPJ já foi cadastrado.',
        ]);
    }
}
