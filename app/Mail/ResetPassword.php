<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.reset_password')
            ->from("no-reply@ultimatecleaners.com.au", $this->data['com_name'])
            ->subject($this->data['subject'])
            ->with([
                'com_name' => $this->data['com_name'], 
                'description' => $this->data['description'], 
                'email' => $this->data['email'], 
                'url' => $this->data['url']
            ]);
    }
}
