<?php

namespace App\Http\Controllers\API;

use App\Models\Oneclickbuy;
use App\Models\ProductAttributeVariation;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\OrdersMaster;
use App\Models\Product;
use App\Models\ProductAttributesData;
use App\Repositories\OrdersDetail\OrdersDetailInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\PaymentSetting\PaymentSettingInterface;
use Illuminate\Http\Request;
use App\Repositories\Product\ProductInterface;
use App\Repositories\ProductSet\ProductSetInterface;
use App\Repositories\Role_user\Role_userInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\PaymentSetting;
use App\Http\Controllers\API\StockController;
use function dd;

class CartController extends Controller
{

    private $product;
    private $session_data;
    private $paymentsetting;
    private $user;
    private $role_user;
    private $order_master;
    private $order_details;
    private $product_set;
    private $stockController;


    public function __construct(
        ProductInterface    $product, SessionDataInterface $session_data, PaymentSettingInterface $paymentsetting, UserInterface $user, Role_userInterface $role_user, OrdersMasterInterface $order_master, OrdersDetailInterface $order_details,
        ProductSetInterface $product_set
    )
    {
        $this->product = $product;
        $this->session_data = $session_data;
        $this->paymentsetting = $paymentsetting;
        $this->user = $user;
        $this->role_user = $role_user;
        $this->order_master = $order_master;
        $this->order_details = $order_details;
        $this->product_set = $product_set;
        $this->stockController = new StockController();
    }

