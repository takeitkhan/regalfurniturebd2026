<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    protected $table = 'homesettings';
    protected $fillable = [
        'cat_first', 'cat_second', 'cat_third', 'cat_fourth', 'cat_fifth', 'cat_sixth', 'cat_seventh', 'cat_eighth', 'home_category',
        'home_banner_one','home_banner_two','home_banner_three','main_slider','home_brand', 'home_slider', 'flash_banner','explore_products'
    ];
}
