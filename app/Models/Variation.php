<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $table = 'variations';
    protected $fillable = [
        'user_id', 'show_on', 'label_name', 'field_name', 'field_values', 'field_type', 'field_attributes', 'is_active'
    ];
}
