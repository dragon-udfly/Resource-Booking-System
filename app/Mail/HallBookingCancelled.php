<?php

namespace App\Mail;

use App\Models\HallBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailables\Attachment;

class HallBookingCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $reason;
    protected $pdfOutput;

    /**
     * Create a new message instance.
     */
    public function __construct(HallBooking $booking, string $reason)
    {
        $this->booking = $booking;
        $this->reason = $reason;

        // Generate PDF
        $data = [
            'booking' => $this->booking,
            'date' => now()->format('Y-m-d'),
            'reason' => $this->reason
        ];

        $pdf = Pdf::loadView('pdf.hall_booking_cancellation_letter', $data);
        $this->pdfOutput = $pdf->output();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hall Booking Cancelled - ' . $this->booking->programme,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hall_booking_cancelled',
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
            Attachment::fromData(fn() => $this->pdfOutput, 'Cancellation_Letter_' . $this->booking->booking_id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
