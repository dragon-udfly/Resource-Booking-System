<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPermission;
use App\Models\Hall;
use App\Models\HallBooking;
use App\Models\Quarter;
use App\Models\QuarterApplication;
use App\Models\QuarterAllocation;
use App\Models\FamilyQuarterApplication;
use App\Models\ScheduledQuarterApplication;
use App\Models\MarkingFamilyQuarter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\HallBookingApproved;
use App\Mail\HallBookingCancelled;
use App\Mail\QuarterAllocated;
use App\Mail\QuarterAllocationCancelled;
use App\Mail\SimpleMail;
use Tests\TestCase;
use Carbon\Carbon;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $ga;
    protected $hall;
    protected $quarter;

    protected function setUp(): void
    {
        parent::setUp();

        // Create GA user
        $this->ga = User::create([
            'user_id' => 'GA-001',
            'first_name' => 'Government',
            'last_name' => 'Agent',
            'designation' => 'Government Agent',
            'email' => 'ga@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'nic_number' => 'GA-NIC',
            'contact_number' => '000000',
            'passcode' => '1234',
            'created_datetime' => now(),
        ]);

        // Create permissions for GA
        UserPermission::create([
            'user_id' => $this->ga->user_id,
            'government_agent_approval' => 1,
            'administrative_officer_approval' => 1,
            'additional_government_agent_approval' => 1,
        ]);

        // Create a test hall
        $this->hall = Hall::create([
            'hall_id' => 'HALL-01',
            'hall_type' => 'Auditorium',
            'capacity' => 100,
            'description' => 'Main Hall',
            'current_state' => 'available',
            'date_created' => now(),
        ]);

        // Create a test quarter
        $this->quarter = Quarter::create([
            'quarter_id' => 'Q-01',
            'quarter_type' => 'Family',
            'status' => 'Unallocated',
            'occupant_number' => 1,
            'current_occupant_number' => 0,
            'date_created' => now(),
        ]);
    }

    /**
     * Test Hall Booking Approval Email
     */
    public function test_hall_booking_approval_email()
    {
        Mail::fake();

        $booking = HallBooking::create([
            'booking_id' => 'HB-001',
            'applicant_name' => 'Test Applicant',
            'applicant_type' => 'Internal',
            'applicant_email' => 'applicant@test.com',
            'hall_id' => $this->hall->hall_id,
            'requested_hall_type' => 'Auditorium',
            'programme' => 'Training',
            'participants' => 50,
            'event_duration' => 2.5,
            'paid_status' => 'Paid',
            'filled_by_nic' => 'NIC-FILL',
            'filled_by_phone' => '0112233',
            'event_date' => Carbon::tomorrow()->toDateString(),
            'event_time' => '10:00',
            'administrative_officer_approved' => 'approved',
            'additional_government_agent_approved' => 'approved',
            'government_agent_approved' => 'pending',
            'final_approval' => 'pending',
            'date_created' => now(),
        ]);

        $this->actingAs($this->ga);

        // Final GA Approval
        $this->post(route('hall_bookings.approve', $booking->booking_id));

        Mail::assertSent(HallBookingApproved::class, function (HallBookingApproved $mail) {
            return $mail->hasTo('applicant@test.com');
        });
    }

    /**
     * Test Hall Booking Cancellation Email
     */
    public function test_hall_booking_cancellation_email()
    {
        Mail::fake();

        $booking = HallBooking::create([
            'booking_id' => 'HB-002',
            'applicant_name' => 'Test Applicant 2',
            'applicant_type' => 'External',
            'applicant_email' => 'applicant2@test.com',
            'hall_id' => $this->hall->hall_id,
            'requested_hall_type' => 'Auditorium',
            'programme' => 'Meeting',
            'participants' => 30,
            'event_duration' => 1.0,
            'paid_status' => 'Paid',
            'filled_by_nic' => 'NIC-FILL2',
            'filled_by_phone' => '0445566',
            'event_date' => Carbon::tomorrow()->toDateString(),
            'event_time' => '14:00',
            'final_approval' => 'approved',
            'date_created' => now(),
        ]);

        $this->actingAs($this->ga);

        // GA Revocation
        $this->post(route('hall_bookings.cancelApproved', $booking->booking_id), [
            'action' => 'revoke',
            'reason' => 'System maintenance'
        ]);

        Mail::assertSent(HallBookingCancelled::class, function (HallBookingCancelled $mail) {
            return $mail->hasTo('applicant2@test.com');
        });
    }

    /**
     * Test Quarter Allocation Email (Family)
     */
    public function test_family_quarter_allocation_email()
    {
        Mail::fake();

        $appId = 'FAM-001';
        $app = QuarterApplication::create([
            'application_id' => $appId,
            'nic' => 'NIC1',
            'email' => 'family@test.com',
            'quarter_type' => 'Family',
            'gender' => 'Male',
            'date_created' => now(),
        ]);
        FamilyQuarterApplication::create(['application_id' => $appId, 'f_application_id' => 'F-001']);
        MarkingFamilyQuarter::create(['f_application_id' => 'F-001']);
        QuarterAllocation::create(['application_id' => $appId, 'allocation_status' => 'pending']);

        $this->actingAs($this->ga);

        $this->post(route('family-quarter.allocate', $appId), [
            'action' => 'allocate',
            'ga_approval_status' => 1,
            'selected_quarter' => $this->quarter->quarter_id,
        ]);

        Mail::assertSent(QuarterAllocated::class, function (QuarterAllocated $mail) {
            return $mail->hasTo('family@test.com');
        });
    }

    /**
     * Test Quarter Cancellation Email (Scheduled)
     */
    public function test_scheduled_quarter_cancellation_email()
    {
        Mail::fake();

        $q = Quarter::create([
            'quarter_id' => 'SQ-01',
            'quarter_type' => 'Scheduled',
            'status' => 'Allocated',
            'occupant_number' => 2,
            'current_occupant_number' => 1,
            'date_created' => now(),
        ]);

        $appId = 'SCH-001';
        $app = QuarterApplication::create([
            'application_id' => $appId,
            'nic' => 'NIC2',
            'email' => 'scheduled@test.com',
            'quarter_type' => 'Scheduled',
            'gender' => 'Female',
            'date_created' => now(),
        ]);
        ScheduledQuarterApplication::create([
            'sq_application_id' => 'SQA-001',
            'application_id' => $appId,
            's_application_id' => 'S-001'
        ]);
        QuarterAllocation::create([
            'application_id' => $appId,
            'allocation_status' => 'allocated',
            'quarter_id' => $q->quarter_id
        ]);

        $this->actingAs($this->ga);

        $this->post(route('scheduled-quarter.cancel', $appId), [
            'ga_note' => 'Revoking due to protocol change',
        ]);

        Mail::assertSent(QuarterAllocationCancelled::class, function (QuarterAllocationCancelled $mail) {
            return $mail->hasTo('scheduled@test.com');
        });
    }

    /**
     * Test Admin System Settings Test Email
     */
    public function test_admin_system_test_email()
    {
        Mail::fake();

        $this->actingAs($this->ga);

        $this->post(route('settings.email.test'), [
            'test_email' => 'admin_test@test.com',
            'subject' => 'Test Notification',
            'email-body' => 'This is a test body content.',
        ]);

        Mail::assertSent(SimpleMail::class, function (SimpleMail $mail) {
            return $mail->hasTo('admin_test@test.com');
        });
    }
}
