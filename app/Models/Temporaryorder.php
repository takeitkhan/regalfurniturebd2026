<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temporaryorder extends Model
{
    protected $table = 'temporary_orders';
    protected $fillable = [
        'cart','coupon_details', 'user_details', 'payment_method', '_token', '_previous'
    ];
}
