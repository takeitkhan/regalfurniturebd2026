<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeGroup;
use App\Models\ProductAttributeGroupItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\ProductAttributeVariation;
use App\Models\Product;
//Nipun
class ProductAttributesDataController extends Controller
{
    //Group Field
    public function groupInndex(Request $request){
        if($request->group_id){
            $editGroup = ProductAttributeGroup::find($request->group_id);
        }else {
            $editGroup = null;
        }
        return view('product.attributes.group', compact('editGroup'));
    }

    public function groupStoreUpdate(Request $request){
        $slug = strtolower($request->group_slug ? str_replace(' ', '-', $request->group_slug) : str_replace(' ', '-', $request->group_name));
        $attr = [
            'group_name' => $request->group_name,
            'group_slug' => $slug,
        ];
        $check = ProductAttributeGroup::where('group_slug', $slug)->first();
        if($request->group_id) {
            ProductAttributeGroup::where('id', $request->group_id)->update($attr);
            return redirect()->back()->with('success', 'Updated successfully');
        }
        if($check){
            return redirect()->back()->with('error', 'Already Added');
        }else {
            ProductAttributeGroup::create($attr);
            return redirect()->back()->with('success', 'Added successfully');
        }
    }

    public function groupDestroy(Request $request){
        $data = ProductAttributeGroup::find($request->group_id);
        $data->delete();
        return redirect()->back()->with('success', 'Delete Successfully');
    }

    /** Item */
    public function groupItemInndex(Request $request, $group_id){
        if($request->item_id){
            $editItem = ProductAttributeGroupItem::find($request->item_id);
        }else {
            $editItem = null;
        }
        $group_id = $group_id;
        return view('product.attributes.group_item', compact('editItem', 'group_id'));
    }

    public function groupItemStoreUpdate(Request $request){
        $slug = strtolower($request->item_slug ? str_replace(' ', '-', $request->item_slug) : str_replace(' ', '-', $request->item_name));
        $prefix = ProductAttributeGroup::where('id', $request->group_id)->first()->group_slug;
        $attr = [
            'item_name' => $request->item_name,
            'item_slug' => $prefix.'-'.$slug,
            'group_id' => $request->group_id,
        ];
        $check = ProductAttributeGroupItem::where('item_slug', $slug)->first();
        if($request->item_id) {
            ProductAttributeGroupItem::where('id', $request->item_id)->update($attr);
            return redirect()->back()->with('success', 'Updated successfully');
        }
        if($check){
            return redirect()->back()->with('error', 'Already Added');
        }else {
            ProductAttributeGroupItem::create($attr);
            return redirect()->back()->with('success', 'Added successfully');
        }
    }

    public function groupItemDestroy(Request $request){
        $data = ProductAttributeGroupItem::find($request->item_id);
        $data->delete();
        return redirect()->back()->with('success', 'Delete Successfully');
    }


    public function productAttributeStore(Request $r){
//        dd($r->all());
        $attrEle = [];
        if($r->attr){
            foreach($r->attr as $v){
                $v = (object)$v;
//                $attrType = $v->attr_type == 'pre-defined' ? implode('|', $v->attr_value) : $v->attr_value;
                $attrValue = $v->attr_type == 'pre-defined' ? $v->attr_value :  explode('|', $v->attr_value);
                $attrImages = $v->attr_type == 'pre-defined' ? explode('|',$v->attr_images) :  explode('|', $v->attr_images);

                $attrEle []= [
                    'fixed_variation' => $r->fixed_variation,
                    'attr_name' => $v->attr_name,
                    'attr_value' => json_encode($attrValue),
                    'attr_images' => json_encode($attrImages),
                    'attr_show_as_decision' => $v->attr_textarea_decision ?? 'Text',
                    'attr_type' =>  $v->attr_type,
                    'attr_group_id' => $v->predefine_id ?? null,
                ];
            }
        }
//        dd($attrEle);
        $attr = [
            'product_id' => $r->product_id,
            'attr_value' => json_encode($attrEle),
            'for_this' => 'attribute',
        ];
        if($r->product_attr_id){
            $data = ProductAttribute::where('id', $r->product_attr_id)->update($attr);
            $msg =  'Updated successfully';
        }else {
            $data = ProductAttribute::create($attr);
            $msg =  'Added successfully';
        }
        if($data){
            return redirect()->back()->with('success', $msg);
        }else {
            return redirect()->back()->with('error', 'Error!');
        }
    }


    function productVariationStore(Request $r){
//        dd($r->all());
        $product_id = $r->product_id;
        $product_code = $r->product_code;        
        $variation = $r->variation;
        $attr = [];
        $check = ProductAttributeVariation::Where('main_pid', $product_id);
        if($check){
            $check->delete();
        }
        if(!empty($variation)) {
            foreach ($variation as $v) {
                $i = 0;
                $variationItems = [];
                foreach($v['items'] as $variationTittle => $item){
                    $i++;
                    $variationItems [$variationTittle] = [
                        "show_as" => $item['show_as'],
                        "value" => $item['value'] ?? null,
                        "sort" => $i,
                        'index' => $variationTittle
                    ];
                }
//                dd(collect($variationItems));
                $attr [] = [
                    'main_pid' => $product_id,
                    'main_pcode' => $product_code,
                    'combination_name' => $v['combination_name'],
                    'variations' => json_encode($variationItems),//json_encode($v['items']),
                    'product_selling_price' => $v['product_selling_price'],
                    'product_regular_price' => $v['product_regular_price'],
                    'variation_sub_title' => $v['sub_title'],
                    'variation_product_code' => $v['product_code'] ?? $product_code,
                    'variation_image' => $v['variation_image'],
                    'variation_video' => $v['variation_video'],
                    'is_active' => $v['is_active'] ?? 0,
                    'disable_buy' => $v['disable_buy'] ?? 'off',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
//            dd($attr);
            ProductAttributeVariation::insert($attr);
        }
        Product::where('id', $product_id)->update([
            'variation_show_as' => $r->variation_show_as,
            'variation_layer_start' =>$r->variation_layer_start
        ]);
//        dd($attr);
        $msg =  'Updated successfully';
        return redirect()->back()->with('success', $msg);
    }
}
