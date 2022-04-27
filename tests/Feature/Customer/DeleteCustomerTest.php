<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_deletes_customer()
    {
        $customer = Customer::factory()->create();
        $response = $this->delete(route('customers.destroy', $customer->id));

        $this->assertSoftDeleted($customer);
        $response->assertRedirect(route('customers.index'));
    }
}
