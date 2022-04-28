<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class Manager
{
    /**
     * @var array<string, string[]>
     */
    private $roles;

    /**
     * @var array<string>
     */
    private $abilities;

    public function __construct()
    {
        $this->roles = config('auth.roles');
        $this->abilities = collect($this->roles)->flatten()->unique()->all();
    }

    public function registerGates()
    {
        Gate::before(function (User $user) {
            if ($user->isAdministrator()) {
                return true;
            }
        });

        foreach ($this->abilities as $ability) {
            Gate::define($ability, function (User $user) use ($ability) {
                return in_array($ability, $this->roles[$user->role]);
            });
        }
    }

    public function abilities(): array
    {
        return $this->abilities;
    }
}
