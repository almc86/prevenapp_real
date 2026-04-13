<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UsuarioCreado extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $email;
    public string $password;
    public string $rol;
    public string $loginUrl;

    public function __construct(string $nombre, string $email, string $password, string $rol)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
        $this->loginUrl = 'http://localhost:5174/login';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido a PrevenApp - Tus credenciales de acceso',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.usuario-creado',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
