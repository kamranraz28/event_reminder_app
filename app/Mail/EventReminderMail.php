<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\EventReminder;

class EventReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public function __construct(EventReminder $event)
    {
        $this->event = $event;
    }
    public function build()
    {
        return $this->subject('Event Reminder: ' . $this->event->title)
                    ->view('emails.event_reminder');
    }
}
