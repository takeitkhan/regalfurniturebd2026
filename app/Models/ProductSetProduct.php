<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSetProduct extends Model
{
    use HasFactory;

    protected $table = 'product_set_product';

    protected $fillable = [
        'product_set_id', 'product_id', 'qty'
    ];
}