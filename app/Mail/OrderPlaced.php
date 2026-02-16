<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPlaced extends Mailable
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
        //dd($this->data['cleaning_details']);
        
        $message = $this->markdown('emails.order_placement')
            ->from("no-reply@ultimatecleaners.com.au", $this->data['com_name'])
            ->subject($this->data['subject'])
            ->with([
                'com_name' => $this->data['com_name'],
                'com_licensecode' => $this->data['com_licensecode'],
                'com_logourl' => $this->data['com_logourl'],
                'name' => $this->data['name'],
                'description' => $this->data['description'],
                'number' => $this->data['number'],
                'email' => $this->data['email'],
                'order_id' => $this->data['order_id'],
                'orderid' => $this->data['orderid'],
                'secret_key' => $this->data['secret_key'],
                'cleaning_details' => $this->data['cleaning_details'],
                'password' => $this->data['password']
            ]);
                $message->attach(url('/storage/terms_and_conditions/terms_and_conditions.pdf'), [ 
                    'as' => 'general_T&C.pdf',
                    'mime' => 'application/pdf',
                ]);
                
            foreach($this->data['cleaning_details'] as $key => $pdf_file) {
                $message->attach(url('/storage/terms_and_conditions/'.$key.'_T&C.pdf'), [ 
                    'as' => $key . 'T&C.pdf',
                    'mime' => 'application/pdf',
                ]);
            }
    }


    private function asJSON($data)
    {
        $json = json_encode($data);
        $json = preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $json);

        return $json;
    }
}
