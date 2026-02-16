<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductAttributeVariation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToModel, WithStartRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
//        dd($row);
//        try {
            if ($row[4]) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                Product::updateOrCreate(
                    ['id' => (int)$row[0], 'user_id' => (int)$row[1]],
                    [
                        'title' => (string)$row[2],
                        'sub_title' => (string)$row[3],
                        'product_code' => (string)$row[4],
                        'local_selling_price' => (string)$row[5],
                        'local_discount' => (string)$row[6],
//                        'intl_selling_price' => (string)$row[7],
//                        'intl_discount' => (string)$row[8],
                        'enable_variation' => $row[12],
                        'disable_buy' => $row[13],
                        'delivery_time' => (string)$row[14],
                        'is_active' => $row[15] ?? 0
                    ]
                );
                if($row[12] == 'on') {
//                    dd($row[11] );
                    ProductAttributeVariation::where('main_pid', $row[0])->where('id', $row[11])->update(
                        [
                            'variation_sub_title' => (string)$row[7],
                            'variation_product_code' => $row[8],
                            'product_regular_price' => $row[9],
                            'product_selling_price' => $row[10],
                            'disable_buy' => $row[13] ?? 'off',
                            'is_active' => $row[15] ?? 1
                        ]);
                }

                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                if (!empty ($row[0])) {
                    $product = Product::find($row[0]);

                    if ($product) {

                        Cache::forget('product-simple-info-' . $product->seo_url);
                        Cache::forget('product-product-info-' . $product->seo_url);
                        Cache::forget('product-images-videos-degrees-' . $product->seo_url);

                        Cache::forget('product-same-cat-products-' . $product->seo_url);
                        Cache::forget('product-similar-product' . $product->seo_url);
                        Cache::forget('product-goes-well-products-' . $product->seo_url);
                        Cache::forget('product-other-see-products-' . $product->seo_url);

                    }

                }

                Cache::forget('home-prebookings');
                Cache::forget('home-offers');
                Cache::forget('home-new-arrivals');
                Cache::forget('common-top-offers');

            }


//        } catch (\Throwable $th) {
//            dd($row);
//        }


        //return true;
        // 'id', 'user_id', 'title', 'product_code',
        //            'local_selling_price', 'local_discount',
        //            'intl_selling_price', 'intl_discount', 'is_active'

    }

    public function startRow(): int
    {
        return 2;
    }
}
