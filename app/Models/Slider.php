<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = ['image_id','type','title','description','color_code','text_color','border_bottom','url','device','internal','position','active'];


    public function image()
    {
        return $this->hasOne(Image::class,'id','image_id');
    }

}