<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = [
        'coupon_code', 'coupon_type', 'amount_type', 'price', 'upto_amount','purchase_min', 'purchase_range', 'used_limit', 'start_date', 'end_date', 'apply_for', 'apply_id','comment', 'is_active'
    ];
}
