<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryTimespan extends Model
{
    protected $table = 'delivery_timespans';
    protected $fillable = [
        'timespan','description','created_at','updated_at','is_active'
    ];
}
