<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\Message\MessageInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\OrdersDetail\OrdersDetailInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Models\ActivityLog;

class MessageController extends Controller
{
    /**
     * @var MessageInterface
     */
    private $message;
    protected $dashboard;
    private $ordersdetail;
    private $ordersmaster;

    public function __construct(MessageInterface $message, DashboardInterface $dashboard,  OrdersDetailInterface   $ordersdetail, OrdersMasterInterface $ordersmaster)
    {
        $this->message = $message;
        $this->dashboard = $dashboard;
        $this->ordersdetail = $ordersdetail;
        $this->ordersmaster = $ordersmaster;
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'order_id' => 'required',
                'message' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('orders_single/' . $request->request_id . '?info_type=staff')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => $request->get('user_id'),
                'order_id' => $request->get('order_id'),
                'message_type' => $request->get('message_type'),
                'status' => $request->get('notes_on'),
                'message' => $request->get('message')
            ];

            try {
                $message = $this->message->create($attributes);
                try {
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'order_staff_note',
                        'entity_type' => 'orders_master',
                        'entity_id' => $request->get('order_id'),
                        'old_values' => null,
                        'new_values' => [
                            'message_type' => $request->get('message_type'),
                            'status' => $request->get('notes_on'),
                            'message' => $request->get('message')
                        ],
                        'note' => $request->get('message'),
                        'ip' => $request->ip(),
                        'url' => $request->fullUrl()
                    ]);
                } catch (\Exception $e) {
                    // Logging failure should not break message save
                }
                return redirect('orders_single/' . $request->request_id . '?info_type=staff')->with('success', 'Successfully Added');

            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('orders_single/' . $request->request_id . '?info_type=staff')->withErrors($ex->getMessage());
            }
        }
    }

    public function customer_message_save(Request $request)
    {
//        dd($request->all());
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'order_id' => 'required',
                //'message' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('orders_single/' . $request->request_id . '?info_type=status')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => $request->get('user_id'),
                'order_id' => $request->get('order_id'),
                'message_type' => $request->get('message_type'),
                'status' => null,
            ];

//            if ($request->get('move_status') == 'normal') {
////                dd(1);
////                try {
////                    //$message = $this->message->create($attributes);
////                    //return redirect('orders_single/' . $request->request_id . '?info_type=status')->with('success', 'Successfully Added');
////
////                } catch (\Illuminate\Database\QueryException $ex) {
////                    //return redirect('orders_single/' . $request->request_id . '?info_type=status')->withErrors($ex->getMessage());
////                }
//            } elseif ($request->get('move_status') == 'deleted') {
//                $attributes = [
//                    'order_status' => $request->get('move_status')
//                ];
//            } else {
//                $attributes = [
//                    'order_status' => $request->get('move_status')
//                ];
//            }
            if ($request->get('move_status') != 'normal') {
                $attributes = array_merge($attributes, [
                    'order_status' => $request->get('move_status')
                ]);
            }
//            dd($attributes);

            if ($request->get('move_status') != 'normal') {
                try {
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'order_status_change',
                        'entity_type' => 'orders_master',
                        'entity_id' => $request->get('order_id'),
                        'old_values' => null,
                        'new_values' => ['order_status' => $request->get('move_status')],
                        'note' => $request->get('message'),
                        'ip' => $request->ip(),
                        'url' => $request->fullUrl()
                    ]);
                } catch (\Exception $e) {
                    // Logging failure should not break message save
                }
            }

            $widgets = $this->dashboard->getAll();

//            dd($request->get('move_status'));

            if ($request->get('status') == 'done') {

                $order_id = $request->get('order_id');
                $om_data = $this->ordersmaster->getById($order_id);

                $pay_setting = $this->paymentsetting->getAll();

                $pay_setting = $pay_setting->first();
                if ($pay_setting->rp_fraction) {
                    $fraction_rate = ($pay_setting->rp_fraction * 100) / $pay_setting->rp_point;
                    $earned_point = ($om_data->local_selling_price * 100) / $fraction_rate;
                } else {
                    $earned_point = 0;
                }


                $rp_atts = [
                    'od_id' => $om_data->id,
                    'customer_id' => $om_data->user_id,
                    'total_purchased_amount' => $om_data->local_selling_price,
                    'total_point' => $earned_point,
                    'type' => 'reward_points',
                    'payment_settings' => json_encode($pay_setting),
                    'is_active' => true
                ];

                $done = $this->point->create($rp_atts);
            }

