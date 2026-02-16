<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hall;
use App\Models\HallBooking;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class HallBookingSubmitTest extends TestCase
{
    use RefreshDatabase;

    protected $officer;
    protected $hall;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test officer
        $this->officer = User::create([
            'user_id' => 'OFFICER-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'designation' => 'Management Assistant',
            'nic_number' => '123456789V',
            'contact_number' => '0771234567',
            'passcode' => '1234',
            'created_datetime' => now(),
        ]);

        // Create a test hall
        $this->hall = Hall::create([
            'hall_id' => 'HALL-01',
            'hall_type' => 'Auditorium',
            'capacity' => 100,
            'description' => 'Main Auditorium',
            'current_state' => 'available',
            'date_created' => now(),
        ]);
    }

    /**
     * Test successful hall booking submission.
     */
    public function test_hall_booking_submission_success()
    {
        $this->actingAs($this->officer);

        $bookingData = [
            'applicant_name' => 'Test Applicant',
            'applicant_email' => 'applicant@test.com',
            'applicant_type' => 'Internal',
            'hall_id' => $this->hall->hall_id,
            'programme' => 'Training Program',
            'event_date' => Carbon::tomorrow()->toDateString(),
            'event_time' => '09:00',
            'participants' => 50,
            'event_duration' => 4,
            'paid_status' => 'Paid',
            'is_emergency_booking' => 0,
            'filled_by_nic' => '987654321V',
            'filled_by_phone' => '0712345678',
        ];

        $response = $this->post(route('hall_bookings.store'), $bookingData);

        $response->assertRedirect(route('halls.schedule'));
        $response->assertSessionHas('success', 'Hall booking request submitted successfully!');

        // Verify database record
        $this->assertDatabaseHas('hall_booking', [
            'applicant_name' => 'Test Applicant',
            'hall_id' => $this->hall->hall_id,
            'event_date' => $bookingData['event_date'],
        ]);

        // Verify audit log
        $this->assertDatabaseHas('audit_log', [
            'log_title' => 'New Hall Booking Application bookH001 submitted',
        ]);
    }

    /**
     * Test capacity validation.
     */
    public function test_hall_booking_capacity_validation()
    {
        $this->actingAs($this->officer);

        $bookingData = [
            'applicant_name' => 'Over Capacity Applicant',
            'applicant_email' => 'over@test.com',
            'applicant_type' => 'External',
            'hall_id' => $this->hall->hall_id,
            'programme' => 'Large Event',
            'event_date' => Carbon::tomorrow()->toDateString(),
            'event_time' => '10:00',
            'participants' => 150, // Capacity is 100
            'event_duration' => 2,
            'paid_status' => 'Pending',
            'is_emergency_booking' => 0,
            'filled_by_nic' => '987654321V',
            'filled_by_phone' => '0712345678',
        ];

        $response = $this->post(route('hall_bookings.store'), $bookingData);

        $response->assertSessionHasErrors(['participants']);
        $this->assertDatabaseMissing('hall_booking', ['applicant_name' => 'Over Capacity Applicant']);
    }

    /**
     * Test duplicate booking prevention.
     */
    public function test_hall_booking_duplicate_prevention()
    {
        $this->actingAs($this->officer);

        $eventDate = Carbon::tomorrow()->toDateString();

        // Create an existing booking
        HallBooking::create([
            'booking_id' => 'bookH001',
            'applicant_name' => 'First User',
            'applicant_email' => 'first@test.com',
            'applicant_type' => 'Internal',
            'requested_hall_type' => 'Auditorium',
            'hall_id' => $this->hall->hall_id,
            'programme' => 'Original Event',
            'event_date' => $eventDate,
            'event_time' => '09:00',
            'participants' => 20,
            'event_duration' => 3,
            'paid_status' => 'Paid',
            'is_emergency_booking' => 0,
            'filled_by_nic' => 'NIC123',
            'filled_by_phone' => '123456',
            'date_created' => now(),
        ]);

        // Attempt second booking for same hall and date
        $bookingData = [
            'applicant_name' => 'Second User',
            'applicant_email' => 'second@test.com',
            'applicant_type' => 'Internal',
            'hall_id' => $this->hall->hall_id,
            'programme' => 'Clashing Event',
            'event_date' => $eventDate,
            'event_time' => '14:00',
            'participants' => 30,
            'event_duration' => 2,
            'paid_status' => 'Paid',
            'is_emergency_booking' => 0,
            'filled_by_nic' => 'NIC456',
            'filled_by_phone' => '654321',
        ];

        $response = $this->post(route('hall_bookings.store'), $bookingData);

        $response->assertSessionHasErrors(['hall_id']);
        $this->assertDatabaseMissing('hall_booking', ['applicant_name' => 'Second User']);
    }

    /**
     * Test emergency booking automatic approval.
     */
    public function test_emergency_booking_auto_approval()
    {
        $this->actingAs($this->officer);

        $bookingData = [
            'applicant_name' => 'Emergency User',
            'applicant_email' => 'emergency@test.com',
            'applicant_type' => 'Government',
            'hall_id' => $this->hall->hall_id,
            'programme' => 'Urgent Meeting',
            'event_date' => Carbon::today()->toDateString(),
            'event_time' => '08:00',
            'participants' => 10,
            'event_duration' => 1,
            'paid_status' => 'Not Required',
            'is_emergency_booking' => 1,
            'filled_by_nic' => 'EMG123',
            'filled_by_phone' => '999999',
        ];

        $response = $this->post(route('hall_bookings.store'), $bookingData);

        $response->assertRedirect(route('halls.schedule'));

        // Verify that it reached approved state immediately
        $this->assertDatabaseHas('hall_booking', [
            'applicant_name' => 'Emergency User',
            'final_approval' => 'approved',
            'administrative_officer_approved' => 'approved',
            'additional_government_agent_approved' => 'approved',
            'government_agent_approved' => 'approved',
        ]);
    }
}
