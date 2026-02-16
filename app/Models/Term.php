<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'terms';
    protected $fillable = [
        'name', 'seo_url', 'seo_h1', 'seo_h2', 'seo_h3', 'seo_h4', 'seo_h5', 'type', 'position', 'cssid', 'cssclass', 'description', 'parent', 'connected_with',
        'page_image', 'in_product_home', 'term_keywords', 'home_image', 'term_menu_icon', 'serial', 'term_menu_arrow', 'with_sub_menu', 'sub_menu_width', 'banner1', 'is_published', 'banner2', 'timespan_id', 'column_count', 'special_notification'
    ];


    public function timespan()
    {
        return $this->hasOne(DeliveryTimespan::class, 'id', 'timespan_id');
    }

    public function home_img()
    {
        return $this->hasOne(Image::class, 'id', 'home_image');
    }

    public function page_img()
    {
        return $this->hasOne(Image::class, 'id', 'page_image');
    }

    public function banner_img()
    {
        return $this->hasOne(Image::class, 'id', 'banner1');
    }

    public function parent_cat()
    {
        return $this->hasOne(Term::class, 'id', 'parent');
    }

    public function sub_cats()
    {
        return $this->hasMany(Term::class, 'parent', 'id')->with('home_img', 'page_img')->withCount('products');
    }

    public function products()
    {
        return $this->hasMany(ProductCategories::class, 'term_id', 'id');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeIsCategory(Builder $query): Builder
    {
        return $query->where('type', 'category');
    }
}
