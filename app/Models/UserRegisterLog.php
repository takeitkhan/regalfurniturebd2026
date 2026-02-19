<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRegisterLog extends Model
{
    protected $table = 'user_register_logs';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'ip',
        'user_agent',
        'source',
        'status',
        'reason',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
