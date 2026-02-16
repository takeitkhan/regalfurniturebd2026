<?php
namespace App\Action;

use App\Models\FlashItem;
use Carbon\Carbon;

class FlashSale
{
    public function handle($product)
    {
        return FlashItem::query()
            ->leftJoin('flash_schedules', 'flash_schedules.id', '=', 'flash_items.fi_shedule_id')
            ->where('flash_items.fi_product_id', $product)
            ->where('flash_schedules.fs_is_active', 1)
            ->where('flash_schedules.fs_start_date', '<=', Carbon::now()->format('Y-m-d h:i:s'))
            ->where('flash_schedules.fs_end_date', '>=', Carbon::now()->format('Y-m-d h:i:s'))
            ->orderBy('flash_items.id', 'desc')
            ->first();
    }
}