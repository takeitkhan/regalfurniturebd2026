<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ThankYou extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@regalfurniturebd.com')
            ->subject('Congratulations! Your account is now ready!')
            ->view('emails.thankyou')
            ->with([
                'email' => $this->email,
                'password' => $this->password,
            ]);
    }
}
