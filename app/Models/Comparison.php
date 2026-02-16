<?php

namespace App\Models;


class Comparison
{
    public $items = array();

    /**
     * Comparison constructor.
     * @param $oldcompare
     */
    public function __construct($oldcompare)
    {
        if ($oldcompare) {
            $this->items = $oldcompare->items;
        }
    }

    /**
     * @param $item
     * @param $id
     */
    public function add($item, $id)
    {
        $storeditem = ['item' => $item];
        //dd($this->items);
        if ($item['productcode'] != null) {
            if (array_key_exists($id, $this->items)) {
                $storeditem = $this->items[$id];
            }
        }
        $this->items[$id] = $storeditem;

    }

}