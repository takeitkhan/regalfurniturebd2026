<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productpricecombination extends Model
{
    protected $table = 'productpricecombinations';
    protected $fillable = [
        'id', 'user_id', 'main_pid', 'type', 'photo_name', 'value', 'created_at', 'updated_at'
    ];
}
