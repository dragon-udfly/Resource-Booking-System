<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\HallBooking;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class HallBookingApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    protected $pdfOutput;

    /**
     * Create a new message instance.
     */
    public function __construct(HallBooking $booking)
    {
        $this->booking = $booking;

        // Generate PDF in memory
        $data = [
            'booking' => $this->booking,
            'date' => Carbon::now()->format('Y-m-d')
        ];
        $this->pdfOutput = Pdf::loadView('pdf.hall_booking_approval_letter', $data)->output();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hall Booking Approved - Booking ID: ' . $this->booking->booking_id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hall_booking_approved',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn() => $this->pdfOutput, 'Hall_Booking_Approval_' . $this->booking->booking_id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
