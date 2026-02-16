<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'newsletters';
    protected $fillable = [
        'gender', 'email', 'post_code', 'service_type', 'is_active'
    ];
}
