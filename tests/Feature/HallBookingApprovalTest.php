<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPermission;
use App\Models\Hall;
use App\Models\HallBooking;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class HallBookingApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected $requester;
    protected $ao;
    protected $aga;
    protected $ga;
    protected $hall;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hall = Hall::create([
            'hall_id' => 'HALL-01',
            'hall_type' => 'Auditorium',
            'capacity' => 100,
            'description' => 'Main Auditorium',
            'current_state' => 'available',
            'date_created' => now(),
        ]);

        $this->requester = $this->createUserWithPermission('requester', '987654321V');
        $this->ao = $this->createUserWithPermission('administrative_officer_approval');
        $this->aga = $this->createUserWithPermission('additional_government_agent_approval');
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
            'view_halls' => 1,
            'view_hall_details' => 1,
        ]);

        return $user;
    }

    /**
     * Helper to create a pending booking.
     */
    protected function createPendingBooking($filledByNic = null)
    {
        return HallBooking::create([
            'booking_id' => 'bookH' . rand(100, 999),
            'applicant_name' => 'Test Applicant',
            'applicant_email' => 'app@test.com',
            'applicant_type' => 'Internal',
            'requested_hall_type' => 'Auditorium',
            'hall_id' => $this->hall->hall_id,
            'programme' => 'Test Prog',
            'event_date' => Carbon::tomorrow()->toDateString(),
            'event_time' => '09:00',
            'participants' => 10,
            'event_duration' => 2,
            'paid_status' => 'Paid',
            'is_emergency_booking' => 0,
            'filled_by_nic' => $filledByNic ?: $this->requester->nic_number,
            'filled_by_phone' => '0771234567',
            'date_created' => now(),
            'administrative_officer_approved' => 'pending',
            'additional_government_agent_approved' => 'pending',
            'government_agent_approved' => 'pending',
            'final_approval' => 'pending',
        ]);
    }

    /**
     * Test full approval flow: AO -> AGA -> GA
     */
    public function test_full_approval_flow()
    {
        $booking = $this->createPendingBooking();

        // 1. AO Approves
        $this->actingAs($this->ao);
        $response = $this->post(route('hall_bookings.approve', $booking->booking_id));
        $response->assertJson(['success' => true]);
        $this->assertEquals('approved', $booking->refresh()->administrative_officer_approved);
        $this->assertEquals('pending', $booking->final_approval);

        // 2. AGA Approves
        $this->actingAs($this->aga);
        $response = $this->post(route('hall_bookings.approve', $booking->booking_id));
        $response->assertJson(['success' => true]);
        $this->assertEquals('approved', $booking->refresh()->additional_government_agent_approved);
        $this->assertEquals('pending', $booking->final_approval);

        // 3. GA Approves (Final)
        $this->actingAs($this->ga);
        $response = $this->post(route('hall_bookings.approve', $booking->booking_id));
        $response->assertJson(['success' => true]);
        $this->assertEquals('approved', $booking->refresh()->government_agent_approved);
        $this->assertEquals('approved', $booking->final_approval);
    }

    /**
     * Test AO Rejection
     */
    public function test_ao_rejection()
    {
        $booking = $this->createPendingBooking();

        $this->actingAs($this->ao);
        $response = $this->post(route('hall_bookings.reject', $booking->booking_id));
        $response->assertJson(['success' => true]);
        $this->assertEquals('rejected', $booking->refresh()->administrative_officer_approved);
        $this->assertEquals('pending', $booking->final_approval); // AO rejection doesn't finalize status per logic
    }

    /**
     * Test GA Rejection (Final)
     */
    public function test_ga_rejection_finalizes()
    {
        $booking = $this->createPendingBooking();

        $this->actingAs($this->ga);
        $response = $this->post(route('hall_bookings.reject', $booking->booking_id));
        $response->assertJson(['success' => true]);
        $this->assertEquals('rejected', $booking->refresh()->government_agent_approved);
        $this->assertEquals('rejected', $booking->final_approval);
    }

    /**
     * Test Requester Cancellation
     */
    public function test_requester_can_cancel_if_pending()
    {
        $booking = $this->createPendingBooking();

        $this->actingAs($this->requester);
        $response = $this->post(route('hall_bookings.cancelApproved', $booking->booking_id), ['reason' => 'Changed my mind']);
        $response->assertJson(['success' => true]);
        $this->assertEquals('cancelled', $booking->refresh()->final_approval);
    }

    public function test_requester_cannot_cancel_if_processed()
    {
        $booking = $this->createPendingBooking();
        $booking->administrative_officer_approved = 'approved';
        $booking->save();

        $this->actingAs($this->requester);
        $response = $this->post(route('hall_bookings.cancelApproved', $booking->booking_id), ['reason' => 'Too late']);
        $response->assertStatus(403);
        $this->assertNotEquals('cancelled', $booking->refresh()->final_approval);
    }

    /**
     * Test GA Revocation and Re-approval
     */
    public function test_ga_revocation_and_reapproval()
    {
        $booking = $this->createPendingBooking();
        $booking->final_approval = 'approved';
        $booking->save();

        // 1. GA Revokes (Cancels)
        $this->actingAs($this->ga);
        $response = $this->post(route('hall_bookings.cancelApproved', $booking->booking_id), ['reason' => 'Mistake']);
        $response->assertJson(['success' => true]);
        $this->assertEquals('cancelled', $booking->refresh()->final_approval);

        // 2. GA Re-approves
        $response = $this->post(route('hall_bookings.reApprove', $booking->booking_id));
        $response->assertJson(['success' => true]);
        $this->assertEquals('approved', $booking->refresh()->final_approval);
    }

    /**
     * Test re-approval conflict check
     */
    public function test_reapproval_conflict_prevention()
    {
        $eventDate = Carbon::tomorrow()->toDateString();

        $booking1 = $this->createPendingBooking();
        $booking1->event_date = $eventDate;
        $booking1->final_approval = 'cancelled';
        $booking1->save();

        // Create another active booking for same slot
        HallBooking::create([
            'booking_id' => 'bookHCONFLICT',
            'applicant_name' => 'Clashing User',
            'applicant_email' => 'clash@test.com',
            'applicant_type' => 'Internal',
            'requested_hall_type' => 'Auditorium',
            'hall_id' => $this->hall->hall_id,
            'programme' => 'Clash',
            'event_date' => $eventDate,
            'event_time' => '10:00',
            'participants' => 10,
            'event_duration' => 1,
            'paid_status' => 'Paid',
            'is_emergency_booking' => 0,
            'filled_by_nic' => 'NICC',
            'filled_by_phone' => '123',
            'final_approval' => 'approved',
            'date_created' => now(),
        ]);

        $this->actingAs($this->ga);
        $response = $this->post(route('hall_bookings.reApprove', $booking1->booking_id));
        $response->assertStatus(422);
        $response->assertJsonFragment(['message' => 'Cannot re-approve: The hall has already been booked by another application for this date.']);
    }

    /**
     * Test PDF Download
     */
    public function test_pdf_download_authorized()
    {
        $booking = $this->createPendingBooking();

        $this->actingAs($this->ga); // Approver can download
        $response = $this->get(route('hall_bookings.download', $booking->booking_id));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
