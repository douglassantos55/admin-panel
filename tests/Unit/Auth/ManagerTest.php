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

    public function test_validates_correctly()
    {
        $manager = new Manager();
        $manager->registerGates();

        $receptionist = User::factory()->create(['role' => 'receptionist']);
        $this->assertTrue(Gate::forUser($receptionist)->check('view-customers'));

        $receptionist = User::factory()->create(['role' => 'receptionist']);
        $this->assertFalse(Gate::forUser($receptionist)->check('delete-customer'));

        $accountant = User::factory()->create(['role' => 'accountant']);
        $this->assertTrue(Gate::forUser($accountant)->check('view-rents'));

        $accountant = User::factory()->create(['role' => 'accountant']);
        $this->assertFalse(Gate::forUser($accountant)->check('create-customer'));

        $administrator = User::factory()->create(['role' => 'administrator']);
        $this->assertTrue(Gate::forUser($administrator)->check('view-customers'));

        $administrator = User::factory()->create(['role' => 'administrator']);
        $this->assertTrue(Gate::forUser($administrator)->check('do-anything'));
    }
}
