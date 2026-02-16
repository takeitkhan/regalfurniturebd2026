<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $table = 'product_variations';

    protected $fillable = [
        'product_id','variation_product_id', 'variation_group_id', 'title', 'image_id', 'active'
    ];

    public function variationGroup()
    {
        return $this->hasOne(VariationGroup::class, 'id', 'variation_group_id' );
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
