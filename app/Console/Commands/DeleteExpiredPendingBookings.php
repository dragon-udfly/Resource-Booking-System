<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HallBooking;
use App\Models\AuditLog;
use Carbon\Carbon;

class DeleteExpiredPendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete hall bookings that are pending and have passed their event date/time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->toTimeString();

        $this->info("Checking for expired pending bookings...");

        $expiredBookings = HallBooking::where('final_approval', 'pending')
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('event_date', '<', $today)
                      ->orWhere(function ($q) use ($today, $currentTime) {
                          $q->where('event_date', '=', $today)
                            ->where('event_time', '<', $currentTime);
                      });
            })
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info("No expired pending bookings found.");
            return;
        }

        foreach ($expiredBookings as $booking) {
            $bookingId = $booking->booking_id;
            
            // Delete the booking
            $booking->delete();

            // Log the action
            AuditLog::create([
                'log_title' => "Expired pending booking {$bookingId} auto-deleted",
                'details' => "Booking for {$booking->event_date} at {$booking->event_time} was still pending and has expired.",
                'performed_by' => null, // System action
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $this->info("Deleted expired booking: {$bookingId}");
        }

        $this->info("Clean up complete. Deleted {$expiredBookings->count()} bookings.");
    }
}
