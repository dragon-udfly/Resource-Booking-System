<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user who has permissions to view the full dashboard
        $this->user = User::create([
            'user_id' => 'USR-DASH-001',
            'first_name' => 'Dashboard',
            'last_name' => 'Tester',
            'email' => 'dashboard@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'designation' => 'Tester',
            'nic_number' => '123456789V',
            'contact_number' => '0771234567',
            'passcode' => '1234',
            'created_datetime' => now(),
        ]);

        // Grant 'requester' permission to ensure they see the dashboard content
        UserPermission::create([
            'user_id' => $this->user->user_id,
            'requester' => 1,
            'view_halls' => 1,
        ]);
    }

    /**
     * Test the Dashboard page CSS and content.
     */
    public function test_dashboard_renders_correctly_with_css()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Verify key content Headers
        $response->assertSee('Dashboard - District Secretariat Vavuniya');
        $response->assertSee('Pending Booking Approvals');
        $response->assertSee('Hall Booking Applications');

        // Verify CSS Classes validity
        $response->assertSee('class="banner"', false);
        $response->assertSee('class="page-header"', false);
        $response->assertSee('id="fixed-nav-menu"', false);
        $response->assertSee('class="nav-btn"', false);
        $response->assertSee('class="badge"', false);
        $response->assertSee('id="approval-details"', false);

        // Verify Inline CSS and unique styles
        $response->assertSee('#fixed-nav-menu {', false);
        $response->assertSee('position: fixed;', false);
        $response->assertSee('.nav-btn {', false);
        $response->assertSee('background-color: rgb(34, 60, 4);', false); // Green theme color
        $response->assertSee('.nav-btn:hover {', false);
        $response->assertSee('transform: scale(1.05);', false);
        $response->assertSee('html {', false);
        $response->assertSee('scroll-behavior: smooth;', false);

        // Verify Table CSS presence
        // Note: The selector is grouped, so we check for the start of the group
        $response->assertSee('#approval-details,', false);
        $response->assertSee('width: 100%;', false);
        $response->assertSee('border-collapse: collapse;', false);
    }
}
