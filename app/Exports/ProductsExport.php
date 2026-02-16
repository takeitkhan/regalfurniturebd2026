<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('product_export')->get();

//        return Product::select(
//            'id', 'user_id', 'title','sub_title', 'product_code',
//            'local_selling_price', 'local_discount',
//            'intl_selling_price', 'intl_discount', 'disable_buy','delivery_time', 'is_active'
//        )->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'User ID',
            'Title',
            'Sub title',
            'Product Code',
            'Local Selling Price',
            'Local Discount',
            'Variant Sub Title',
            'Variant Product Code',
            'Variant Product Price',
            'Variant Discount',
            'Variant ID',
            'Enable Variation',
            'Disable Buy',
            'Delivery Time',
            'Is Active'
        ];
    }
}
