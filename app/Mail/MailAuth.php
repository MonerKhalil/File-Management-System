<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailAuth extends Mailable
{
    use Queueable, SerializesModels;

    private $token,$type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token,$type = 'Reset Password')
    {
        $this->token = $token;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->type)
            ->markdown('auth.temp_email_reset_password_api')
            ->with([
                "token" => $this->token,
                "type" => $this->type,
            ]);
    }
}
