<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteriorImage extends Model
{
    use HasFactory;
    protected $table = "interior_images";

    protected $fillable = [
        'image_id', 'interior_id', 'title', 'caption', 'info'
    ];

    public function image(){

        return $this->hasOne(Image::class,'id','image_id');
    }
}