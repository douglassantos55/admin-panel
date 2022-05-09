<?php

namespace Tests\Feature\Transporter;

use App\Models\Transporter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeleteTransporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_authentication()
    {
        $transporter = Transporter::factory()->create();

        $response = $this->delete(route('transporters.destroy', $transporter->id));
        $response->assertRedirect(route('login'));
    }

    public function test_needs_authorization()
    {
        $transporter = Transporter::factory()->create();

        Auth::login(User::factory()->create(['role' => 'accountant']));

        $response = $this->delete(route('transporters.destroy', $transporter->id));
        $response->assertForbidden();
    }

    public function test_soft_deletes()
    {
        $transporter = Transporter::factory()->create();

        Auth::login(User::factory()->create());

        $this->delete(route('transporters.destroy', $transporter->id));

        $this->assertSoftDeleted($transporter, [], null, 'deletedAt');
    }

    public function test_redirect()
    {
        $transporter = Transporter::factory()->create();

        Auth::login(User::factory()->create());

        $response = $this->delete(route('transporters.destroy', $transporter->id));

        $response->assertRedirect(route('transporters.index'));
        $response->assertSessionHas('flash', 'Transportador excluido');
    }
}
