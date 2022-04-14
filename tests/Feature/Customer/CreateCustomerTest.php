<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use App\Models\Customer;
use Tests\TestCase;

class CreateCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_displays_customer_form()
    {
        $response = $this->get(route('customers.create'));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component('Customer/Form')
        );
    }

    public function test_validates_input()
    {
        $response = $this->post(route('customers.store'), [
            'name' => ' ',
            'email' => 'email#domain.com',
            'birthdate' => '21/13/2000',
            'cpf_cnpj' => '123.123.123-99',
            'rg_insc_est' => '',
            'phone' => '(19) 9074e0009',
            'cellphone' => '(19) 9000-4433',
            'ocupation' => '',
            'address' => '',
            'number' => '',
            'complement' => '',
            'neighborhood' => '',
            'city' => '',
            'state' => 'BRA',
            'postcode' => '13889-0090',
            'observations' => 'testing observation',
        ]);

        $response->assertInvalid([
            'name' => 'O campo nome e obrigatorio.',
            'email' => 'O campo email deve ser um endereco de email valido.',
            'birthdate' => 'O campo data de nascimento nao e uma data valida.',
            'cpf_cnpj' => 'O campo CPF/CNPJ nao e valido.',
            'phone' => 'O campo telefone nao e valido.',
            'cellphone' => 'O campo celular nao e valido.',
            'state' => 'O campo UF deve conter 2 caracteres.',
            'postcode' => 'O campo CEP nao e valido.',
        ]);
    }

    public function test_no_email()
    {
        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '2000-12-23',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '(19) 3333-3333',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => 'Av 9 de Abril',
            'number' => '2346',
            'complement' => '',
            'neighborhood' => 'Centro',
            'city' => 'Mogi Guacu',
            'state' => 'SP',
            'postcode' => '13840-000',
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_birthdate()
    {
        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '(19) 3333-3333',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => 'Av 9 de Abril',
            'number' => '2346',
            'complement' => '',
            'neighborhood' => 'Centro',
            'city' => 'Mogi Guacu',
            'state' => 'SP',
            'postcode' => '13840-000',
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_cellphone()
    {
        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '',
            'ocupation' => 'Developer',
            'address' => 'Av 9 de Abril',
            'number' => '2346',
            'complement' => '',
            'neighborhood' => 'Centro',
            'city' => 'Mogi Guacu',
            'state' => 'SP',
            'postcode' => '13840-000',
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_state()
    {
        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => 'Av 9 de Abril',
            'number' => '2346',
            'complement' => '',
            'neighborhood' => 'Centro',
            'city' => 'Mogi Guacu',
            'state' => '',
            'postcode' => '13840-000',
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_phone()
    {
        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => 'Av 9 de Abril',
            'number' => '2346',
            'complement' => '',
            'neighborhood' => 'Centro',
            'city' => 'Mogi Guacu',
            'state' => 'SP',
            'postcode' => '13840-000',
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_no_postcode()
    {
        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => '',
            'birthdate' => '',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => 'Av 9 de Abril',
            'number' => '2346',
            'complement' => '',
            'neighborhood' => 'Centro',
            'city' => 'Mogi Guacu',
            'state' => 'SP',
            'postcode' => '',
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }

    public function test_creates_customer()
    {
        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => 'email@domain.com',
            'birthdate' => '2000-12-23',
            'cpf_cnpj' => '926.395.660-08',
            'rg_insc_est' => '',
            'phone' => '(19) 3333-3333',
            'cellphone' => '(19) 98888-4433',
            'ocupation' => 'Developer',
            'address' => 'Av 9 de Abril',
            'number' => '2346',
            'complement' => '',
            'neighborhood' => 'Centro',
            'city' => 'Mogi Guacu',
            'state' => 'SP',
            'postcode' => '13840-000',
            'observations' => 'observation',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertModelExists(Customer::where('name', 'John Doe')->first());
    }
}
