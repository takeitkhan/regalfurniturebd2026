<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrderSearchExport;
use App\Exports\OrdersExport;
use App\Helpers\OrderMailHelper;
use App\Http\Controllers\Controller;
use App\Models\Oneclickbuy;
use App\Models\OrdersDetail;
use App\Models\OrdersMaster;
use App\Models\Product;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\Message\MessageInterface;
use App\Repositories\OrdersDetail\OrdersDetailInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\PaymentSetting\PaymentSettingInterface;
use App\Repositories\Point\PointInterface;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class OrdersManagement extends Controller
{
    /**
     * @var OrdersDetailInterface
     */
    private $ordersdetail;
    /**
     * @var OrdersMasterInterface
     */
    private $ordersmaster;
    /**
     * @var PaymentSettingInterface
     */
    private $paymentsetting;
    /**
     * @var PointInterface
     */
    private $point;
    /**
     * @var DashboardInterface
     */
    private $dashboard;
    /**
     * @var UserInterface
     */
    private $user;
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * OrdersManagement constructor.
     * @param  OrdersMasterInterface  $ordersmaster
     * @param  OrdersDetailInterface  $ordersdetail
     * @param  PaymentSettingInterface  $paymentsetting
     * @param  PointInterface  $point
     * @param  DashboardInterface  $dashboard
     * @param  UserInterface  $user
     * @param  MessageInterface  $message
     */
    public function __construct(OrdersMasterInterface   $ordersmaster,
                                OrdersDetailInterface   $ordersdetail,
                                PaymentSettingInterface $paymentsetting,
                                PointInterface          $point,
                                DashboardInterface      $dashboard, UserInterface $user,
                                MessageInterface        $message)
    {
        $this->ordersdetail = $ordersdetail;
        $this->ordersmaster = $ordersmaster;
        $this->paymentsetting = $paymentsetting;
        $this->point = $point;
        $this->dashboard = $dashboard;
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * @return Factory|View
     */
    public function orders(Request $request)
    {
        $preBooking = 0;
        $getAttribute = $request->all();
        if ($request->preBooking == "show") {
            $preBooking = 1;
        }
        if (!empty($request->all())) {
            $column = $request->get('column');
            if ($request->formDate != null && $request->toDate != null) {

                $formDate = Carbon::parse($request->get('formDate'));
                $toDate = Carbon::parse($request->get('toDate'));
                $toDate = $toDate->toDateString();
                $formDate == $formDate->toDateString();

            } else {
                $formDate = null;
                $toDate = null;
            }
            $default = [
                'column' => $column,
                'search_key' => $request->get('search_key'),
                'formDate' => $formDate,
                'toDate' => $toDate,
                'pre_booking_order' => $preBooking,
                'order_from' => $request->order_from
            ];
            //$orders = $this->ordersmaster->getAllByUser($default);
            $orders = $this->ordersdetail->getAllByUser($default);
//             dd($orders);
            return view('order.orders')->with(['orders' => $orders, 'getAttribute' => $getAttribute]);
        } else {
            //$orders = $this->ordersmaster->getAllByUser();
            $orders = $this->ordersdetail->getAllByUser(['pre_booking_order' => $preBooking]);
            // dd($orders);

            return view('order.orders')->with(['orders' => $orders, 'getAttribute' => $getAttribute]);
        }
    }

    public function orders_single($order_random)
    {
        if (isset($order_random)) {
            $orderMaster = $this->ordersmaster->getByAny('order_random', $order_random);
            $orderDetails = $this->ordersdetail->getByAny('order_random', $order_random);
        }

        return view('order.orders_single')->with([
            'order_master' => $orderMaster,
            'order_details' => $orderDetails
        ]);
    }

    public function move(Request $request)
    {

        if ($request->get('status') == 'deleted') {
            $attributes = [
                'od_status' => $request->get('status')
            ];
        } else {
            $attributes = [
                'od_status' => $request->get('status')
            ];
        }


        $widgets = $this->dashboard->getAll();


        if ($request->get('status') == 'done') {

            $order_id = $request->get('id');
            $om_data = $this->ordersdetail->getById($order_id);

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


        if (auth()->user()->isVendor()) {

            $order_id = $request->get('id');
            //$mas_info = $this->ordersmaster->getById($order_id);
            $att = [
                'id' => $request->get('id')
            ];
            $up_data = [
                'od_status' => $request->get('status')
            ];

            //dd($up_data);

            $data = $this->ordersdetail->updateByVendor($att, $up_data);

        } elseif (auth()->user()->isAdmin()) {

            $data = $this->ordersdetail->update($request->get('id'), $attributes);
        }

        // SMS functionality
        //dd($data);
        if (!empty($data) && $request->get('send_sms') == true) {
            //$mm = adminSMSConfig($this->paymentsetting->getById(1));
            // $admins_nos = implode(',', $mm);
            //dd($data['phone'] . ',' . $admins_nos, 'Order has been placed. Order ID: ' . $request->get('id'));
            //sendSMS($data['phone'] . ',' . $admins_nos, 'Order are in ' . $attributes['od_status'] . '. Order ID: ' . $request->get('id'));


            $send_sms_phone_number = explode(',', strip_tags(dynamic_widget($widgets, ['id' => 6])));
            $od_master = $data->ordermaster;
            $send_sms_phone_number[] = $od_master->phone;

            switch (strtolower($request->get('status'))) {
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

                default:
                    $customer_message_id = 7;
            }

            $msg_for_customer = strip_tags(dynamic_widget($widgets, ['id' => $customer_message_id]));
            send_sms_formatting($send_sms_phone_number, $od_master->id, $od_master->customer_name, $msg_for_customer);
        }

        $msg = 'Order status has been changed';
        //dd($msg);

        return response()->json(['data' => $data, 'message' => $msg]);
    }

    public function bulk_move(Request $request)
    {
        //return response()->json('helloworld@bulk_move');

        $os_items = explode('_', $request->os_items);
        $widgets = $this->dashboard->getAll();
        $send_sms_phone_number = explode(',', strip_tags(dynamic_widget($widgets, ['id' => 6])));
        switch (strtolower($request->order_status)) {
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

            default:
                $customer_message_id = 7;
        }

        $msg_for_customer = strip_tags(dynamic_widget($widgets, ['id' => $customer_message_id]));


        foreach ($os_items as $item) {
            if ($request->order_status == 'deleted') {
                $attributes = [
                    'order_status' => $request->order_status,
                    'is_active' => 0
                ];
            } else {
                $attributes = [
                    'order_status' => $request->order_status
                ];
            }


            $data = $this->ordersmaster->update($item, $attributes);

            /**SMS Functionality**/
            $send_sms_phone_number[] = $data->phone;
            send_sms_formatting($send_sms_phone_number, $data->id, $data->customer_name, $msg_for_customer);
            /*End SMS*/

        }

//        dump($request->os_items);
//        dump($request->sms_items);
//        dump($request->order_status);
//        dd($request);

        // SMS functionality

//        if (!empty($data)) {
//            sendSMS('01680139540', 'done');
//        }

        $msg = 'Order status has been changed';

        return response()->json(['data' => $data, 'success' => $msg]);
    }

    public function save_or_update_delivery_date(Request $request)
    {

        if ($request->get('order_id') != 'none') {
            $order_id = $request->get('order_id');
            $attributes = [
                'delivery_date' => date('Y-m-d', strtotime($request->get('appr_delivery_date')))
            ];
        }

        $this->ordersdetail->update($order_id, $attributes);

        $msg = 'Approximate delivery date has been changed';

        return Redirect::to('orders')->with(['success' => $msg]);
        //return response()->json(['data' => $data, 'message' => $msg]);
    }

    public function date_between()
    {
        dump('Construction is going on');
    }

    public function placed()
    {
        $default = [
            'order_status' => 'placed'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function placed_cash_on_delivery()
    {
        $default = [
            'column' => 'payment_method',
            'search_key' => 'cash_on_delivery',
            'order_status' => 'placed'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function production()
    {
        $default = [
            'order_status' => 'production'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function pause()
    {
        $default = [
            'order_status' => 'pause'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function processing()
    {
        $default = [
            'order_status' => 'processing'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function distribution()
    {
        $default = [
            'order_status' => 'distribution'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function done()
    {
        $default = [
            'order_status' => 'done'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function refund()
    {
        $default = [
            'order_status' => 'refund'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function cash_on_delivery()
    {
        $default = [
            'order_status' => 'cash_on_delivery'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function placed_online_payment()
    {
        $orders = OrdersMaster::where('payment_method', '!=', 'cash_on_delivery')->latest()->paginate(10);

        return view('order.orders')->with(['orders' => $orders]);
    }

    public function deleted()
    {
        $default = [
            'order_status' => 'deleted'
        ];
        $orders = $this->ordersmaster->getAll($default);
        return view('order.orders')->with(['orders' => $orders]);
    }

    public function temporary()
    {
        dump('Construction is going on here');
    }

    public function export_orders(Request $request)
    {
        $filter = $request->all();

        return Excel::download(new OrdersExport(compact('filter')), 'orders.xlsx');
    }


    /* ======================
        custom Order
    =======================*/

    public function customOrder()
    {
        return response()->view('order.custom_order');
    }

    public function customOrderStore(Request $r)
    {
        $r->validate([
            'customer_name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);

        $rand = time().uniqid('rand');
        $secret_key = time().uniqid('secret');
        $orderMaster = [
            'order_random' => $rand,
            'order_from' => $r->order_source,
            'secret_key' => $secret_key,
            'user_id' => auth()->user()->id,
            'customer_name' => $r->customerName,
            'phone' => $r->phone,
            'emergency_phone' => $r->emergencyPhone,
            'address' => $r->address,
            'notes' => $r->notes,
            'email' => $r->email,
            'order_date' => date('Y-m-d'),
            'payment_method' => $r->paymentmethod,
            'payment_term_status' => $r->payment_term_status,
            'order_status' => $r->order_status,
            'currency' => 'BDT',
            'district' => $r->district,
            'is_active' => 1,
            'total_amount' => $r->total_price,
            'delivery_fee' => $r->delivery_price,
            'grand_total' => $r->total_price + $r->delivery_price,
        ];

        //Check One click Buy Order is approve
        if ($r->oneclickbuy_id) {
            $checkOneClick = Oneclickbuy::where('id', $r->oneclickbuy_id)->first();
            if ($checkOneClick->order_status == 'approve') {
                return redirect()->route('order.one_click_buy_now')->with('success', 'Already Approve this order');
            }
        }

        $order_master_create = OrdersMaster::create($orderMaster);
        $products = [];
        if ($r->product) {
            foreach ($r->product as $key => $product) {
//                $product = $r->product[$key];
                $product_info = Product::Where(['id' => $product['product_id']])->first();
//                dd($product_info);
                $item = [
                    "productid" => $product['product_id'],
                    "productcode" => $product['product_code'],
                    "size_colo" => null,
                    "purchaseprice" => $product['price'],
                    "qty" => $product['qty'],
                    "is_dp" => null,
                    "flash_discount" => null,
                    "item_code" => null,
                    "dis_tag" => 0,
                    "pre_price" => $product['price']
                ];

                $products [] = [
                    'user_id' => auth()->user()->id,
                    'vendor_id' => $product_info->user_id,
                    'order_random' => $order_master_create->order_random,
                    'product_id' => $product['product_id'],
                    'product_name' => product_title($product['product_id']),
                    'product_code' => $product['product_code'],
                    'qty' => $product['qty'],
                    'order_date' => date('Y-m-d'),
                    'img' => null,
                    'local_selling_price' => $product['price'],
                    'local_purchase_price' => $product['price'],
                    'delivery_charge' => null,
                    'discount' => $product['product_discount'],
                    'is_dp' => '',
                    'is_flash' => '',
                    'flash_id' => null,
                    'flash_discount' => '',
//                    'item_code' => (($multi_data) ? $multi_data->item_code : null),
//                    'color_type' => (($multi_data) ? $multi_data->type : null),
//                    'size_color_id' => (($multi_data) ? $multi_data->id : null),
//                    'color' => (($multi_data) ? $multi_data->color_codes : null),
//                    'size' => (($multi_data) ? $multi_data->size : null),
//                    'product_set_id' => $item->pset_id??null,
//                    'product_fabric_id' => $item->fabric_id??null,
                    'item_jeson' => json_encode($item),
                    'secret_key' => $order_master_create->secret_key,
                    'od_status' => $order_master_create->order_status,
                    'is_active' => 1
                ];
            }
            $orderDetails = OrdersDetail::insert($products);
            //Oneclick Buy Order Do Approve
            $checkOneClick = false;
            if ($r->oneclickbuy_id) {
                $do = Oneclickbuy::find($r->oneclickbuy_id);
                $do->order_status = 'approve';
                $do->save();
                $checkOneClick = true;
            }

            //End
            $subject = 'Thank you for ordering from Regal!';
            $address = $r->email;
            $data = $order_master_create->id;
            OrderMailHelper::send($data, $subject, $address, $cc_emails = false);
        }
//        dd($products);
//        dd($checkOneClick);
        if ($checkOneClick) {
            return redirect()->route('order.one_click_buy_now')->with('success', 'Order successfully created');
        } else {
            return redirect('orders');
        }

    }

    /* ========================
    One Clic Buy Now
    =========================*/
    public function oneClickBuyNow()
    {
        return view('order.one_click_buy_now');
    }

    public function oneClickBuyNowUpdate(Request $r)
    {
        $data = Oneclickbuy::find($r->id);
        $data->order_status = $r->order_status;
        $data->save();
        return redirect()->back()->with('success', 'Successfully updated');
    }
}
