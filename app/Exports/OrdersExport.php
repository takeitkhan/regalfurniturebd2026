<?php

namespace App\Exports;

use App\Models\OrdersDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $filters = $this->data['filter'] ?? [];

        $query = OrdersDetail::query()
            ->select([
                'om.id as OMID',
                'orders_detail.product_name as Product Name',
                'orders_detail.product_code as PCode',
                'orders_detail.qty as Qty',
                'orders_detail.local_selling_price as Unit Price',
                'orders_detail.local_purchase_price as Selling Price',
                'om.customer_name as CName',
                'om.phone as Phone',
                'om.emergency_phone as EPhone',
                'om.address as Address',
                'om.email as Email',
                'om.order_date as Order Date',
                'om.payment_method as Payment Method',
                'om.grand_total as GTotal',
                'om.total_amount as Total',
                'om.coupon_type as CType',
                'om.coupon_code as CCode',
                'om.coupon_discount as CDiscount',
                'om.district as District',
                'om.order_status as Action',
            ])
            ->leftJoin('orders_master as om', 'orders_detail.order_random', '=', 'om.order_random');

        if (!empty($filters['order_ids'])) {
            $orderIds = is_array($filters['order_ids'])
                ? $filters['order_ids']
                : explode(',', $filters['order_ids']); // support comma-separated strings

            $query->whereIn('om.id', $orderIds);

            // Skip all other filters if order_ids is provided
            return $query->get();
        }

        // Optional: Filter by search key
        if (!empty($filters['column']) && !empty($filters['search_key'])) {
            $query->where($filters['column'], 'like', '%'.$filters['search_key'].'%');
        }

        // Optional: Filter by date range
        if (!empty($filters['formDate']) && !empty($filters['toDate'])) {
            $from = Carbon::parse($filters['formDate'])->startOfDay();
            $to = Carbon::parse($filters['toDate'])->endOfDay();
            $query->whereBetween('om.created_at', [$from, $to]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'OMID',
            'Product Name',
            'PCode',
            'Qty',
            'Unit Price',
            'Selling Price',
            'CName',
            'Phone',
            'EPhone',
            'Address',
            'Email',
            'Order Date',
            'Payment Method',
            'GTotal',
            'Total',
            'CType',
            'CCode',
            'CDiscount',
            'District',
            'Action'
        ];
    }
}
