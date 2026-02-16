<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSetFabric extends Model
{
    use HasFactory;

    protected $table = 'product_set_fabrics';
    protected $fillable = [
        'title','product_set_id', 'image_id','images', 'active'
    ];


    public function image()
    {
        return $this->hasOne(Image::class,'id','image_id');
    }
}
