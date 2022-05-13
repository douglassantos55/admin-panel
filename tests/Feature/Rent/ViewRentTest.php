<?php

namespace Tests\Feature\Rent;

use App\Models\Rent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ViewRentTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $rent = Rent::factory()->create();

        $response = $this->get(route('rents.view', $rent->id));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        $rent = Rent::factory()->create();

        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->get(route('rents.view', $rent->id));
        $response->assertForbidden();
    }

    public function test_renders_component()
    {
        $rent = Rent::factory()->create();

        Auth::login(User::factory()->create());
        $response = $this->get(route('rents.view', $rent->id));

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page->component('Rent/View')
                ->where(
                    'rent',
                    $rent->refresh()->load([
                        'period',
                        'customer',
                        'transporter',
                        'paymentMethod',
                        'paymentCondition',
                    ])
                )
        );
    }
}
