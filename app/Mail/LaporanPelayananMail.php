<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LaporanPelayananMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $filePaths;
    public int $totalVisitors;
    public int $totalNews;
    public int $totalReports;
    public int $totalLettersIn;
    public int $totalLettersOut;

    /**
     * Create a new message instance.
     */
    public function __construct(array $filePaths, int $totalVisitors, int $totalNews, int $totalReports = 0, int $totalLettersIn = 0, int $totalLettersOut = 0)
    {
        $this->filePaths = $filePaths;
        $this->totalVisitors = $totalVisitors;
        $this->totalNews = $totalNews;
        $this->totalReports = $totalReports;
        $this->totalLettersIn = $totalLettersIn;
        $this->totalLettersOut = $totalLettersOut;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laporan Rekapitulasi E-Pelayanan & Pengarsipan Kemenhaj - ' . date('d M Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.laporan',
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
        foreach ($this->filePaths as $path) {
            if (file_exists($path)) {
                $attachments[] = Attachment::fromPath($path);
            }
        }
        return $attachments;
    }
}
