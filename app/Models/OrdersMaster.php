<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;

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

    public function proofs()
    {
        return $this->hasMany(OrderProof::class, 'order_id');
    }

    protected static function booted()
    {
        static::created(function ($order) {
            if (app()->runningInConsole()) {
                return;
            }

            try {
                ActivityLog::create([
                    'user_id' => auth()->id() ?? $order->user_id,
                    'action' => 'order_created',
                    'entity_type' => 'orders_master',
                    'entity_id' => $order->id,
                    'old_values' => null,
                    'new_values' => [
                        'order_status' => $order->order_status,
                        'payment_term_status' => $order->payment_term_status,
                        'payment_method' => $order->payment_method,
                        'order_from' => $order->order_from
                    ],
                    'note' => 'Order placed',
                    'ip' => request()->ip(),
                    'url' => request()->fullUrl()
                ]);
            } catch (\Exception $e) {
                // Logging failure should not break order creation
            }
        });
    }

}
