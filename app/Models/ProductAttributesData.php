<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributesData extends Model
{
    protected $table = 'productattributesdata';

    protected $fillable = [
        'id', 'user_id', 'main_pid', 'attgroup_id', 'key', 'value', 'default_value', 'attribute_id', 'created_at', 'updated_at'
    ];

    public static function getValueByProductId($main_pid, $key){
        $data = ProductAttributesData::where('main_pid', $main_pid)->where('key', $key)->first();
        return $data->value ?? null;
    }
}
