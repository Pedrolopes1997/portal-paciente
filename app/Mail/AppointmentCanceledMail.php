<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCanceledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Appointment $appointment
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Consulta Cancelada - ' . $this->appointment->tenant->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            // Vamos criar essa view simples no próximo passo
            view: 'emails.appointment_canceled',
        );
    }
}