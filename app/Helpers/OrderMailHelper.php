<?php

namespace App\Helpers;

use App\Mail\OrderSendMail;
use Mail;

//use Maatwebsite\Excel\Facades\Excel;
//use \App\Exports\MailAttachment;

class OrderMailHelper
{
    public static function send($data, $subject, $address, $cc_emails = true)
    {
        Mail::to($address)->send(new OrderSendMail($data, $subject, $cc_emails));
    }
}
