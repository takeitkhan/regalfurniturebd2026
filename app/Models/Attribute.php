<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';
    protected $fillable = [
        'user_id', 'attgroup_id', 'field_label', 'field_name', 'css_class',
        'css_id', 'minimum', 'maximum', 'prepend', 'append', 'field_type', 'field_capability',
        'instructions', 'is_required', 'default_value', 'placeholder',
        'position'
    ];
}