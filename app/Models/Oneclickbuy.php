<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oneclickbuy extends Model
{
    use HasFactory;
    protected $table = 'oneclickbuy';
    protected $fillable = [
        'customer_name', 'customer_phone', 'customer_address', 'customer_email', 'product_id', 'order_status'
    ];
}
