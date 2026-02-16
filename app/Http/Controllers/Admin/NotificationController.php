<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;
class NotificationController extends Controller
{
    //
    public function index(){
        return view('admin.notifications.index');
    }

    public function readNotifications(Request $r){
        $data = Notification::find($r->notification_id);
        $data->is_read = 1;
        $data->updated_at = Carbon::now();
        $data->save();
        return redirect()->back()->with('message', 'Successfully updated');
    }
}
