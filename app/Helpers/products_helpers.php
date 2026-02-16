<?php

use App\Models\Depot;
use App\Models\Pcombinationdata;
use App\Models\Image;
use App\Models\ProductAttributeVariation;
use App\Models\Term;
use App\Models\Review;
use App\Models\FlashShedule;
use App\Models\FlashItem;
use App\Models\PaymentSetting;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\HomeSetting;
use App\Models\Cart;
use App\Models\OrdersDetail;
use Carbon\Carbon;
use App\Models\ProductStock;
/**
 * $term_id Term Id
 * $return
 * return Term Info
 */
if (!function_exists('get_term_info')) {
    function get_term_info($term_id, $return, $key = null)
    {
        if (!empty($key) && $key == true) {
            $cat_info = Term::where('seo_url', $term_id)->get()->first();
            if (!empty($cat_info)) {
                return $cat_info->$return;
            } else {
                return false;
            }
        } else {
            //$cat_id = Request::segment($term_id);
            $cat_info = Term::find($term_id);
            return $cat_info->$return;
        }
    }
}

if (!function_exists('coupon_voucher_verify')) {
    function coupon_voucher_verify($coupon_type, $coupon_code)
    {
        $oldcart = Session::get('cart');
        $cart = new Cart($oldcart);
        foreach ($cart->items as $item) {
            $totalprice[] = $item['purchaseprice'] * $item['qty'];
        }
        $total_buy = array_sum($totalprice);

        $option = [
            'coupon_code' => $coupon_code,
            'coupon_type' => $coupon_type,
            'is_active' => 1
        ];

        date_default_timezone_set('Asia/Dhaka');
        $coupon = Coupon::where($option)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->where('end_date', '>=', date('Y-m-d H:i:s'))
            ->first();

        if ($coupon != null) {
            $count = $coupon->count();
        } else {
            $count = 0;
        }
        //dump($coupon);

        if ($count != 0) {
            $c_type = $coupon['amount_type'];
            $c_price = $coupon['price'];
            $purchase_min = $coupon['purchase_min'];
            $c_upto = $coupon['upto_amount'];
            $c_limit = $coupon['used_limit'];

            if ($c_type == 'Fixed') {
                if ($total_buy >= $purchase_min) {
                    if ($total_buy > $c_price) {
                        $discount = $c_price;
                        $massage = '';
                        $action = ['action' => 'Yes', 'amount' => $discount, 'massage' => $massage];
                    } else {
                        $massage = 'Please buy more than ' . $c_price;
                        $action = ['action' => 'No', 'amount' => null, 'massage' => $massage];
                    }
                } else {
                    $massage = 'Please buy more than ' . $purchase_min;
                    $action = ['action' => 'No', 'amount' => null, 'massage' => $massage];
                }
            } else {
                if ($total_buy >= $purchase_min) {
                    $persent = ($total_buy * $c_price) / 100;

                    if ($c_upto == 0 || $c_upto == null || $c_upto == '') {
                        $discount = $persent;
                        $massage = '';
                        $action = ['action' => 'Yes', 'amount' => $discount, 'massage' => $massage];
                    } else {
                        if ($persent > $c_upto) {
                            $discount = $c_upto;
                            $massage = '';
                            $action = ['action' => 'Yes', 'amount' => $discount, 'massage' => $massage];
                        } else {
                            //dd($discount);
                            $discount = $persent;
                            $massage = '';
                            $action = ['action' => 'Yes', 'amount' => $discount, 'massage' => $massage];
                        }
                    }
                } else {
                    $massage = 'Please buy more than ' . $purchase_min;
                    $action = ['action' => 'No', 'amount' => null, 'massage' => $massage];
                }
            }
        } else {
            $massage = 'invalid ' . $coupon_type;
            $action = ['action' => 'No', 'amount' => null, 'massage' => $massage];
        }

        //dd($action);
        if ($action['action'] == 'Yes') {
            $attributes = [
                'coupon_amount' => round($action['amount']),
                'coupon_type' => $coupon_type,
                'coupon_code' => $coupon_code
            ];

            $ouput = Session::put('my_coupon', $attributes);

            if ((Session::has('user_details')) && (Session::has('payment_method'))) {
                $total_amount = Session::get('payment_method')['total_amount'];
                $deliveryfee = Session::get('user_details')['deliveryfee'];
                $grand_total = $total_amount + $deliveryfee - round($action['amount']);
                Session::put('payment_method.grand_total', $grand_total);
                Session::put('user_details.deliveryfee', $deliveryfee);
                Session::save();
            }

            if ($ouput) {
                return true;
            } else {
                return false;
            }

            //Session::save();
        } else {
            if (Session::has('my_coupon')) {
                Session::forget('my_coupon');
            }
        }
    }
}

/**
 * return Product name by id
 */
if (!function_exists('get_product_by_id')) {
    function get_product_by_id($id)
    {
        $item = Product::find($id);
        return $item;
    }
}

/**
 * return Product name by id
 */
if (!function_exists('product_title')) {
    function product_title($id)
    {
        $item = Product::find($id);
        return $item->title;
    }
}

if (!function_exists('get_product_selling_price')) {
    function get_product_selling_price($id)
    {
        $item = Product::find($id);
        return $item->local_selling_price;
    }
}

if (!function_exists('get_product_code')) {
    function get_product_code($id)
    {
        $item = Product::find($id);
        return $item->product_code;
    }
}

if (!function_exists('get_product_info_by_key')) {
    function get_product_info_by_key($id, $key)
    {
        $item = Product::find($id);
        return $item->$key;
    }
}

/**
 *
 */
if (!function_exists('get_all_product_image')) {
    function get_all_product_image($product_array)
    {
        $images = explode(',', $product_array->images);
        $imgs = Image::find($images);

        $html = [];
        foreach ($imgs as $img):
            $html[] = url($img['full_size_directory']);
        endforeach;

        return $html;
    }
}

/**
 * return Product price by id
 */
if (!function_exists('product_price')) {
    function product_price($id)
    {
        $tksign = '&#2547; ';
        $item = Product::find($id);
        $i = product_attributes($item, false);
        $infoss[] = json_decode($i['values']);
        if (!empty($infoss[0][0]->discount)) {
            $percentage = ($infoss[0][0]->selling_price * $infoss[0][0]->discount) / 100;
            $discounted = $infoss[0][0]->selling_price - $percentage;
            $price = $discounted . ' <span>' . $tksign . $infoss[0][0]->selling_price . '</span>';
        //Tk. 90.00 <span>Tk. 100.00 </span>
        } else {
            $price = $infoss[0][0]->selling_price;
            //Tk. 90.00
        }

        return $price;
    }
}

///**
// * Returns product price with discount in percentage
// */
//if (!function_exists('product_normal_price')) {
//    function product_normal_price($values)
//    {
//        if (!empty($values->discount)) {
//            $price = $values->selling_price % $values->discount;
//            $html = $values->selling_price . ' <span>' . $price . ' </span>';
//        } else {
//            $html = $values->selling_price;
//        }
//
//        return $html;
//    }
//}

/**
 *
 */
if (!function_exists('get_first_product_image')) {
    function get_first_product_image($id, $product_array = null)
    {
        $images = get_product_info_by_key($id, 'images');
        $imagesss = explode(',', $images);
        $imgs = Image::find($imagesss)->first();
        $html = url($imgs['full_size_directory']);
        //dd($html);
        return $html;
    }
}

/**
 * Returns main image from attribute table where relation with image table
 */
if (!function_exists('main_image')) {
    function main_image($post)
    {
        $html = null;
        $i = 0;
        foreach ($post as $image) {
            if ($image->module_type === 'products' && $image->attribute === 'image') {
                if ($i <= 1) {
                    $img = Image::find($image->values);
                    $html = url($img->full_size_directory);
                }
            }
            $i++;
        }
        return $html;
    }
}
/**
 * Returns All Product Information from attributes table
 */
if (!function_exists('product_information_attributes')) {
    function product_information_attributes($post)
    {
        $html = [];
        $html['values'] = null;
        $html['id'] = null;
        foreach ($post as $variation) {
            //dd($variation);
            if ($variation->module_type === 'products' && $variation->attribute === 'product_information') {
                $html['values'] = $variation->values;
            }
            $html['id'] = $variation->id;
        }

        return $html;
        //Tk. 90.00 <span>Tk. 100.00 </span>
    }
}
/**
 * Returns selling price only
 */
if (!function_exists('get_category_name_by_id')) {
    function get_category_name_by_id($id)
    {
        $category = Term::findOrFail($id);
        return $category->name;
    }
}
/**
 * Returns selling price only
 */
