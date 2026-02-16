<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interior extends Model
{
    use HasFactory;

    protected $table = "interiors";

    protected $fillable = [
        'user_id', 'title', 'slug', 'sub_title', 'image_id', 'category_id', 'active'
    ];
    public function image(){

        return $this->hasOne(Image::class,'id','image_id');
    }

    public function images()
    {
        return $this->hasMany(InteriorImage::class,'interior_id','id');
    }

    public function category()
    {
        return $this->hasOne(Term::class,'id','category_id');
    }
}