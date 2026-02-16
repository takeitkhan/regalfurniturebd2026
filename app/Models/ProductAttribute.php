<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_attributes';

    protected $fillable = ['product_id', 'for_this', 'attr_value'];

    public static function getData($product_id){
        $data = ProductAttribute::where('product_id', $product_id)->where('for_this', 'attribute')->orderBy('id', 'desc')->first();
        if($data) {
            $values = json_decode($data->attr_value);
            $value = [];
            foreach ($values as $key => $v) {
                $attrValue = json_decode($v->attr_value);
                $attrImages = $v->attr_images ?? null;
                $attrImages = json_decode($attrImages)  ?? [];
                $getVArr = [];
                if ($v->attr_type == 'pre-defined') {
                    $checkGroup = ProductAttributeGroup::where('id', $v->attr_group_id)->first();
                    $attrName = $checkGroup ? true : false;
                    foreach ($attrValue as $a) {
                        $aExplode = explode('|', $a);
                        $checkItem = ProductAttributeGroupItem::where('group_id', $v->attr_group_id)->where('id', $aExplode[1])->first();
                        if ($checkItem) {
                            $getVArr [] = $a;
                        }
                    }
                    $getIArr = $attrImages;
                } else {
                    $attrName = true;
                    $getVArr = $attrValue;
                    $getIArr = $attrImages;
                }
                if ($attrName) {
                    $fixed_variation = $v->fixed_variation ?? null;
                    if($fixed_variation == $v->attr_name){
                        $value []= (object)[
                            'fixed_variation' => $fixed_variation,
                            'key' => 0,
                            'attr_name' => $v->attr_name,
                            'attr_value' => json_encode($getVArr),
                            'attr_images' => json_encode($attrImages),
                            'attr_type' => $v->attr_type,
                            'attr_group_id' => $v->attr_group_id ?? null,
                            'attr_show_as_decision' => $v->attr_show_as_decision ?? null,
                        ];
                    }
                   if($fixed_variation != $v->attr_name){
                        $value []= (object)[
                            'fixed_variation' => $fixed_variation,
                            'key' => $key+1,
                            'attr_name' => $v->attr_name,
                            'attr_value' => json_encode($getVArr),
                            'attr_images' => json_encode($attrImages),
                            'attr_type' => $v->attr_type,
                            'attr_group_id' => $v->attr_group_id ?? null,
                            'attr_show_as_decision' => $v->attr_show_as_decision ?? null,
                        ];
                    }

                }
            }
            $data = [
                'id' => $data->id,
                'data' => collect($value)->sortBy(['key', 'asc']),
            ];
        }
            return $data ?? null;
    }
}
