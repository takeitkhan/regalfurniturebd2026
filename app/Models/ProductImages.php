<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    protected $table = 'productimages';
    protected $fillable = [
        'id', 'user_id', 'main_pid', 'media_id', 'filename', 'full_size_directory', 'icon_size_directory', 'is_main_image', 'created_at', 'updated_at'
    ];
}
