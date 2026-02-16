<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderSendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subject;
    public $iscc;

    public function __construct($data, $subject, $cc_emails)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->cc_emails = $cc_emails;
    }

    public function build()
    {
        if ($this->cc_emails == true) {
            $exForCC = explode(' | ', $getSettingMailAddress);
        } else {
            $exForCC = [];
        }


        //dd($this->data[0]['site_id']);
        //dd($this->data['message']);
        $address = 'info@regalfurniturebd.com';
        $subject = $this->subject;
        $name = 'Regal Furniture';
        $messages = $this->data;

        //$file_name = $subject;
        //$mail_path= public_path().'/'.$subject.'.xlsx';

        return $this->view('order-email-template', compact('messages'))
            //->attach($mail_path,['as'=>$file_name])
            ->from($address, $name)
            ->cc($exForCC, $name)
            //->replyTo($address, $name)
            ->subject($subject);
    }
}
