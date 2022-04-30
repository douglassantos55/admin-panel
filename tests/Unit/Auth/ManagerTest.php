<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Auth\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

class ManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_registers_gates()
    {
        $manager = new Manager();
        $manager->registerGates();

        $this->assertEquals(array_keys(Gate::abilities()), $manager->abilities());
    }

    public function test_administrator_permissions()
    {
        $manager = new Manager();
        $manager->registerGates();

        $administrator = User::factory()->create(['role' => 'administrator']);

        $this->assertTrue(Gate::forUser($administrator)->check('view-customers'));
        $this->assertTrue(Gate::forUser($administrator)->check('create-customer'));
        $this->assertTrue(Gate::forUser($administrator)->check('update-customer'));
        $this->assertTrue(Gate::forUser($administrator)->check('destroy-customer'));

        $this->assertTrue(Gate::forUser($administrator)->check('view-suppliers'));
        $this->assertTrue(Gate::forUser($administrator)->check('create-supplier'));
        $this->assertTrue(Gate::forUser($administrator)->check('update-supplier'));
        $this->assertTrue(Gate::forUser($administrator)->check('destroy-supplier'));

        $this->assertTrue(Gate::forUser($administrator)->check('view-rents'));
        $this->assertTrue(Gate::forUser($administrator)->check('create-rent'));
        $this->assertTrue(Gate::forUser($administrator)->check('update-rent'));
        $this->assertTrue(Gate::forUser($administrator)->check('destroy-rent'));

        $this->assertTrue(Gate::forUser($administrator)->check('do-anything'));
    }

    public function test_receptionist_permissions()
    {
        $manager = new Manager();
        $manager->registerGates();

        $receptionist = User::factory()->create(['role' => 'receptionist']);

        $this->assertTrue(Gate::forUser($receptionist)->check('view-customers'));
        $this->assertTrue(Gate::forUser($receptionist)->check('create-customer'));
        $this->assertTrue(Gate::forUser($receptionist)->check('update-customer'));
        $this->assertTrue(Gate::forUser($receptionist)->check('destroy-customer'));

        $this->assertTrue(Gate::forUser($receptionist)->check('view-suppliers'));
        $this->assertTrue(Gate::forUser($receptionist)->check('create-customer'));
        $this->assertTrue(Gate::forUser($receptionist)->check('update-supplier'));
        $this->assertTrue(Gate::forUser($receptionist)->check('destroy-customer'));

        $this->assertTrue(Gate::forUser($receptionist)->check('view-rents'));
        $this->assertTrue(Gate::forUser($receptionist)->check('create-rent'));
        $this->assertTrue(Gate::forUser($receptionist)->check('update-rent'));
        $this->assertTrue(Gate::forUser($receptionist)->check('destroy-rent'));
    }

    public function test_accountant_permissions()
    {
        $manager = new Manager();
        $manager->registerGates();

        $accountant = User::factory()->create(['role' => 'accountant']);

        $this->assertTrue(Gate::forUser($accountant)->check('view-rents'));
        $this->assertFalse(Gate::forUser($accountant)->check('create-customer'));
    }
}
