<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CredenciaisAcessoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $usuario,
        public string $senhaTemporaria,
        public string $perfil,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'IFBANK - Credenciais de acesso',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.credenciais-acesso',
        );
    }
}
