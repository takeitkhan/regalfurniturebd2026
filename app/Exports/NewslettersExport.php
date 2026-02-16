<?php

namespace App\Exports;

use App\Models\Newsletter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewslettersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Newsletter::select(
            'gender', 'email', 'created_at'
        )->get();
//            ->leftJoin('orders_master AS om', function ($join) {
//            $join->on('orders_detail.order_random', '=', 'om.order_random');
//        })
    }

    public function headings(): array
    {
        return [
            'Gender',
            'Email',
            'Added At'
        ];
    }
}
