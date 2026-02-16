<?php

namespace App\Action;

use App\Models\Image;

class ProductAttribute
{
    public function handle($attribute_variations, $fixed_variation): array
    {
        if (!$attribute_variations) {
            return [
                'status' => 1,
                'data' => false,
                'count' => 0,
            ];
        }

        $tempArr = [];
        $variationImagesArray = [];

        foreach ($attribute_variations as $variation) {
            // Handle variation images
            if ($variation->variation_image) {
                $variationImages = explode('|', $variation->variation_image);
                $imageLinks = Image::query()->whereIn('id', $variationImages)->get();

                foreach ($imageLinks as $image) {
                    $variationImagesArray[] = [
                        'id' => $image->id,
                        'url' => 'https://admin.regalfurniturebd.com/'.$image->full_size_directory,
                    ];
                }
            }

            // Sort and process variation data
            $variation_data = collect(json_decode($variation->variations, true))->sortBy('sort');

            foreach ($variation_data as $item) {
                $data = (object)[
                    'value' => $item['value'],
                    'show_as' => $item['show_as'] === 'Image' ? 'Image' : 'Text',
                    'sort' => $item['sort'],
                    'index' => $item['index']
                ];

                // Handle 'Image' type
                if ($item['show_as'] === 'Image') {
                    $image = Image::query()->find($item['value']);
                    $data->link = 'https://admin.regalfurniturebd.com/'.$image->full_size_directory;
                }

                // Append data to the temp array by attribute index
                if (!isset($tempArr[$item['index']])) {
                    $tempArr[$item['index']] = [
                        'attribute' => $item['index'],
                        'values' => [$data],
                    ];
                } else {
                    $tempArr[$item['index']]['values'][] = $data;
                }
            }
        }

        // Process attributes to remove duplicate values
        $finalArr = $this->removeDuplicate($tempArr, $fixed_variation);

        return [
            'status' => 0,
            'data' => $finalArr,
            'count' => count($attribute_variations),
            'variation_images' => $variationImagesArray ?? false,
        ];
    }

    protected function removeDuplicate($tempArr, $fixed_variation): array
    {
        $finalArr = [];
        foreach ($tempArr as $attrData) {
            $uniqueValues = collect($attrData['values'])
                ->unique('value')
                ->values()->map(function ($item) use ($fixed_variation) {
                    $item->fixed_variation = $item->index === $fixed_variation;
                    return $item;
                })->all();

            $finalArr[] = [
                'attribute' => $attrData['attribute'],
                'values' => $uniqueValues,
            ];
        }

        return $finalArr;
    }
}