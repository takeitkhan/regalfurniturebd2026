<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;

class MobileController extends Controller
{
    
    public function sendMessage()
    {
        $topic = "/topics/xxx";
        $messaging = app('firebase.messaging');

        $message = CloudMessage::fromArray([
            'topic' => $topic,
            'notification' => [
                'title' => 'title',
                'body'  => 'body',
                'image' => 'image'
            ]
        ]);

        $messaging->send($message);

        return redirect()->back();
    }
}
