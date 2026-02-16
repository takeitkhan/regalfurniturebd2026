<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVideos extends Model
{
    protected $table = 'product_videos';
    protected $fillable = [
        'link', 'product_id', 'type','position','active'
    ];
    
    
    public function image(){
        
        return $this->hasOne(Image::class,'id','link');
    }
}
