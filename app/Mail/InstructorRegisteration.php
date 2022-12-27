<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstructorRegisteration extends Mailable
{
    use Queueable, SerializesModels;

    public $email = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Build the mesaage 
     * 
     * @return $this
     */
    public function build()
    {
        return $this->from('a.attendancy@gmail.com', 'Automatic Attendance')
        ->subject('Registration Info')->markdown('emails.InstructorRegisteration');
    }
}
