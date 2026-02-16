<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    protected $table = 'returns';
    protected $fillable = [
        'id', 'first_name', 'last_name', 'email', 'telephone', 'order_id', 'date_ordered', 'product_name', 'product_code',
        'quantity', 'reason_return', 'product_opened', 'comment','is_active', 'created_at', 'updated_at'
    ];
}
