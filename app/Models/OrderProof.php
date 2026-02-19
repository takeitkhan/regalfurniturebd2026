<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProof extends Model
{
    protected $table = 'order_proofs';

    protected $fillable = [
        'order_id',
        'image_id',
        'note',
        'created_by'
    ];

    public function order()
    {
        return $this->belongsTo(OrdersMaster::class, 'order_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
