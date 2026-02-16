<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin User
        $this->admin = User::create([
            'user_id' => 'ADMIN-RESTRICT-001',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin_restrict@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'designation' => 'Admin',
            'nic_number' => '123456789V',
            'contact_number' => '0771234567',
            'passcode' => '1234',
            'created_datetime' => now(),
        ]);
        UserPermission::create([
            'user_id' => $this->admin->user_id,
            'government_agent_approval' => 1,
        ]);

        // Create Regular User
        $this->user = User::create([
            'user_id' => 'USER-RESTRICT-001',
            'first_name' => 'Regular',
            'last_name' => 'User',
            'email' => 'user_restrict@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'designation' => 'User',
            'nic_number' => '987654321V',
            'contact_number' => '0777654321',
            'passcode' => '1234',
            'created_datetime' => now(),
        ]);
        UserPermission::create([
            'user_id' => $this->user->user_id,
            'requester' => 1,
        ]);
    }

    /**
     * Test Admin cannot access User Dashboard
     */
    public function test_admin_cannot_access_dashboard()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('dashboard'));

        // Should redirect to admin dashboard
        $response->assertStatus(302);
        $response->assertRedirect(route('admin'));
        $response->assertSessionHas('error', 'Admins cannot access the user dashboard.');
    }

    /**
     * Test User can access User Dashboard
     */
    public function test_user_can_access_dashboard()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
    }

    /**
     * Test Admin cannot access History
     */
    public function test_admin_cannot_access_history()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('history'));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin'));
    }

    /**
     * Test User can access History
     */
    public function test_user_can_access_history()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('history'));

        // History page might need data to render fully, checking status is enough for middleware test
        $response->assertStatus(200);
    }
}
