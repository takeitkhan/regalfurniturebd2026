<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersMaster extends Model
{
    protected $table = 'orders_master';
    protected $fillable = [
        'user_id', 'order_random', 'order_from', 'customer_name', 'phone', 'emergency_phone', 'address', 'different_address', 'notes', 'email',
        'order_date', 'payment_method', 'payment_term_status', 'payment_parameter', 'order_status',
        'params', 'secret_key', 'delivery_date',  'currency', 'delivery_fee', 'grand_total', 'total_amount',
        'coupon_type', 'coupon_code', 'coupon_discount', 'division', 'district','pre_booking_order', 'thana', 'trans_id','amount_paid', 'is_active'
    ];

    protected $dates = ['order_date'];


    public function orderdetails(){

        return $this->hasMany(OrdersDetail::class,'order_random','order_random')->with('firstImage','product');
    }

}
