<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $table = 'product_stocks';
    protected $fillable = [
        'id', 'depot_id', 'product_id', 'product_code', 'available_qty', 'created_at', 'updated_at'
    ];
}
