<?php

namespace App\Http\Resources;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'seo_url' => $this->seo_url,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'short_description' => $this->short_description,
            'description' => strip_tags($this->description),
            'seo_description' => strip_tags($this->seo_description),
            'seo_keywords' => $this->seo_keywords,
            'image' => $this->firstImage->full_size_directory,
            'local_selling_price' => $this->local_selling_price,
            'local_discount' => $this->local_discount,
            'actual_discount' => $this->actual_discount,
            'sku' => $this->sku,
            'price_now' => $this->product_price_now,
            'prebook' => $this->product_price_now,
            'pre_booking' => $this->pre_booking,
            'variation_show_as' => $this->variation_show_as,
            'variation_layer_start' => $this->variation_layer_start,
            'disable_buy' => $this->disable_buy,
            'enable_variation' => $this->enable_variation,
            // 'review_count' => $review_count
            'variations' => $this->additional['variation'] ?? false,
            'special_notification' => $this->additional['main_category'] ? Term::query()->where('id',
                $this->additional['main_category']->term_id)->first()->special_notification ?? false : false,
            'flash_item_information' => $this->additional->have_flash ?? false,
            'combinations' => $this->additional['combinations'] ?? false
        ];
    }
}
