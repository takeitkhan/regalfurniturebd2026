<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeGroup extends Model
{
    use HasFactory;

    protected $table = 'product_attribute_groups';

    protected $fillable = ['group_name', 'group_slug'];

    public function items(){
        return $this->hasMany(ProductAttributeGroupItem::class, 'group_id', 'id');
    }
}