if (!function_exists('value_by_key')) {
    function value_by_key($values, $key)
    {
        return $values->$key;
    }
}
/**
 * Returns selling price only
 */
if (!function_exists('local_selling_price')) {
    function local_selling_price($values, $withsign = false)
    {
        //dd($withsign);

        $tksign = '&#2547; ';

        if ($withsign === true) {
            return $values->local_selling_price;
        } elseif ($withsign === false) {
            return $tksign . $values->local_selling_price;
        } else {
            return $tksign . $values->local_selling_price;
        }
    }
}
/**
 * Returns discounted price only
 */
if (!function_exists('discounted_price')) {
    function discounted_price($values, $withsign = false)
    {
        $tksign = '&#2547; ';
        if ($withsign === true) {
            $save = ($values->local_selling_price * $values->local_discount) / 100;
            return ($values->local_selling_price - $save);
        } elseif ($withsign === false) {
            $save = ($values->local_selling_price * $values->local_discount) / 100;
            return $tksign . ($values->local_selling_price - $save);
        } else {
            $save = ($values->local_selling_price * $values->local_discount) / 100;
            return $tksign . ($values->local_selling_price - $save);
        }
    }
}

/**
 * Returns save price only
 */
if (!function_exists('save_price')) {
    function save_price($values, $withoutsign = false)
    {
        $tksign = '&#2547; ';
        if ($withoutsign === true) {
            $save = ($values->local_selling_price * $values->local_discount) / 100;
            return $save;
        } elseif ($withoutsign === false) {
            $save = ($values->local_selling_price * $values->local_discount) / 100;
            return $tksign . $save;
        } else {
            $save = ($values->local_selling_price * $values->local_discount) / 100;
            return $tksign . $save;
        }
    }
}

if (!function_exists('get_price_by_type_using_product_id')) {
    function get_price_by_type_using_product_id($id, $return)
    {
    }
}

/**
 * return category seo url by category id
 */
if (!function_exists('category_seo_url_by_id')) {
    function category_seo_url_by_id($id)
    {
        $cat_seo_url = Term::findOrFail($id);
        $seo_url = $cat_seo_url->cssclass;

        return url('c/' . $seo_url);
    }
}

/**
 * Returns product price with discount in percentage
 */
if (!function_exists('seo_url_by_id_with_product_code')) {
    function seo_url_by_id_with_product_code($id, $product_code)
    {
        $item = Product::find($id);
        return url('product/' . $id . '/' . $item->seo_url . '?product_code=' . $product_code);
    }
}

/**
 * Returns product price with discount in percentage
 */
if (!function_exists('seo_url_by_id')) {
    function seo_url_by_id($id)
    {
        $item = Product::find($id);
        return url('product/' . $id . (!empty($item->seo_url) ? '/' . $item->seo_url : null));
    }
}

/**
 * return url with $values
 */
if (!function_exists('product_seo_url')) {
    function product_seo_url($values, $id = null)
    {
        // previous
        //return url('product/' . $id . '/' . $values);

        // new
        return url('product/' . $values);
    }
}

if (!function_exists('you_may_also_like')) {
    function you_may_also_like(array $options = [])
    {
        $default = [
            'category_id' => [],
            'limit' => 5,
            'post_id' => null
        ];

        $options = array_merge($default, $options);
        $m = Product::whereRaw('FIND_IN_SET(' . implode(',', $options['category_id']) . ', categories)')->limit($options['limit'])->get();

        return $matched = !empty($m) ? $m : [];
    }
}

if (!function_exists('product_design')) {
    function product_design(array $options = [])
    {
        $default = [
            'bootstrap_cols' => null,
            'seo_url' => null,
            'straight_seo_url' => null,
            'title' => null,
            'first_image' => null,
            'second_image' => null,
            'discount_rate' => null,
            'price' => null,
            'old_price' => null,
            'product_code' => null,
            'product_sku' => null,
            'product_id' => null,
            'product_qty' => null,
            'flash_id' => null,
            'flash_now' => null,
            'flash_old_count' => null,
            'flash_now_count' => null,
            'emi' => null,
            'sign' => '&#2547; '
        ];

        $option = array_merge($default, $options);

        $pro = Product::where(['id' => $option['product_id']])->first();

        $get_price = get_product_price(['main_pid' => $option['product_id']]);

        // dump($get_price);

        $save = $get_price['r_price'] - $get_price['s_price'];
        $price = $option['sign'] . number_format($get_price['s_price']);

        if ($save > 0) {
            $old_price = $option['sign'] . ' ' . number_format($get_price['r_price']);
            $save_v = 'Save ' . $option['sign'] . ' ' . number_format($save);
        } else {
            $old_price = null;
            $save_v = null;
        }

        $proid = '"' . trim($option['product_id']) . '"';
        $procode = '"' . $option['product_code'] . '"';
        $str_seo_url = '"' . $option['straight_seo_url'] . '"';

        $html = '';
        $html .= '<div class="pt-2 end-product end-product2">
                 <div class="single-product home-product">
                    <div class="product-img">';


        $disable_buy = (!empty($pro->disable_buy) && $pro->disable_buy == 'on') ? 'disabled' : '';
        $button_name = (!empty($pro->disable_buy) && $pro->disable_buy == 'on') ? 'Stock Out' : false;

        $html .= $button_name ? '<div class="product-price-btn buy-btn not-available-btn"><input type="button" data-toggle="tooltip" title="" value="' . $button_name . '" class="addToCart" data-original-title="' . $button_name . '" ' . $disable_buy . ' ></div>' : '';



        $html .='<a href="' . url($option['seo_url']) . '">';

        $html .= image_container([
            'seo_url' => $option['seo_url'],
            'straight_seo_url' => $option['straight_seo_url'],
            'title' => $option['title'],
            'first_image' => $option['first_image'],
            'second_image' => $option['second_image']
        ]);

        if ($get_price['save'] != null) {
            $html .= '<h3 class="product-eft ">' . $get_price['save'] . '% OFF</h3>';
        }
        $html .= '</a>';

        $html .= '<div class="wishlist-wp"><div class="wishlist-icone">';

        $html .= addtocompare_button_container(['proid' => $proid, 'procode' => $procode, 'str_seo_url' => $str_seo_url, 'multi_id' => $get_price['multi_id']]);
        $html .= addttowish_button_container(['proid' => $proid, 'procode' => $procode, 'str_seo_url' => $str_seo_url, 'multi_id' => $get_price['multi_id']]);

        $html .= '</div> </div>';

        $html .= '
                     </div>
                     <div class="product-over">
                       <div class="product-over-left">';

        $html .= title_container(['seo_url' => $option['seo_url'], 'title' => $option['title'], 'sub_title' => $pro->sub_title]);

        $html .= '<div class="prodict-price">
                            <div class="ct-price">';
        $html .= '<p class="price">' . $price . '</p>';
        $html .= '</div><div class="old-price">
                             <p><span class="old-pc">' . $old_price . '</span>  <span class="save-pc">  ' . $save_v . '</span></p>
                           </div>
                          </div>
                       </div>
                       <div class="product-over-right">
                          <div class="product-price-btn details-btn">
                             <a href="' . url($option['seo_url']) . '">Details</a>
                          </div>';

        $html .= addtocart_button_container([
            'straight_seo_url' => null,
            'title' => null,
            'discount_rate' => null,
            'price' => null,
            'old_price' => null,
            'product_code' => null,
            'product_sku' => null,
            'product_id' => null,
            'product_qty' => null,
            'flash_id' => null,
            'flash_now' => null,
            'flash_old_count' => null,
            'flash_now_count' => null,
            'emi' => null,
            'sign' => '&#2547; ',
            'save_price' => $save,
            'pro' => $pro
        ]);

        $html .= ' </div>
                   </div>
                </div>
              </div>';
        $html .= '';

        return $html;
    }
}

