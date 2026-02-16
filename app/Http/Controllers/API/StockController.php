<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\Temporaryorder\TemporaryorderInterface;
use Illuminate\Http\Request;
use DB;
class StockController extends Controller
{
    //

    public function checkStock(Request $r){
        return $this->getProductArriveTime('Chandpur',   [
            'code' => '993346',
            'qty' => 1
        ],);

        $districtName = 'Tangail';
        $depot_id = $this->getDepotId($districtName);
        $product_codes = [
            [
                'code' => '993346',
                'qty' => 1
            ],
            [
                'code' => '993308',
                'qty' => 2
            ],
        ];

        $arr = [];

        foreach($product_codes as $p){
            $regular_query = DB::table('product_stocks')->where('depot_id', $depot_id)->where('product_code', $p['code'])->get()->sum('available_qty'); // 0
            if($regular_query >= $p['qty']) {
                // return 3 days
                $qty = $regular_query;
                $msg = 'Product will arrive within 3 days';
            } else {
                $main_depot_search = DB::table('product_stocks')->where('depot_id', 4)->where('product_code', $p['code'])->get()->sum('available_qty'); // 0
                if($main_depot_search >= $p['qty']) {
                    // return 5 days
                    $qty = $main_depot_search;
                    $msg = 'Product will arrive within 5 days';
                } else {
                    // return 15 days
                    $qty = 0;
                    $msg = 'Product will arrive within 15 days';
                }
            }

            $arr []= [
                'message' => $msg,
                'product_code' => $p['code'],
                'qty' => $qty ?? 0,
            ];
        //$regular_query = SELECT * FROM `product_stocks` WHERE depot_id = 6 AND product_id = 993346;

        // If regular deport available quantity is less than the required quantity, then move to below query-

        //$main_depot_query = SELECT * FROM `product_stocks` WHERE depot_id = 4 AND product_id = 993346;
        // quantity available 5
        // so, customer will get the product within 6 days..
        }

        return $arr;


    }


    public function getDepotId($district) {
        $get = DB::raw("SELECT id, name FROM `depots` WHERE districts LIKE '%$district%'");
        return $get->id ?? null;
    }


    public function getProductArriveTime($districtName, $product){
        $districtName = $districtName;
        $depot_id = Depot::where('districts', 'LIKE', '%'. $districtName.'%')->first()->id ?? null;

        $p = [
            'code' => '', // product code
            'qty' => ''  //Product Quantity
        ];
        $p = array_merge($p, $product);

        $arr = [];
            $regular_query = DB::table('product_stocks')->where('depot_id', $depot_id)->where('product_code', $p['code'])->get()->sum('available_qty'); // 0
            if($regular_query >= $p['qty']) {
                // return 3 days
                $qty = $regular_query;
                $msg = 'Product will arrive within 3 days';
                $days = 3;
            } else {
                $main_depot_search = DB::table('product_stocks')->where('depot_id', 4)->where('product_code', $p['code'])->get()->sum('available_qty'); // 0
                if($main_depot_search >= $p['qty']) {
                    // return 5 days
                    $qty = $main_depot_search;
                    $msg = 'Product will arrive within 5 days';
                    $days = 5;
                } else {
                    // return 15 days
                    $qty = 0;
                    $msg = 'Product will arrive within 15 days';
                    $days = 15;
                }
            }

            $arr = [
                'message' => $msg,
                'product_code' => $p['code'],
                'qty' => $qty ?? 0,
                'days' => $days ?? 15
            ];
            //$regular_query = SELECT * FROM `product_stocks` WHERE depot_id = 6 AND product_id = 993346;

            // If regular deport available quantity is less than the required quantity, then move to below query-

            //$main_depot_query = SELECT * FROM `product_stocks` WHERE depot_id = 4 AND product_id = 993346;
            // quantity available 5
            // so, customer will get the product within 6 days..


        return $arr;


    }

    public function getMaximumProductArriveTime($cart_product, $district){
        $d = [];
        $max_day = 15;
        foreach ($cart_product as $item) {
            $p = [
                'code' => $item['item']['productcode'],
                'qty' => $item['qty']
            ];
            $depot_id = $district;
            $regular_query = DB::table('product_stocks')->where('depot_id', $depot_id)->where('product_code', $p['code'])->get()->sum('available_qty'); // 0
            if($regular_query >= $p['qty']) {
                // return 3 days
                $qty = $regular_query;
                $msg = 'Product will arrive within 3 days';
                $days = 3;
            } else {
                $main_depot_search = DB::table('product_stocks')->where('depot_id', 4)->where('product_code', $p['code'])->get()->sum('available_qty'); // 0
                if($main_depot_search >= $p['qty']) {
                    // return 5 days
                    $qty = $main_depot_search;
                    $msg = 'Product will arrive within 5 days';
                    $days = 5;
                } else {
                    // return 15 days
                    $qty = 0;
                    $msg = 'Product will arrive within 15 days';
                    $days = 15;
                }
            }

           $d []= $days;
        }
        if($d){
            $max_day = max($d);
        }
        return 'Product will arrive within '.$max_day.' days';
    }
}
