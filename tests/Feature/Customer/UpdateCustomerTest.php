<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $customer = Customer::factory()->create();
        $response = $this->get(route('customers.edit', $customer->id));

        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.edit', $customer->id));
        $response->assertForbidden();

        $response = $this->put(route('customers.update', $customer->id));
        $response->assertForbidden();
    }

    public function test_renders_customer_form()
    {
        Auth::login(User::factory()->create(['role' => 'receptionist']));

        $customer = Customer::factory()->create();
        $response = $this->get(route('customers.edit', $customer->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Customer/Form')->where(
                'customer',
                $customer->refresh()
            )
        );
    }

    public function test_validation()
    {
        Auth::login(User::factory()->create());
        $customer = Customer::factory()->create();

        $response = $this->put(route('customers.update', $customer->id), [
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
            'name' => 'O campo nome e obrigatorio.',
            'email' => 'O campo email deve ser um endereco de email valido.',
            'birthdate' => 'O campo data de nascimento nao e uma data valida.',
            'cpf_cnpj' => 'O campo CPF/CNPJ nao e valido.',
            'phone' => 'O campo telefone nao e valido.',
            'cellphone' => 'O campo celular nao e valido.',
            'address.state' => 'O campo UF deve conter 2 caracteres.',
            'address.postcode' => 'O campo CEP nao e valido.',
        ]);
    }

    public function test_duplicate_cpf()
    {
        Auth::login(User::factory()->create());
        $customer = Customer::factory()->create();
        $other = Customer::factory()->create();

        $response = $this->put(route('customers.update', $customer->id), [
            'name' => $customer->name,
            'email' => $customer->email,
            'birthdate' => $customer->birthdate,
            'cpf_cnpj' => $other->cpf_cnpj,
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '',
            'ocupation' => '',
            'address' => array(
                'street' => '',
                'number' => '',
                'complement' => '',
                'neighborhood' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
            ),
            'observations' => '',

        ]);

        $response->assertInvalid([
            'cpf_cnpj' => 'Este CPF/CNPJ já foi cadastrado.',
        ]);
    }

    public function test_updates_successfully()
    {
        Auth::login(User::factory()->create());
        $customer = Customer::factory()->create();

        $response = $this->put(route('customers.update', $customer->id), [
            'name' => $customer->name,
            'email' => $customer->email,
            'birthdate' => $customer->birthdate,
            'cpf_cnpj' => $customer->cpf_cnpj,
            'rg_insc_est' => '',
            'phone' => '',
            'cellphone' => '',
            'ocupation' => '',
            'address' => array(
                'street' => 'rua abc',
                'number' => '',
                'complement' => '',
                'neighborhood' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
            ),
            'observations' => 'updated',

        ]);

        $response->assertValid();
        $response->assertRedirect(route('customers.index'));

        $this->assertEquals('updated', $customer->refresh()->observations);
        $this->assertEquals('rua abc', $customer->refresh()->street);
    }
}