if (!function_exists('product_design_two')) {
    function product_design_two(array $options = [])
    {
        $default = [
            'bootstrap_cols' => null,
            'seo_url' => null,
            'straight_seo_url' => null,
            'title' => null,
            'first_image' => null,
            'second_image' => null,
            'discount_rate' => null,
            'price' => null,
            'old_price' => null,
            'product_code' => null,
            'product_sku' => null,
            'product_id' => null,
            'product_qty' => null,
            'flash_id' => null,
            'flash_now' => null,
            'flash_old_count' => null,
            'flash_now_count' => null,
            'emi' => null,
            'sign' => '&#2547; '
        ];

        $option = array_merge($default, $options);

        $pro = Product::where(['id' => $option['product_id']])->first();
        $get_price = get_product_price(['main_pid' => $option['product_id']]);
        //dump($get_price['multi_id']);

        // dump($get_price);

        $save = $get_price['r_price'] - $get_price['s_price'];
        $price = $option['sign'] . number_format($get_price['s_price']);

        if ($save > 0) {
            $old_price = $option['sign'] . ' ' . number_format($get_price['r_price']);
            $save_v = 'Save ' . $option['sign'] . ' ' . number_format($save);
        } else {
            $old_price = null;
            $save_v = null;
        }

        $proid = '"' . trim($option['product_id']) . '"';
        $procode = '"' . $option['product_code'] . '"';
        $str_seo_url = '"' . $option['straight_seo_url'] . '"';

        $html = '';
        $html .= '<div class="single-product single-category-product">
                    <div class="product-img">';


        $disable_buy = (!(($pro->enable_timespan == 1 && $pro->disable_buy == 'on') || $pro->disable_buy == 'off')) ? 'disabled' : '';
        $button_name = (!(($pro->enable_timespan == 1 && $pro->disable_buy == 'on') || $pro->disable_buy == 'off')) ? 'Stock Out' : false;

        $html .= $button_name ? '<div class="product-price-btn buy-btn not-available-btn"><input type="button" data-toggle="tooltip" title="" value="' . $button_name . '" class="addToCart" data-original-title="' . $button_name . '" ' . $disable_buy . ' ></div>' : '';


        $html .='<a href="' . url($option['seo_url']) . '">';

        $html .= image_container([
            'seo_url' => $option['seo_url'],
            'straight_seo_url' => $option['straight_seo_url'],
            'title' => $option['title'],
            'first_image' => $option['first_image'],
            'second_image' => $option['second_image']
        ]);

        if ($get_price['save'] != null && (($pro->enable_timespan == 1 && $pro->disable_buy == 'on') || $pro->disable_buy == 'off')) {
            $html .= '<h3 class="product-eft ">' . $get_price['save'] . '% OFF</h3>';
        }
        $html .= '</a>';

        $html .= '<div class="wishlist-wp"><div class="wishlist-icone">';

        $html .= addtocompare_button_container(['proid' => $proid, 'procode' => $procode, 'str_seo_url' => $str_seo_url, 'multi_id' => $get_price['multi_id']]);
        $html .= addttowish_button_container(['proid' => $proid, 'procode' => $procode, 'str_seo_url' => $str_seo_url, 'multi_id' => $get_price['multi_id']]);

        $html .= '</div> </div>';

        $html .= '
                     </div>
                     <div class="product-over">
                       <div class="product-over-left">';

        $html .= title_container(['seo_url' => $option['seo_url'], 'title' => $option['title'], 'sub_title' => $pro->sub_title]);

        $html .= '<div class="prodict-price">
                            <div class="ct-price">';
        $html .= '<p class="price">' . $price . '</p>';
        $html .= '</div><div class="old-price">
                             <p><span class="old-pc">' . $old_price . '</span>  <span class="save-pc">  ' . $save_v . '</span></p>
                           </div>
                          </div>
                       </div>
                       <div class="product-over-right">
                          <div class="product-price-btn details-btn">
                             <a href="' . url($option['seo_url']) . '">Details</a>
                          </div>';

        $html .= addtocart_button_container([
            'straight_seo_url' => null,
            'title' => null,
            'discount_rate' => null,
            'price' => null,
            'old_price' => null,
            'product_code' => null,
            'product_sku' => null,
            'product_id' => null,
            'product_qty' => null,
            'flash_id' => null,
            'flash_now' => null,
            'flash_old_count' => null,
            'flash_now_count' => null,
            'emi' => null,
            'sign' => '&#2547; ',
            'save_price' => $save,
            'pro' => $pro
        ]);

        $html .= ' </div>
                   </div>
                </div>';
        $html .= '';

        return $html;
    }
}

if (!function_exists('product_design_flash')) {
    function product_design_flash(array $options = [])
    {
        $default = [
            'bootstrap_cols' => null,
            'seo_url' => null,
            'straight_seo_url' => null,
            'title' => null,
            'first_image' => null,
            'second_image' => null,
            'discount_rate' => null,
            'price' => null,
            'old_price' => null,
            'product_code' => null,
            'product_sku' => null,
            'product_id' => null,
            'product_qty' => null,
            'flash_id' => null,
            'flash_now' => null,
            'flash_old_count' => null,
            'flash_now_count' => null,
            'emi' => null,
            'sign' => '&#2547; '
        ];
        $option = array_merge($default, $options);

        $pro = Product::where(['id' => $option['product_id']])->first();

        $get_price = get_product_price(['main_pid' => $option['product_id']]);

        // dump($get_price);

        $save = $get_price['r_price'] - $get_price['s_price'];
        $price = $option['sign'] . number_format($get_price['s_price']);

        if ($save > 0) {
            $old_price = $option['sign'] . ' ' . number_format($get_price['r_price']);
            $save_v = 'Save ' . $option['sign'] . ' ' . number_format($save);
        } else {
            $old_price = null;
            $save_v = null;
        }

        $proid = '"' . trim($option['product_id']) . '"';
        $procode = '"' . $option['product_code'] . '"';
        $str_seo_url = '"' . $option['straight_seo_url'] . '"';

        $html = '';
        $html .= '<div class="pt-2 end-product end-product2 end-product3">
                 <div class="single-product single-category-product">
                    <div class="product-img">
                      <a href="' . url($option['seo_url']) . '">';

        $html .= image_container([
            'seo_url' => $option['seo_url'],
            'straight_seo_url' => $option['straight_seo_url'],
            'title' => $option['title'],
            'first_image' => $option['first_image'],
            'second_image' => $option['second_image']
        ]);

        if ($get_price['flash_start'] && !$get_price['is_flash']) {
            $html .= '<h3 class="product-eft "> ??? % OFF</h3>';
            $old_price = $get_price['r_price'];
            $price = '???';
            $save_v = 'Save ' . $option['sign'] . ' ???';
        } elseif (!$get_price['flash_start'] && $get_price['is_flash']) {
            $html .= '<h3 class="product-eft ">' . $get_price['save'] . '% OFF</h3>';
        } else {
            if ($get_price['save'] != null) {
                $html .= '<h3 class="product-eft ">' . $get_price['save'] . '% OFF</h3>';
            }
        }

        $html .= '</a>';

        $html .= '<div class="wishlist-wp"><div class="wishlist-icone">';

        $html .= addtocompare_button_container(['proid' => $proid, 'procode' => $procode, 'str_seo_url' => $str_seo_url, 'multi_id' => $get_price['multi_id']]);
        $html .= addttowish_button_container(['proid' => $proid, 'procode' => $procode, 'str_seo_url' => $str_seo_url, 'multi_id' => $get_price['multi_id']]);

        $html .= '</div> </div>';

        $html .= '
                     </div>
                     <div class="product-over">
                       <div class="product-over-left">';

        $html .= title_container(['seo_url' => $option['seo_url'], 'title' => $option['title'], 'sub_title' => $pro->sub_title]);

        $html .= '<div class="prodict-price">
                            <div class="ct-price">';
        $html .= '<p class="price">' . $price . '</p>';
        $html .= '</div><div class="old-price">
                             <p><span class="old-pc">' . $old_price . '</span>  <span class="save-pc">  ' . $save_v . '</span></p>
                           </div>
                          </div>
                       </div>
                       <div class="product-over-right">
                          <div class="product-price-btn details-btn">
                             <a href="' . url($option['seo_url']) . '">Details</a>
                          </div>';

        $html .= addtocart_button_container([
            'straight_seo_url' => null,
            'title' => null,
            'discount_rate' => null,
            'price' => null,
            'old_price' => null,
            'product_code' => null,
            'product_sku' => null,
            'product_id' => null,
            'product_qty' => null,
            'flash_id' => null,
            'flash_now' => null,
            'flash_old_count' => null,
            'flash_now_count' => null,
            'emi' => null,
            'sign' => '&#2547; ',
            'save_price' => $save,
            'pro' => $pro
        ]);

        $html .= ' </div>
                   </div>
                </div>
              </div>';
        $html .= '';

        return $html;
    }
}