//            if (auth()->user()->isVendor()) {
//
//                $order_id = $request->get('id');
//                //$mas_info = $this->ordersmaster->getById($order_id);
//                $att = [
//                    'id' => $request->get('id')
//                ];
//                $up_data = [
//                    'od_status' => $request->get('status')
//                ];
//
//                //dd($up_data);
//
//                $data = $this->ordersdetail->updateByVendor($att, $up_data);
//
//            } elseif (auth()->user()->isAdmin()) {
//                $data = $this->ordersdetail->update($request->get('id'), $attributes);
//            }
            //dd($request->all());

//            dd($data);
            $data = $this->ordersmaster->update($request->get('order_id'), $attributes);
            // SMS funct    ionality
            //dd($data);
            if (!empty($data)) {
                //dd($data);
                //$mm = adminSMSConfig($this->paymentsetting->getById(1));
                // $admins_nos = implode(',', $mm);
                //dd($data['phone'] . ',' . $admins_nos, 'Order has been placed. Order ID: ' . $request->get('id'));
                //sendSMS($data['phone'] . ',' . $admins_nos, 'Order are in ' . $attributes['od_status'] . '. Order ID: ' . $request->get('id'));


                $send_sms_phone_number = explode(',', strip_tags(dynamic_widget($widgets, ['id' => 6])));
                $od_master = $data;
                $send_sms_phone_number = $od_master->phone;
                //dd($od_master);

                switch (strtolower($request->get('move_status'))) {
                    case 'placed':
                        $customer_message_id = 7;
                        break;
                    case 'processing':
                        $customer_message_id = 8;
                        break;

                    case 'refund':
                        $customer_message_id = 9;
                        break;
                    case 'done':
                        $customer_message_id = 10;
                        break;
                    case 'normal';
                        $customer_message_id = 0;
                        break;
                    case 'cancel';
                        $customer_message_id = 23;
                        break;
                    case 'confirmed';
                        $customer_message_id = 24;
                        break;
                    default:
                        $customer_message_id = 7;
                }
                if ($customer_message_id == 0) {
                    $msg_for_customer = 'Hi '.$od_master->customer_name.', '.$request->message.' #Regal Furniture';
                } else {
                    $msg_for_customer = strip_tags(dynamic_widget($widgets, ['id' => $customer_message_id]));
                }
                if( $request->get('sending_to_customer') == 'on') {
//                    dd($msg_for_customer);
                    $f = send_sms_formatting($send_sms_phone_number, $od_master->id, $od_master->customer_name, $msg_for_customer);
                    $attributes = array_merge($attributes, [
                        'status' => 1,
                    ]);
                }
                $attributes = array_merge($attributes, [
                    'message' => $msg_for_customer,
                ]);
                $message = $this->message->create($attributes);
                try {
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'order_customer_message',
                        'entity_type' => 'orders_master',
                        'entity_id' => $request->get('order_id'),
                        'old_values' => null,
                        'new_values' => [
                            'move_status' => $request->get('move_status'),
                            'message_type' => $request->get('message_type'),
                            'message' => $attributes['message'] ?? null
                        ],
                        'note' => $attributes['message'] ?? $request->get('message'),
                        'ip' => $request->ip(),
                        'url' => $request->fullUrl()
                    ]);
                } catch (\Exception $e) {
                    // Logging failure should not break message save
                }
            }

            $msg = 'Order status has been changed';
            try {
                return redirect('orders_single/' . $request->request_id . '?info_type=status')->with('success', 'Successfully Added');

            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('orders_single/' . $request->request_id . '?info_type=status')->withErrors($ex->getMessage());
            }
        }

    }


}
