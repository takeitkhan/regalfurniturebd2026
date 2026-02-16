<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashItem extends Model
{
    protected $table = 'flash_items';
    protected $fillable = [
        'fi_shedule_id', 'fi_product_id', 'fi_discount', 'fi_show_tag', 'fi_qty'
    ];

    public function product()
    {
        return $this->hasOne(Product::class,'id','fi_product_id');
    }
}
