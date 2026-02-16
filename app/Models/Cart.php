<?php

namespace App\Models;


use Illuminate\Support\Facades\Session;

class Cart
{
    public $items = null;
    public $totalqty = 0;
    public $totalprice = 0;


    public function __construct($oldcart)
    {

        // dd($oldcart);

        if ($oldcart) {
            $this->items = json_decode(json_encode($oldcart->items),true);
            $this->totalqty = $oldcart->totalqty;
            $this->totalprice = $oldcart->totalprice;
        }
    }


    public function add($item, $id)
    {

        if ($item['productcode'] != null) {
            $storeditem = [
                'qty' => 0,
                'purchaseprice' => $item['purchaseprice'],
                'pset_id' => ($item['pset_id']??null),
                'fabric_id' => ($item['fabric_id']??null),
                'item' => $item
            ];


            if ($this->items) {
                if (array_key_exists($id, $this->items)) {
                    $storeditem = $this->items[$id];
                }
            }
            $storeditem['qty'] = (int)$storeditem['qty'] + (int)$item['qty'];

            //$storeditem['purchaseprice'] = (int)$item['purchaseprice'] * (int)$item['qty'];

            $this->items[$id] = $storeditem;

            $this->totalqty += $item['qty'];
            $this->totalprice += (int)$item['purchaseprice'] * $item['qty'];

        }
    }

    public function remove_item($oldcart, $code)
    {

    }

    public function update($oldCart)
    {

        //dd($oldCart);

        if (!empty($oldCart)) {

            if (!empty($oldCart->items)) {
                foreach ($oldCart->items as $item) {
                    $qty[] = $item['qty'];
                    $in_total[] = (int)$item['purchaseprice'] * $item['qty'];

                    //echo $item['qty'] . ' | ' . (int)$item['purchaseprice'] * $item['qty'];
                    //echo '<br/>';
                }

                $cart['qtysum'] = array_sum($qty);
                $cart['totalsum'] = array_sum($in_total);


                return $cart;
                //dd($cart);
            } else {

            }


        }

    }

}