if (!function_exists('addtocart_button_container')) {
    function addtocart_button_container(array $options = [])
    {
        $default = [
            'bootstrap_cols' => null,
            'seo_url' => null,
            'straight_seo_url' => null,
            'title' => null,
            'first_image' => null,
            'second_image' => null,
            'discount_rate' => null,
            'price' => null,
            'old_price' => null,
            'product_code' => null,
            'product_sku' => null,
            'product_id' => null,
            'product_qty' => null,
            'flash_id' => null,
            'flash_now' => null,
            'flash_old_count' => null,
            'flash_now_count' => null,
            'emi' => null,
            'sign' => '&#2547; ',
            'save_price' => null,
            'pro' => null
        ];
        $option = array_merge($default, $options);
        $pro = $option['pro'];

        $color_info = Pcombinationdata::where(['main_pid' => $option['pro']->id])->orderBy('stock', 'DESC')->get();
        if ($color_info->count() > 0) {
            $color_id = $color_info->first()->id;
        } else {
            $color_id = null;
        }

        $html = null;

        $disable_buy = (!(($pro->enable_timespan == 1 && $pro->disable_buy == 'on') || $pro->disable_buy == 'off')) ? 'disabled' : '';
        $button_name = (!(($pro->enable_timespan == 1 && $pro->disable_buy == 'on') || $pro->disable_buy == 'off')) ? 'Stock Out' : 'Buy';

        if (!(($pro->enable_timespan == 1 && $pro->disable_buy == 'on') || $pro->disable_buy == 'off')) {
        //   $html .= '<div class="product-price-btn buy-btn not-available-btn"><input type="button" data-toggle="tooltip" title="" value="' . $button_name . '" class="addToCart" data-original-title="' . $button_name . '" ' . $disable_buy . ' ></div>';
          $html .= '<input class="btn btn-primary btn-danger disable-buy-button buy-btn" type="button" data-toggle="tooltip" title="" value="Buy" class="addToCart" data-original-title="' . $button_name . '" ' . $disable_buy . ' >';
         } else {
            $html .= '<div class="product-price-btn buy-btn"> <input type="button" data-toggle="tooltip" title="" value="' . $button_name . '"
                        data-loading-text="Loading..." id="button-cart"
                        class="addToCart"
                        data-color_id = "' . $color_id . '"
                        data-size_id = "' . $color_id . '"
                        data-productid="' . $option['pro']->id . '"
                        data-qty="1"
                        onclick="add_to_cart_data(this);"
                        data-original-title="' . $button_name . '" ' . $disable_buy . ' ></div>';
         }

        return $html;
    }
}

if (!function_exists('addtowishlist_button_container')) {
    function addtowishlist_button_container(array $options = [])
    {
        $default = [
            'proid' => null,
            'procode' => null,
            'str_seo_url' => null
        ];
        $option = array_merge($default, $options);

        $html = null;
        $html .= '<button onclick="add_to_wishlist($option[\'proid\'], $option[\'procode\'], $option[\'str_seo_url\']);" type="button" class="wishlist btn-button" title="Add to Wish List">';
        $html .= '<i class="fa fa-heart-o"></i><span>Add to Wish List</span>';
        $html .= '</button>';

        return $html;
    }
}

if (!function_exists('addtocompare_button_container')) {
    function addtocompare_button_container(array $options = [])
    {
        $default = [
            'proid' => null,
            'procode' => null,
            'str_seo_url' => null,
            'multi_id' => null
        ];
        $option = array_merge($default, $options);
        // dump($option);

        $html = null;
        $html .= '<button onclick="add_to_compare_all(this);" class="scale_ic" data-multi="' . $option['multi_id'] . '" data-url=' . $option['str_seo_url'] . ' data-procode=' . $option['procode'] . ' data-proid=' . $option['proid'] . ' href="javascript:void(0);"  class="compare btn-button" title="Compare this Product">';
        $html .= '<i class="fa fa-balance-scale"></i><span></span>';
        $html .= '</button>';

        return $html;
    }
}

if (!function_exists('addttowish_button_container')) {
    function addttowish_button_container(array $options = [])
    {
        $default = [
            'proid' => null,
            'procode' => null,
            'str_seo_url' => null
        ];
        $option = array_merge($default, $options);

        $html = null;

        $html .= '<button onclick="add_to_wish_list(this);" data-multi="' . $option['multi_id'] . '" data-proid=' . $option['proid'] . ' href="javascript:void(0);"  class="wishlist_ic" title="Add to wish list">';
        $html .= '<i class="fa fa-heart"></i>';
        $html .= '</button>';

        return $html;
    }
}

if (!function_exists('title_container')) {
    function title_container(array $options = [])
    {
        $default = [
            'seo_url' => null,
            'sub_title' => null,
            'title' => null
        ];

        $option = array_merge($default, $options);

        $html = '<div class="product-title">
                            <h3><a href="' . $option['seo_url'] . '">' . $option['title'] . '</a></h3>
                            <h3 class="mdl"><a href="' . $option['seo_url'] . '">' . $option['sub_title'] . '</a></h3>
                          </div>';
        return $html;
    }
}

if (!function_exists('image_container')) {
    function image_container(array $options = [])
    {
        $default = [
            'seo_url' => null,
            'straight_seo_url' => null,
            'title' => null,
            'first_image' => null,
            'second_image' => null,
        ];
        $option = array_merge($default, $options);

        $html = '';

        if (!empty($option['second_image'])) {
            $html .= '<img class="pd-img-on" src="' . $option['first_image'] . '" alt="' . $option['title'] . '">';
            $html .= '<img class="pd-img-two" src="' . $option['second_image'] . '" alt="' . $option['title'] . '">';
        } else {
            $html .= '<img class="pd-img-on" src="' . $option['first_image'] . '" alt="' . $option['title'] . '">';
        }

        return $html;
    }
}

if (!function_exists('flash_now_container')) {
    function flash_now_container(array $options = [])
    {
        $default = [
            'flash_now' => null,
            'flash_old_count' => null,
            'flash_now_count' => null,
        ];
        $option = array_merge($default, $options);

        $html = '';
        if ($option['flash_now'] == 'Yes') {
            $sole = $option['flash_old_count'] - $option['flash_now_count'];
            $sole_par = $sole * 100 / $option['flash_old_count'];

            $html = '<div class="progress progress_flash">';
            $html .= '<div class="progress-bar progress_flash_bar" role="progressbar" style="width: ' . $sole_par . '%" aria-valuenow="' . $sole_par . '" aria-valuemin="0" aria-valuemax="100"></div>';
            $html .= '</div>';
            $html .= '<p class="pc-sold">Sold: <b>' . $sole . '</b></p>';
        }

        return $html;
    }
}

if (!function_exists('product_review_count')) {
    function product_review_count($id)
    {
        //$id = 560;
        $this_review = Review::where(['product_id' => $id, 'is_active' => 1])->get();
        //dump($id);

        $review_count = 0;
        $review_total = 0;
        foreach ($this_review as $review) {
            $review_total += $review->rating;
            ++$review_count;
        }
        if ($review_count == 0) {
            $avarage_ratting = 0;
        } else {
            $avarage_ratting = round($review_total / $review_count);
        }

        $html = '';
        $html .= '<div class="single-page-contant-rev">';
        $html .= '<ul class="list-unstyled">';
        for ($x = 1; $x <= 5; $x++) {
            if ($x <= $avarage_ratting) {
                $html .= '<li><a href=""><i class="fa fa-star"></i></a></li>';
            } else {
                $html .= '<li><a href=""><i class="fa fa-star-o"></i></a></li>';
            }
        }
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '<div class="single-page-contant-cs-rev">';
        $html .= '<p>' . number_format($review_count) . ' Customer Reviews</p>';
        $html .= '</div>';
        //dump('hasan');
        return $html;
    }
}

if (!function_exists('product_review_count_only')) {
    function product_review_count_only($id)
    {
        $this_review = Review::where(['product_id' => $id, 'is_active' => 1])->get();

        $review_count = 0;
        foreach ($this_review as $review) {
            ++$review_count;
        }

        return $review_count;
    }
}

if (!function_exists('product_review')) {
    function product_review($id)
    {
        $this_review = Review::where(['product_id' => $id, 'is_active' => 1])->get();

        $review_count = 0;
        $review_total = 0;
        foreach ($this_review as $review) {
            $review_total += $review->rating;
            ++$review_count;
        }
        if ($review_count == 0) {
            $avarage_ratting = 0;
        } else {
            $avarage_ratting = round($review_total / $review_count);
        }

        $html = '';

        $html .= '<ul class="list-unstyled">';
        for ($x = 1; $x <= 5; $x++) {
            if ($x <= $avarage_ratting) {
                $html .= '<li><a href=""><i class="fa fa-star"></i></a></li>';
            } else {
                $html .= '<li><a href=""><i class="fa fa-star-o"></i></a></li>';
            }
        }
        $html .= '</ul>';
        //dump('hasan');
        return $html;
    }
}

