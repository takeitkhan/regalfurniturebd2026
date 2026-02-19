<?php

namespace App\Exports;

use App\Models\OrdersDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $user = Auth::user();
        if ($user && $user->isVendor()) {
            $query->where('orders_detail.vendor_id', $user->id);
        }

        if (!empty($filters['order_ids'])) {
            $orderIds = is_array($filters['order_ids'])
                ? $filters['order_ids']
                : explode(',', $filters['order_ids']); // support comma-separated strings

            $query->whereIn('om.id', $orderIds);

            // Skip all other filters if order_ids is provided
            return $query->get();
        }

        $hasOrFilters = !empty($filters['search_key']) || !empty($filters['search_term']) || !empty($filters['order_id']) || !empty($filters['order_random'])
            || !empty($filters['customer_name']) || !empty($filters['phone']) || !empty($filters['email'])
            || !empty($filters['product_code']) || !empty($filters['product_name']) || !empty($filters['order_status'])
            || !empty($filters['payment_method']) || !empty($filters['payment_term_status']);

        if ($hasOrFilters) {
            $query->where(function ($q) use ($filters) {
                if (!empty($filters['column']) && !empty($filters['search_key'])) {
                    $q->orWhere($filters['column'], 'like', '%' . $filters['search_key'] . '%');
                }
                if (!empty($filters['search_term'])) {
                    $term = $filters['search_term'];
                    if (is_numeric($term)) {
                        $q->orWhere('om.id', $term);
                    }
                    $q->orWhere('om.order_random', 'like', '%' . $term . '%')
                        ->orWhere('om.customer_name', 'like', '%' . $term . '%')
                        ->orWhere('om.phone', 'like', '%' . $term . '%')
                        ->orWhere('om.email', 'like', '%' . $term . '%')
                        ->orWhere('orders_detail.product_code', 'like', '%' . $term . '%')
                        ->orWhere('orders_detail.product_name', 'like', '%' . $term . '%');
                }
                if (!empty($filters['order_id'])) {
                    $q->orWhere('om.id', $filters['order_id']);
                }
                if (!empty($filters['order_random'])) {
                    $q->orWhere('om.order_random', $filters['order_random']);
                }
                if (!empty($filters['customer_name'])) {
                    $q->orWhere('om.customer_name', 'like', '%' . $filters['customer_name'] . '%');
                }
                if (!empty($filters['phone'])) {
                    $q->orWhere('om.phone', 'like', '%' . $filters['phone'] . '%');
                }
                if (!empty($filters['email'])) {
                    $q->orWhere('om.email', 'like', '%' . $filters['email'] . '%');
                }
                if (!empty($filters['product_code'])) {
                    $q->orWhere('orders_detail.product_code', 'like', '%' . $filters['product_code'] . '%');
                }
                if (!empty($filters['product_name'])) {
                    $q->orWhere('orders_detail.product_name', 'like', '%' . $filters['product_name'] . '%');
                }
                if (!empty($filters['order_status'])) {
                    $q->orWhere('om.order_status', $filters['order_status']);
                }
                if (!empty($filters['payment_method'])) {
                    $q->orWhere('om.payment_method', $filters['payment_method']);
                }
                if (!empty($filters['payment_term_status'])) {
                    $q->orWhere('om.payment_term_status', $filters['payment_term_status']);
                }
            });
        }

        if (!empty($filters['formDate']) && !empty($filters['toDate'])) {
            $from = Carbon::parse($filters['formDate'])->startOfDay();
            $to = Carbon::parse($filters['toDate'])->endOfDay();
            $query->whereBetween('om.order_date', [$from, $to]);
        }

        if (!empty($filters['amount_min']) || !empty($filters['amount_max'])) {
            $min = $filters['amount_min'] !== null ? $filters['amount_min'] : 0;
            $max = $filters['amount_max'] !== null ? $filters['amount_max'] : 999999999;
            $query->whereBetween(DB::raw('CAST(om.total_amount AS DECIMAL(12,2))'), [$min, $max]);
        }

        if (!empty($filters['order_from'])) {
            $query->where('om.order_from', $filters['order_from']);
        }

        if (!empty($filters['pre_booking_order'])) {
            $query->where('om.pre_booking_order', $filters['pre_booking_order']);
        }

        return $query->limit(500)->get();
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
