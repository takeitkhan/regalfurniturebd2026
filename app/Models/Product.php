<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $table = 'products';

    protected $appends = ['product_price_now', 'actual_discount'];

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'sub_title',
        'seo_url',
        'product_code',
        'sku',
        'qty',
        'local',
        'local_selling_price',
        'local_discount',
        'dp_price',
        'intl',
        'intl_selling_price',
        'careInfo',
        'intl_discount',
        'pre_booking_discount',
        'stock_status',
        'delivery_area',
        'delivery_charge',
        'delivery_time',
        'pro_warranty',
        'barcode',
        'upc',
        'ean',
        'isbn',
        'mpn',
        'short_description',
        'purchase_note',
        'seo_description',
        'seo_title',
        'seo_h1',
        'seo_h2',
        'description',
        'seo_keywords',
        'tags',
        'offer_details',
        'offer_start_date',
        'offer_end_date',
        'enable_comment',
        'enable_review',
        'enable_variation',
        'express_delivery',
        'new_arrival',
        'best_selling',
        'flash_sale',
        'flash_time',
        'recommended',
        'disable_buy',
        'order_by_phone',
        'multiple_pricing',
        'emi_available',
        'product_set_id',
        'position',
        'product_set_qty',
        'is_sticky',
        'lang',
        'parent_id',
        'enable_timespan',
        'pre_booking',
        'is_active',
        'created_at',
        'updated_at',
        'recommended_serial',
        'new_arrival_serial',
        'cash_on_delivery',
        'variation_show_as'
    ];

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }


    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'item_id', 'id');
    }

    public function threeSixtyDegreeImage(): HasMany
    {
        return $this->hasMany(ProductVideos::class, 'product_id', 'id')
            ->with('image')
            ->where('product_videos.type', '360')
            ->orderBy('product_videos.position', 'ASC');
    }


    public function youtubeVideo(): HasOne
    {
        return $this->hasOne(ProductVideos::class, 'product_id', 'id')
            ->where('product_videos.type', 'youtube');
    }

    public function ar(): HasOne
    {
        return $this->hasOne(ProductVideos::class, 'product_id', 'id')
            ->where('product_videos.type', 'ar');
    }

    public function firstImage(): HasOne
    {
        return $this->hasOne(ProductImages::class, 'main_pid', 'id')->where(
            'is_main_image',
            '!=',
            2
        )->orderBy('is_main_image', 'DESC');
    }

    public function secondImage(): HasOne
    {
        return $this->hasOne(ProductImages::class, 'main_pid', 'id')->where(
            'is_main_image',
            '!=',
            1
        )->orderBy('is_main_image', 'DESC');
    }

    public function priorityImages(): HasMany
    {
        return $this->hasMany(ProductImages::class, 'main_pid', 'id')->where(
            'is_main_image',
            '!=',
            0
        )->orderBy('is_main_image', 'ASC');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImages::class, 'main_pid', 'id');
    }


    public function getProductPriceNowAttribute(): ?float
    {
        $regularprice = $this->local_selling_price;
        $save = ($this->local_selling_price * $this->local_discount) / 100;
        $sp = round($regularprice - $save);

        return $sp ?? 0;
    }


    public function getActualDiscountAttribute(): ?float
    {
        $discountPercentage = ($this->local_selling_price * $this->local_discount) / 100;

        return round($discountPercentage);
    }

    public function product_set(): HasOne
    {
        return $this->hasOne(ProductSet::class, 'id', 'product_set_id')
            ->with('image');
    }

    public function category(): HasMany
    {
        return $this->hasMany(ProductCategories::class, 'main_pid', 'id');
    }

    public function attribute_variations(): HasMany
    {
        return $this->hasMany(ProductAttributeVariation::class, 'main_pid', 'id');
    }

    /**
     * Scope a query to only include fixed variation of a product.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeFixedVariation($query)
    {
        $attribute = $this->attributes()->where('for_this', 'attribute')
            ->whereJsonContains('attr_value', function ($value) {
                return is_array($value) && array_key_exists('fixed_variation', $value) && $value['fixed_variation'];
            })
            ->first();

        $attrValue = json_decode($attribute->attr_value, true);

        return $attrValue ? $attrValue[0]['fixed_variation'] : null;
    }

    /**
     * Get the product's attributes.
     *
     * @return HasMany
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id');
    }
}
