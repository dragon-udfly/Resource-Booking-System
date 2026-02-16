<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Quarter;
use App\Models\UserPermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuarterOccupantValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin User for adding/updating quarters
        $this->admin = User::create([
            'user_id' => 'ADMIN-Q-VAL',
            'first_name' => 'Admin',
            'last_name' => 'Val',
            'email' => 'admin_val@test.com',
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
            // Add other necessary permissions if needed, usually admin role bypasses or has specific check
        ]);
    }

    public function test_cannot_create_quarter_with_current_occupants_exceeding_allowed()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('quarters.store'), [
            'quarter_type' => 'Family',
            'status' => 'Unallocated',
            'location' => 'Test Location',
            'occupant_number' => 2, // Allowed
            'current_occupant_number' => 3, // Exceeds allowed
            'service_grade' => '1',
        ]);

        $response->assertSessionHasErrors(['current_occupant_number']);
    }

    public function test_can_create_quarter_with_valid_occupants()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('quarters.store'), [
            'quarter_type' => 'Family',
            'status' => 'Unallocated',
            'location' => 'Test Location',
            'occupant_number' => 3,
            'current_occupant_number' => 2,
            'service_grade' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('quarters.index'));
    }

    public function test_cannot_update_quarter_with_current_occupants_exceeding_allowed()
    {
        $this->actingAs($this->admin);

        $quarter = Quarter::create([
            'quarter_id' => 'Q-TEST-001',
            'quarter_type' => 'Family',
            'status' => 'Unallocated',
            'location' => 'Test Location',
            'occupant_number' => 5,
            'current_occupant_number' => 1,
            'date_created' => now(),
            'date_modified' => now(),
        ]);

        $response = $this->patch(route('quarters.update', $quarter), [
            'quarter_type' => 'Family',
            'status' => 'Unallocated',
            'location' => 'Test Location',
            'occupant_number' => 2, // Reducing allowed
            'current_occupant_number' => 3, // Keeping current high
        ]);

        $response->assertSessionHasErrors(['current_occupant_number']);
    }

    public function test_zero_allowed_means_one_occupant_validation()
    {
        // Logic: if occupant_number is 0, it means 1 allowed.
        // So current_occupant_number = 2 should fail.

        $this->actingAs($this->admin);

        $response = $this->post(route('quarters.store'), [
            'quarter_type' => 'Family',
            'status' => 'Unallocated',
            'location' => 'Test Location',
            'occupant_number' => 0, // Means 1
            'current_occupant_number' => 2, // Exceeds 1
            'service_grade' => '1',
        ]);

        $response->assertSessionHasErrors(['current_occupant_number']);
    }
}
