<?php

namespace App\Repositories\Report;


use App\Models\Report;
use Illuminate\Support\Facades\DB;

class EloquentReport implements ReportInterface
{

    public function highest_sold_products(array $options = [])
    {

        // SELECT product_id, SUM(qty) AS TotalQuantity FROM orders_detail GROUP BY product_id ORDER BY SUM(qty) DESC

        return \App\Models\OrdersDetail::select(DB::raw("product_id, SUM(qty) AS total_qty, SUM(local_purchase_price) AS total_amount"))->groupBy('product_id')->orderByRaw('SUM(qty) DESC')->paginate(30);

    }

    public function never_sold_products(array $options = [])
    {
        // SELECT * FROM products WHERE products.id NOT IN (SELECT orders_detail.product_id FROM orders_detail WHERE orders_detail.qty > 1)
        return \App\Models\Product::select(DB::raw("*"))
            ->whereRaw('products.id NOT IN (SELECT orders_detail.product_id FROM orders_detail WHERE orders_detail.qty > 1)')
            ->orderBy('id', 'DESC')
            ->paginate(30);
    }

    public function monthly_sales(array $options = [])
    {

    }
}