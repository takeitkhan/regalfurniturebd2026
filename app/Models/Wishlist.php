<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id', 'product_id'
    ];

    public $items = [];

    /**
     * Comparison constructor.
     * @param $oldcompare
     */
    // public function __construct($oldcompare)
    // {
    //     if ($oldcompare) {
    //         $this->items = $oldcompare->items;
    //     }
    // }

    // /**
    //  * @param $item
    //  * @param $id
    //  */
    // public function add($item, $id)
    // {
    //     $storeditem = ['item' => $item];
    //     //dd($this->items);
    //     if ($item['productcode'] != null) {
    //         if (array_key_exists($id, $this->items)) {
    //             $storeditem = $this->items[$id];
    //         }
    //     }
    //     $this->items[$id] = $storeditem;
    // }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
