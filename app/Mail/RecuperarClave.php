<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mail de recuperación de contraseña (en español, con la marca PrevenApp).
 * Reemplaza la notificación default de Laravel (inglés + template genérico).
 */
class RecuperarClave extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nombre,
        public string $resetUrl,
        public int $expiraMinutos = 60,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperá tu contraseña — PrevenApp',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.recuperar-clave');
    }

    public function attachments(): array
    {
        return [];
    }
}
