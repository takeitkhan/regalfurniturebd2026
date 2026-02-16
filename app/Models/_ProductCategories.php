<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    protected $table = 'productcategories';
    protected $fillable = [
        'id', 'user_id', 'main_pid', 'term_id', 'term_name', 'term_attgroup', 'is_attgroup_active', 'created_at', 'updated_at'
    ];
}
