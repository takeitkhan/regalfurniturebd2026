<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    protected $table = 'product_questions';
    protected $fillable = [
     'user_id', 'main_pid', 'vendor_id', 'qa_type', 'que_id', 'description', 'is_active', 'created_at', 'updated_at'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function answers(){
        return $this->hasMany(ProductQuestion::class, 'que_id', 'id' )->where('qa_type', 2);
    }


}
