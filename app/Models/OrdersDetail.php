<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersDetail extends Model
{
    protected $table = 'orders_detail';
    protected $fillable = [
        'user_id', 'order_random', 'vendor_id', 'product_id', 'product_name', 'product_code', 'qty', 'order_date',
        'img', 'local_selling_price', 'local_purchase_price', 'delivery_charge', 'discount','od_status','delivery_date',
        'is_dp','is_flash','flash_id','flash_discount','item_code','color_type','size_color_id','color','size','item_jeson',
        'secret_key', 'is_active', 'product_arrive_times', 'product_arrive_times_day'
    ];

    protected $dates = ['order_date'];

    public function ordermaster(){

        return $this->hasOne(OrdersMaster::class,'order_random','order_random');
    }

    public function firstImage()
    {
        return $this->hasOne(ProductImages::class,'main_pid','product_id')->where('is_main_image',1);
    }

    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id')->with('category');
    }
}
