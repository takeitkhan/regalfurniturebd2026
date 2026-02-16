<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pcombinationdata extends Model
{
    protected $table = 'productcombinationsdata';
    protected $fillable = [
        'id', 'user_id', 'main_pid', 'color_codes', 'size', 'item_code', 'dp_price', 'regular_price', 'selling_price', 'stock', 'is_stock', 'is_dp', 'type', 'created_at', 'updated_at'
    ];
}
