<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Hash;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser($role, $permissions = [])
    {
        $user = User::create([
            'user_id' => 'user' . rand(100, 999),
            'first_name' => 'Test',
            'last_name' => 'User',
            'designation' => 'Test Designation',
            'nic_number' => '12345678' . rand(10, 99) . 'V',
            'email' => 'test' . rand(100, 999) . '@example.com',
            'contact_number' => '0712345' . rand(100, 999),
            'role' => $role,
            'passcode' => Hash::make('password'),
            'created_datetime' => now(),
        ]);

        $userPermissions = [
            'view_officers' => 0,
            'view_officer_details' => 0,
            'view_halls' => 0,
            'view_hall_details' => 0,
            'view_quarters' => 0,
            'view_quarter_details' => 0,
            'view_audit_log' => 0,
            'administrative_officer_approval' => 0,
            'additional_government_agent_approval' => 0,
            'government_agent_approval' => 0,
            'form_history' => 0,
            'account_setting' => 1, // All users can manage their own account
            'requester' => 0,
        ];

        foreach ($permissions as $permission) {
            if (array_key_exists($permission, $userPermissions)) {
                $userPermissions[$permission] = 1;
            }
        }
        
        UserPermission::create(array_merge(['user_id' => $user->user_id], $userPermissions));
        
        return $user;
    }

    public function test_admin_can_access_admin_routes()
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_user_with_permission_can_access_route()
    {
        $user = $this->createUser('user', ['view_halls']);

        $response = $this->actingAs($user)->get('/seehalls');
        $response->assertStatus(200);
    }

    public function test_user_without_permission_cannot_access_route()
    {
        $user = $this->createUser('user');

        $response = $this->actingAs($user)->get('/seehalls');
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error', 'You do not have permission to access this page.');
    }

    public function test_user_cannot_access_admin_routes()
    {
        $user = $this->createUser('user');

        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error', 'You do not have permission to access this page.');
    }

    public function test_guest_cannot_access_protected_routes()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}
