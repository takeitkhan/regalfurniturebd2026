<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactFormEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $subject)
    {
        $this->data = $data;
        $this->subject = $subject;

    }

    public function build()
    {

        $exForCC = [];
        //dd($this->data[0]['site_id']);
        //dd($this->data['message']);
        $address = $this->data['email'];
        $subject = $this->subject;
        $name = 'Regal Furniture';
        $data = $this->data;

        //$file_name = $subject;
        //$mail_path= public_path().'/'.$subject.'.xlsx';

        return $this->view('emails.contact-form-email-template', compact('data'))
            //->attach($mail_path,['as'=>$file_name])
            ->from($address, $name)
            //->cc($exForCC, $name)
            //->replyTo($address, $name)
            ->subject($subject);
    }
}
