<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{

    protected $table = 'widgets';
    protected $fillable = [
        'name', 'type', 'position', 'cssid', 'cssclass', 'description', 'special', 'is_active'
    ];

}
