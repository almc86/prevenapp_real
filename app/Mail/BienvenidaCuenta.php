<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mail de bienvenida al crear una cuenta (tenant) desde el registro.
 */
class BienvenidaCuenta extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nombre,
        public string $empresa,
        public string $planNombre,
        public bool $esGratis,
        public string $loginUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido a PrevenApp! Tu cuenta ya está lista',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.bienvenida');
    }

    public function attachments(): array
    {
        return [];
    }
}
