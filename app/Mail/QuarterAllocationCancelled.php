<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\QuarterApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class QuarterAllocationCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    protected $pdfOutput;

    /**
     * Create a new message instance.
     */
    public function __construct(QuarterApplication $application)
    {
        $this->application = $application;

        // Generate PDF in memory
        $data = [
            'application' => $this->application,
            'allocation' => $this->application->quarterAllocation,
            'date' => Carbon::now()->format('Y-m-d')
        ];

        $this->pdfOutput = Pdf::loadView('pdf.quarter_cancellation_letter', $data)->output();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cancellation of Quarter Allocation Application',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.quarter_cancelled',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn() => $this->pdfOutput, 'Quarter_Cancellation_Letter.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
