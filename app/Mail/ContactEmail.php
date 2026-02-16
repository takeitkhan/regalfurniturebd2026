<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $com_name;
    public $name;
    public $number;
    public $description;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($com_name, $name, $number, $description)
    {
        $this->com_name = $com_name;
        $this->name = $name;
        $this->number = $number;
        $this->description = $description;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contactus');
    }
}
