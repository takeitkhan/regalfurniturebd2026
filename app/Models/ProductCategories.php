<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCategories extends Model
{
    protected $table = 'productcategories';
    protected $fillable = [
        'id', 'user_id', 'main_pid', 'term_id', 'term_name', 'term_attgroup', 'is_attgroup_active', 'sort_order', 'created_at', 'updated_at'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'main_pid', 'id');
    }
}
