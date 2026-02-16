<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSet extends Model
{
    use HasFactory;

    protected $table = 'product_sets';
    protected $fillable = [
        'title','subtitle', 'slug', 'product_ids', 'category_id', 'image_id', 'description', 'active'
    ];

    protected $appends = ['price_all','price_info'];


    public function image()
    {
        return $this->hasOne(Image::class,'id','image_id');
    }
    
    public function category()
    {
        return $this->hasOne(Term::class,'id','category_id');
    }

    public function getPriceAllAttribute()
    {
        
        $db_productSetProduct = ProductSetProduct::where('product_set_id',$this->id)->get()->keyBy('product_id')->toArray();
        $product_ids = [];
        if(count($db_productSetProduct) > 0){
            $product_ids = array_column($db_productSetProduct,'product_id');
        }
        $products = Product::whereIn('id', $product_ids)->get();
        $product_price = 0;
        foreach($products as $pro){
            $product_price += ($db_productSetProduct[$pro->id]['qty']??1) * $pro->product_price_now;
        }

        return number_format($product_price,2);
    }


    public function getPriceInfoAttribute()
    {

        $db_productSetProduct = ProductSetProduct::where('product_set_id',$this->id)->get()->keyBy('product_id')->toArray();
        $product_ids = [];
        if(count($db_productSetProduct) > 0){
            $product_ids = array_column($db_productSetProduct,'product_id');
        }
        $products = Product::whereIn('id', $product_ids)->get();
        $product_price_now = 0;
        $local_selling_price = 0;

        foreach($products as $pro){
            $product_price_now += ($db_productSetProduct[$pro->id]['qty']??1) * $pro->product_price_now;
            $local_selling_price += ($db_productSetProduct[$pro->id]['qty']??1) * $pro->local_selling_price;
        }

        $discount_amount = $local_selling_price - $product_price_now;
        $discount_perchantage = $local_selling_price > 0 ? ($discount_amount * 100) /$local_selling_price : 0;

        return [
            'local_selling_price' => number_format( $local_selling_price, 2),
            'price_now' => number_format( $product_price_now, 2),
            'discount_amount' =>number_format( $discount_amount, 2),
            'discount_perchantage' => number_format( $discount_perchantage, 2)
        ];
    }


    public function productLinked()
    {
        return $this->hasOne(Product::class,'product_set_id','id');
    }
    

}

