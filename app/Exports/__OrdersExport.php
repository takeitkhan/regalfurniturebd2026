<?php

namespace App\Exports;

use App\Models\OrdersDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{

    private $data = [];
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     * 
    public function __construct()
    {
        
    }
     */
    public function collection()
    {
        
       
        $orders = OrdersDetail::select(
            'om.id', 'orders_detail.product_name', 'orders_detail.product_code', 'orders_detail.qty', 'om.customer_name', 'om.phone', 'om.emergency_phone', 'om.address',
            'om.email', 'om.order_date', 'om.payment_method', 'om.grand_total', 'om.total_amount', 'om.coupon_type',
            'om.coupon_code', 'om.coupon_discount', 'om.district', 'om.order_status'
        )->leftJoin('orders_master AS om', function ($join) {
            $join->on('orders_detail.order_random', '=', 'om.order_random');
        });


        
        if (!empty($this->data['filter']['column']) && !empty($this->data['filter']['search_key'])) {
            $orders = $orders->where($this->data['filter']['column'], 'like', '%' . $this->data['filter']['search_key'] . '%');
            
        }


        if (!empty($this->data['filter']['formDate']) && !empty($this->data['filter']['toDate'])) {
            $from = Carbon::parse($this->data['filter']['formDate']);
            $to  = Carbon::parse($this->data['filter']['toDate']);
            $orders = $orders->whereBetween('om.created_at', array($from->toDateString(), $to->toDateString()));
            
        }


        return $orders->get();;
    }

    public function headings(): array
    {
        return [
            'OMID',
            'Product Name',
            'PCode',
            'Qty',
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
