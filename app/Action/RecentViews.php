<?php

namespace App\Action;

use Illuminate\Support\Facades\Cache;

class RecentViews
{
    public function handle($token, $product)
    {
        if ($token == null || $token == "")
            return false;

        $self_views_key = "self_views_" . $token;
        $existing_product_ids = Cache::get($self_views_key, []);
        $product_ids = [$product];


        if (is_array($existing_product_ids)) {
            $product_ids = array_merge($product_ids, $existing_product_ids);
        }

        // Cache::put($self_views_key, $product_ids, $product_ids);

        return Cache::remember($self_views_key, 18080, function () use ($product_ids) {
            return $product_ids;
        });
    }
}