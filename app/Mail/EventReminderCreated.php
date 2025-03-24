<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class EventReminderCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $eventReminder;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $eventReminder
     * @return void
     */
    public function __construct($eventReminder)
    {
        $this->eventReminder = $eventReminder;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
    {
        return $this->view('emails.event_reminder_created')
                    ->with([
                        'title' => $this->eventReminder['title'],
                        'description' => $this->eventReminder['description'],
                        'event_date' => $this->eventReminder['event_date'],
                    ])
                    ->subject('Event Reminder Created');
    }
}
