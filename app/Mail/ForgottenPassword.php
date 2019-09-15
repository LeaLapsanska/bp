<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgottenPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $random_password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($random_password)
    {
        $this->random_password = $random_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.forgottenpassword');
    }
}
