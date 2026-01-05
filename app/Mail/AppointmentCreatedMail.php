<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    // Recebe o agendamento
    public function __construct(
        public Appointment $appointment
    ) {}

    // Define o Assunto
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📅 Consulta Agendada - ' . $this->appointment->tenant->name,
        );
    }

    // Define qual arquivo HTML usar
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment_created',
        );
    }
}