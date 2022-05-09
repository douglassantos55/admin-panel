<?php

namespace Tests\Feature\Transporter;

use App\Models\Transporter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ListTransportersTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $response = $this->get(route('transporters.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('transporters.index'));
        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('transporters.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Transporter/List')->has('transporters')
        );
    }

    public function test_paginates_results()
    {
        Auth::login(User::factory()->create());
        Transporter::factory()->count(100)->create();

        $response = $this->get(route('transporters.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Transporter/List')->has('transporters.data', 15)
        );
    }
}
