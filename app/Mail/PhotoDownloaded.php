<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

use App\Models\{User, Source};

class PhotoDownloaded extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        protected Source $source, 
        protected User $user
    ) {
        // Vous pouvez ajouter un code supplémentaire ici si nécessaire.
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre téléchargement',
            replyTo: [config('mail.from.address')]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.photo',
            with: [
                'source' => $this->source,
                'user' => $this->user,
            ]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorage($this->source->path)
        ];
    }
}