    public function test()
    {
        return response()->json(['success' => true], 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Credentials' => 'true',
        ]);
    }

    public function addToCart(Request $request)
    {
        $success = false;
        $count = 0;
        $message = "Initial";
        $response = [];

        if (!empty($request->main_pid)) {
            $response = $this->addItem($request);
            $count++;
            $message .= "Default - $count";
        }

        if (!empty($request->psets)) {
            $expSets = explode(",", $request->psets);

            if (is_iterable($expSets)) {
                foreach ($expSets as $expSet) {
                    $exset = explode(":", $expSet);
                    $request->main_pid = $exset[0];
                    $request->size = $exset[1];
                    $request->color = $exset[2];
                    $request->qty = $exset[3];
                    $ins = $this->addItem($request);

                    if (!empty($ins['success']) && $ins['success']) {
                        $count++;
                        $message .= " Psets - $count";
                        $response['success'] = true;
                        $response['main_pid'][] = $request->main_pid;
                    }
                }
            }
        }

        if (is_array($response)) {
            $response['d_message'] = $message;
        } else {
            $response['d_message'] = $message;
        }

        return response()->json($response);

    }


    private function addItem($request)
    {
        $self_token = $request->header('Self-Token');
        $cart_token = "cart_" . $self_token;

        $product = [
            'productid' => $request->main_pid,
            'size' => $request->size,
            'color' => $request->color,
            'qty' => $request->qty ?? 1,
        ];


        // dd($product);

        $product_ifo = $this->product->getByAny('id', $product['productid'])->first();

        if (!$product_ifo) {

            return [
                'success' => false,
                'message' => 'Product not exist'
            ];
        }
        $session_data = $this->session_data->getFirstByKey($cart_token);
        $oldcart = ($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null;

        $cash_on_delivery_token = "cash_on_delivery_" . $self_token;
        $cash_on_session = $this->session_data->getFirstByKey($cash_on_delivery_token);
        $cash_on_session = ($cash_on_session->session_data ?? false) ? json_decode($cash_on_session->session_data) : null;

        if ($oldcart->totalqty ?? 0 == 0) {
            $this->session_data->updateByKey($cash_on_delivery_token, null);
        }

        if ($cash_on_session == null && $product_ifo->cash_on_delivery == 1) {
            $this->session_data->updateByKey($cash_on_delivery_token, json_encode(true));
        } elseif ($cash_on_session != null && $product_ifo->cash_on_delivery != 1) {
            return [
                'success' => true,
                'message' => 'The cart is for only cash on delivery.'
            ];
        }


        $prebooking_token = "prebooking_" . $self_token;
        $session_data = $this->session_data->getFirstByKey($prebooking_token);
        $prebooking = ($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null;

        if ($prebooking == null && ($product_ifo->pre_booking ?? 0) == 1 && (($oldcart->totalqty ?? 0) > 0)) {

            return [
                'success' => false,
                'message' => 'Prebooking product can not added. try to remove other product first'
            ];
        }

        if ($prebooking != null && (($product_ifo->pre_booking ?? 0) != 1 && (($oldcart->totalqty ?? 0) > 0))) {

            return [
                'success' => false,
                'message' => 'A prebooking product already added.'
            ];

        }

        if (($product_ifo->pre_booking ?? 0) == 1) {
            $update_prebooking = $this->session_data->updateByKey($prebooking_token, json_encode($product_ifo->id));
        }

        $is_falsh = is_flash_itme($product['productid']);
        if (($product_ifo->enable_timespan == 1 && $product_ifo->disable_buy == 'on') || $product_ifo->disable_buy == 'off') {
            if ($product_ifo['multiple_pricing'] == 'on') {
                if ($product['color'] != null) {
                    $color_info = \App\Models\Pcombinationdata::where(['id' => $product['color'], 'main_pid' => $product['productid']])->first();
                    $get_color = [
                        'color_codes' => $color_info->color_codes,
                        'main_pid' => $product['productid']
                    ];
                } else {
                    $get_color = [];
                }
                if ($product['size'] != null) {
                    $size_info = \App\Models\Pcombinationdata::where(['id' => $product['size'], 'main_pid' => $product['productid']])->first();
                    $get_size = [
                        'size' => $size_info->size,
                        'main_pid' => $product['productid']
                    ];
                } else {
                    $get_size = [];
                }
                $color_size_arr = array_merge($get_color, $get_size);
                if ($color_size_arr) {
                    $m_price_infos = \App\Models\Pcombinationdata::where($color_size_arr)->get();
                    if ($m_price_infos->count() == 1) {
                        $m_price_info = $m_price_infos->first();

                        $final_sp = $m_price_info->selling_price;
                        $is_dp = 'No';
                        $flash_discount = ($is_falsh) ? $is_falsh->fi_discount : null;
                        $final_sp = $final_sp - $flash_discount;
                        $price_dif = $m_price_info->regular_price - $final_sp;
                        $dis_tag = round(($price_dif * 100) / $m_price_info->regular_price);
                        $add_cat = [
                            'productid' => $request->main_pid,
                            'productcode' => $product_ifo->product_code . 'CIC' . $m_price_info->id,
                            'size_colo' => $m_price_info->id,
                            'purchaseprice' => $final_sp,
                            'qty' => $request->qty ?? 1,
                            'is_dp' => $is_dp,
                            'flash_discount' => $flash_discount,
                            'item_code' => $m_price_info->item_code,
                            'dis_tag' => $dis_tag,
                            'pre_price' => $m_price_info->regular_price,
                            'pset_id' => $request->pset_id,
                            'fabric_id' => $request->fabric_id
                        ];
                    } else {
                        $add_cat = [];
                    }
                } else {
                    $add_cat = [];
                }
            }

            else {
                // Okay! New Variation Codes written by Nipun
                $variationInfo = $request->variation_id ? ProductAttributeVariation::variation_info($request->main_pid, $request->variation_id) : null;
                $get_data = [
                    'main_pid' => $product['productid'],
                    'color' => $product['color'],
                    'size' => $product['size'],
                    'type' => null,
                    'variation_id' => $request->variation_id ?? null, //Added by Nipun
                ];

                $get_price = get_product_price($get_data);

                $final_sp = $get_price['s_price'];
                $is_dp = 'No';
                $flash_discount = ($is_falsh) ? $is_falsh->fi_discount : null;
                $final_sp = $final_sp - $flash_discount;
                // dd($get_price);
                //Nipun
                $db_product = $this->product->self()->where('id', $request->main_pid)->with('firstImage')->first();

                $variation_info = $variationInfo?? false;
                $main_inmage = $db_product->firstImage->full_size_directory ?? null;
                $img = $variation_info ? (\App\Models\Image::where('id', $variation_info['variation_image'])->first()->full_size_directory ?? null) : false;
                $img = $img ? $img : $main_inmage;
                //Nipun
                $add_cat = [
                    'productid' => $request->main_pid,
                    'sub_title' => $variationInfo ? $variationInfo['variation_sub_title'] : $product_ifo->sub_title,
                    'productcode' => $variationInfo ? $variationInfo['variation_product_code'] : $product_ifo->product_code,
                    'size_colo' => null,
                    'purchaseprice' => $get_price['s_price'],
                    'qty' => $request->qty ?? 1,
                    'variation_id' => $request->variation_id ?? null,
                    'variation_info' => $variationInfo,
                    'is_dp' => null,
                    'image_url' => $img,
                    'flash_discount' => $flash_discount,
                    'item_code' => null,
                    'dis_tag' => $get_price['save'],
                    'pre_price' => $get_price['r_price'],
                    'pset_id' => $request->pset_id,
                    'fabric_id' => $request->fabric_id
                ];
            }
        } else {
            $add_cat = [];
        }
        if ($add_cat) {
//            dd($add_cat);

            $session_data = $this->session_data->getFirstByKey($cart_token);
            $oldcart = ($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null;

//             dd($oldcart);
            $cart = new Cart($oldcart);

            // dd($cart);

            //$cart->add($product, $request->get('productcode'));
            $cart->add($add_cat, $add_cat['productcode']);
//             dd(json_encode($cart));
            $this->session_data->updateByKey($cart_token, json_encode($cart));

            $session_data = $this->session_data->getFirstByKey($cart_token);
            $newcart = ($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null;
            // dd($newcart);

            $ncart = new Cart($newcart);



            if (!empty($ncart->items)) {
                // dd($ncart->items);
                $total_qty = array_sum(array_column($ncart->items, 'qty'));
                $individual_price = [];
                foreach ($ncart->items as $item) {
                    //dd($item);
                    $individual_price[] = $item['purchaseprice'] * $item['qty'];
                }
                $totalprice = array_sum($individual_price);
            } else {
                $total_qty = 0;
                $totalprice = number_format(0);
            }

            $pro = $this->product->getById($request->main_pid);
            $categories = $this->product->getProductCategories($pro->id);
            $cat_info = \App\Models\Term::where('id', $categories[0]['term_id'])->get()->first();
            $cat_name = $cat_info['name'] ?? '';
            //dd($pro);

            $first_image = \App\Models\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

            //dd($first_image);

            $regularprice = $pro->local_selling_price;
            $save = ($pro->local_selling_price * $pro->local_discount) / 100;
            $sp = $regularprice - $save;
            $tksign = '&#2547; ';

            $variationInfo = $request->variation_id ? ProductAttributeVariation::variation_info($request->main_pid, $request->variation_id) : null;
            $response = [
                'success' => true,
                'sp' => $sp,
                'seo_url' => $pro->seo_url,
                'product_code' => $variationInfo ? $variationInfo['variation_product_code'] : $pro->product_code,
                'title' => $pro->title,
                'sub_title' => $variationInfo ? $variationInfo['variation_sub_title'] : $pro->sub_title, //$pro->sub_title,
                'color' => ProductAttributesData::getValueByProductId($request->main_pid, 'color'),
                'size' => ProductAttributesData::getValueByProductId($request->main_pid, 'size'),
                'material' => ProductAttributesData::getValueByProductId($request->main_pid, 'material'),
                'dimension' => ProductAttributesData::getValueByProductId($request->main_pid, 'dimension'),
                'image_url' => $first_image->icon_size_directory,
                'purchaseprice' => $add_cat['purchaseprice'],
                'total_qty' => $total_qty,
                'totalprice' => $totalprice,
                'report' => 'Yes',
                'product_pprice' => $get_price['s_price'] ?? 0,
                'cat_name' => $cat_name ?? '',
                'pset_id' => $request->pset_id,
                'variation_id' => $request->variation_id ?? null,
                'variation_info' => $variationInfo,
                'fabric_id' => $request->fabric_id
            ];

            if (Session::has('my_coupon')) {
                $coupons = Session::get('my_coupon');
                coupon_voucher_verify($coupons['coupon_type'], $coupons['coupon_code']);
                //dd(22);
            }
            $exord_token = "existing_order_id" . $self_token;
            $update_order_id = $this->session_data->updateByKey($exord_token, json_encode(null));
            return $response;
        } else {

            $response = [
                'success' => false,
                'message' => 'Product can not added to cart, something wrong. Try again later or contact us.'
            ];

            return $response;
        }


    }

    public function removeCartItem(Request $request)
    {
        $id = $request->pid;
        $code = $request->pcode;
        $pid_codes = $request->pid_codes;
        $self_token = $request->header('Self-Token');
        $message = "Initial";
        if (!empty($self_token)) {


            if ($id && $code) {
                $this->removeItem($self_token, $id, $code);
                $message .= " Default";
            }

            $ex_pid_codes = explode(",", $pid_codes);

            if (is_iterable($ex_pid_codes)) {
                foreach ($ex_pid_codes as $epc) {
                    $epcX = explode(":", $epc);

                    if (!empty($epcX[0]) && !empty($epcX[1])) {
                        $this->removeItem($self_token, $epcX[0], $epcX[1]);
                    }
                }
                $message .= " Iterable";
            }

        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }


    private function removeItem($self_token, $id, $code)
    {
        $cart_token = "cart_" . $self_token;

        $session_data = $this->session_data->getFirstByKey($cart_token);
        $oldcart = ($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null;

        $cart = new Cart($oldcart);
        unset($cart->items[$code]);

        $cart->totalprice = 0;
        $cart->totalqty = 0;

        foreach ($cart->items as $item) {

            $cart->totalqty += ($item['qty'] ?? 0);
            $cart->totalprice += ($item['qty'] ?? 0) * ($item['purchaseprice'] ?? 0);
        }

        $this->session_data->updateByKey($cart_token, json_encode($cart));
        $exord_token = "existing_order_id" . $self_token;
        $update_order_id = $this->session_data->updateByKey($exord_token, json_encode(null));


        $prebooking_token = "prebooking_" . $self_token;
        $session_data = $this->session_data->getFirstByKey($prebooking_token);
        $prebooking = ($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null;

        if ($prebooking != null && (($cart->totalqty ?? 0) == 0)) {
            $update_prebooking = $this->session_data->updateByKey($prebooking_token, json_encode(null));
        }

    }

    public function updateCart(Request $request)
    {
        $update_items = $request->json('items');

        if (count($update_items) == 0) {

            return response()->json([
                'success' => false,
                'message' => 'No product to update.'
            ]);
        }

        $self_token = $request->header('Self-Token');
        $cart_token = "cart_" . $self_token;

        $session_data = $this->session_data->getFirstByKey($cart_token);
        $oldcart = ($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null;

        $cart = new Cart($oldcart);




        $itemExist = false;
        foreach ($request->json('items') as $item) {

            if (!empty($cart->items[$item['code']])) {
                $itemExist = true;
                $cart->items[$item['code']]['qty'] = $item['qty'];
            }
        }

        if ($itemExist) {

            $cart->totalprice = 0;
            $cart->totalqty = 0;

            foreach ($cart->items as $item) {

                $cart->totalqty += ($item['qty'] ?? 0);
                $cart->totalprice += ($item['qty'] ?? 0) * ($item['purchaseprice'] ?? 0);
            }



            $this->session_data->updateByKey($cart_token, json_encode($cart));

            $exord_token = "existing_order_id" . $self_token;
            $update_order_id = $this->session_data->updateByKey($exord_token, json_encode(null));

        }

        return response()->json([
            'success' => $itemExist,
            'message' => $itemExist ? 'Cart updated' : 'No product to update'
        ]);

    }

    public function getCart(Request $request)
    {
        $self_token = $request->header('Self-Token');
        $cart_token = "cart_" . $self_token;
        $pm_token = "paymethod_" . $self_token;
        $ud_token = "user_details" . $self_token;
        $data_ud = $this->session_data->getFirstByKey($ud_token);
        $data_ud = ($data_ud->session_data ?? false) ? json_decode($data_ud->session_data, true) : null;

        $district = $data_ud['district'] ?? '';

        //$coupon_token = "coupon_".$self_token;
        $checkDisocuntVal = $this->session_data->getFirstByKey($pm_token)->session_data ?? false;
        $checkDisocunt = json_decode($checkDisocuntVal, true)['discount'] ?? 0;
        $checkDisocuntType = json_decode($checkDisocuntVal, true)['discount_type'] ?? false;
//        dd($this->couponApply(request()->get('coupon', 'Love')));

        $session_data = $this->session_data->getFirstByKey($cart_token);
        $cart = ($session_data->session_data ?? false) ? json_decode($session_data->session_data, true) : null;
        $prebooking_min_amount = 0;


        if (!empty($cart['items']) && is_array($cart['items'])) {
            $re_pricing = [];
            $currentTotal = 0;
            $currentQty = 0;
            foreach ($cart['items'] as $rKey => $re_price) {
                $reProduct = Product::find($re_price['item']['productid'] ?? null);
                if ($reProduct && $reProduct->is_active == 1) {
                    $re_price['purchaseprice'] = $re_price['item']['purchaseprice']; //$reProduct->product_price_now;
                    $re_price['item']['purchaseprice'] = $re_price['item']['purchaseprice'];  //$reProduct->product_price_now;
                    $currentTotal += (((float)$re_price['item']['purchaseprice']) * $re_price['qty']); //(((float)$reProduct->product_price_now) * $re_price['qty']);
                    $re_pricing[$rKey] = $re_price;
                    $currentQty += $re_price['qty'];
                    // return [$currentQty,$currentTotal, $reProduct->product_price_now, $re_price['qty']];
                }
            }

            $cart['items'] = $re_pricing;
            $cart['totalprice'] = $currentTotal;
            $cart['totalqty'] = $currentQty;
            $cart['discount'] = $checkDisocunt;
            $cart['discount_type'] = $checkDisocuntType;
            // return $cart;
            $updateCart = json_encode(json_decode(json_encode($cart), FALSE));
            $this->session_data->updateByKey($cart_token, $updateCart);
            $items = array_map(function ($item) {
                $item['product_id'] = $item['item']['productid'];
                return $item;
            }, $cart['items']);


            $product_ids = [];
            $product_ids = array_column($items, 'product_id');
            $pset_ids = array_filter(array_column($items, 'pset_id'));
            $psets = [];

            if (count($pset_ids) > 0) {
                $db_psets = $this->product_set->self()->with('image')->whereIn('id', $pset_ids)->get();

                foreach ($db_psets as $db_pset) {
                    $psets[$db_pset->id] = [
                        'id' => $db_pset->id,
                        'title' => $db_pset->title,
                        'slug' => $db_pset->slug,
                        'description' => $db_pset->description,
                        'image_url' => $db_pset->image->icon_size_directory ?? null
                    ];
                }
            }


            $db_products = $this->product->self()->whereIn('id', $product_ids)->with('firstImage')->get();
            $products_by_key_id = [];

            /* Nipun */
            foreach ( $cart['items'] as $cart_product) {
                $cart_product = (object)$cart_product;
                $cart_product_item = (object)$cart_product->item;
                $db_product = $this->product->self()->where('id', $cart_product_item->productid)->with('firstImage')->first();

                $variation_info = $cart_product_item->variation_info ?? false;
                $main_inmage = $db_product->firstImage->full_size_directory ?? null;
                $img = $variation_info ? (\App\Models\Image::where('id', $variation_info['variation_image'])->first()->full_size_directory ?? null) : false;
                $img = $img ? $img : $main_inmage;

                $productArriveArr = [
                    'code' => $cart_product_item->productcode,
                    'qty' =>$cart_product->qty,
                ];
                $products_by_key_id[$cart_product_item->productcode] = [
                    'id' => $cart_product_item->productid,
                    'title' => $db_product->title,
                    'sub_title' => $db_product->sub_title,
                    'short_description' => $db_product->short_description,
                    'seo_url' => $db_product->seo_url,
                    'code' => $cart_product_item->productcode,
                    'sku' => $db_product->sku,
                    'qty' => $db_product->qty,
                    'price' => $cart_product->purchaseprice,
                    'image_url' => $img,
                    'product_category' => $db_product->category,
                    'product_arrive' => $district ? $this->stockController->getProductArriveTime($district, $productArriveArr) : null, //StockController::checkStock(),
                ];
                if ($db_product->pre_booking_discount) {
                    $prebooking_min_amount += ($db_product->pre_booking_discount * $db_product->product_price_now / 100);
                }
            }
            /* Nipun */
            /*
            foreach ($db_products as $db_product) {
                $productArriveArr = [
                    'code' => $db_product->product_code,
                    'qty' =>$cart['items'][$db_product->product_code]['qty'],
                ];
                $products_by_key_id[$db_product->id] = [
                    'id' => $db_product->id,
                    'title' => $db_product->title,
                    'sub_title' => $db_product->sub_title,
                    'short_description' => $db_product->short_description,
                    'seo_url' => $db_product->seo_url,
                    'code' => $db_product->product_code,
                    'sku' => $db_product->sku,
                    'qty' => $db_product->qty,
                    'price' => $db_product->product_price_now,
                    'image_url' => $db_product->firstImage->full_size_directory ?? null,
                    'product_category' => $db_product->category,
                    'product_arrive' => $district ? StockController::getProductArriveTime($district, $productArriveArr) : null, //StockController::checkStock(),
                ];
                if ($db_product->pre_booking_discount) {
                    $prebooking_min_amount += ($db_product->pre_booking_discount * $db_product->product_price_now / 100);
                }
            }
            */

            $cart_itemz = array_map(function ($item) use ($products_by_key_id, $psets) {
                $productCode = $item['item']['productcode'] ?? $item['product_id'];// Nipun
//                $item['info'] = $products_by_key_id[$item['product_id']] ?? null;
                $item['info'] = $products_by_key_id[$productCode] ?? null; //Nipun
                $item['pset'] = null;

                if ($item['pset_id'] != null) {
                    $item['pset'] = $psets[$item['pset_id']] ?? null;
                }

                return $item;
            }, $items);

            $cart_items = [];
            $product_sets = [];

            foreach ($cart_itemz as $cart_item) {


                if ($cart_item['pset_id'] != null) {
                    $product_sets[$cart_item['pset_id']]['info'] = $cart_item['pset'];
                    $product_sets[$cart_item['pset_id']]['items'][] = $cart_item;
                } else
                    $cart_items[] = $cart_item;

            }

            $cart['items'] = $cart_items;

        }

        $cart['psets'] = $product_sets ?? [];

        $exord_token = "existing_order_id" . $self_token;
        $existing_order_id = $this->session_data->getFirstByKey($exord_token);
        $existing_order_id = ($existing_order_id->session_data ?? false) ? json_decode($existing_order_id->session_data, true) : null;


        $prebooking_token = "prebooking_" . $self_token;
        $session_data = $this->session_data->getFirstByKey($prebooking_token);
        $prebooking = ($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null;

        $prebooking_min_token = "prebooking_min" . $self_token;
        $this->session_data->updateByKey($prebooking_min_token, json_encode($prebooking_min_amount));


        $pm_session = $this->session_data->getFirstByKey($pm_token);
        $pm = ($pm_session->session_data ?? false) ? json_decode($pm_session->session_data, true) : null;


        if (!$pm || !isset($pm['deliveryfee']) || !$pm['deliveryfee']) {

            if ($data_ud) {
                $pm = $pm ? $pm : [];
                $pm['deliveryfee'] = $data_ud['deliveryfee'] ?? 0;
            }
        }

        $cash_on_delivery_token = "cash_on_delivery_" . $self_token;
        $cash_on_session = $this->session_data->getFirstByKey($cash_on_delivery_token);
        $cash_on_session = ($cash_on_session->session_data ?? false) ? json_decode($cash_on_session->session_data) : null;
        $cash_on_delivery = $cash_on_session;

        $cart['product_maximum_arrive_time']  = $district ? $this->stockController->getMaximumProductArriveTime($cart['items'], $district) : null;

        return response()->json(compact('cart', 'existing_order_id', 'prebooking', 'prebooking_min_amount', 'pm', 'district', 'cash_on_delivery'));
    }


    public function checkoutAddress(Request $request)
    {
        $self_token = $request->header('Self-Token');
        $cart_token = "cart_" . $self_token;
        $pm_token = "paymethod_" . $self_token;
        $coupon_token = "coupon_" . $self_token;
        $session_data = $this->session_data->getFirstByKey($cart_token);
        $oldcart = ($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null;


        if (!$oldcart) {

            return response()->json([
                'success' => false
            ]);
        }

        $cart = new Cart($oldcart);

        $total_price = [];
        foreach ($cart->items as $item) {
            $item_qty = $item['qty'];

            $item_price = $item['purchaseprice'];

            $total_price[] = $item_qty * $item_price;
        }
        $total_amount = array_sum($total_price);

        //Check Coupon

        $checkDisocunt = $this->session_data->getFirstByKey($pm_token)->session_data ?? false;
        $discount = json_decode($checkDisocunt, true)['discount'] ?? 0;
        $discountType = json_decode($checkDisocunt, true)['discount_type'] ?? false;

        $checkCoupon = $this->couponApply($request)->getData();
        //dd($checkCoupon);
        if ($checkCoupon->success) {
            $discount = $checkCoupon->data->price;
            $discountType = $checkCoupon->data->amount_type; //$checkCoupon->data->price;
        } else {
            $discount = 0;
            $discountType = false;
            $this->session_data->updateByKey($coupon_token, null);
        }
        //dd($discount);

        $attributes = [
            'currency' => 'BDT',
            'total_amount' => $total_amount,
            'grand_total' => null,
            'payment_method' => null,
            'terms_check' => null,
            'discount' => $discount,
            'discount_type' => $discountType,
        ];

//        $updateCart = ['discount' =>$discount];
//        $this->session_data->updateByKey($cart_token,$updateCart);

        $this->session_data->updateByKey($pm_token, json_encode($attributes));


        return response()->json([
            'success' => true
        ]);
    }


    public function checkoutDelivreyAddress(Request $request)
    {
        //dd($request);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3|max:255',
                'mobile' => 'required|min:11|max:11',
                'emergency_mobile' => 'required|min:11|max:11|different:mobile',
                'email' => 'required',
                'address' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'messages' => $validator->errors()
            ]);
        } else {

            $paymentsetting = $this->paymentsetting->getAll();
            $paymentsetting = $paymentsetting->first();

            $self_token = $request->header('Self-Token');
            $cart_token = "cart_" . $self_token;
            $pm_token = "paymethod_" . $self_token;
            $ud_token = "user_details" . $self_token;

            $pm = $this->session_data->getFirstByKey($pm_token);
            $pm = ($pm->session_data ?? false) ? json_decode($pm->session_data, true) : null;
            //dump($pm['total_amount']);
            //dd($request->get('district'));

            if (!$pm) {

                return response()->json([
                    'success' => false,
                    'message' => 'Payment Missing'
                ]);

            }


            $deliverycharge = 0;
            $total_amount = $pm['total_amount'];

            $district = strtolower($request->district);

            if ($district == 'dhaka') {
                if ($total_amount <= $paymentsetting->decidable_amount) {
                    $deliverycharge = $paymentsetting->inside_dhaka_fee;
                } else if ($total_amount <= $paymentsetting->decidable_amount_od) {
                    $deliverycharge = $paymentsetting->inside_dhaka_od;
                }

            } else {
                if ($total_amount <= $paymentsetting->decidable_amount) {
                    $deliverycharge = $paymentsetting->outside_dhaka_fee;
                } else if ($total_amount <= $paymentsetting->decidable_amount_od) {
                    $deliverycharge = $paymentsetting->outside_dhaka_od;
                }
            }


            $email_exploded = explode('@', $request->email);
            $username = $email_exploded[0];

            if (!empty($request->password) && !empty($request->username)) {
                $attributes = [
                    'name' => $request->name,
                    'phone' => $request->mobile,
                    'emergency_phone' => $request->emergency_mobile,
                    'email' => $request->email,
                    'district' => $request->district,
                    'thana' => $request->thana,
                    'deliveryfee' => $deliverycharge,
                    'address' => $request->address,
                    'username' => $username,
                    'password' => bcrypt($request->password),
                    'is_active' => 1
                ];
            } else {


                $attributes = [
                    'name' => $request->name,
                    'phone' => $request->mobile,
                    'emergency_phone' => $request->emergency_mobile,
                    'email' => $request->email,
                    'district' => $request->district,
                    'thana' => $request->thana,
                    'deliveryfee' => $deliverycharge,
                    'address' => $request->address,
                    'username' => $username,
                    'password' => bcrypt('12345678'),
                    'is_active' => 1
                ];

            }

            $this->session_data->updateByKey($ud_token, json_encode($attributes));

            try {
                $user = $this->user->create($attributes);
                if (!empty($user)) {
                    //dd($user);
                    $attributes_role = [
                        'role_id' => 8,
                        'user_id' => $user->id
                    ];

                    $adding_user_id = [
                        'user_id' => [$user->id]
                    ];

                    $natt = array_merge($attributes, $adding_user_id);
                    $this->session_data->updateByKey($ud_token, json_encode($natt));

                    //Session::push('user_details.user_id', $user->id);

                    try {
                        $this->role_user->create($attributes_role);

                        return response()->json([
                            'success' => true,
                            'message' => 'Your contact and address has been saved'
                        ]);

                    } catch (\Illuminate\Database\QueryException $ex) {
                        $errorCode = $ex->errorInfo[1];
                        if ($errorCode == '1062') {

                            return response()->json([
                                'success' => false,
                                'message' => $ex->errorInfo[2]
                            ]);

                        } else {

                            return response()->json([
                                'success' => false,
                                'message' => 'Something went wrong'
                            ]);

                        }
                    }
                } else {

                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong'
                    ]);

                }
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];


                $data_pm = $this->session_data->getFirstByKey($pm_token);
                $data_pm = ($data_pm->session_data ?? false) ? json_decode($data_pm->session_data, true) : null;

                $data_ud = $this->session_data->getFirstByKey($ud_token);
                $data_ud = ($data_ud->session_data ?? false) ? json_decode($data_ud->session_data, true) : null;


                if (!$data_pm || !$data_ud) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Something went wrong'
                    ]);
                }

                if ($errorCode === 1062) {
                    //dd($data['user_details']['email']);
                    $user = \App\Models\User::where('email', $data_ud['email'])->get()->first();

                    $user->address = $data_ud['address'];
                    $user->save(['username' => false, 'email' => false, 'password' => false, 'name' => false, 'phone' => false, 'emrgency_phone' => false]);

                    $adding_user_id = [
                        'user_id' => [$user->id]
                    ];

                    $natt = array_merge($attributes, $adding_user_id);
                    $this->session_data->updateByKey($ud_token, json_encode($natt));

                    return response()->json([
                        'success' => true,
                        'message' => 'Your contact and address has been saved'
                    ]);


                } else {

                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong'
                    ]);

                }
            }
        }

        if (Session::has('my_coupon')) {
            $coupons = Session::get('my_coupon');
            coupon_voucher_verify($coupons['coupon_type'], $coupons['coupon_code']);
        }


    }

    public function getDeliveryCharge(Request $request)
    {
        $paymentsetting = $this->paymentsetting->getAll();
        $paymentsetting = $paymentsetting->first();

        $self_token = $request->header('Self-Token');
        $pm_token = "paymethod_" . $self_token;


        $pm = $this->session_data->getFirstByKey($pm_token);
        $pm = ($pm->session_data ?? false) ? json_decode($pm->session_data, true) : null;
        //dd($pm_token);
//        return $pm['total_amount'];
        $charge_for_amount = 0;
        $deliverycharge = 0;
        $total_amount = $pm['total_amount'];

        $district = strtolower($request->district);

        if ($district == 'dhaka') {

            if ($total_amount <= $paymentsetting->decidable_amount) {

                $deliverycharge = $paymentsetting->inside_dhaka_fee;
                $charge_for_amount = $paymentsetting->decidable_amount;

            } else if ($total_amount <= $paymentsetting->decidable_amount_od) {

                $deliverycharge = $paymentsetting->inside_dhaka_od;
                $charge_for_amount = $paymentsetting->decidable_amount_od;
            }

            if ($paymentsetting->inside_dhaka_fee == $paymentsetting->inside_dhaka_od) {
                $charge_for_amount = $paymentsetting->decidable_amount_od;
            }


        } elseif ($district != "") {

            if ($total_amount <= $paymentsetting->decidable_amount) {

                $deliverycharge = $paymentsetting->outside_dhaka_fee;
                $charge_for_amount = $paymentsetting->decidable_amount;

            } else if ($total_amount <= $paymentsetting->decidable_amount_od) {

                $deliverycharge = $paymentsetting->outside_dhaka_od;
                $charge_for_amount = $paymentsetting->decidable_amount_od;
            }

            if ($paymentsetting->outside_dhaka_fee == $paymentsetting->outside_dhaka_od) {
                $charge_for_amount = $paymentsetting->decidable_amount_od;
            }

        }


        return response()->json(compact('deliverycharge', 'total_amount', 'charge_for_amount'));
    }

    public function storePaymentMethod(Request $request)
    {

        //return response()->json($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'terms_check' => 'required|accepted',
                'payment_method' => 'required'
            ], [
                'terms_check.required' => 'You must agree with terms',
                'payment_method.required' => 'You must select a payment method'
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);

        } else {

            //dd($attributes);

            $self_token = $request->header('Self-Token');
            $cart_token = "cart_" . $self_token;
            $pm_token = "paymethod_" . $self_token;
            $ud_token = "user_details" . $self_token;

            $session_data = $this->session_data->getFirstByKey($cart_token);
            $cart = ($session_data->session_data ?? false) ? json_decode($session_data->session_data, true) : null;

            $ud_session = $this->session_data->getFirstByKey($ud_token);
            $ud = ($ud_session->session_data ?? false) ? json_decode($ud_session->session_data, true) : null;


            // re-calculate price
            $totalprice = 0;
            if (is_array($cart['items'])) {
                foreach ($cart['items'] as $item) {

                    $totalprice += ($item['qty'] ?? 0) * ($item['purchaseprice'] ?? 0);
                }
            }

            $checkDisocuntVal = $this->session_data->getFirstByKey($pm_token)->session_data ?? false;

            $discount = json_decode($checkDisocuntVal, true)['discount'] ?? 0;
            $discountType = json_decode($checkDisocuntVal, true)['discount_type'] ?? false;
            $grandTotal = $discountType == 'Percentage' ? $totalprice - (($totalprice * $discount) / 100) : $totalprice - $discount;
            $attributes = [
                'currency' => 'BDT',
                'total_amount' => $totalprice,
                'discount' => ($discount ?? 0),
                'discount_type' => $discountType,
                'deliveryfee' => ($ud['deliveryfee'] ?? 0),
//                'grand_total' => ($totalprice - ($discount??0)) + ($ud['deliveryfee']??0),
                'grand_total' => $grandTotal + ($ud['deliveryfee'] ?? 0),
                'payment_method' => $request->payment_method,
                'terms_check' => $request->terms_check,
            ];

            $this->session_data->updateByKey($pm_token, json_encode($attributes));
            //return $attributes;
            try {
                return response()->json([
                    'success' => true,
                    'message' => 'Your preferred payment method has been selected successfully'
                ]);
            } catch (\Illuminate\Database\QueryException $ex) {
                return response()->json([
                    'success' => false
                ]);
            }
        }

    }

    public function orderDetails(Request $request)
    {

        $order_random = $request->order_random;
        $secret_key = $request->secret_key;
        $order_masters = null;
        $order_details = [];
        $db_orders = $this->order_master->self()->where('order_random', $order_random)->where('secret_key', $secret_key)->first();
        if ($db_orders) {
            $order_masters = [
                'order_id' => $db_orders->id,
                'customer_name' => $db_orders->customer_name,
                'phone' => $db_orders->phone,
                'emergency_phone' => $db_orders->emergency_phone,
                'address' => $db_orders->address,
                'different_address' => $db_orders->different_address,
                'notes' => $db_orders->notes,
                'email' => $db_orders->email,
                'order_date' => $db_orders->order_date,
                'payment_method' => $db_orders->payment_method,
                'payment_term_status' => $db_orders->payment_term_status,
                'order_status' => $db_orders->order_status,
                'params' => $db_orders->params,
                'delivery_date' => $db_orders->delivery_date,
                'currency' => $db_orders->currency,
                'delivery_fee' => $db_orders->delivery_fee,
                'grand_total' => $db_orders->grand_total,
                'total_amount' => $db_orders->total_amount,
                'amount_paid' => $db_orders->amount_paid,
                'coupon_type' => $db_orders->coupon_type,
                'coupon_code' => $db_orders->coupon_code,
                'coupon_discount' => $db_orders->coupon_discount,
                'division' => $db_orders->division,
                'district' => $db_orders->district,
                'thana' => $db_orders->thana,
                'trans_id' => $db_orders->trans_id,
                'order_date' => Carbon::parse($db_orders->created_at)->format('Y-m-d H:i')
            ];
        }

        $db_order_details_by_random = $this->order_details->self()->where('order_random', $order_random)->where('secret_key', $secret_key)->get();


        foreach ($db_order_details_by_random as $order) {

            $product = $order->product;

            $db_product = $this->product->self()->where('id', $order->product_id)->with('firstImage')->first();
            $item_jeson = json_decode($order->item_jeson);
            $variation_info = $item_jeson->variation_info ?? false;
            $main_inmage = $order->firstImage->icon_size_directory ?? null;
            $img = $variation_info ? (\App\Models\Image::where('id', $variation_info->variation_image)->first()->full_size_directory ?? null) : false;
            $img = $img ? $img : $main_inmage;

            $order_details[] = [
                //'id' => $order->id,
                // 'user_id' => $order->user_id,
                // 'order_random'=> $order->order_random,
                'product_id' => $order->product_id,
                'seo_url' => $order->product->seo_url ?? '',
                'name' => $product->title ?? '',
                'sub_title' => $product->sub_title ?? '',
                'product_code' => $order->product_code,
                'product_category' => $product->category,
                'qty' => $order->qty,
                'order_date' => $order->order_date,
                'image_url' => $img ?? null,
                'local_selling_price' => $order->local_selling_price,
                'local_purchase_price' => $order->local_purchase_price,
                'total_purchase_price' => $order->local_purchase_price * $order->qty,
                'delivery_charge' => $order->delivery_charge,
                'discount' => $order->discount,
                'is_dp' => $order->is_dp,
                'is_flash' => $order->is_flash,
                'flash_id' => $order->flash_id,
                'flash_discount' => $order->flash_discount,
                'item_code' => $order->item_code,
                'color_type' => $order->color_type,
                'size_color_id' => $order->size_color_id,
                'color' => $order->color,
                'size' => $order->size,
                'item_jeson' => json_decode($order->item_jeson),
                'od_status' => $order->od_status,
                'is_active' => $order->is_active,
                'created_at' => Carbon::parse($order->created_at)->format('Y-m-d H:i')

            ];
        }
        return response()->json(compact('order_details', 'order_masters'));
    }

    public function trackOrder(Request $request)
    {
        $phone = $request->phone;
        $id = $request->id;

        if ($phone && $id) {
            $db_order_master = $this->order_master->getByAnyTwoValue('phone', $phone, 'id', $id)->first();
            $order_details = $db_order_master->orderdetails ?? null;
            $order_masters = $db_order_master ? [
                'payment_term_status' => $db_order_master->payment_term_status,
                'order_status' => $db_order_master->order_status,
                'order_date' => Carbon::parse($db_order_master->order_date)->format('Y-m-d'),
                'customer_name' => $db_order_master->customer_name,
                'order_random' => $db_order_master->order_random,
                'order_key' => $db_order_master->secret_key

            ] : [];

            $success = (boolean)$db_order_master;

            return response()->json(compact('success', 'order_masters', 'order_details'));
        }

    }


    public function couponApply(Request $request)
    {
        //dd($request->all());

//        $coupon_code = $request->get('coupon');
        $coupon_code = $request->coupon;
//        dd($request->all());
        $coupon = Coupon::where('coupon_code', $coupon_code)
            ->where('start_date', '<=', Carbon::now('Asia/Dhaka'))
            ->where('end_date', '>=', Carbon::now('Asia/Dhaka'))
            ->where('is_active', 1)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'coupon' => $coupon_code,
                'message' => 'Coupon Code Doesnt Exist',
                'data' => false,
            ]);
        }

        //end &start

        //order master count & match with $coupon->usd_limit

        $used = OrdersMaster::where('coupon_code', $coupon_code)->count();

        if ($used >= $coupon->used_limit) {

            return response()->json([
                'success' => false,
                'message' => 'Coupon Usage Limit has been reached',
                'data' => false,
            ]);

        }


        $self_token = $request->header('Self-Token');
        $coupon_token = "coupon_" . $self_token;

        $store = $this->session_data->updateByKey($coupon_token, $coupon_code);

        $exord_token = "existing_order_id" . $self_token;
        $update_order_id = $this->session_data->updateByKey($exord_token, json_encode(null));


        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'data' => $coupon,
        ]);


    }

    public function addCartMulti(Request $request)
    {

        $added = 0;

        if (count($request->products) > 0) {

            foreach ($request->products as $item) {
                // $request->main_pid = $item['main_pid']
            }
        }

        // $products =[

        // ];
        return response()->json([
            'success' => (boolean)$added,
            'message' => $added ? "Successfully added to cart" : "Fail to add",
            'addedCount' => $added,
            'totalCount' => count($$request->products ?? [])
        ]);
    }


    public function setPayingAmount(Request $request)
    {
        $self_token = $request->header('Self-Token');
        $prebooking_min_token = "prebooking_min" . $self_token;
        $session_data = $this->session_data->getFirstByKey($prebooking_min_token);
        $prebooking_min = (($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null);

        $pm_token = "paymethod_" . $self_token;
        $pm_session = $this->session_data->getFirstByKey($pm_token);
        $pm = ($pm_session->session_data ?? false) ? json_decode($pm_session->session_data) : null;

        $paying_amount = $request->payingAmount;

        if ($prebooking_min > $paying_amount || $paying_amount > $pm->grand_total) {
            $paying_amount = $pm->grand_total;
        }

        $paying_amount_token = "paying_amount_" . $self_token;
        $this->session_data->updateByKey($paying_amount_token, json_encode($paying_amount));

        return response()->json([
            'success' => true,
            'message' => "paying amount - " . $request->payingAmount
        ]);
    }


    public function countNotification(Request $request)
    {
        $self_token = $request->header('Self-Token');
        $compareCount = 0;
        $wishCount = 0;
        $cartCount = 0;


        if ($self_token != null && $self_token != "") {

            $self_compare_key = "compare_" . $self_token;
            $existing_compare_session = $this->session_data->getFirstByKey($self_compare_key);
            $compareCount = $existing_compare_session ? count(json_decode($existing_compare_session->session_data, true)) : 0;

            if (auth()->check()) {
                $self_wishlist_key = "wishlist_" . auth()->guard('api')->id();
                $existing_wish_session = $this->session_data->getFirstByKey($self_wishlist_key);
                $wishCount = $existing_wish_session ? count(json_decode($existing_wish_session->session_data, true)) : 0;
            }

            $cart_token = "cart_" . $self_token;
            $session_data = $this->session_data->getFirstByKey($cart_token);
            $cart = ($session_data->session_data ?? false) ? json_decode($session_data->session_data, true) : null;
            $cartCount = $cart ? count($cart['items']) : 0;

        }


        return response()->json(compact('compareCount', 'wishCount', 'cartCount'));
    }


    public function getPaymentGateway()
    {
        $paymentSetting = PaymentSetting::where('id', 1)->first();
        $get = function ($columnName) use ($paymentSetting) {
            return $paymentSetting->$columnName ?? false;
        };


        //dd($paymentSetting);

        return response()->json([
            'bkash' => [
                'active' => $get('bkash_active') == 1 ? true : false,
                'logo' => $get('image_bkash'),
            ],
            'nagad' => [
                'active' => $get('nagad_active') == 1 ? true : false,
                'logo' => $get('image_nagad')
            ],
            'mobile_banking' => [
                'active' => $get('mobilebanking_active') == 1 ? true : false,
                'logo' => $get('image_mobilebanking')
            ],
            'cash_on_delivery' => [
                'active' => $get('cashondelivery_active') == 1 ? true : false,
                'logo' => $get('image_cashondelivery')
            ],
        ]);
    }


    // One Click Buy

    public function oneClickBuyNow(Request $request)
    {

        $attr = [
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'product_id' => $request->product_id,
            'order_status' => 'pending',
        ];
        $done = Oneclickbuy::create($attr);
        if ($done) {
            return response()->json([
                'status' => true,
                'data' => $request->all(),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => false,
            ]);
        }

    }//End

}
