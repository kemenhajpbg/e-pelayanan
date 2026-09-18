<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VisitorMonthlyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $filePath;
    public string $namaBulan;
    public int $tahun;
    public array $stats;
    public string $petugasName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $filePath, string $namaBulan, int $tahun, array $stats, string $petugasName)
    {
        $this->filePath = $filePath;
        $this->namaBulan = $namaBulan;
        $this->tahun = $tahun;
        $this->stats = $stats;
        $this->petugasName = $petugasName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Rekapitulasi Bulanan Buku Tamu ({$this->namaBulan} {$this->tahun}) - Kemenhaj Purbalingga",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.visitor_monthly',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        if (file_exists($this->filePath)) {
            $attachments[] = Attachment::fromPath($this->filePath)
                ->as("rekap_buku_tamu_{$this->namaBulan}_{$this->tahun}.csv")
                ->withMime('text/csv');
        }
        return $attachments;
    }
}
