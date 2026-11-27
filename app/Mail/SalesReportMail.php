<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SalesReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $reportData,
        public string $pdfContent,
        public string $filename,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sales Report - '.$this->reportData['from'].' to '.$this->reportData['to'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sales-report',
            with: ['data' => $this->reportData],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, $this->filename)
                ->withMime('application/pdf'),
        ];
    }
}
