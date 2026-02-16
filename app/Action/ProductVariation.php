<?php

namespace App\Action;

use App\Models\Image;
use App\Models\ProductAttributeVariation;
use function explode;
use function json_decode;

class ProductVariation
{
    public function handle($product_id)
    {
        $get = ProductAttributeVariation::query()
            ->where('main_pid', $product_id)
            ->where('is_active', 1)
            ->get();


        $value = [];


        foreach ($get as $key => $data) {

            $images = [];
            $img = $data->variation_image ? explode('|', $data->variation_image) : [];

            $img = $data->variation_image ? Image::query()->whereIn('id', $img)->get() : false;

            if ($img) {
                foreach ($img as $im) {
                    $images[] = [
                        'id' => $im->id,
                        'url' => 'https://admin.regalfurniturebd.com/'.$im->full_size_directory,
                    ];
                }
            }

            $preselect = $key == 0;

            $variations = json_decode($data->variations);
            $new_variations = [];

            foreach ($variations as $variation) {
                if ($variation->show_as == 'Image') {
                    $image = Image::query()->find($variation->value);
                    $link = 'https://admin.regalfurniturebd.com/'.$image->full_size_directory;
                    $new_variations[$variation->index] = (object)[
                        'value' => $variation->value,
                        'show_as' => 'Image',
                        'sort' => $variation->sort,
                        'index' => $variation->index,
                        'link' => $link,
                    ];
                } else {
                    $new_variations[$variation->index] = (object)[
                        'value' => $variation->value,
                        'show_as' => 'Text',
                        'sort' => $variation->sort,
                        'index' => $variation->index,
                    ];
                }
            }
            
            $value [$data->id] = [
                'is_first_selected' => $preselect,
                'variation_id' => $data->id,
                'main_pid' => $data->main_pid,
                'main_pcode' => $data->main_pcode,
                'combination_name' => $data->combination_name,
                'variation_video' => $data->variation_video,
                'variations' => (object)$new_variations,
                'product_regular_price' => $data->product_regular_price ?? 0,
                'product_selling_discount' => $data->product_selling_price ?? 0,
                'product_price_now' => !empty($data->product_selling_price) ? $data->product_regular_price - ($data->product_regular_price * $data->product_selling_price / 100) : $data->product_regular_price,
                'save_price' => !empty($data->product_selling_price) ? $data->product_regular_price * $data->product_selling_price / 100 : 0,
                'variation_product_code' => $data->variation_product_code,
                'variation_sub_title' => $data->variation_sub_title,
                'variation_image' => $images ?? false,
                'is_active' => $data->is_active,
                'disable_buy' => $data->disable_buy,
            ];
        }

        return $value;
    }
}