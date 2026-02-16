<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedProducts extends Model
{
    protected $table = 'relatedproducts';
    protected $fillable = [
        'id', 'user_id', 'main_pid', 'product_id', 'title', 'seo_url', 'local_price', 'local_discount', 'int_price', 'local', 'int_discount', 'created_at', 'updated_at'
    ];
}