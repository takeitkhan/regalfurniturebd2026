<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSetInfo extends Model
{
    use HasFactory;

    protected $table = "product_set_infos";

    protected $fillable = ['product_set_id', 'title', 'sub_title', 'description', 'active'];


}