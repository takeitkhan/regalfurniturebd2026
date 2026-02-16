<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emi extends Model
{
    protected $table = 'productsemidata';
    protected $fillable = [
        'user_id', 'main_pid', 'bank_id', 'month_range', 'interest', 'is_active'
    ];
}
