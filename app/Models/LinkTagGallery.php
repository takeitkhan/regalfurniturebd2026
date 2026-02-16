<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkTagGallery extends Model
{
    use HasFactory;
    protected $fillable = ['image_id','category_id','url','url_type','active'];

    public function image()
    {
        return $this->hasOne(Image::class,'id','image_id');
    }


    public function term()
    {
        return $this->hasOne(Term::class,'id','category_id');
    }
}