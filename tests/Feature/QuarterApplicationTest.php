<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPermission;
use App\Models\Quarter;
use App\Models\QuarterApplication;
use App\Models\FamilyQuarterApplication;
use App\Models\MarkingFamilyQuarter;
use App\Models\ScheduledQuarterApplication;
use App\Models\QuarterAllocation;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class QuarterApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected $requester;
    protected $ao;
    protected $ga;
    protected $familyQuarter;
    protected $scheduledQuarter;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Quarters
        $this->familyQuarter = Quarter::create([
            'quarter_id' => 'Q-FAM-01',
            'quarter_type' => 'Family',
            'service_grade' => '1',
            'status' => 'Unallocated',
            'occupant_number' => 1,
            'current_occupant_number' => 0,
            'date_created' => now(),
        ]);

        $this->scheduledQuarter = Quarter::create([
            'quarter_id' => 'Q-SCH-01',
            'quarter_type' => 'Scheduled',
            'service_grade' => '1',
            'status' => 'Unallocated',
            'occupant_number' => 3,
            'current_occupant_number' => 0,
            'allowed_gender' => 'Male',
            'date_created' => now(),
        ]);

        // Create Users
        $this->requester = $this->createUserWithPermission('requester', '987654321V');
        $this->ao = $this->createUserWithPermission('administrative_officer_approval');
        $this->ga = $this->createUserWithPermission('government_agent_approval');
    }

    protected function createUserWithPermission($permission, $nic = null)
    {
        $user = User::create([
            'user_id' => 'USER-' . uniqid(),
            'first_name' => 'F-' . $permission,
            'last_name' => 'L-' . $permission,
            'email' => $permission . '@test.com',
            'password' => bcrypt('password'),
            'role' => ($permission === 'government_agent_approval') ? 'admin' : 'user',
            'designation' => strtoupper($permission),
            'nic_number' => $nic ?: 'NIC-' . uniqid(),
            'contact_number' => '071' . rand(1000000, 9999999),
            'passcode' => '1234',
            'created_datetime' => now(),
        ]);

        UserPermission::create([
            'user_id' => $user->user_id,
            $permission => 1,
            'view_quarters' => 1,
            'view_quarter_details' => 1,
        ]);

        return $user;
    }

    /**
     * Test Family Quarter Submission
     */
    public function test_family_quarter_submission()
    {
        $this->actingAs($this->requester);

        $formData = [
            'officer_name' => 'John Doe',
            'nic' => '881234567V',
            'dob' => '1988-05-10',
            'designation' => 'Doctor',
            'gender' => 'Male',
            'service_and_grade' => '1',
            'permanent_address' => 'No 1, Colombo',
            'phone_number' => '0112345678',
            'monthly_salary' => 150000,
            'f_date_of_last_salary_increment' => '2023-01-01',
            'date_of_assumption_of_duties' => '2015-06-01',
            'marking_f_department' => 'Officers_attached_to_District_and_Divisional_Secretariats',
            'number_of_dependant' => '02_person',
            'is_dependant_with_disability' => 0,
            'f_distance_of_residency' => 'Out_District_above_100km',
            'filled_by_nic' => $this->requester->nic_number,
            'filled_by_phone' => '0771234567',
            'confirm_details' => 1,
        ];

        $response = $this->post(route('familyquarter.store'), $formData);

        $response->assertRedirect(route('bookquarter'));
        $response->assertSessionHas('success');

        // Verify Data in DB
        $this->assertDatabaseHas('quarter_application', [
            'nic' => '881234567V',
            'quarter_type' => 'Family'
        ]);

        $app = QuarterApplication::where('nic', '881234567V')->first();
        $this->assertDatabaseHas('family_quarter_application', ['application_id' => $app->application_id]);
        $this->assertDatabaseHas('marking_family_quarter', ['f_application_id' => $app->familyQuarterApplication->f_application_id]);
        $this->assertDatabaseHas('quarter_allocation', ['application_id' => $app->application_id, 'allocation_status' => 'pending']);
    }

    /**
     * Test Scheduled Quarter Submission
     */
    public function test_scheduled_quarter_submission()
    {
        $this->actingAs($this->requester);

        $formData = [
            'officer_name' => 'Jane Smith',
            'nic' => '901234567V',
            'designation' => 'Clerk',
            'gender' => 'Female',
            'service_and_grade' => '3',
            'permanent_address' => 'No 5, Kandy',
            'phone_number' => '0812345678',
            'monthly_salary' => 80000,
            'date_of_assumption_of_duties' => '2018-02-15',
            'filled_by_nic' => $this->requester->nic_number,
            'filled_by_phone' => '0771234567',
            'confirm_details' => 1,
        ];

        $response = $this->post(route('scheduledquarter.store'), $formData);

        $response->assertRedirect(route('bookquarter'));

        $this->assertDatabaseHas('quarter_application', [
            'nic' => '901234567V',
            'quarter_type' => 'Scheduled'
        ]);
    }

    /**
     * Test Family Quarter Allocation & Marking
     */
    public function test_family_quarter_allocation_and_marking()
    {
        $this->actingAs($this->requester);
        // Step 1: Submit Application
        $formData = [
            'officer_name' => 'Mark Tester',
            'nic' => '771234567V',
            'dob' => '1977-01-01',
            'designation' => 'Manager',
            'gender' => 'Male',
            'service_and_grade' => '1',
            'permanent_address' => 'Test Address',
            'phone_number' => '123',
            'monthly_salary' => 100000,
            'f_date_of_last_salary_increment' => '2023-01-01',
            'date_of_assumption_of_duties' => '2010-01-01',
            'marking_f_department' => 'Other_Officers',
            'number_of_dependant' => '01_person',
            'is_dependant_with_disability' => 1,
            'f_distance_of_residency' => 'Out_District_below_25km',
            'filled_by_nic' => $this->requester->nic_number,
            'filled_by_phone' => '123',
            'confirm_details' => 1,
        ];
        $this->post(route('familyquarter.store'), $formData);
        $app = QuarterApplication::where('nic', '771234567V')->first();

        // Step 2: GA Allocates and Adds Special Marks
        $this->actingAs($this->ga);
        $allocData = [
            'action' => 'allocate', // default is allocate
            'ga_approval_status' => 1,
            'selected_quarter' => $this->familyQuarter->quarter_id,
            'ga_note' => 'Allocated for urgent service',
            'f_special_reason' => 'Exceptional performance',
            'f_special_reason_marks' => 8,
        ];

        $response = $this->post(route('family-quarter.allocate', $app->application_id), $allocData);
        $response->assertRedirect(route('dashboard'));

        // Verify Status
        $this->assertEquals('allocated', $app->quarterAllocation->refresh()->allocation_status);
        $this->assertEquals('Allocated', $this->familyQuarter->refresh()->status);

        // Verify Marking
        $marking = $app->familyQuarterApplication->markingFamilyQuarter;
        $this->assertEquals('Exceptional performance', $marking->f_special_reason);
        $this->assertEquals(8, $marking->f_special_reason_marks);
    }

    /**
     * Test Scheduled Quarter Gender Mismatch Check
     */
    public function test_scheduled_quarter_gender_mismatch()
    {
        $this->actingAs($this->requester);
        // Submit Female application
        $formData = [
            'officer_name' => 'Female User',
            'nic' => '990000000V',
            'designation' => 'Officer',
            'gender' => 'Female',
            'service_and_grade' => '1',
            'permanent_address' => 'Addr',
            'phone_number' => '123',
            'monthly_salary' => 50000,
            'date_of_assumption_of_duties' => '2020-01-01',
            'filled_by_nic' => $this->requester->nic_number,
            'filled_by_phone' => '123',
            'confirm_details' => 1,
        ];
        $this->post(route('scheduledquarter.store'), $formData);
        $app = QuarterApplication::where('nic', '990000000V')->first();

        // Attempt to allocate Male-only quarter
        $this->actingAs($this->ga);
        $allocData = [
            'action' => 'allocate',
            'ga_approval_status' => 1,
            'selected_quarter' => $this->scheduledQuarter->quarter_id,
        ];

        $response = $this->post(route('scheduled-quarter.allocate', $app->application_id), $allocData);
        $response->assertStatus(422);
        $response->assertJsonFragment(['status' => 'error']);
        $this->assertStringContainsString('Gender Mismatch', $response->json()['message']);
    }

    /**
     * Test Scheduled Quarter Capacity
     */
    public function test_scheduled_quarter_occupancy_increment()
    {
        $this->actingAs($this->requester);
        // Submit Male application
        $formData = [
            'officer_name' => 'Male User',
            'nic' => '880000000V',
            'designation' => 'Officer',
            'gender' => 'Male',
            'service_and_grade' => '1',
            'permanent_address' => 'Addr',
            'phone_number' => '123',
            'monthly_salary' => 50000,
            'date_of_assumption_of_duties' => '2020-01-01',
            'filled_by_nic' => $this->requester->nic_number,
            'filled_by_phone' => '123',
            'confirm_details' => 1,
        ];
        $this->post(route('scheduledquarter.store'), $formData);
        $app = QuarterApplication::where('nic', '880000000V')->first();

        // Allocate
        $this->actingAs($this->ga);
        $allocData = [
            'submit_action' => 'allocate',
            'ga_approval_status' => 1,
            'selected_quarter' => $this->scheduledQuarter->quarter_id,
        ];

        $response = $this->post(route('scheduled-quarter.allocate', $app->application_id), $allocData);
        $response->assertJsonFragment(['status' => 'success']);

        // Verify Occupant Number
        $this->assertEquals(1, $this->scheduledQuarter->refresh()->current_occupant_number);
        // Should still be 'Allocated' (which means in-use/assigned) even if not at full capacity (3)
        // Controller logic: $quarter->status = 'Allocated' if current >= occupant OR status becomes Allocated if it was Unallocated
        $this->assertEquals('Allocated', $this->scheduledQuarter->status);
    }
}
