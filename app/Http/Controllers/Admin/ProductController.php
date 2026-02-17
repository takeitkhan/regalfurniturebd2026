<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Exports\ProductsExport;
use App\Models\ActivityLog;
use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Imports\StockImport;
use App\Models\Product;
use App\Models\Productpricecombination;
use App\Models\ProductSetFabric;
use App\Models\ProductSetInfo;
use App\Models\ProductSetProduct;
use App\Models\ProductVariation;
use App\Models\ProductVideos;
use App\Models\VariationGroup;
use App\Pcombinationdata;
use App\Repositories\Attribute\AttributeInterface;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Emi\EmiInterface;
use App\Repositories\Media\MediaInterface;
use App\Repositories\Pcombinationdata\PcombinationdataInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\ProductAttributesData\ProductAttributesDataInterface;
use App\Repositories\ProductCategories\ProductCategoriesInterface;
use App\Repositories\ProductImages\ProductImagesInterface;
use App\Repositories\Productpricecombination\ProductpricecombinationInterface;
use App\Repositories\ProductSet\ProductSetInterface;
use App\Repositories\RelatedProducts\RelatedProductsInterface;
use App\Repositories\Term\TermInterface;
use App\Repositories\Variation\VariationInterface;
use DB;
use Form;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class ProductController extends Controller
{
    /**
     * @var ProductInterface
     */
    private $product;
    /**
     * @var AttributeInterface
     */
    private $attribute;
    /**
     * @var MediaInterface
     */
    private $media;
    /**
     * @var VariationInterface
     */
    private $variation;
    /**
     * @var TermInterface
     */
    private $term;
    /**
     * @var Excel
     */
    private $excel;
    /**
     * @var RelatedProductsInterface
     */
    private $related_products;
    /**
     * @var ProductCategoriesInterface
     */
    private $product_categories;
    /**
     * @var ProductImagesInterface
     */
    private $product_images;
    /**
     * @var ProductAttributesDataInterface
     */
    private $product_attributes_data;
    /**
     * @var ProductpricecombinationInterface
     */
    private $products_price_combination;
    /**
     * @var PcombinationdataInterface
     */
    private $pcombinationdata;
    /**
     * @var BankInterface
     */
    private $bank;
    /**
     * @var EmiInterface
     */
    private $emi;

    private $product_set;

    /**
     * ProductController constructor.
     * @param  ProductInterface  $product
     * @param  AttributeInterface  $attribute
     * @param  MediaInterface  $media
     * @param  VariationInterface  $variation
     * @param  TermInterface  $term
     * @param  Excel  $excel
     * @param  RelatedProductsInterface  $related_products
     * @param  ProductCategoriesInterface  $product_categories
     * @param  ProductImagesInterface  $product_images
     * @param  ProductAttributesDataInterface  $product_attributes_data
     * @param  ProductpricecombinationInterface  $products_price_combination
     * @param  PcombinationdataInterface  $pcombinationdata
     * @param  BankInterface  $bank
     * @param  EmiInterface  $emi
     */
    public function __construct(
        ProductInterface                 $product,
        AttributeInterface               $attribute,
        MediaInterface                   $media,
        VariationInterface               $variation,
        TermInterface                    $term,
        Excel                            $excel,
        RelatedProductsInterface         $related_products,
        ProductCategoriesInterface       $product_categories,
        ProductImagesInterface           $product_images,
        ProductAttributesDataInterface   $product_attributes_data,
        ProductpricecombinationInterface $products_price_combination,
        PcombinationdataInterface        $pcombinationdata,
        BankInterface                    $bank,
        EmiInterface                     $emi,
        ProductSetInterface              $product_set

    ) {
        $this->product = $product;
        $this->attribute = $attribute;
        $this->media = $media;
        $this->variation = $variation;
        $this->term = $term;
        $this->excel = $excel;
        $this->related_products = $related_products;
        $this->product_categories = $product_categories;
        $this->product_images = $product_images;
        $this->product_attributes_data = $product_attributes_data;
        $this->products_price_combination = $products_price_combination;
        $this->pcombinationdata = $pcombinationdata;
        $this->bank = $bank;
        $this->emi = $emi;
        $this->product_set = $product_set;

    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function products(Request $request)
    {
        //dd($request);
        $default = [];
        if (!empty($request)) {
            $column = $request->get('column');
            $default = [
                'column' => $column,
                'search_key' => $request->get('search_key')
            ];
            $products = $this->product->getAllByRole($default);

            return view('product.products', compact('products'))->with(['products' => $products]);
        } else {
            $products = $this->product->getAllByRole($default);
            return view('product.products', compact('products'))->with(['products' => $products]);
        }
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function products_express_delivery(Request $request)
    {
        //dd($request);
        $default = null;
        if (!empty($request)) {
            $column = $request->get('column');
            $default = [
                'column' => $column,
                'search_key' => clean($request->get('search_key'))
            ];
            $where = [
                'express_delivery' => 'on'
            ];

            $products = $this->product->getAllWhereByRole($default, $where);
            return view('product.products', compact('products'))->with(['products' => $products]);
        } else {
            $products = $this->product->getAllWhereByRole($default);
            return view('product.products', compact('products'))->with(['products' => $products]);
        }
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function products_enable_comment(Request $request)
    {
        //dd($request);
        $default = null;
        if (!empty($request)) {
            $column = $request->get('column');
            $default = [
                'column' => $column,
                'search_key' => clean($request->get('search_key'))
            ];
            $where = [
                'enable_comment' => 'on'
            ];

            $products = $this->product->getAllWhereByRole($default, $where);
            return view('product.products', compact('products'))->with(['products' => $products]);
        } else {
            $products = $this->product->getAllWhereByRole($default);
            return view('product.products', compact('products'))->with(['products' => $products]);
        }
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function products_enable_review(Request $request)
    {
        //dd($request);
        $default = null;
        if (!empty($request)) {
            $column = $request->get('column');
            $default = [
                'column' => $column,
                'search_key' => clean($request->get('search_key'))
            ];
            $where = [
                'enable_review' => 'on'
            ];

            $products = $this->product->getAllWhereByRole($default, $where);
            return view('product.products', compact('products'))->with(['products' => $products]);
        } else {
            $products = $this->product->getAllWhereByRole($default);
            return view('product.products', compact('products'))->with(['products' => $products]);
        }
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function products_new_arrival(Request $request)
    {
        //dd($request);
        $default = null;
        if (!empty($request)) {
            $column = $request->get('column');
            $default = [
                'column' => $column,
                'search_key' => clean($request->get('search_key'))
            ];
            $where = [
                'new_arrival' => 'on'
            ];

            $products = $this->product->getAllWhereByRole($default, $where);
            return view('product.products', compact('products'))->with(['products' => $products]);
        } else {
            $products = $this->product->getAllWhereByRole($default);
            return view('product.products', compact('products'))->with(['products' => $products]);
        }
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function products_best_selling(Request $request)
    {
        //dd($request);
        $default = null;
        if (!empty($request)) {
            $column = $request->get('column');
            $default = [
                'column' => $column,
                'search_key' => clean($request->get('search_key'))
            ];
            $where = [
                'best_selling' => 'on'
            ];

            $products = $this->product->getAllWhereByRole($default, $where);
            return view('product.products', compact('products'))->with(['products' => $products]);
        } else {
            $products = $this->product->getAllWhereByRole($default);
            return view('product.products', compact('products'))->with(['products' => $products]);
        }
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function products_recommended(Request $request)
    {
        //dd($request);
        $default = null;
        if (!empty($request)) {
            $column = $request->get('column');
            $default = [
                'column' => $column,
                'search_key' => clean($request->get('search_key'))
            ];
            $where = [
                'recommended' => 'on'
            ];

            $products = $this->product->getAllWhereByRole($default, $where);
            return view('product.products', compact('products'))->with(['products' => $products]);
        } else {
            $products = $this->product->getAllWhereByRole($default);
            return view('product.products', compact('products'))->with(['products' => $products]);
        }
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function products_disable_buy(Request $request)
    {
        //dd($request);
        $default = null;
        if (!empty($request)) {
            $column = $request->get('column');
            $default = [
                'column' => $column,
                'search_key' => clean($request->get('search_key'))
            ];
            $where = [
                'disable_buy' => 'on'
            ];

            $products = $this->product->getAllWhereByRole($default, $where);
            return view('product.products', compact('products'))->with(['products' => $products]);
        } else {
            $products = $this->product->getAllWhereByRole($default);
            return view('product.products', compact('products'))->with(['products' => $products]);
        }
    }

    /**
     * @param $id
     * @return $this
     */
    public function add_product()
    {
        $medias = $this->media->getAll();
        $variations = $this->variation->getAll();

        $terms = $this->term->getAll()->toArray();

        $default = [
            'type' => 'category',
            'limit' => 250,
            'offset' => 0
        ];
        $cats = $this->get_product_categories($default);
        $categories = $cats->toArray();

        return view('product.form')->with(['medias' => $medias, 'variations' => $variations, 'terms' => $terms, 'categories' => $categories]);
    }

    public function get_product_categories(array $options = [])
    {
        $default = [
            'type' => 'category',
            'limit' => 10,
            'offset' => 0
        ];

        $optionss = array_merge($default, $options);
        return $this->term->get_terms_by_options($optionss);
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit_product($id)
    {
        if (isset($id)) {
            $product = $this->product->getById($id);
            if (auth()->user()->isVendor() && (auth()->user()->id == $product->user_id)) {
                $premision = true;
            } elseif (auth()->user()->isProduct()) {
                $premision = true;
            } else {
                $premision = false;
            }


            if ($premision) {
                $medias = $this->media->self()->orderBy('id', 'DESC')->limit(10)->get();
                $variations = $this->variation->getAll();
                $terms = $this->term->getAll()->toArray();
                $variationGroups = VariationGroup::all();
                $productVariations = ProductVariation::where('product_id', $id)->get();
                $variation_groups = null;
                foreach ($variationGroups as $variationGroup) {
                    $variation_groups[$variationGroup->id] = $variationGroup->title;
                }

                $default = [
                    'type' => 'category',
                    'limit' => 500,
                    'offset' => 0
                ];
                $cats = $this->get_product_categories($default);
                $categories = $cats->toArray();

                // dd($product);

                return view('product.form')
                    ->with(['product' => $product, 'medias' => $medias, 'productVariations' => $productVariations, 'variation_groups' => $variation_groups, 'variations' => $variations, 'terms' => $terms, 'categories' => $categories]);

            } else {

                return redirect('products');
            }


        }
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return $this|RedirectResponse
     */
    public function product_update_save(Request $request, $id)
    {
        $d = $this->product->getById($id);
        //dd($request->get('p_request'));
        // validate
        // read more on validation at
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'seo_url' => 'required',
                'description' => 'required',
                'local' => 'required',
                'local_selling_price' => 'required',
                'description' => 'required',
                'lang' => 'required'
            ]
        );


        // process the login
        if ($validator->fails()) {
            return redirect('products')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => !empty($request->get('user_id')) ? $request->get('user_id') : null,
                'title' => !empty($request->get('title')) ? $request->get('title') : null,
                'sub_title' => !empty($request->get('sub_title')) ? $request->get('sub_title') : null,
                'seo_url' => !empty($request->get('seo_url')) ? $request->get('seo_url') : null,
                'description' => !empty($request->get('description')) ? $request->get('description') : null,
                'product_code' => !empty($request->get('product_code')) ? $request->get('product_code') : null,
                'sku' => !empty($request->get('sku')) ? $request->get('sku') : null,
                'qty' => !empty($request->get('qty')) ? $request->get('qty') : null,
                'local' => !empty($request->get('local')) ? $request->get('local') : null,
                'local_selling_price' => !empty($request->get('local_selling_price')) ? $request->get('local_selling_price') : null,
                'local_discount' => !empty($request->get('local_discount')) ? $request->get('local_discount') : null,
                'dp_price' => !empty($request->get('dp_price')) ? $request->get('dp_price') : null,
                'intl' => !empty($request->get('intl')) ? $request->get('intl') : null,
                'intl_selling_price' => !empty($request->get('intl_selling_price')) ? $request->get('intl_selling_price') : null,
                'intl_discount' => !empty($request->get('intl_discount')) ? $request->get('intl_discount') : null,
                'pre_booking_discount' => !empty($request->get('pre_booking_discount')) ? $request->get('pre_booking_discount') : null,
                'stock_status' => !empty($request->get('stock_status')) ? $request->get('stock_status') : null,
                'delivery_area' => !empty($request->get('delivery_area')) ? $request->get('delivery_area') : null,
                'delivery_charge' => !empty($request->get('delivery_charge')) ? $request->get('delivery_charge') : null,
                'delivery_time' => !empty($request->get('delivery_time')) ? $request->get('delivery_time') : null,
                'pro_warranty' => !empty($request->get('pro_warranty')) ? $request->get('pro_warranty') : null,
                'short_description' => !empty($request->get('short_description')) ? $request->get('short_description') : null,
                'purchase_note' => !empty($request->get('purchase_note')) ? $request->get('purchase_note') : null,
                'tags' => !empty($request->get('tags')) ? $request->get('tags') : null,
                'offer_details' => !empty($request->get('offer_details')) ? $request->get('offer_details') : null,
                'parent_id' => !empty($request->get('parent_id')) ? $request->get('parent_id') : null,
                'offer_start_date' => !empty($request->get('offer_start_date')) ? $request->get('offer_start_date') : null,
                'offer_end_date' => !empty($request->get('offer_end_date')) ? $request->get('offer_end_date') : null,
                'is_sticky' => !empty($request->get('is_sticky')) ? $request->get('is_sticky') : null,

                'position' => !empty($request->get('position')) ? $request->get('position') : null,
                'new_arrival' => !empty($request->get('new_arrival')) ? 'on' : 'off',
                'new_arrival_serial' => !empty($request->get('new_arrival_serial')) ? $request->get('new_arrival_serial') : 0,
                'express_delivery' => !empty($request->get('express_delivery')) ? 'on' : 'off',
                'enable_review' => !empty($request->get('enable_review')) ? 'on' : 'off',
                'enable_variation' => !empty($request->get('enable_variation')) ? 'on' : 'off',
                'enable_comment' => !empty($request->get('enable_comment')) ? 'on' : 'off',
                'best_selling' => !empty($request->get('best_selling')) ? 'on' : 'off',
                'flash_sale' => !empty($request->get('flash_sale')) ? 'on' : 'off',
                'flash_time' => !empty($request->get('flash_time')) ? $request->get('flash_time') : null,
                'recommended' => !empty($request->get('recommended')) ? 'on' : 'off',
                'recommended_serial' => !empty($request->get('recommended_serial')) ? $request->get('recommended_serial') : 0,
                'multiple_pricing' => !empty($request->get('multiple_pricing')) ? 'on' : 'off',
                'emi_available' => !empty($request->get('emi_available')) ? 'on' : 'off',
                'disable_buy' => !empty($request->get('disable_buy')) ? 'on' : 'off',
                'order_by_phone' => !empty($request->get('order_by_phone')) ? 'on' : 'off',
                'enable_timespan' => $request->enable_timespan == 1 ? 1 : 0,
                'cash_on_delivery' => $request->cash_on_delivery == 1 ? 1 : 0,
                'pre_booking' => $request->pre_booking == 1 ? 1 : 0,
                'seo_h1' => !empty($request->get('seo_h1')) ? $request->get('seo_h1') : null,
                'seo_h2' => !empty($request->get('seo_h2')) ? $request->get('seo_h2') : null,
                'seo_title' => !empty($request->get('seo_title')) ? $request->get('seo_title') : null,
                'seo_description' => !empty($request->get('seo_description')) ? $request->get('seo_description') : null,
                'seo_keywords' => !empty($request->get('seo_keywords')) ? $request->get('seo_keywords') : null,

                'lang' => !empty($request->get('lang')) ? $request->get('lang') : null,
                'is_active' => $request->is_active ?? 0
            ];


            // dd($request->all());

            try {
                $product = $this->product->update($d->id, $attributes);
                try {
                    $old_values = array_intersect_key($d->toArray(), $attributes);
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'product_update',
                        'entity_type' => 'product',
                        'entity_id' => $d->id,
                        'old_values' => $old_values,
                        'new_values' => $attributes,
                        'note' => $request->get('note'),
                        'ip' => $request->ip(),
                        'url' => $request->fullUrl()
                    ]);
                } catch (\Exception $e) {
                    // Logging failure should not break product update
                }
                // dd($product);
                Cache::forget('product-simple-info-'.$product->seo_url);
                Cache::forget('product-product-info-'.$product->seo_url);
                Cache::forget('product-images-videos-degrees-'.$product->seo_url);

                Cache::forget('product-same-cat-products-'.$product->seo_url);
                Cache::forget('product-similar-product'.$product->seo_url);
                Cache::forget('product-goes-well-products-'.$product->seo_url);
                Cache::forget('product-other-see-products-'.$product->seo_url);

                Cache::forget('home-prebookings');
                Cache::forget('home-offers');
                Cache::forget('home-new-arrivals');
                Cache::forget('common-top-offers');

                // dd($this->product->getProductCategories($d->id));

                return redirect('edit_product/'.$d->id.'?p='.$request->get('p_request'))->with('success', 'Successfully save changed');
            } catch (QueryException $ex) {
                // dd($ex->getMessage());

                return redirect('products')->withErrors($ex->getMessage());
            }
        }
    }

    public function product_update_other_information(Request $request, $id)
    {
        //dd($request);
        $d = $this->product->getById($id);
        $attributes = [
            'delivery_area' => !empty($request->get('delivery_area')) ? $request->get('delivery_area') : null,
            'delivery_charge' => !empty($request->get('delivery_charge')) ? $request->get('delivery_charge') : null,
            'delivery_time' => !empty($request->get('delivery_time')) ? $request->get('delivery_time') : null,
            'pro_warranty' => !empty($request->get('pro_warranty')) ? $request->get('pro_warranty') : null,
            'tags' => !empty($request->get('tags')) ? $request->get('tags') : null,
            'offer_details' => !empty($request->get('offer_details')) ? $request->get('offer_details') : null,
            'offer_start_date' => !empty($request->get('offer_start_date')) ? $request->get('offer_start_date') : null,
            'offer_end_date' => !empty($request->get('offer_end_date')) ? $request->get('offer_end_date') : null,
            'lang' => !empty($request->get('lang')) ? $request->get('lang') : null,
            'careInfo' => !empty($request->get('careInfo')) ? $request->get('careInfo') : null
        ];
        // dd($attributes);

        try {
            $product = $this->product->update($d->id, $attributes);
            try {
                $old_values = array_intersect_key($d->toArray(), $attributes);
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'product_update_other_info',
                    'entity_type' => 'product',
                    'entity_id' => $d->id,
                    'old_values' => $old_values,
                    'new_values' => $attributes,
                    'note' => $request->get('note'),
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl()
                ]);
            } catch (\Exception $e) {
                // Logging failure should not break product update
            }

            Cache::forget('product-simple-info-'.$product->seo_url);
            Cache::forget('product-product-info-'.$product->seo_url);
            Cache::forget('product-images-videos-degrees-'.$product->seo_url);
            Cache::forget('common-top-offers');


            Cache::forget('product-same-cat-products-'.$product->seo_url);
            Cache::forget('product-similar-product'.$product->seo_url);
            Cache::forget('product-goes-well-products-'.$product->seo_url);
            Cache::forget('product-other-see-products-'.$product->seo_url);


            //dd($product);
            return redirect()->back()->with('success', 'Successfully save changed');
        } catch (QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }
    }

    // dd($datas);

    public function attribute_based_information(Request $request, $id)
    {
        // dd($request);


        $atts_for_update = [];

        foreach ($request->get('attr_save') as $key => $row) {
            if (!empty($row['data_save_id']) && isset($row['value'])) {
                if (is_array($row['value'])) {
                    $value = implode(',', $row['value']);
                } else {
                    $value = $row['value'];
                }

                $update_attributes = [
                    'value' => $value,
                    'attgroup_id' => $row['attgroup_id'],
                    'key' => $row['key'],
                    'user_id' => $row['user_id'],
                    'main_pid' => $row['main_pid'],
                    'default_value' => $row['default_value'],
                    'attribute_id' => $row['attribute_id'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at']
                ];

                try {
                    $done = $this->product_attributes_data->updateByWhere(['id' => $row['data_save_id']], $update_attributes);

                    //$done = \App\ProductAttributesData::updateOrCreate(['id' => $row['data_save_id']], $update_attributes);
                } catch (QueryException $ex) {
                    return redirect()->back()->withErrors($ex->getMessage());
                }
            } else {
                // dump($row);
                if (isset($row['value'])) {
                    if (is_array($row['value'])) {
                        $value = implode(',', $row['value']);
                    } else {
                        // dump($row['value']);
                        $value = $row['value'];
                    }
                } else {
                    $value = null;
                }

                $insert_attributes = [
                    'user_id' => $row['user_id'],
                    'main_pid' => $row['main_pid'],
                    'attgroup_id' => $row['attgroup_id'],
                    'key' => $row['key'],
                    'value' => $value,
                    'default_value' => $row['default_value'],
                    'attribute_id' => $row['attribute_id'],
                    'updated_at' => $row['updated_at']
                ];
                // die;

                try {
                    $done = $this->product_attributes_data->create($insert_attributes);
                } catch (QueryException $ex) {
                    return redirect()->back()->withErrors($ex->getMessage());
                }
            }
        }
        if ($done) {
            return redirect()->back()->with('success', 'Successfully save changed');
        } else {
            return redirect()->back()->withErrors($ex->getMessage());
        }

        // die;

        //dd($atts_for_update);
//        if ($request->get('on_update') == 'Yes') {
//            if ($done) {
//                return redirect()->back()->with('success', 'Successfully save changed');
//            } else {
//                return redirect()->back()->withErrors('Sorry, Something went wrong');
//            }
//        } else {
//
//            if (empty($row['data_save_id'])) {
//
//                if (is_array($row['value'])) {
//                    $value = implode(',', $row['value']);
//                } else {
//                    $value = $row['value'];
//                }
//
//                $datas[] = array(
//                    'user_id' => $row['user_id'],
//                    'main_pid' => $row['main_pid'],
//                    'attgroup_id' => $row['attgroup_id'],
//                    'key' => $row['key'],
//                    'value' => $value,
//                    'default_value' => $row['default_value'],
//                    'attribute_id' => $row['attribute_id'],
//                    'updated_at' => $row['updated_at']
//                );
//                $done = null;
//
//            }
//
//            dump($datas);
//die;
//            if ($datas) {
//                try {
//                    $this->product_attributes_data->insert($datas);
//                    return redirect()->back()->with('success', 'Successfully save changed');
//                } catch (\Illuminate\Database\QueryException $ex) {
//                    return redirect()->back()->withErrors($ex->getMessage());
//                }
//            }
//        }
    }

    /**
     * @param  Request  $request
     * @return $this
     * @internal param Request $request
     */
    public function store(Request $request)
    {
        // validate
        // read more on validation at
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'seo_url' => 'required',
                'description' => 'required',
                'local' => 'required',
                'local_selling_price' => 'required',
                'description' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('products')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => !empty($request->get('user_id')) ? $request->get('user_id') : null,
                'title' => !empty($request->get('title')) ? $request->get('title') : null,
                'sub_title' => !empty($request->get('sub_title')) ? $request->get('sub_title') : null,
                'seo_url' => !empty($request->get('seo_url')) ? $request->get('seo_url') : null,
                'description' => !empty($request->get('description')) ? $request->get('description') : null,
                'product_code' => !empty($request->get('product_code')) ? $request->get('product_code') : null,
                'sku' => !empty($request->get('sku')) ? $request->get('sku') : null,
                'qty' => !empty($request->get('qty')) ? $request->get('qty') : null,
                'local' => !empty($request->get('local')) ? $request->get('local') : null,
                'local_selling_price' => !empty($request->get('local_selling_price')) ? $request->get('local_selling_price') : null,
                'local_discount' => !empty($request->get('local_discount')) ? $request->get('local_discount') : null,
                'dp_price' => !empty($request->get('dp_price')) ? $request->get('dp_price') : null,
                'intl' => !empty($request->get('intl')) ? $request->get('intl') : null,
                'intl_selling_price' => !empty($request->get('intl_selling_price')) ? $request->get('intl_selling_price') : null,
                'intl_discount' => !empty($request->get('intl_discount')) ? $request->get('intl_discount') : null,
                'stock_status' => !empty($request->get('stock_status')) ? $request->get('stock_status') : null,
                'delivery_area' => !empty($request->get('delivery_area')) ? $request->get('delivery_area') : null,
                'delivery_charge' => !empty($request->get('delivery_charge')) ? $request->get('delivery_charge') : null,
                'delivery_time' => !empty($request->get('delivery_time')) ? $request->get('delivery_time') : null,
                'pro_warranty' => !empty($request->get('pro_warranty')) ? $request->get('pro_warranty') : null,

                'short_description' => !empty($request->get('short_description')) ? $request->get('short_description') : null,
                'purchase_note' => !empty($request->get('purchase_note')) ? $request->get('purchase_note') : null,
                'tags' => !empty($request->get('tags')) ? $request->get('tags') : null,
                'offer_details' => !empty($request->get('offer_details')) ? $request->get('offer_details') : null,
                'parent_id' => !empty($request->get('parent_id')) ? $request->get('parent_id') : null,
                'offer_start_date' => !empty($request->get('offer_start_date')) ? $request->get('offer_start_date') : null,
                'offer_end_date' => !empty($request->get('offer_end_date')) ? $request->get('offer_end_date') : null,
                'is_sticky' => !empty($request->get('is_sticky')) ? $request->get('is_sticky') : null,

                'position' => !empty($request->get('position')) ? $request->get('position') : null,
                'new_arrival' => !empty($request->get('new_arrival')) ? 'on' : 'off',
                'express_delivery' => !empty($request->get('express_delivery')) ? 'on' : 'off',
                'enable_review' => !empty($request->get('enable_review')) ? 'on' : 'off',
                'enable_variation' => !empty($request->get('enable_variation')) ? 'on' : 'off',
                'enable_comment' => !empty($request->get('enable_comment')) ? 'on' : 'off',
                'best_selling' => !empty($request->get('best_selling')) ? 'on' : 'off',
                'flash_sale' => !empty($request->get('flash_sale')) ? 'on' : 'off',
                'flash_time' => !empty($request->get('flash_time')) ? $request->get('flash_time') : null,
                'recommended' => !empty($request->get('recommended')) ? 'on' : 'off',
                'multiple_pricing' => !empty($request->get('multiple_pricing')) ? 'on' : 'off',
                'emi_available' => !empty($request->get('emi_available')) ? 'on' : 'off',
                'disable_buy' => !empty($request->get('disable_buy')) ? 'on' : 'off',
                'order_by_phone' => !empty($request->get('order_by_phone')) ? 'on' : 'off',
                'enable_timespan' => $request->enable_timespan == 1 ? 1 : 0,

                'seo_h1' => !empty($request->get('seo_h1')) ? $request->get('seo_h1') : null,
                'seo_h2' => !empty($request->get('seo_h2')) ? $request->get('seo_h2') : null,
                'seo_title' => !empty($request->get('seo_title')) ? $request->get('seo_title') : null,
                'seo_description' => !empty($request->get('seo_description')) ? $request->get('seo_description') : null,
                'seo_keywords' => !empty($request->get('seo_keywords')) ? $request->get('seo_keywords') : null,

                'lang' => !empty($request->get('lang')) ? $request->get('lang') : null,
                'is_active' => !empty($request->get('is_active')) ? $request->get('is_active') : 0
            ];

            // dd($attributes);

            try {
                $product = $this->product->create($attributes);
                try {
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'product_create',
                        'entity_type' => 'product',
                        'entity_id' => $product->id,
                        'old_values' => null,
                        'new_values' => $attributes,
                        'note' => $request->get('note'),
                        'ip' => $request->ip(),
                        'url' => $request->fullUrl()
                    ]);
                } catch (\Exception $e) {
                    // Logging failure should not break product create
                }
                Cache::forget('common-top-offers');

                return redirect('edit_product/'.$product->id)->with('success', 'Successfully Added');
            } catch (QueryException $ex) {
                // dd($ex->getMessage());
                $errorCode = $ex->errorInfo[1];
                if ($errorCode == '1062') {
                    return back()->with('failed', $ex->errorInfo[2]);
                } else {
                    return back()->with('failed', 'Something went wrong');
                }
                //return redirect('products')->withErrors($ex->getMessage());
            }
        }
    }

    /**
     * @param $id
     * @return $this|RedirectResponse
     */
    public function destroy($id)
    {
        //dd($id);
        try {
            $this->product->delete($id);
            return redirect('products')->with('success', 'Successfully deleted');
        } catch (QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }
    }

    /**
     * Extra Methods for products
     * All methods in below are custom
     * Developer made methods
     */
    public function get_product_json_data(Request $request)
    {
        $id = (int)$request->get('productid');

        $product = $this->product->getById($id);
        return $product->toJson();
    }

    public function check_if_url_exists(Request $request)
    {
        $seo_url = $request->get('seo_url');
        $product = $this->product->getByAny('seo_url', $seo_url);
        if ($product->first()) {
            $url = $product->first()->seo_url;
            $nu = $url.'-'.date('ms');
            $m = $nu;
        } else {
            $m = $seo_url;
        }
        return response()->json(['url' => $m]);
    }

    public function export_products(Request $request)
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function import_products_view()
    {
        return view('product.import_products_form');
    }

    public function import_products(Request $request)
    {
        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file');
            $data = Excel::import(new ProductsImport, $path);
        }

        return redirect('products')->with('success', 'Successfully imported and updated');
    }

    public function related_products_getter(Request $request)
    {
        $search_key = $request->get('search_key');

        $data = $this->product->get_product_list_by_search_key($search_key);

        $output = '<ul class="dropdown-menu" style="display: block; position: relative;">';
        foreach ($data as $row) {
            $output .= '<li><a data-id="'.$row->id.'" href="javascript:void(0)" id="select_this">'.$row->title.'</a>';
        }
        $output .= '</ul>';

        echo $output;
    }

    public function add_related_products(Request $request)
    {
        if (!empty($request->get('mainpid'))) {
            $attributes = [
                'main_pid' => !empty($request->get('mainpid')) ? $request->get('mainpid') : null,
                'product_id' => !empty($request->get('product_id')) ? $request->get('product_id') : null,
                'user_id' => !empty($request->get('userid')) ? $request->get('userid') : null,
                'title' => !empty($request->get('title')) ? $request->get('title') : null,
                'local_price' => !empty($request->get('local_price')) ? $request->get('local_price') : null,
                'local_discount' => !empty($request->get('local_discount')) ? $request->get('local_discount') : null,
                'int_price' => !empty($request->get('int_price')) ? $request->get('int_price') : null,
                'int_discount' => !empty($request->get('int_discount')) ? $request->get('int_discount') : null,
            ];
            try {
                $created = $this->related_products->create($attributes);

                $new_data = $this->related_products->getById($created->id);

                $html = '<li style="border-bottom: 1px solid #555;">';
                $html .= '<a onclick="return confirm(\'Are you Sure ?\')" href="'.url('delete_relatedproduct/'.$new_data->id).'"><i class="fa fa-times pull-right"></i></a>';
                $html .= '<a href="">'.$new_data->title.'</a>';
                $html .= '</li>';

                return response()->json(['html' => $html, 'success' => 'Successfully Added', 'status' => 1]);
            } catch (QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];
                if ($errorCode == '1062') {
                    echo 0;
                } else {
                    echo 0;
                }
                //return redirect('products')->withErrors($ex->getMessage());
            }
        } else {
            echo 0;
        }
    }

    public function add_product_categories(Request $request)
    {
        if (!empty($request->get('mainpid'))) {
            $attributes = [
                'user_id' => !empty($request->get('userid')) ? $request->get('userid') : null,
                'main_pid' => !empty($request->get('mainpid')) ? $request->get('mainpid') : null,
                'term_id' => !empty($request->get('term_id')) ? $request->get('term_id') : null,
                'term_name' => !empty($request->get('term_name')) ? $request->get('term_name') : null,
                'term_attgroup' => !empty($request->get('term_attgroup')) ? $request->get('term_attgroup') : null
            ];
            try {
                $created = $this->product_categories->create($attributes);

                $new_data = $this->product_categories->getById($created->id);

                $html = '<tr>';
                $html .= '<td>';
                $html .= $new_data->term_name;
                $html .= '</td>';
                $html .= '<td>';
                $html .= '<a id="set_attgroup" data-id="'.$new_data->id.'" data-value="1" data-type="set" href="javascript:void(0)" class="btn btn-success btn-xs pull-right">Set</a>';
                $html .= '</td>';

                $html .= '<td class="text-center">';
                $html .= '<a onclick="return confirm(\'Are you Sure ?\')" href="'.url('delete_productcategory/'.$new_data->id).'"><i class="fa fa-times"></i></a>';
                $html .= '</td>';
                $html .= '</tr>';

                return response()->json(['html' => $html, 'success' => 'Successfully Added', 'status' => 1]);
            } catch (QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];
                if ($errorCode == '1062') {
                    echo 0;
                } else {
                    echo 0;
                }
                //return redirect('products')->withErrors($ex->getMessage());
            }
        } else {
            echo 0;
        }
    }

    public function add_product_images(Request $request)
    {
        if (!empty($request->get('mainpid'))) {
            $attributes = [
                'user_id' => !empty($request->get('userid')) ? $request->get('userid') : null,
                'main_pid' => !empty($request->get('mainpid')) ? $request->get('mainpid') : null,
                'media_id' => !empty($request->get('media_id')) ? $request->get('media_id') : null,
                'filename' => !empty($request->get('filename')) ? $request->get('filename') : null,
                'full_size_directory' => !empty($request->get('fullsize')) ? $request->get('fullsize') : null,
                'icon_size_directory' => !empty($request->get('iconsize')) ? $request->get('iconsize') : null,
                'is_main_image' => 0,
                'is_active' => 1,
            ];
            try {
                $this->product_images->create($attributes);
                $product = $this->product->getById($request->mainpid);
                Cache::forget('product-images-videos-degrees-'.$product->seo_url);


                return response()->json(['success' => 'Successfully Added', 'status' => 1]);
            } catch (QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];
                if ($errorCode == '1062') {
                    echo 0;
                } else {
                    echo 0;
                }
                //return redirect('products')->withErrors($ex->getMessage());
            }
        } else {
            echo 0;
        }
    }

    public function add_product_price_combination(Request $request)
    {
        if (!empty($request->get('mainpid'))) {
            $attributes = [
                'user_id' => !empty($request->get('userid')) ? $request->get('userid') : null,
                'main_pid' => !empty($request->get('mainpid')) ? $request->get('mainpid') : null,
                'type' => !empty($request->get('type')) ? $request->get('type') : null,
                'value' => !empty($request->get('color_code')) ? $request->get('color_code') : null
            ];
            //dd($attributes);
            try {
                $created = $this->products_price_combination->create($attributes);

                $default = [
                    'type' => $created->type,
                    'id' => $created->id
                ];

                $new_data = $this->products_price_combination->getByColumns($default)->first();

                //dd($new_data);
                if ($new_data->type == 'color') {
                    $html = '<li style="position: relative;">';
                    $html .= '<a href="'.url('delete_productpricecombination/'.$new_data->id).'" class="cross_btn" onclick="return confirm("\'Are you sure?\'")">x</a>';
                    $html .= '<a style="color: '.'#'.$new_data->value.'"><i class="fa fa-square"></i></a></li>';
                } else {
                    $html = '<li><a href="javascript:void(0);">'.$new_data->value.'</a></li>';
                }

                return response()->json(['html' => $html, 'success' => 'Successfully Added', 'status' => 1]);
            } catch (QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];
                if ($errorCode == '1062') {
                    echo 0;
                } else {
                    echo 0;
                }
                //return redirect('products')->withErrors($ex->getMessage());
            }
        } else {
            echo 0;
        }
    }

    public function get_colors_sizes(Request $request)
    {
        $id = $request->get('main_pid');

        $options = $this->products_price_combination->getByAny('main_pid', $id);

        //dd($options->value);

        $html = '<tr>';
        $html .= Form::open(['url' => '', 'method' => 'post', 'value' => 'PATCH', 'files' => true, 'autocomplete' => 'off']);

        $html .= Form::hidden('userid', (!empty(\Auth::user()->id) ? \Auth::user()->id : null), ['id' => 'userid']);
        $html .= Form::hidden('mainpid', $id, ['id' => 'mainpid']);

        $html .= '<td colspan="2">';

        $html .= '<select name="color" class="form-control input-sm" id="color">';
        $html .= '<option value="">Select a color</option>';
        foreach ($options as $colors) {
            if ($colors->type == 'color') {
                $html .= '<option value="'.$colors->value.'" style="background: #'.$colors->value.'" data-type="0">'.'#'.$colors->value.'</option>';
            } elseif ($colors->type == 'photo') {
                $html .= '<option value="'.$colors->value.'" data-type="1"> Photo - '.$colors->id.'</option>';
            }
        }
        $html .= '</select>';

        $html .= '</td>';
        $html .= '<td>';

        $html .= '<select name="size" class="form-control input-sm" id="size">';
        $html .= '<option value="">Select a size</option>';
        foreach ($options as $colors) {
            if ($colors->type == 'size') {
                $html .= '<option value="'.$colors->value.'">'.$colors->value.'</option>';
            }
        }
        $html .= '</select>';

        $html .= '<td>';
        $html .= Form::input('text', 'item_code', null, ['required', 'id' => 'item_code', 'class' => 'form-control input-sm', 'placeholder' => 'Enter item code...']);
        $html .= '</td>';

        $html .= '</td>';

        $html .= '<td>';
        $html .= Form::input('number', 'stock', null, ['required', 'id' => 'stock', 'class' => 'form-control input-sm', 'placeholder' => 'Enter stock...']);

        $html .= '</td>';

        $html .= '<td>';
        $html .= Form::input('number', 'dp_price', null, ['required', 'id' => 'dp_price', 'class' => 'form-control input-sm', 'placeholder' => 'Enter DP price...']);

        $html .= '</td>';

        $html .= '<td>';
        $html .= Form::input('number', 'regular_price', null, ['required', 'id' => 'regular_price', 'class' => 'form-control input-sm', 'placeholder' => 'Enter regular price...']);

        $html .= '</td>';

        $html .= '<td>';

        $html .= Form::input('number', 'selling_price', null, ['required', 'id' => 'selling_price', 'class' => 'form-control input-sm', 'placeholder' => 'Enter selling price...']);

        $html .= '</td>';

        $html .= '<td style="position: relative;">';

        $html .= '<select name="is_stock" class="form-control input-sm" id="is_stock" >';

        $html .= '<option value="1">Available</option>';
        $html .= '<option value="0">Out of stock</option>';

        $html .= '</select>';
        $html .= '<div class="small_btn" style="position: absolute; top: 6px; bottom: 0; right: -47px;">';
        $html .= '<button type="button" class="btn btn-xs btn-success" id="save_variation">';
        $html .= '<i class="fa fa-check-circle" aria-hidden="true"></i>Save';
        $html .= '</button>';
        $html .= '</div>';
        $html .= '</td>';
        $html .= Form::close();

        $html .= '</tr>';

        return response()->json(['html' => $html]);
    }

    public function add_more_bank(Request $request)
    {
        $id = $request->get('main_pid');

        $options = $this->bank->getAll();

        $html = '<tr>';
        $html .= Form::open(['url' => '', 'method' => 'post', 'value' => 'PATCH', 'files' => true, 'autocomplete' => 'off']);
        $html .= Form::hidden('userid', (!empty(\Auth::user()->id) ? \Auth::user()->id : null), ['id' => 'userid']);
        $html .= Form::hidden('mainpid', $id, ['id' => 'mainpid']);

        $html .= '<td>';
        $html .= '<select name="bank_id" class="form-control input-sm" id="bank_id">';
        $html .= '<option value="">Select a bank</option>';
        foreach ($options as $bank) {
            $html .= '<option value="'.$bank->id.'">'.$bank->name.'</option>';
        }
        $html .= '</select>';

        $html .= '</td>';

        $html .= '<td>';
        $html .= '</td>';

        $html .= '<td>';
        $html .= '</td>';

        $html .= '<td>';
        $html .= '</td>';

        $html .= '<td>';
        $html .= Form::input('month_range', 'month_range', null, ['required', 'id' => 'month_range', 'class' => 'form-control input-sm', 'placeholder' => 'Enter month...']);
        $html .= '</td>';
        $html .= '<td style="position: relative;">';
        $html .= Form::input('interest', 'interest', null, ['required', 'id' => 'interest', 'class' => 'form-control input-sm', 'placeholder' => 'Enter interest in percentage...']);
        $html .= '<div class="small_btn" style="position: absolute; top: 6px; bottom: 0; right: -47px;">';
        $html .= '<button type="button" class="btn btn-xs btn-success" id="save_emi_data">';
        $html .= '<i class="fa fa-check-circle" aria-hidden="true"></i>Save';
        $html .= '</button>';
        $html .= '</div>';
        $html .= '</td>';
        $html .= Form::close();

        $html .= '</tr>';

        return response()->json(['html' => $html]);
    }

    public function save_variation(Request $request)
    {
        //dd($request);
        if (!empty($request->get('mainpid'))) {
            $attributes = [
                'user_id' => !empty($request->get('userid')) ? $request->get('userid') : null,
                'main_pid' => !empty($request->get('mainpid')) ? $request->get('mainpid') : null,
                'color_codes' => !empty($request->get('color_codes')) ? $request->get('color_codes') : null,
                'size' => !empty($request->get('size')) ? $request->get('size') : null,
                'stock' => !empty($request->get('stock')) ? $request->get('stock') : null,
                'item_code' => !empty($request->get('item_code')) ? $request->get('item_code') : null,
                'dp_price' => !empty($request->get('dp_price')) ? $request->get('dp_price') : null,
                'regular_price' => !empty($request->get('regular_price')) ? $request->get('regular_price') : null,
                'selling_price' => !empty($request->get('selling_price')) ? $request->get('selling_price') : null,
                'is_stock' => !empty($request->get('is_stock')) ? $request->get('is_stock') : null,
                'type' => $request->get('type')
            ];

            //dd($attributes);

            try {
                $created = $this->pcombinationdata->create($attributes);
                $tksign = '&#2547; ';
                $html = '<tr>';
                $html .= '<td>';
                $html .= $created->id;
                $html .= '</td>';
                $html .= '<td>';
                if ($created->type == 0) {
                    $html .= '<span style="background-color: #'.$created->color_codes.' !important; padding: 5px; display: block; width: 20px; height: 20px;"></span>';
                } else {
                    $html .= '<img src="'.asset('public/pmp_img/'.$created->color_codes).'" width="20" height="20">';
                }
                $html .= '</td>';

                $html .= '<td>';
                $html .= $created->size;
                $html .= '</td>';

                $html .= '<td>';
                $html .= $created->item_code;
                $html .= '</td>';

                $html .= '<td>';
                $html .= $created->stock;
                $html .= '</td>';

                $html .= '<td>';
                $html .= $tksign.$created->dp_price;
                $html .= '</td>';

                $html .= '<td>';
                $html .= $tksign.$created->regular_price;
                $html .= '</td>';

                $html .= '<td>';
                $html .= $tksign.$created->selling_price;
                $html .= '</td>';

                $html .= '<td style="position: relative;">';

                $html .= $created->is_stock;
                $html .= '<div class="small_btn" style="position: absolute; top: 6px; bottom: 0; right: -47px;">';
                $html .= '<a href="'.url('delete_pcomdata/'.$created->id).'" class="btn btn-xs btn-danger" onclick="return confirm(\'Are you Sure ?\')">';
                $html .= '<i class="fa fa-times-circle" aria-hidden="true"></i> Del';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';

                return response()->json(['html' => $html, 'success' => 'Successfully Added', 'status' => 1]);
            } catch (QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];
                if ($errorCode == '1062') {
                    echo 0;
                } else {
                    echo 0;
                }
                //return redirect('products')->withErrors($ex->getMessage());
            }
        } else {
            echo 0;
        }
    }

    public function save_emi_data(Request $request)
    {
        if (!empty($request->get('mainpid'))) {
            $attributes = [
                'user_id' => !empty($request->get('userid')) ? $request->get('userid') : null,
                'main_pid' => !empty($request->get('mainpid')) ? $request->get('mainpid') : null,
                'bank_id' => !empty($request->get('bank_id')) ? $request->get('bank_id') : null,
                'month_range' => !empty($request->get('month_range')) ? $request->get('month_range') : null,
                'interest' => !empty($request->get('interest')) ? $request->get('interest') : null
            ];
            //dd($attributes);

            try {
                $created = $this->emi->create($attributes);

                //$banks = $this->bank->getById('id', $attributes['bank_id'])->get()->first();

                //dd($banks);

                $html = '<tr>';
                $html .= '<td>';
                $html .= $created->bank_id;
                $html .= '</td>';
                $html .= '<td>';
                $html .= $created->month_range;
                $html .= '</td>';
                $html .= '<td style="position: relative;">';
                $html .= $created->interest;
                $html .= '<div class="small_btn" style="position: absolute; top: 6px; bottom: 0; right: -47px;">';
                $html .= '<a href="'.url('delete_emi/'.$created->id).'" class="btn btn-xs btn-danger" onclick="return confirm(\'Are you Sure ?\')">';
                $html .= '<i class="fa fa-times-circle" aria-hidden="true"></i> Del';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';

                //dd($html);

                return response()->json(['html' => $html, 'success' => 'Successfully Added', 'status' => 1]);
            } catch (QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];
                if ($errorCode == '1062') {
                    echo 0;
                } else {
                    echo 0;
                }
                //return redirect('products')->withErrors($ex->getMessage());
            }
        } else {
            echo 0;
        }
    }

    public function get_products_on_search(Request $request)
    {
        $products = Product::where('title', 'like', '%'.$request->get('search_param').'%')
            ->orWhere('sub_title', 'like', '%'.$request->get('search_param').'%')
            ->orderBy('title', 'asc')->get();
        // dd($products);
        $main_pid = $request->get('main_pid');

        $html = null;
        foreach ($products as $p) {
            $html .= '<option
            id="dblclick_related"
            value="'.$p->id.'"
            data-mainpid="'.(!empty($main_pid) ? $main_pid : null).'"
            data-userid="'.(!empty(\auth()->user()->id) ? Auth::user()->id : null).'"
            data-local_price="'.$p->local_selling_price.'"
            data-local_discount="'.$p->local_discount.'"
            data-title="'.$p->title.'"
            data-int_price="'.$p->intl_selling_price.'"
            data-int_discount="'.$p->intl_discount.'">';
            $html .= $p->title;
            $html .= '- '.($p->sub_title ?? '');
            $html .= '</option>';
        }

        return response()->json(['html' => $html]);
    }

    public function MultiplePricingPhoto(Request $request)
    {
        $this->validate($request, [
            'photo_name' => 'nullable|image|mimes:jpeg,jpg,png|max:6144',
        ]);

        if (request()->hasFile('photo_name')) {
            $photo = request()->file('photo_name');

            $filename = date('Y_m_d_His').'.'.$photo->getClientOriginalExtension();
            $photo->move(public_path('pmp_img'), $filename);

            $ppc = new Productpricecombination();
            $ppc->user_id = $request->userid;
            $ppc->main_pid = $request->mainpid;
            $ppc->type = $request->type;
            $ppc->value = strtolower($filename);
            $ppc->save();

            $html = '<li style="position: relative;">';
            $html .= '<a href="'.url('delete_productpricecombination/'.$ppc->id).'" class="cross_btn" onclick="return confirm("\'Are you sure?\'")">x</a>';
            $html .= '<div class="photo"> <img style="border-radius:5px;" src="'.asset('public/pmp_img').'/'.$ppc->value.'" width="40" height="40">
        </div>';

            return response()->json(['html' => $html, 'success' => 'Successfully Added', 'status' => 1]);
        }
    }


    public function deleteVideos360(Request $request, $id)
    {
        $delete = ProductVideos::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Frame has been deleted successfully..');
    }

    public function update360DegreeImage(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        $product->threeSixtyDegreeImage()->delete();
        Cache::forget('product-images-videos-degrees-'.$product->seo_url);
        $inserting = [];

        foreach (explode(',', $request->degree360) as $degree) {
            if ($degree) {
                $inserting[] = [
                    'link' => $degree,
                    'product_id' => $id,
                    'type' => '360'
                ];
            }

        }

        $product->threeSixtyDegreeImage()->insert($inserting);


        return redirect()->back();

    }


    public function change360DegreePosition($id, $position)
    {
        //return response()->json([$id,$position]);
        $data = ProductVideos::findOrFail($id);
        $data->position = $position;
        $data->save();

        return response()->json([
            'success' => true
        ]);

    }


    public function updateProductYoutube(Request $request, $id)
    {
        parse_str(parse_url($request->youtube, PHP_URL_QUERY), $url);

        $ytId = $url['v'] ?? $request->youtube;

        if ($ytId) {
            $data = ProductVideos::updateOrCreate(
                ['product_id' => $id, 'type' => 'youtube'],
                ['link' => $url['v'] ?? $request->youtube]
            );
        } else {
            $ytVid = ProductVideos::where('product_id', $id)->where('type', 'youtube')->first();
            if ($ytVid) {
                $ytVid->delete();
            }
        }

        return redirect()->back()->with('success', 'Data updated successfully..');
    }

    public function updateProductAR(Request $request, $id)
    {

        $ar = $request->ar;

        if ($ar) {
            $data = ProductVideos::updateOrCreate(
                ['product_id' => $id, 'type' => 'ar'],
                ['link' => $ar]
            );
        } else {
            $arData = ProductVideos::where('product_id', $id)->where('type', 'ar')->first();
            if ($arData) {
                $arData->delete();
            }
        }

        return redirect()->back()->with('success', 'Data updated successfully..');
    }

    //Product Variation Section.... start

    public function addProductVariation(Request $request)
    {
        $productVariation = [
            'product_id' => $request->product_id,
            'variation_group_id' => $request->variation_group_id,
            'variation_product_id' => $request->variation_product_id,
            'title' => $request->product_variation_title,
            'image_id' => $request->product_variation_image,
            'active' => $request->active
        ];

        $result = ProductVariation::create($productVariation);
        if ($result) {
            return back()->with('success', 'Data Uploded');
        } else {
            return back()->with('success', 'Somthings want wrong!');
        }
    }


    public function updateProductVariation(Request $request, $id)
    {
        $productVariation = [
            'product_id' => $request->product_id,
            'variation_group_id' => $request->variation_group_id,
            'variation_product_id' => $request->variation_product_id,
            'title' => $request->product_variation_title,
            'image_id' => $request->product_variation_image,
            'active' => $request->active
        ];
        $result = ProductVariation::where('id', $id)->update($productVariation);
        if ($result) {
            return back()->with('success', 'Data Updated');
        } else {
            return back()->with('success', 'Somthings want wrong!');
        }
    }

    public function deleteProductVariation($id)
    {

        $result = ProductVariation::where('id', $id)->delete();
        if ($result) {
            return back()->with('success', 'Delete Success');
        } else {
            return back()->with('success', 'Somthings want wrong!');
        }
    }

    // product Variation Section end ....


    public function productSetIndex(Request $request)
    {
        $product_sets = [];
        $query = true;
        $product_sets = $this->product_set->self()->orderBy('id', 'DESC')->paginate(10);
        // finding a Product Set usign by Id

        return response()->view('admin.product_set.index', compact('query', 'product_sets'));
    }

    public function productSetCreate(Request $request)
    {
        $product_sets = [];
        $db_terms = $this->term->getAll();
        $terms = [];
        $query = true;
        foreach ($db_terms as $term) {
            $terms[$term->id] = $term->name;
        }
        $tab = ($request->tab ?? "basic");
        $tab = "admin.product_set.$tab";
        $medias = $this->media->self()->orderBy('id', 'DESC')->paginate(12);
        return response()->view('admin.product_set.form', compact('terms', 'tab', 'query', 'medias'));

    }

    // Product Set Storing data into database

    public function productSetStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|min:5',
        ]);
        $slug = Str::slug($request->input('title'), '-');
        // $product_ids = join(",",$request->products);
        $productSetProduct = [];

        $product_set = [
            'title' => $request->title,
            'slug' => $slug,
            'subtitle' => $request->subtitle,
            // 'product_ids' => $product_ids,
            'category_id' => $request->category_id,
            'image_id' => $request->image_id,
            'description' => $request->description,
            'active' => $request->active
        ];

        try {

            $productSet = $this->product_set->create($product_set);


            $product_attr = [
                'user_id' => auth()->id(),
                'is_active' => $request->active,
                'product_set_id' => $productSet->id,
                'title' => $request->title,
                'seo_url' => $slug.'pset'.$productSet->id,
                'local_selling_price' => $productSet->price_all
            ];

            $this->product->create($product_attr);


            // inserting Product_Set_Product
            if (is_array($request->products)) {
                for ($i = 0; $i < count($request->products); $i++) {
                    $productSetProduct = [
                        'product_set_id' => $productSet->id,
                        'product_id' => $request->products[$i],
                        'qty' => $request->qty[$request->products[$i]],

                    ];

                    $result = ProductSetProduct::create($productSetProduct);


                    // $proudctSetProduct_id[] = $result->id;
                }
            }

            // dd($product_attr);

            // end ProductSetProduct
            return redirect()->back()->with('success', 'Data Uploaded');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Not Upload!');
        }


    }

    public function productSetEdit(Request $request, $id)
    {

        $product_sets = [];
        $db_terms = $this->term->getAll();
        $tab = ($request->tab ?? "basic");
        $tab = "admin.product_set.$tab";
        $terms = [];
        $query = true;
        foreach ($db_terms as $term) {
            $terms[$term->id] = $term->name;
        }


        // finding a Product Set usign by Id
        if (!empty($id)) {
            $product_bys = [];
            $productSet = $this->product_set->getById($id);

            $product_id = $productSet->product_ids;
            $products = [];
            $db_productSetProduct = ProductSetProduct::where('product_set_id', $id)->get();
            // $arr_product_id = explode(',', $product_id);
            foreach ($db_productSetProduct as $productSetProduct) {

                $product_by_id = $this->product->self()->where('id', $productSetProduct->product_id)->get();

                foreach ($product_by_id as $product) {
                    $products[] = [
                        'id' => $product->id,
                        'title' => $product->title,
                        'sub_title' => $product->sub_title,
                        'first_image' => $product->firstImage->full_size_directory,
                        'product_code' => $product->product_code,
                        'price_now' => $product->product_price_now,
                        'product_set_qty' => $productSetProduct->qty

                    ];
                }

            }

            $medias = $this->media->self()->orderBy('id', 'DESC')->paginate(12);
            $infos = ProductSetInfo::where('product_set_id', $id)->get();
            $productSet_fabric = ProductSetFabric::where('product_set_id', $id)->with("image")->get();

            return response()->view('admin.product_set.form', compact('infos', 'medias', 'terms', 'tab', 'query', 'productSet', 'products', 'productSet_fabric'));
        }

    }

    public function productSetUpdate(Request $request, $id)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required|min:5',
        ]);
        $proudctSetProduct_id = [];

        $slug = Str::slug($request->input('title'), '-');
        $productSetProduct = [];

        $product_set_product_ids = [];

        if (is_array($request->products)) {
            for ($i = 0; $i < count($request->products); $i++) {
                $productSetProduct = [
                    'product_set_id' => $id,
                    'product_id' => $request->products[$i],
                    'qty' => $request->qty[$request->products[$i]],

                ];
                $db_productSetProduct = ProductSetProduct::where('product_id', $request->products[$i])->where('product_set_id', $id)->first();
                if ($db_productSetProduct) {
                    $db_productSetProduct->update($productSetProduct);
                    $product_set_product_ids[] = $db_productSetProduct->id;
                } else {
                    $result = ProductSetProduct::create($productSetProduct);
                    $product_set_product_ids[] = $result->id;
                }

                // $proudctSetProduct_id[] = $result->id;
            }
        }

        $delete = ProductSetProduct::whereNotIn('id', $product_set_product_ids)->where('product_set_id', $id)->delete();


        // $product_ids = is_array($request->products) ? join(",",$request->products) : '';
        $product_set = [
            'title' => $request->title,
            'slug' => $slug,
            'subtitle' => $request->subtitle,
            // 'product_ids' => $product_ids,
            'category_id' => $request->category_id,
            'image_id' => $request->image_id,
            'description' => $request->description,
            'active' => $request->active
        ];

        try {

            $this->product_set->update($id, $product_set);
            $this->product->self()->where('product_set_id', $id)->update(['is_active' => $request->active]);

            return redirect()->back()->with('success', 'Data Updated');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Not Update!');
        }

    }


    public function productSetDelete($id)
    {
        try {
            $this->product_set->delete($id);
            return redirect()->back()->with('success', 'Data Deleted');
        } catch (Exception $e) {
            return redirect()->back()->with('success', 'Data Not Deleted');
        }


    }

    public function productSearch(Request $request)
    {

        $products = [];
        $db_product = $this->product->getProductByFilter(['keyword' => $request->keyword], null);

        foreach ($db_product as $product) {

            $products[] = [
                'id' => $product->id,
                'title' => $product->title,
                'sub_title' => $product->sub_title,
                'first_image' => $product->firstImage->full_size_directory,
                'price_now' => $product->product_price_now,
                'product_code' => $product->product_code,
                'product_set_qty' => $product->product_set_qty

            ];


        }

        return response()->json(compact('products'));

    }

    public function productSetFabricStore(Request $request)
    {
        $attr = [
            'title' => $request->title,
            'product_set_id' => $request->product_set_id,
            'image_id' => $request->image_id,
            'images' => $request->images,
            'active' => $request->active
        ];
        // dd($attr);
        ProductSetFabric::create($attr);

        return redirect()->back()->with('success', 'Fabric Saved.');
    }


    public function productSetInfoStore(Request $request)
    {
        $attr = [
            'title' => $request->title,
            'product_set_id' => $request->product_set_id,
            'sub_title' => $request->sub_title,
            'description' => $request->description,
            'active' => $request->active
        ];

        $product_set = ProductSetInfo::create($attr);


        return redirect()->back()->with('success', 'Info Successfully Saved');
    }

    public function productSetInfoUpdate(Request $request, $id)
    {
        $attr = [
            'title' => $request->title,
            'product_set_id' => $request->product_set_id,
            'sub_title' => $request->sub_title,
            'description' => $request->description,
            'active' => $request->active
        ];

        ProductSetInfo::where('id', $id)->update($attr);

        return redirect()->back()->with('success', 'Info updated!');
    }

    public function productSetInfoDelete($id)
    {

        ProductSetInfo::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Info deleted.');
    }

    public function productSetFabricUpdate(Request $request, $id)
    {
        $attr = [
            'title' => $request->title,
            'product_set_id' => $request->product_set_id,
            'image_id' => $request->image_id,
            'images' => $request->images,
            'active' => $request->active
        ];
        // dd($attr);
        ProductSetFabric::where('id', $id)->update($attr);

        return redirect()->back()->with('success', 'Fabric updated.');
    }

    public function productSetFabricDelete($id)
    {

        ProductSetFabric::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Fabric deleted.');
    }


    //Nipun
    public function search_product(Request $request)
    {

        $seo_url = $request->slug;
        $get_keyworld = $request->only(['page', 'price_min', 'price_max', 'sort_by', 'sort_show', 'keyword', 'fbclid']);

        $filteringDataArr = $request->all();
        $filteringData = [];

        foreach ($filteringDataArr as $key => $val) {
            if (strpos($key, 'filterby_') !== false) {
                $filteringData[strtolower(str_replace('filterby_', '', $key))] = explode('|', $val);
            }
        }

        $get_keyworld = array_merge($get_keyworld, $filteringData);

        //return response()->json($get_keyworld);

        $category = false;
        $products = false;
        $categories = false;
        $filters_att = false;
        $filters_cat = false;
        $view_cat = false;

        $category = $this->term->getByAny('seo_url', $seo_url)->first();

        //dd($category);

        if (!empty($category->id)) {
            $filters_cat = get_filters_cat($category->id);
            // dd($filters_cat);

            $con_att = $category->connected_with;
            $filters_atts = $this->attribute->getFilter($con_att);

            $filters_att = [];
            foreach ($filters_atts as $f_at) {
                $p_attr = explode('|', $f_at->default_value);

                $f_at->ProccessDefault = array_map(function ($pr_data) {
                    $e2 = explode(':', $pr_data);
                    return $e2;
                }, $p_attr);

                $filters_att[] = $f_at;
            }

            $view_cat = get_all_sub_cat($category->id);


            $default = [
                'type' => 'category',
                'limit' => 500,
                'offset' => 0
            ];
            $cats = $this->get_product_categories($default);
            $categories = $cats->toArray();

        }

        $products = $this->product->getProductByFilter($get_keyworld, $view_cat);


        return response()->json(compact(
            'category',
            'products',
            'categories',
            'filters_cat',
            'filters_att'
        ));

    }


    public function product_details_by_id(Request $request)
    {
        $self_token = $request->self_token;
        $slug = $request->slug;
        $productId = $request->productId;

        $column = $slug ? 'seo_url' : 'id';
        $value = $slug ? $slug : $productId;

        if ($request->sku) {
            $column = 'sku';
            $value = $request->sku;
        }


        $product = $this->product
            ->getByAny($column, $value)
            ->first();

        if (!$product) {
            return response()->json(false);
        }
        return response()->json(compact(
            'product',
        ));
        /*
        $product->flashTime = isset($product->flashItem)?$this->flashSchedule->getTodayFlashOnly() : null;
        //$product->category  = false; //$product->categories()->first()->term??null;
        $product->images;
        $product->attributes;
        //$product->relatedProducts = $product->relatedProducts()->with('product.image', 'product.ratingSum')->get();

        $SizeCol         = \App\Models\Product::where('id', $product->id)
            //->with('colors.sizes')
            ->first();
        $product->colors = $SizeCol->colors;


        $session_data   = $this->sessionToDB->getByKey($self_token . '_recently_views');
        $recently_views = $session_data?json_decode($session_data, true) : [];

        $recently_views[time()] = $product->id;
        //$recently_views = array_unique($recently_views);
        krsort($recently_views);
        $recently_views = array_slice($recently_views, 0, 50);
        $this->sessionToDB->updateOrCreate($self_token . '_recently_views', json_encode($recently_views));


        // $similar_products                = $this->product->getSimilarProduct($product->id);

        $similarWord      = substr($product->title, 0, 6);
        $similar_products = \App\Product::where('title', 'like', '%' . $similarWord . '%')
            ->with('image', 'ratingSum')
            ->limit(8)
            ->get();


        $whoBoughts = $this->product
            ->getSimilarProduct($product->id);

        return response()->json(compact(
            'product',
            'similar_products',
            'whoBoughts'
        ));
        */

    }


    public function add_to_cart(Request $request)
    {
        $self_token = $request->self_token;
        $slug = $request->slug;
        $main_pid = $request->main_pid;

        $column = $slug ? 'seo_url' : 'id';
        $value = $slug ? $slug : $main_pid;

        if ($request->sku) {
            $column = 'sku';
            $value = $request->sku;
        }


        $product_ifo = $this->product
            ->getByAny($column, $value)
            ->first();


        if (!$product_ifo || $product_ifo->stock_status != 1) {
            return response()->json(false);
        }

        $product = [
            'productid' => $product_ifo->id,
            'size' => $request->get('size'),
            'color' => $request->get('color'),
            'qty' => $request->get('qty'),
        ];


        $get_product_data = [
            'main_pid' => $product_ifo->id,
            'color' => $request->get('color'),
            'size' => $request->get('size'),
            'type' => null,
        ];
        $get_fp = get_product_price($get_product_data);
        //dd($get_fp);


        if ($product_ifo->disable_buy != 'on') {
            if ($product_ifo['multiple_pricing'] == 'on') {
                $m_price_infos = Pcombinationdata::where(['id' => $get_fp['multi_id']])->get();
                if ($m_price_infos->count() == 1) {
                    $m_price_info = $m_price_infos->first();
                    if ($get_fp['has_cs']) {

                        $add_cat = [
                            'productid' => $get_fp['productid'],
                            'productcode' => $product_ifo->product_code.'CIC'.$m_price_info->id,
                            'size_colo' => $get_fp['multi_id'],
                            'purchaseprice' => $get_fp['s_price'],
                            'qty' => $request->get('qty'),
                            'is_dp' => $get_fp['is_dp'],
                            'flash_discount' => $get_fp['flash_discount'],
                            'item_code' => $get_fp['item_code'],
                            'dis_tag' => $get_fp['save'],
                            'pre_price' => $get_fp['r_price']
                        ];
                    } else {
                        $add_cat = [];
                    }
                } else {
                    $add_cat = [];
                }
            } else {
                $add_cat = [
                    'productid' => $get_fp['productid'],
                    'productcode' => $get_fp['productcode'],
                    'size_colo' => $get_fp['multi_id'],
                    'purchaseprice' => $get_fp['s_price'],
                    'qty' => $request->get('qty'),
                    'is_dp' => $get_fp['is_dp'],
                    'flash_discount' => $get_fp['flash_discount'],
                    'item_code' => $get_fp['item_code'],
                    'dis_tag' => $get_fp['save'],
                    'pre_price' => $get_fp['r_price']
                ];
            }
        } else {
            $add_cat = [];
        }


        //dd($add_cat);
        if ($add_cat) {
            $self_token = $request->get('self_token');


            $oldcart = null;
            $ocart = $this->sessionToDB->getByKey($self_token.'_cart');
            $oldcart = $ocart ? (object)json_decode($ocart, true) : null;
            //dd($oldcart);


            $cart = new Cart($oldcart);
            //dd($cart);
            //$cart->add($product, $request->get('productcode'));
            $cart->add($add_cat, $add_cat['productcode']);
            //dd($cart);
            //return response()->json($cart);

            $this->sessionToDB->updateOrCreate($self_token.'_cart', json_encode($cart));

            $nscart = $this->sessionToDB->getByKey($self_token.'_cart');
            $newcart = $nscart ? (object)json_decode($nscart, true) : null;
            //dd($newcart);
            //$request->session()->put('cart', $cart); because session save to db..
            //$newcart = Session::has('cart') ? Session::get('cart') : null;


            $ncart = new Cart($newcart);
            //dd($ncart);

            if (!empty($ncart->items)) {
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


            $pro = $this->product->getById($request->get('main_pid'));


            $report = true;

            return response()->json(compact(
                'pro',
                'totalprice',
                'total_qty',
                'report',
                'ncart'
            ));
        } else {
            return response()->json(['pro' => null, 'totalprice' => null, 'total_qty' => null, 'report' => false]);
        }
    }


    public function cart(Request $request)
    {
        $self_token = $request->self_token;


        if (!$self_token) {
            return response()->json(false);
        }

        $session_data = $this->sessionToDB->getByKey($self_token.'_cart');
        $session_data = $session_data ? (object)json_decode($session_data, true) : false;

        $items = $this->proccessCartWithDeliveryCharge($session_data, $self_token);

        if (!$items) {
            return response()->json(false);
        }

        $groupByShippingTime = $this->cartGroupingByShipment($items);

        if (($self_token != 'thisistokenforonbehalfbuy') || ($self_token == 'thisistokenforonbehalfbuy' && $request->admin_delivery_charge != 1)) {
            $totalDeliveryCharge = array_sum(array_column($groupByShippingTime, 'delivery_charge'));
            $this->sessionToDB->updateOrCreate($self_token.'_total_delivery_charge', json_encode($totalDeliveryCharge));
        } else {
            $totalDeliveryCharge = $this->sessionToDB->getByKey($self_token.'_total_delivery_charge');
            $totalDeliveryCharge = $totalDeliveryCharge ? json_decode($totalDeliveryCharge) : 0;
        }

        $totalqty = $session_data->totalqty ?? 0;
        $totalprice = $session_data->totalprice ?? 0;
        $discount = $this->getCouponDiscount($self_token, $session_data);
        $paymentFirst = $this->paymentFirstProductAvailable($items);

        return response()->json(compact(
            'items',
            'totalqty',
            'totalprice',
            'discount',
            'groupByShippingTime',
            'totalDeliveryCharge',
            'paymentFirst'
        ));
    }


    public function imageSorting360(Request $request)
    {

        $items = $request->post('item');
        //dd($items);

        foreach ($items as $key => $item) {
            ProductVideos::where('id', $item)->update(['position' => $key]);
        }

        return response()->json(['status' => 1, 'message' => 'Successfully sorted']);
        try {

            //return redirect()->back();
        } catch (QueryException $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }


    public function import_stock_view()
    {
        return response()->view('product.stock_import_view');
    }

    public function import_stocks(Request $request)
    {
        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file');
            $data = Excel::import(new StockImport, $path);
        }
        //Cache::forget('common-showrooms-' . $request->district);
        //Cache::forget('common-showrooms-');

        return redirect('import_stock_view')->with('success', 'Successfully imported and updated');
    }

}
