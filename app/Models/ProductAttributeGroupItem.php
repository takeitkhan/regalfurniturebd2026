<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeGroupItem extends Model
{
    use HasFactory;
    protected $table = 'product_attribute_group_items';

    protected $fillable = ['group_id', 'item_name', 'item_slug'];
}
