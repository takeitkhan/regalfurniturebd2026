<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $table = 'points';
    protected $fillable = [
        'od_id','customer_id', 'total_purchased_amount', 'total_point', 'type', 'payment_settings', 'is_active'
    ];
}
