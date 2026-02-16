<?php

namespace App\Imports;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ShowroomsImport implements ToModel,WithStartRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        //dd($row[0]);
       
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Post::updateOrCreate(
            [
                'id' => (int)$row[0],
                'categories' => 651
            ], [
                'user_id' => (int)$row[1],
                'title' => (string)$row[2],
                'sub_title' => (string)$row[3],
                'seo_url' => (string)$row[4],
                'author' => (string)$row[5],
                'description' => (string)$row[6],
                'short_description' => (string)$row[7],
                'brand' => (string)$row[8],
                'phone' => (string)$row[9],
                'opening_hours' => (string)$row[10],
                'latitude' => (string)$row[11],
                'longitude' => (string)$row[12],
                'address' => (string)$row[13],
                'division' => (string)$row[14],
                'district' => (string)$row[15],
                'thana' => (string)$row[16],
                'shop_type' => (string)$row[17],
                'is_active' => (string)$row[18]
            ]
        );
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Cache::forget('common-showrooms-'.$row[15]);
        Cache::forget('common-showrooms-');

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
