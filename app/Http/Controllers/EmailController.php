<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\HallBookingApproved;
use App\Mail\HallBookingCancelled;
use App\Mail\QuarterAllocated;
use App\Mail\QuarterAllocationCancelled;

class EmailController extends Controller
{
    /**
     * Send email notification with PDF attachment.
     *
     * @param string $recipientEmail
     * @param string $type
     * @param mixed $data
     * @return bool
     */
    public static function sendEmail($recipientEmail, $type, $data)
    {
        if (empty($recipientEmail)) {
            Log::warning("EmailController: No recipient email provided for type: {$type}");
            return false;
        }

        try {
            switch ($type) {
                case 'hall_approved':
                    Mail::to($recipientEmail)->send(new HallBookingApproved($data));
                    break;

                case 'hall_cancelled':
                    Mail::to($recipientEmail)->send(new HallBookingCancelled($data));
                    break;

                case 'quarter_allocated':
                    Mail::to($recipientEmail)->send(new QuarterAllocated($data));
                    break;

                case 'quarter_cancelled':
                    Mail::to($recipientEmail)->send(new QuarterAllocationCancelled($data));
                    break;

                default:
                    Log::error("EmailController: Unknown email type: {$type}");
                    return false;
            }

            Log::info("Email sent successfully: {$type} to {$recipientEmail}");
            return true;

        } catch (\Exception $e) {
            Log::error("EmailController: Failed to send email ({$type}) to {$recipientEmail}. Error: " . $e->getMessage());
            return false;
        }
    }
}
