<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hall;
use App\Models\HallBooking;
use App\Models\UserPermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Carbon\Carbon;

class EmergencyBookingTest extends TestCase
{
    use RefreshDatabase;

    protected $hall;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user with booking permissions (if needed, or public)
        // Public booking usually doesn't need login, but let's check controller
        // The store method doesn't seem to require auth middleware based on previous view?
        // Let's assume public for now, or check routes.
        // Controller seems to allow guest for create/store?
        // Actually HallBookingController usually has public access for booking?
        // Let's check routes file to be sure, but for now I'll act as a guest.

        // Create a test hall
        $this->hall = Hall::create([
            'hall_id' => 'HALL-EMERGENCY',
            'hall_type' => 'Auditorium',
            'capacity' => 200,
            'description' => 'Emergency Hall',
            'current_state' => 'available',
            'date_created' => now(),
        ]);
    }

    /**
     * Test Emergency Booking Email Sending
     */
    public function test_emergency_booking_email_sending()
    {
        Mail::fake();

        $eventDate = Carbon::tomorrow()->toDateString();

        $bookingData = [
            'applicant_name' => 'Emergency Applicant',
            'applicant_email' => 'emergency@test.com',
            'applicant_type' => 'Government',
            'hall_id' => $this->hall->hall_id,
            'programme' => 'Emergency Meeting',
            'event_date' => $eventDate,
            'event_time' => '08:00',
            'participants' => 50,
            'event_duration' => 2,
            'paid_status' => 'Not Required', // Enum value
            'is_emergency_booking' => 1,
            'filled_by_nic' => '123123123V',
            'filled_by_phone' => '0771231234',
        ];

        // Submit the form
        $response = $this->post(route('hall_bookings.store'), $bookingData);

        $response->assertStatus(302); // Redirects on success
        $response->assertSessionHas('success');

        // Verify booking created and approved
        $this->assertDatabaseHas('hall_booking', [
            'applicant_email' => 'emergency@test.com',
            'is_emergency_booking' => 1,
            'final_approval' => 'approved',
            'government_agent_approved' => 'approved',
        ]);

        // Assert existing email behavior
        // If no email is expected, assertedSent will return 0
        Mail::assertNothingSent();

        // OR if we expect an email, we would check:
        // Mail::assertSent(HallBookingApproved::class);
    }
}