if (!function_exists('product_review')) {
    function product_review($id)
    {
        $this_review = Review::where(['product_id' => $id, 'is_active' => 1])->get();

        $review_count = 0;
        $review_total = 0;
        foreach ($this_review as $review) {
            $review_total += $review->rating;
            ++$review_count;
        }
        if ($review_count == 0) {
            $avarage_ratting = 0;
        } else {
            $avarage_ratting = round($review_total / $review_count);
        }

        $html = '';

        $html .= '<ul class="list-unstyled">';
        for ($x = 1; $x <= 5; $x++) {
            if ($x <= $avarage_ratting) {
                $html .= '<li><a href=""><i class="fa fa-star"></i></a></li>';
            } else {
                $html .= '<li><a href=""><i class="fa fa-star-o"></i></a></li>';
            }
        }
        $html .= '</ul>';
        //dump('hasan');
        return $html;
    }
}

if (!function_exists('review_star')) {
    function review_star($review)
    {
        $avarage_ratting = $review;
        $html = '';
        $html .= '<div class="ratings">';
        $html .= '<div class="rating-box">';
        for ($x = 1; $x <= 5; $x++) {
            if ($x <= $avarage_ratting) {
                $html .= '<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>';
            } else {
                $html .= '<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>';
            }
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('order_master_create')) {
    function order_master_create($data, $rand, $secret_key, $user_id)
    {
        if ($data['payment_method']->payment_method == 'cash_on_delivery') {
            $order_status = 'placed';
        } else {
            $order_status = null;
        }
        if (!empty($data['user_details']) && $data['cart']) {
            return $orders_master_attributes = [
                'user_id' => $user_id,
                'order_random' => $rand,
                'order_from' => $data['device']??'website',
                'customer_name' => $data['user_details']->name,
                'phone' => $data['user_details']->phone,
                'emergency_phone' => $data['user_details']->emergency_phone,
                'address' => $data['user_details']->address,
                'email' => $data['user_details']->email,
                'order_date' => date('Y-m-d'),
                'payment_method' => $data['payment_method']->payment_method,
                'payment_term_status' => null,
                'payment_parameter' => null,
                'order_status' => $order_status,
                'params' => null,
                'secret_key' => $secret_key,
                'delivery_date' => null,
                'currency' => 'BDT',
                'delivery_fee' => $data['user_details']->deliveryfee,
                'grand_total' => $data['payment_method']->grand_total,
                'total_amount' => $data['payment_method']->total_amount,
                'coupon_type' => (isset($data['my_coupon']->coupon_type) ? $data['my_coupon']->coupon_type : null),
                'coupon_code' => (isset($data['my_coupon']->coupon_type) ? $data['my_coupon']->coupon_code : null),
                'coupon_discount' => (isset($data['my_coupon']->coupon_type) ? $data['my_coupon']->coupon_amount : null),
                'district' => $data['user_details']->district,
                'thana' => $data['user_details']->thana,
                'trans_id' => null,
                'pre_booking_order' => $data['prebooking']??0,
                'is_active' => 1
            ];
        } else {
            return false;
        }
    }
}


if (!function_exists('order_detail_create')) {
    function order_detail_create($data, $rand, $secret_key, $user_id)
    {
        if (!empty($data['cart'])) {
            $details = [];
            $max_arrive_day = [];
            // dump($data['cart']);
            $order_random = $rand;
            foreach ($data['cart']->items as $item) {
                $product_info = Product::Where(['id' => $item->item->productid])->first();

                if ($item->item->size_colo) {
                    $multi_data = App\Models\Pcombinationdata::Where(['id' => $item->item->size_colo])->first();
                } else {
                    $multi_data = false;
                }

                if ($data['payment_method']->payment_method == 'cash_on_delivery') {
                    $order_status = 'placed';
                } else {
                    $order_status = 'Placed';
                }
                $stocController =  new \App\Http\Controllers\API\StockController;
                $aarive_times_arr = $stocController->getProductArriveTime($data['user_details']->district, ['code' => $item->item->productcode, 'qty' => $item->qty]);
                $aarive_times = $aarive_times_arr ? $aarive_times_arr['message'] : null;
                $arrive_time_only_day = $aarive_times_arr ? $aarive_times_arr['days'] : null;
                $details[] = [
                    'user_id' => $user_id,
                    'vendor_id' => $product_info->user_id,
                    'order_random' => $rand,
                    'product_id' => $item->item->productid,
                    'product_name' => product_title($item->item->productid),
                    'product_code' => $item->item->productcode,
                    'qty' => $item->qty,
                    'order_date' => date('Y-m-d'),
                    'img' => null,
                    'local_selling_price' => $item->item->pre_price,
                    'local_purchase_price' => $item->purchaseprice,
                    'delivery_charge' => null,
                    'discount' => $item->item->dis_tag,
                    'is_dp' => $item->item->is_dp,
                    'is_flash' => (($item->item->flash_discount) ? 'Yes' : 'No'),
                    'flash_id' => null,
                    'flash_discount' => $item->item->flash_discount,
                    'item_code' => (($multi_data) ? $multi_data->item_code : null),
                    'color_type' => (($multi_data) ? $multi_data->type : null),
                    'size_color_id' => (($multi_data) ? $multi_data->id : null),
                    'color' => (($multi_data) ? $multi_data->color_codes : null),
                    'size' => (($multi_data) ? $multi_data->size : null),
                    'product_set_id' => $item->pset_id??null,
                    'product_fabric_id' => $item->fabric_id??null,
                    'item_jeson' => json_encode($item->item),
                    'secret_key' => $secret_key,
                    'od_status' => $order_status,
                    'is_active' => 1,
                    'product_arrive_times' => $aarive_times ?? nulll,
                    'product_arrive_times_day' => $arrive_time_only_day ?? 15,
                ];
                $max_arrive_day []= $arrive_time_only_day;
                product_stock_out([
                    'product_id' => $item->item->productid,
                    'product_code' => $item->item->productcode,
                    'qty' => $item->qty,
                    'depot_id' => $data['user_details']->district,
                ]);
                //dd($details);

                //$this->ordersdetail->create($orders_detail_attributes);
            }
//            dump($details);
//            die();
            //return $order_random;
            return OrdersDetail::insert($details);


        } else {
            return false;
        }
    }
}

if (!function_exists('get_filters_cat')) {
    function get_filters_cat($id)
    {
        // dd($id);
        $get_first = Term::where(['id' => $id])->get()->first();

        if ($get_first->parent == 1) {
            $parent_data = ['parent' => $get_first->id, 'step' => 1];
        } else {
            $parent_get = Term::where(['id' => $get_first->parent])->get()->first();
            if (($parent_get->parent??null) == 1) {
                $parent_data = ['parent' => $parent_get->id, 'step' => 2];
            } else {
                $parent_get = Term::where(['id' => ($parent_get->parent??null)])->get()->first();
                $parent_data = [
                    'parent' => (!empty($parent_get->id)) ? $parent_get->id : ($parent_get['id']??null),
                    'step' => 3];
            }
        }

        if ($parent_data['step'] == 1) {
            $parent_cat = Term::where(['id' => ($parent_data['parent']??null)])->get()->first();
            $sub_menu = Term::where(['parent' => ($parent_cat->id??null)])->get();
            $child_menu = null;
        } elseif ($parent_data['step'] == 2) {
            //dd($id);
            $parent_cat = Term::where(['id' =>($parent_data['parent']??null)])->get()->first();
            $sub_menu = Term::where(['id' => $id])->get()->first();
            $child_menu = Term::where(['parent' => $sub_menu->id])->orderBy('name', 'ASC')->get();
        } elseif ($parent_data['step'] == 3) {
            $parent_cat = Term::where(['id' => ($parent_data['parent']??null)])->get()->first();
            $child_menu = Term::where(['id' => $id])->get()->first();
            // dd($child_menu);
            $sub_menu = Term::where([
                'id' => (isset($child_menu->parent)) ? $child_menu->parent : ($parent_get['id']??null)
            ])->get()->first();
        }

        $cat_data = ['parent_cat' => $parent_cat, 'sub_menu' => $sub_menu, 'child_menu' => $child_menu];

        return $cat_data;
    }
}

if (!function_exists('get_all_sub_cat')) {
    function get_all_sub_cat($id)
    {
        // dd($id);
        $get_first = Term::where(['id' => $id])->get()->first();

        if (($get_first->parent??null) == 1) {
            $parent_data = ['parent' => $get_first->id, 'step' => 1];
        } else {

            $parent_get = Term::where(['id' => ($get_first->parent??null)])->get()->first();
            if (($parent_get->parent??null) == 1) {
                $parent_data = ['parent' => ($parent_get->id??null), 'step' => 2];
            } else {
                $parent_get = Term::where(['id' => ($parent_get->parent??null)])->get()->first();
                $parent_data = [
                    'parent' => (isset($parent_get->id)) ? $parent_get->id : ($parent_get['id']??null),
                    'step' => 3];
            }
        }
        //dd($parent_data['step']);

        if ($parent_data['step'] == 1) {
            $parent_cat = Term::where(['id' => $id])->select('id')->get()->first();
            $sub_menu = Term::where(['parent' => $parent_cat->id])->select('id')->get();
            $all_cat = [];
            $all_cat[] .= $parent_cat->id;

            foreach ($sub_menu as $sub) {
                $all_cat[] .= $sub->id;
                $chil_menu = Term::where(['parent' => $sub->id])->select('id')->get();
                foreach ($chil_menu as $chil) {
                    $all_cat[] .= $chil->id;
                }
            }
        } elseif ($parent_data['step'] == 2) {
            $parent_cat = Term::where(['id' => $id])->select('id')->get()->first();
            $sub_menu = Term::where(['parent' => $parent_cat->id])->select('id')->get();
            $all_cat = [];
            $all_cat[] .= $parent_cat->id;

            foreach ($sub_menu as $sub) {
                $all_cat[] .= $sub->id;
            }
        } elseif ($parent_data['step'] == 3) {
            $parent_cat = Term::where(['id' => $id])->select('id')->get()->first();
            $all_cat = [];
            $all_cat[] .= $parent_cat->id;
        }

        $cat_data = $all_cat;

        return $cat_data;
    }
}

if (!function_exists('is_flash_item')) {
    function is_flash_item($id)
    {
        $flash_rule = [
            'fs_is_active' => 1
        ];
        $flash_schedule = FlashShedule::where($flash_rule)
            ->whereRaw('NOW() BETWEEN fs_start_date AND fs_end_date ')
            ->orderBy('fs_start_date', 'ASC')->get()->first();

        if (!empty($flash_schedule)) {
            $flash_itmes = FlashItem::where(['fi_shedule_id' => $flash_schedule->id, 'fi_product_id' => $id])->first();

            // dump($flash_itmes);
            if ($flash_itmes) {
                $data = [
                    'flash_item' => $flash_itmes->id,
                    'discount' => $flash_itmes->fi_discount,
                    'discount_tag' => $flash_itmes->fi_show_tag,
                    'old_qty' => $flash_itmes->fi_qty,
                    'end_time' => $flash_schedule->fs_end_date
                ];
            } else {
                $data = [];
            }

            $result = $data;
        } else {
            $result = false;
        }

        return $result;
    }
}

if (!function_exists('get_search_category')) {
    function get_search_category($options)
    {
        $dufault = [
            'keyword' => null,
            'cat' => null
        ];

        $new = array_merge($dufault, $options);

        $result = Product::where(['products.is_active' => 1])
            ->leftJoin('productcategories AS pc', function ($join) {
                $join->on('pc.main_pid', '=', 'products.id');
            })
            ->leftJoin('terms AS t', function ($join) {
                $join->on('t.id', '=', 'pc.term_id');
            });

        if ($new['cat'] != null) {
            $result = $result->where('pc.term_id', $new['cat']);
            $result = $result->where('pc.term_id', '!=', null);
        }

        $result = $result->where(function ($query) use ($new) {
            $query->orWhere('products.title', 'like', "%{$new['keyword']}%");
            $query->orWhere('products.sub_title', 'like', "%{$new['keyword']}%");
            $query->orWhere('products.product_code', 'like', "%{$new["keyword"]}%");
            $query->orWhere('products.sku', 'like', "%{$new["keyword"]}%");
        });
        $result = $result->select('pc.term_id as cat_id', 't.seo_url as t_url');

        $result = $result->groupBy('pc.term_id')->get();

        $cat_list = [];
        foreach ($result as $cat) {
            $cat_list[] .= $cat->cat_id;
        }

        return $cat_list;
    }
}

if (!function_exists('get_product_pricing')) {
    function get_product_pricing($option)
    {
        $default = [
            'main_pid' => null,
            'color' => null,
            'size' => null,
            'type' => null,
        ];

        $key = array_merge($default, $option);

        $p_info = Product::where(['id' => $key['main_pid']])->first();
        // dd($p_info->multiple_pricing);

        if ($p_info->multiple_pricing == 'on') {
            $get_price = Pcombinationdata::where(['main_pid' => $key['main_pid']]);

            if ($key['color'] != null) {
                $color_info = Pcombinationdata::where(['id' => $key['color']])->first();
                if ($color_info->color_codes) {
                    $get_price = $get_price->where(['color_codes' => $color_info->color_codes]);
                }
            }

            if ($key['size'] != null) {
                $size_info = Pcombinationdata::where(['id' => $key['size']])->first();

                if (isset($color_info->color_codes)) {
                    $get_price = $get_price->where(['size' => $size_info->size]);
                }
            }
            $get_price = $get_price->get()->first();
        }

        if (Auth::check() && auth()->user()->isDP()) {
            if ($p_info->multiple_pricing == 'on') {
                //  dd($get_price);
                $dp_price = $get_price->dp_price;
            } else {
                $dp_price = $p_info->dp_price;
            }
        } else {
            $dp_price = 0;
        }

        $p_html = '';
        $tksign = '&#2547; ';
        $tk_pre = 'Tk. ';
        $tk_aft = '/-';

        if ($p_info->multiple_pricing == 'on') {
            $r_price = $get_price->regular_price;
            $s_price = $get_price->selling_price;
        } else {
            $r_price = $p_info->local_selling_price;
            if ($p_info->local_discount > 0) {
                $s_price = $p_info->local_selling_price - (($p_info->local_selling_price * $p_info->local_discount) / 100);
            } else {
                $s_price = $p_info->local_selling_price;
            }
        }

        $is_flash = is_flash_itme($key['main_pid']);
        if ($is_flash) {
            $save = $is_flash->fi_discount;
            $save_per = number_format($is_flash->fi_show_tag);

            // $s_price = number_format($r_price - $is_flash->fi_discount);
        } else {
            $save = $r_price - $s_price;
            if ($save > 0) {
                $save_per = round(($save * 100) / $r_price);
            } else {
                $save_per = 0;
            }
        }

        if ($save > 0) {
            $p_html .= '<div class="price-top"><h1>Discount Price (' . $save_per . '%) : ' . $tk_pre . number_format($s_price) . $tk_aft . ' <span>(Save ' . number_format($save) . $tk_aft . ')</span></h1></div>';

            $p_html .= '<div class="regular-price"><h3>Regular Price : ' . $tk_pre . number_format($r_price) . $tk_aft . '</h3> </div>';

            if ($dp_price > 0) {
                $p_html .= '<div class="price-top"><h1 style="font-size: 18px">DP Price : ' . $tk_pre . number_format($dp_price) . $tk_aft . '</h1></div>';
            }
        } else {
            $p_html .= '<div class="price-top"><h1>Price : ' . $tk_pre . number_format($s_price) . $tk_aft . '</h1></div>';

            if ($dp_price > 0) {
                $p_html .= '<div class="price-top"><h1 style="font-size: 18px">DP Price : ' . $tk_pre . number_format($dp_price) . $tk_aft . '</h1></div>';
            }
        }

        //dd($p_html);

        $color = Pcombinationdata::where(['main_pid' => $key['main_pid']])->whereNotNull('color_codes')->groupBy('color_codes')->get();
        // dd($color);
        if ($key['color'] != null) {
            $get_color = Pcombinationdata::where(['id' => $key['color']])->first();
            $size = Pcombinationdata::where([
                'main_pid' => $key['main_pid'], 'color_codes' => $get_color->color_codes])
                ->whereNotNull('size')->groupBy('size')->get();
        } else {
            $size = Pcombinationdata::where(['main_pid' => $key['main_pid']])->whereNotNull('size')->groupBy('size')->get();
        }

        //dd($color->count());
        $c_html = '';

        $item_code = null;

        if ($color->count() > 0) {
            $c_html .= '<div class="short_description form-group"> <h4>Color:</h4></div> <div class="coor-warp"><div class="cc-selector">';
            $c_html .= '<ul id="color_ul">';
           // dd($color);

            foreach ($color as $col) {
                // dd($col->item_code);
                if ($key['color'] == $col->id) {
                    $active = 'active';
                     $item_code = $col->item_code;

                } else {
                    $active = '';
                }

                $c_html .= '<li class="item-color-front ' . $active . '" data-color="' . $col->id . '" >';
                $c_html .= '<a  href="javascript:void(0)">';
                if ($col->type == 1) {
                    $c_html .= '<img id="img_01" src="' . url('public/pmp_img/' . $col->color_codes) . '" width="30px" height="30px" title="Images"></a>';
                } else {
                    $c_html .= '<span class="colo-box-front" style="background: #' . $col->color_codes . '" title="Product Color"></span>';
                }
                $c_html .= '</li>';
            }
            $c_html .= '</ul></div></div>';
        }

        $s_html = '';
        if ($size->count() > 0) {
            $s_html .= '<div class="size-area"><div class="short_description form-group"> <h4>Size:</h4> </div><div class="size-area-warp"> <div class="cc-selector">';
            $s_html .= '<ul id="color_ul">';
            foreach ($size as $siz) {
           // dd($siz);
                if ($key['size'] == $siz->id) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                $s_html .= '<li class="item-size-front ' . $active . '" data-color="' . $siz->id . '">';
                $s_html .= '<a href="javascript:void(0)" title="'. $siz->size.'">';
                $s_html .= '<span class="size-box-front">' . $siz->size . '</span>';
                $s_html .= '</li>';
            }
            $s_html .= '</ul></div></div></div>';
        }

        $data = [
            'color' => $c_html,
            'size' => $s_html,
            'price' => $p_html,
            'item_code' => $item_code
        ];
        // dd($data);

        return $data;
    }
}

if (!function_exists('get_product_price')) {
    function get_product_price($option)
    {
        $default = [
            'main_pid' => null,
            'color' => null,
            'size' => null,
            'type' => null,
        ];

        $key = array_merge($default, $option);

        $p_info = Product::where(['id' => $key['main_pid']])->first();
       // dump($p_info);

        if ($p_info->multiple_pricing == 'on') {
            $get_price = Pcombinationdata::where(['main_pid' => $key['main_pid']]);

            if ($key['color'] != null) {
                $color_info = Pcombinationdata::where(['id' => $key['color']])->first();
                if ($color_info->color_codes) {
                    $get_price = $get_price->where(['color_codes' => $color_info->color_codes]);
                }
            }

            if ($key['size'] != null) {
                $size_info = Pcombinationdata::where(['id' => $key['size']])->first();

                if (isset($color_info->color_codes)) {
                    $get_price = $get_price->where(['size' => $size_info->size]);
                }
            }
            $get_price = $get_price->get();
            if ($get_price->count() > 0) {
                $get_price = $get_price->first();
            } else {
                $get_price = null;
            }
            // dump($get_price->regular_price);
        }

        if (Auth::check() && auth()->user()->isDP()) {
            if ($p_info->multiple_pricing == 'on') {
                // dd($get_price);
                if ($get_price) {
                    $dp_price = $get_price->dp_price;
                } else {
                    $dp_price = $p_info->dp_price;
                    //  dump($dp_price);
                }
            } else {
                $dp_price = $p_info->dp_price;
            }
        } else {
            $dp_price = 0;
        }

        $p_html = '';
        $tksign = '&#2547; ';

        if ($p_info->multiple_pricing == 'on' && $get_price != null) {
            $r_price = $get_price->regular_price;
            $s_price = $get_price->selling_price;
            $multi_id = $get_price->id;
        } else {
            $r_price = $p_info->local_selling_price;
             //dump($p_info->local_discount);
            if ($p_info->local_discount > 0) {
                $today = Carbon::today()->format('m/d/Y');
                // echo $p_info->today;
                // echo $p_info->offer_end_date;

                    $s_price = $p_info->local_selling_price - (($p_info->local_selling_price * $p_info->local_discount) / 100);

            } else {
                $s_price = $p_info->local_selling_price;
            }
            $multi_id = null;
        }
        //$s_price = $p_info->local_selling_price - (($p_info->local_selling_price * $p_info->local_discount) / 100);
        $is_flash_start = is_flash_start($key['main_pid']);

        $is_flash = is_flash_itme($key['main_pid']);
        //dump($is_flash);
        if ($is_flash) {
            $isFlash = true;
            $save = $is_flash->fi_discount;
            $save_per = $is_flash->fi_show_tag;
            if ($p_info->multiple_pricing == 'on') {
                $s_price = $get_price->selling_price - $is_flash->fi_discount;
            } else {
//                $s_price = $p_info->local_selling_price - $is_flash->fi_discount;
                if ($p_info->local_discount > 0) {
                    $s_price = ($p_info->local_selling_price - (($p_info->local_selling_price * $p_info->local_discount) / 100)) - $is_flash->fi_discount;
                }else {
                    $s_price = $p_info->local_selling_price - $is_flash->fi_discount;
                }
            }
        } else {
            $isFlash = false;
            $save = $r_price - $s_price;
            if ($save > 0) {
                $save_per = round(($save * 100) / $r_price);
            } else {
                $save_per = 0;
            }
        }
        if (Auth::check() && auth()->user()->isDP()) {
            if ($dp_price > 0) {
                $s_price = $dp_price;
                $save = $r_price - $s_price;
                if ($save > 0) {
                    $save_per = round(($save * 100) / $r_price);
                } else {
                    $save_per = 0;
                }
            }
        }
        if(isset($key['variation_id']) && !empty($key['variation_id'])){
            $variation_attr = ProductAttributeVariation::variation_info($key['main_pid'], $key['variation_id']);
            $variation_discountprice = $variation_attr['product_selling_discount'] ?? 0;
            $s_price = $variation_attr['local_selling_price'];
            $r_price = $variation_attr['local_regular_price'];
            $save_per = $variation_discountprice ?? 0;
        }
        $data = [
            's_price' => $s_price,
            'r_price' => $r_price,
            'd_price' => $dp_price,
            'save' => $save_per,
            'multi_id' => $multi_id,
            'main_pro' => $key['main_pid'],
            'is_flash' => $isFlash,
            'flash_start' => $is_flash_start
        ];

       // dump($data);
        return $data;
    }
}

if (!function_exists('is_flash_itme')) {
    function is_flash_itme($id)
    {
        $data = FlashShedule::leftJoin('flash_items AS fi', function ($join) {
            $join->on('fi.fi_shedule_id', '=', 'flash_schedules.id');
        })->where(['flash_schedules.fs_is_active' => 1, 'fi.fi_product_id' => $id])
            ->whereRaw('NOW() BETWEEN fs_start_date AND fs_end_date ')
            ->orderBy('fs_start_date', 'ASC')->get();

        if ($data->count() > 0) {
            return $data->first();
        } else {
            return false;
        }
    }
}

if (!function_exists('is_flash_start')) {
    function is_flash_start($id)
    {
        $data = FlashShedule::leftJoin('flash_items AS fi', function ($join) {
            $join->on('fi.fi_shedule_id', '=', 'flash_schedules.id');
        })->where(['flash_schedules.fs_is_active' => 1, 'fi.fi_product_id' => $id])
            ->whereRaw('fs_start_date >= NOW()')
            ->orderBy('fs_start_date', 'ASC')->get();
        if ($data->count() > 0) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('get_buy_discount')) {
    function get_buy_discount($price)
    {
        $setting_dis = PaymentSetting::first();

        $second_range = $setting_dis->second_range;
        $first_range = $setting_dis->first_range;
        if ($price >= $second_range) {
            $get_discount = $setting_dis->second_range_discount;
        } elseif ($price >= $first_range) {
            $get_discount = $setting_dis->first_range_discount;
        } else {
            $get_discount = null;
        }

        if ($get_discount != null) {
            $total = ($get_discount * $price) / 100;
            return $total;
        } else {
            return false;
        }
    }
}

if (!function_exists('get_home_cat')) {
    function get_home_cat()
    {
        $cats_data = HomeSetting::first()->home_category;
        if ($cats_data != null) {
            $cats = explode('|', $cats_data);

            $data = '';
            foreach ($cats as $cat) {
                $cat_info = Term::where(['id' => $cat])->first();
                //dump($cat_info);
                if ($cat_info) {
                    $data .= '<div class="pt-2">';
                    $data .= '<div class="single-product-category">';
                    $data .= '<span class="product-category-left">';
                    $data .= '<a href="' . url('/c/' . $cat_info->seo_url) . '">';

                    if ($cat_info->home_image) {
                        $data .= '<img src="' . $cat_info->term_menu_icon . '" >';
                    } else {
                        $data .= '<img src="' . url('/public/frontend/images/no-image.svg') . '" style="height:50px; width: auto;" alt="' . $cat_info->name . '">';
                    }

                    $data .= '</a>';
                    $data .= '</span>';
                    $data .= '<span class="product-category-right">';
                    $data .= '<h4>';
                    $data .= '<a href="' . url('/c/' . $cat_info->seo_url) . '">';
                    $data .= $cat_info->name;
                    $data .= ' </a>';
                    $data .= '</h4>';
                    $data .= '<span class="view-details">';
                    $data .= '<a href="' . url('/c/' . $cat_info->seo_url) . '">';
                    $data .= 'View Details';
                    $data .= '</a>';
                    $data .= '</span>';
                    $data .= '</span>';
                    $data .= '</div>';
                    $data .= '</div>';
                }
            }

            return $data;
        } else {
            return false;
        }
    }
}

if (!function_exists('home_banner_one')) {
    function home_banner_one($data)
    {


        if ($data) {
            $html = '';

            if ($data[0]) {
                $html .= '<div class="pt-3">';
                $html .= '<div class="thumb-banner">';
                $html .= '<a href="'.$data[0]['link'].'">';
                $html .= '<img src="' . get_full_img($data[0]['img']) . '" alt="baner-img">';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</div>';
            }

            if ($data[1]) {
                $html .= '<div class="pt-3">';
                $html .= '<div class="thumb-banner">';
                $html .= '<a href="'.$data[1]['link'].'">';
                //if(img)
                $html .= '<img src="' . get_full_img($data[1]['img']) . '" alt="baner-img">';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</div>';
            }
            if ($data[2]) {
                $html .= '<div class="pt-3">';
                $html .= '<div class="thumb-banner">';
                $html .= '<a href="'.$data[2]['link'].'">';

                $html .= '<img src="' . get_full_img($data[2]['img']) . '" alt="baner-img">';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '<div class="">';
                $html .= '<div class="sms-banner">';
                if ($data[3]) {
                    $html .= '<div class="ptd-5 pt-5_one">';
                    $html .= '<div class="thumb-banner">';
                    $html .= '<a href="'.$data[3]['link'].'">';
                    $html .= '<img src="' . get_full_img($data[3]['img']) . '" alt="baner-img">';
                    $html .= '</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
                if ($data[4]) {
                    $html .= '<div class="ptd-5 pt-5_one">';
                    $html .= '<div class="thumb-banner">';
                    $html .= '<a href="'.$data[4]['link'].'">';
                    $html .= '<img src="' . get_full_img($data[4]['img']) . '" alt="baner-img">';
                    $html .= '</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                }

                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }

            return $html;
        }
    }
}

if (!function_exists('get_icon_img')) {
    function get_icon_img($id = null)
    {
        if ($id) {
            $img_g = Image::where(['id' => $id])->get();
            // dump($img_g);

            if ($img_g->count() > 0) {
                $img = $img_g->first()->icon_size_directory;
            } else {
                $img = url('/public/frontend/images/no-image.svg');
            }
        } else {
            $img = url('/public/frontend/images/no-image.svg');
        }

        return $img;
    }
}

if (!function_exists('get_full_img')) {
    function get_full_img($id = null)
    {
        if ($id) {
            $img_g = Image::where(['id' => $id])->get();
            //dump($img_g);

            if ($img_g->count() > 0) {
                $img = $img_g->first()->full_size_directory;
            } else {
                $img = url('/public/frontend/images/no-image.svg');
            }
        } else {
            $img = url('/public/frontend/images/no-image.svg');
        }

        return $img;
    }
}

if (!function_exists('home_brand_slider')) {
    function home_brand_slider($data)
    {
        $html = '';
        if ($data) {
            foreach ($data as $itme) {
                if ($itme['link'] && $itme['img']) {
                    $html .= '<div class="choose-single">';
                    $html .= '<div class="col-md-12">';
                    $html .= '<a href="' . url($itme['link']) . '">';
                    $html .= '<img src="' . get_full_img($itme['img']) . '" alt="baner-img">';
                    $html .= '</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
            }
        }
        // dump($html);

        return $html;
    }
}

if (!function_exists('home_main_slider')) {
    function home_main_slider($data)
    {
        $html = '';
        if ($data) {
            foreach ($data as $itme) {
                if ($itme['link'] && $itme['img']) {
                    $html .= '<div class="item">';
                    $html .= '<div class="banner-item">';
                    $html .= '<div class="slider-herrod">';
                    $html .= '<a href="' . url($itme['link']) . '">';
                    $html .= '<img src="' . get_full_img($itme['img']) . '" alt="baner-img">';
                    $html .= '</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
            }
        }
        // dump($html);

        return $html;
    }
}

if (!function_exists('home_banner_two')) {
    function home_banner_two($data)
    {
        $html = '';
        if ($data) {
            foreach ($data as $itme) {
                if ($itme['link'] && $itme['img']) {
                    $html .= '<a href="' . url($itme['link']) . '">';
                    $html .= '<img src="' . get_full_img($itme['img']) . '" alt="baner-img">';
                    $html .= '</a>';
                }
            }
        }
        // dump($html);

        return $html;
    }
}

if (!function_exists('home_banner_tree')) {
    function home_banner_tree($data)
    {
        $html = '';
        if ($data) {
            foreach ($data as $itme) {
                if ($itme['link'] && $itme['img']) {
                    $html .= '<a href="' . url($itme['link']) . '">';
                    $html .= '<img src="' . get_full_img($itme['img']) . '" alt="baner-img">';
                    $html .= '</a>';
                }
            }
        }
        // dump($html);

        return $html;
    }
}

if (!function_exists('home_explore_products')) {
    function home_explore_products($data)
    {

        //dd($data);
        $html = '';
        $html .= '<div class="pt-2">';


        if ($data) {


            $i = 1;
            foreach ($data as $itme) {
                ++$i;
                if($itme['beck'] != 'N'){
                    $html .= '</div><div class="pt-2">';
                }

                $parrent = App\Models\Term::Where('id', $itme['cat'])->first();

                $subs = App\Models\Term::Where('parent', $itme)->get();

                $html .= '<div class="single-explore-products">';
                $html .= '<div class="single-explore-list">';
                $html .= '<ul class="nav nav-stacked" id="accordion1">';
                $html .= '<li class="colp_nav">';

                $html .= '<a class="" data-toggle="collapse" data-target="#collapseOne'.$i.'"
                                aria-expanded="true" aria-controls="collapseOne'.$i.'">
                                            <strong>'.$parrent->name.'</strong>';
                $html .= '</a>';
                $html .= '<ul id="collapseOne'.$i.'" class="collapse show list-unstyled"
                                            aria-labelledby="headingOne" data-parent="#accordion">';
                if($itme['sub'] != ""){
                    $subs = explode(',',$itme['sub']);
                    //dump($subs);
                    foreach ($subs as $sub){
                        $sub = App\Models\Term::Where('id', $sub)->first();
                        $html .= '<li><a href="'.url('c/'.$sub->seo_url).'">'.$sub->name.'</a></li>';
                    }
                }else{
                    foreach ($subs as $sub){
                        $html .= '<li><a href="'.url('c/'.$sub->seo_url).'">'.$sub->name.'</a></li>';
                    }
                }


                $html .= '</ul>';
                $html .= '</li>';
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</div>';

            }
        }

        $html .= '</div>';

        return $html;
    }
}


if (!function_exists('product_stock_out')) {
    function product_stock_out($cartProductInfo){
        $product = [
            'product_id' => '',
            'product_code' => '',
            'depot_id' => '',
            'qty' => 0,
        ];
        $merge = array_merge($product, $cartProductInfo);
        $product_id = $merge['product_id'];
        $product_code = $merge['product_code'];
        $depot_id = $merge['depot_id'];
        $qty = $merge['qty'];
        $depot_id = Depot::where('districts', 'LIKE', '%'. $depot_id.'%')->first()->id ?? null;
        $getCurrent = ProductStock::where('depot_id', $depot_id)
                        ->where('product_code', $product_code)
                        ->where('product_id', $product_id)
                        ->first();
        if($getCurrent) {
            $getStock = $getCurrent->available_qty ?? 0;
            $now = $getStock - $qty;
            $getCurrent->update(['available_qty' => $now]);
        }
    }
}
