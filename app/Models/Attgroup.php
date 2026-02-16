<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attgroup extends Model
{
    protected $table = 'attgroups';
    protected $fillable = [
        'user_id', 'group_name', 'group_name_slug', 'position', 'is_active'
    ];
}
