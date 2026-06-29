<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_service_account_is_assigned_customer_service_role(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        User::whereIn('email', ['super@lila.com', 'admin@lila.com', 'cs@lila.com'])->delete();
        Role::whereIn('name', ['super_admin', 'admin', 'customer_service'])->delete();

        $this->seed(PermissionSeeder::class);
        $this->seed(UserSeeder::class);

        $user = User::where('email', 'cs@lila.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('customer_service'));
    }
}
