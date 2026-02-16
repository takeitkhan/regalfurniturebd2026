<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Emailing extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        //dd($this->data['com_name']);
        return $this->markdown('emails.contacting')
            ->from("no-reply@regalfurniturebd.com", $this->data['com_name'])
            ->subject($this->data['subject'])
            ->with(['com_name' => $this->data['com_name'], 'description' => $this->data['description'], 'number' => $this->data['number'], 'email' => $this->data['email']]);


    }

    private function asJSON($data)
    {
        $json = json_encode($data);
        $json = preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $json);

        return $json;
    }


    private function asString($data)
    {
        $json = $this->asJSON($data);

        return wordwrap($json, 76, "\n   ");
    }
}