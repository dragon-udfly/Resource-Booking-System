<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPermission;
use App\Models\Quarter;
use App\Models\QuarterApplication;
use App\Models\QuarterAllocation;
use App\Models\ScheduledQuarterApplication;
use App\Models\FamilyQuarterApplication;
use App\Models\MarkingFamilyQuarter; // Added this import
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModalOverlayTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $requester;
    protected $scheduledApp;
    protected $familyApp;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin User
        $this->admin = User::create([
            'user_id' => 'ADMIN-001',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'designation' => 'Admin',
            'nic_number' => '123456789V',
            'contact_number' => '0771234567',
            'passcode' => '1234',
            'created_datetime' => now(),
        ]);

        // Grant permissions
        UserPermission::create([
            'user_id' => $this->admin->user_id,
            'government_agent_approval' => 1,
            'view_halls' => 1,
        ]);


        // Create Quarter
        $quarter = Quarter::create([
            'quarter_id' => 'Q-01',
            'quarter_type' => 'Scheduled',
            'status' => 'Available',
            'occupant_number' => 1,
            'current_occupant_number' => 0,
            'date_created' => now(),
        ]);

        // Create Scheduled Application
        $this->scheduledApp = QuarterApplication::create([
            'application_id' => 'SQA-TEST-001',
            'nic' => '123456789V',
            'email' => 'sqa@test.com',
            'quarter_type' => 'Scheduled',
            'date_created' => now(),
        ]);
        ScheduledQuarterApplication::create([
            'sq_application_id' => 'SQA-DET-001',
            'application_id' => $this->scheduledApp->application_id,
            's_application_id' => 'SA-001',
        ]);
        // Add allocation for history view
        QuarterAllocation::create([
            'application_id' => $this->scheduledApp->application_id,
            'quarter_id' => $quarter->quarter_id,
            'allocation_status' => 'allocated',
        ]);


        // Create Family Application
        $this->familyApp = QuarterApplication::create([
            'application_id' => 'FQA-TEST-001',
            'nic' => '987654321V',
            'email' => 'fqa@test.com',
            'quarter_type' => 'Family',
            'date_created' => now(),
        ]);
        FamilyQuarterApplication::create([
            'f_application_id' => 'FQA-DET-001',
            'application_id' => $this->familyApp->application_id,
        ]);
        // Add MarkingFamilyQuarter to avoid null reference in view
        MarkingFamilyQuarter::create([
            'f_application_id' => 'FQA-DET-001',
            // Add other necessary fields with default values if strictly required by model, 
            // assuming defaults or nullable for simplicity based on previous context
        ]);

        // Add allocation for history view
        QuarterAllocation::create([
            'application_id' => $this->familyApp->application_id,
            'quarter_id' => $quarter->quarter_id,
            'allocation_status' => 'allocated',
        ]);
    }

    /**
     * Test System Setting Confirmation Overlay
     */
    public function test_system_Settings_overlay_presence()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('systemsetting'));

        $response->assertStatus(200);

        // Check for overlay HTML and CSS
        $response->assertSee('id="confirmation-overlay"', false);
        $response->assertSee('class="overlay-content"', false);

        // Check inline CSS for overlay
        // Note: checking fragments as exact formatting might differ
        $response->assertSee('#confirmation-overlay {', false);
        $response->assertSee('display: none;', false); // Should be hidden by default
        $response->assertSee('position: fixed;', false);
        $response->assertSee('z-index: 1000;', false);
    }

    /**
     * Test Dashboard Global Confirmation Overlay
     */
    public function test_dashboard_overlay_presence()
    {
        // Require a regular user with requester permission to access dashboard
        $user = User::create([
            'user_id' => 'USR-TEST-OVERLAY',
            'first_name' => 'Overlay',
            'last_name' => 'User',
            'email' => 'overlay_user@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'designation' => 'Tester',
            'nic_number' => 'OVERLAY-NIC',
            'contact_number' => '0779998888',
            'passcode' => '1234',
            'created_datetime' => now(),
        ]);
        UserPermission::create([
            'user_id' => $user->user_id,
            'requester' => 1,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        $response->assertSee('id="global-confirmation-overlay"', false);
        $response->assertSee('display: none;', false);
        $response->assertSee('background: rgba(0, 0, 0, 0.7);', false);
        $response->assertSee('z-index: 1002;', false);
    }

    /**
     * Test Scheduled Review Page Overlays
     */
    public function test_scheduled_review_overlays_presence()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('scheduled-quarter.review', $this->scheduledApp->application_id));

        $response->assertStatus(200);

        // Generic Modal Overlay
        $response->assertSee('id="modal-overlay"', false);
        $response->assertSee('class="modal-overlay"', false);

        // Processing Overlay
        $response->assertSee('id="processing-overlay"', false);

        // CSS Checks - looking for the style block content
        $response->assertSee('.modal-overlay {', false);
        $response->assertSee('position: fixed;', false);
        // The view uses 'background' shorthand and 0.6 opacity
        $response->assertSee('rgba(0, 0, 0, 0.6)', false);
        $response->assertSee('display: none;', false);
        $response->assertSee('.modal-overlay.active {', false);
    }

    /**
     * Test Processed Scheduled Page Overlays
     */
    public function test_processed_scheduled_overlays_presence()
    {
        $this->actingAs($this->admin);

        // Ensure status is approved/allocated to see this view properly
        $response = $this->get(route('history.view_scheduled', $this->scheduledApp->application_id));

        $response->assertStatus(200);

        $response->assertSee('id="modal-overlay"', false);
        $response->assertSee('id="processing-overlay"', false);

        // CSS Checks
        $response->assertSee('.modal-overlay {', false);
        $response->assertSee('.modal-content {', false);
    }

    /**
     * Test Processed Family Page Overlays
     */
    public function test_processed_family_overlays_presence()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('history.view_family', $this->familyApp->application_id));

        $response->assertStatus(200);

        $response->assertSee('id="modal-overlay"', false); // Actually view might use #processing-overlay primarily or both
        // Based on grep, showprocessedfamily has #processing-overlay
        $response->assertSee('id="processing-overlay"', false);

        $response->assertSee('.modal-overlay {', false);
    }
}
