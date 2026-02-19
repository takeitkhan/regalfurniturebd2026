<?php

use App\Http\Controllers\Admin\CommonController as AdminCommonController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\OrdersManagement;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\InteriorController;
use App\Http\Controllers\Admin\OthersController;
use App\Http\Controllers\BkashController;
use App\Http\Controllers\CashOnDeliveryController;
use App\Repositories\SessionData\SessionDataInterface;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\CommonController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SslCommerzController;
use App\Http\Controllers\Admin\ProductAttributesDataController;
use App\Http\Controllers\UtilityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//For Social Login
// Route::get('/social/auth/redirect/{provider}', 'SocialController@redirect');
// Route::get('/social/callback/{provider}', 'SocialController@callback');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('fb_feeds.xml', [HomeController::class, 'facebookFeed']);

Route::prefix('sslcommerz')->group(function () {
    Route::post('pay-via-ajax', [SslCommerzController::class, 'payViaAjax']);
    Route::post('existing-order-pay', [SslCommerzController::class, 'existingOrderPay']);
    Route::post('success', [SslCommerzController::class, 'success']);
    Route::post('fail', [SslCommerzController::class, 'fail']);
    Route::post('ipn', [SslCommerzController::class, 'ipn']);
});


/** Previous bKash Integration Code */
/**
 * Route::group(['prefix' => 'bkash', 'as' => 'bkash.', 'middleware' => ['cors']], function () {
 *
 * Route::post('get-token', [BkashController::class, 'getToken'])->name('get-token');
 * Route::post('create-payment', [BkashController::class, 'createPayment'])->name('create-payment');
 * Route::post('execute-payment', [BkashController::class, 'executePayment'])->name('execute-payment');
 * Route::post('query-payment', [BkashController::class, 'queryPayment'])->name('query-payment');
 * Route::post('success', [BkashController::class, 'bkashSuccess'])->name('success');
 * });
 **/


/** Brand New Bkash Integration */
Route::group(['middleware' => ['cors']], function () {

    // Payment Routes for bKash
    Route::post('bkash/get-token', '\App\Http\Controllers\BrandNewBkashController@getToken')->name('bkash-get-token');
    Route::post('bkash/create-payment', '\App\Http\Controllers\BrandNewBkashController@createPayment')->name('bkash-create-payment');
    Route::post('bkash/execute-payment', '\App\Http\Controllers\BrandNewBkashController@executePayment')->name('bkash-execute-payment');
    Route::get('bkash/query-payment', '\App\Http\Controllers\BrandNewBkashController@queryPayment')->name('bkash-query-payment');
    Route::post('bkash/success', '\App\Http\Controllers\BrandNewBkashController@bkashSuccess')->name('bkash-success');

    // Refund Routes for bKash
    Route::get('bkash/refund', '\App\Http\Controllers\BrandNewBkashController@index')->name('bkash-refund-1');
    Route::post('bkash/refund', '\App\Http\Controllers\BrandNewBkashController@refund')->name('bkash-refund-2');

});

/** Brand New Bkash Integration */

Route::prefix('medias')->middleware(['outlet'])->group(function () {
    Route::get('/all', '\App\Http\Controllers\Admin\MediaController@index')->name('allmedias');
});

// Route::post('/frontend/ssl_ipn_handler','\App\Http\Controllers\Site\ShopController@ipn');

Route::prefix('medias')->middleware('outlet')->group(function () {
    Route::get('all', ['as' => 'uploadfile', 'uses' => '\App\Http\Controllers\Admin\MediaController@index']);
    Route::post('all', '\App\Http\Controllers\Admin\MediaController@index')->name('index');
    Route::post('upload', ['as' => 'upload-post', 'uses' => '\App\Http\Controllers\Admin\ImageController@postUpload']);
    Route::get('upload/delete/{id}', ['as' => 'upload-remove/{id}', 'uses' => '\App\Http\Controllers\Admin\ImageController@destroy']);
});

Route::middleware(['outlet'])->group(function () {
    // users panel routes
    Route::post('search_users', ['as' => 'search_users', 'uses' => '\App\Http\Controllers\Admin\User\UserController@users'])->name('search_users');
    Route::get('users', ['as' => 'users', 'uses' => '\App\Http\Controllers\Admin\User\UserController@users'])->name('users');
    Route::get('add_user', ['as' => 'add_user', 'uses' => '\App\Http\Controllers\Admin\User\UserController@add_user'])->name('add_user');
    Route::post('user_save', ['as' => 'user_save', 'uses' => '\App\Http\Controllers\Admin\User\UserController@store'])->name('user_save');
    Route::get('edit_user/{id}', ['as' => 'edit_user', 'uses' => '\App\Http\Controllers\Admin\User\UserController@edit_user'])->name('edit_user');
    Route::post('user/{id}/update', ['as' => 'user/{id}/update', 'uses' => '\App\Http\Controllers\Admin\User\UserController@user_update_save'])->name('user_update_save');
    Route::delete('user_delete/{id}', ['as' => 'delete_user', 'uses' => '\App\Http\Controllers\Admin\User\UserController@destroy']);
    // users panel routes close

    Route::post('order/payment-status', ['as' => 'order.payment_status', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@payment_status_update'])->name('order.payment_status');
    Route::post('order/payment-method', ['as' => 'order.payment_method', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@payment_method_update'])->name('order.payment_method');
    Route::post('order/proofs', [OrdersManagement::class, 'proofsStore'])->name('order.proofs.store');
    Route::delete('order/proofs/{proof}', [OrdersManagement::class, 'proofsDelete'])->name('order.proofs.delete');

    // posts panel routes
    Route::get('posts', ['as' => 'posts', 'uses' => '\App\Http\Controllers\Admin\PostController@posts'])->name('posts');
    Route::get('add_post', ['as' => 'add_post', 'uses' => '\App\Http\Controllers\Admin\PostController@add_post'])->name('add_post');
    Route::get('add_showroom', ['as' => 'add_showroom', 'uses' => '\App\Http\Controllers\Admin\PostController@add_showroom'])->name('add_showroom');
    Route::post('post_save', '\App\Http\Controllers\Admin\PostController@store')->name('post_save');
    Route::get('edit_post/{id}', ['as' => 'edit_post', 'uses' => '\App\Http\Controllers\Admin\PostController@edit_post'])->name('edit_post');
    Route::get('edit_showroom/{id}', ['as' => 'edit_showroom', 'uses' => '\App\Http\Controllers\Admin\PostController@edit_showroom'])->name('edit_showroom');
    Route::post('post/{id}/update', ['as' => 'post/{id}/update', 'uses' => '\App\Http\Controllers\Admin\PostController@post_update_save'])->name('post_update_save');
    Route::delete('post_delete/{id}', ['as' => 'delete_post', 'uses' => '\App\Http\Controllers\Admin\PostController@destroy']);
    Route::get('/our_showroom', '\App\Http\Controllers\Admin\PostController@our_showroom')->name('our_showroom');
    // posts panel routes close

    // Review panel routes
    Route::get('review', ['as' => 'review', 'uses' => '\App\Http\Controllers\Admin\ReviewController@review'])->name('review');
    Route::get('quick_review_approve/{id}/{action}', ['as' => 'quick_review_approve', 'uses' => '\App\Http\Controllers\Admin\ReviewController@quick_review_approve']);

    // Review panel routes close

    // pages panel routes
    Route::get('pages', ['as' => 'pages', 'uses' => '\App\Http\Controllers\Admin\PageController@pages'])->name('pages');
    Route::get('add_page', ['as' => 'add_page', 'uses' => '\App\Http\Controllers\Admin\PageController@add_page'])->name('add_page');
    Route::post('page_save', ['as' => 'page_save', 'uses' => '\App\Http\Controllers\Admin\PageController@store'])->name('page_save');
    Route::get('edit_page/{id}', ['as' => 'edit_page', 'uses' => '\App\Http\Controllers\Admin\PageController@edit_page'])->name('edit_page');
    Route::post('page/{id}/update', ['as' => 'page/{id}/update', 'uses' => '\App\Http\Controllers\Admin\PageController@page_update_save'])->name('page_update_save');
    Route::delete('page_delete/{id}', ['as' => 'delete_page', 'uses' => '\App\Http\Controllers\Admin\PageController@destroy']);
    // pages panel routes close

    // dashboard, menus, widgets
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => '\App\Http\Controllers\Admin\DashboardController@index'])->name('dashboard');
    Route::get('dashboard/activity-logs', ['as' => 'dashboard.activity_logs', 'uses' => '\App\Http\Controllers\Admin\DashboardController@activity_logs'])->name('dashboard.activity_logs');
    Route::get('menus', ['as' => 'menus', 'uses' => '\App\Http\Controllers\Admin\DashboardController@menus'])->name('menus');

    Route::get('widgets', ['as' => 'widgets', 'uses' => '\App\Http\Controllers\Admin\DashboardController@widgets'])->name('widgets');
    Route::post('widget_save', ['as' => 'widget_save', 'uses' => '\App\Http\Controllers\Admin\DashboardController@store'])->name('widget_save');
    Route::get('edit_widget/{id}', ['as' => 'edit_widget', 'uses' => '\App\Http\Controllers\Admin\DashboardController@edit_widget'])->name('edit_widget');
    Route::post('widget/{id}/update', ['as' => 'widget/{id}/update', 'uses' => '\App\Http\Controllers\Admin\DashboardController@widget_update_save'])->name('widget_update_save');
    Route::delete('widget_delete/{id}', ['as' => 'delete_widget', 'uses' => '\App\Http\Controllers\Admin\DashboardController@destroy']);
    // dashboard, menus, widgets close

    // variations management routes
    Route::get('variations', ['as' => 'variations', 'uses' => '\App\Http\Controllers\Admin\VariationController@variations'])->name('variations');
    Route::get('add_variation', ['as' => 'add_variation', 'uses' => '\App\Http\Controllers\Admin\VariationController@add_variation'])->name('add_variation');
    Route::post('variation_save', ['as' => 'variation_save', 'uses' => '\App\Http\Controllers\Admin\VariationController@store'])->name('variation_save');
    Route::get('edit_variation/{id}', ['as' => 'edit_variation', 'uses' => '\App\Http\Controllers\Admin\VariationController@edit_variation'])->name('edit_variation');
    Route::post('variation/{id}/update', ['as' => 'variation/{id}/update', 'uses' => '\App\Http\Controllers\Admin\VariationController@variation_update_save'])->name('variation_update_save');
    Route::delete('variation_delete/{id}', ['as' => 'delete_variation', 'uses' => '\App\Http\Controllers\Admin\VariationController@destroy']);
    // variations management routes close

    Route::get('admin/variation-group', [App\Http\Controllers\Admin\VariationGroupController::class, 'index'])->name('variation.group');
    Route::get('admin/add-variation-group', [\App\Http\Controllers\Admin\VariationGroupController::class, 'create'])->name('add.variation.group');
    Route::post('admin/store-variation-group', [\App\Http\Controllers\Admin\VariationGroupController::class, 'store'])->name('store.variation.group');
    Route::get('admin/edit-variation-group/{id}', [\App\Http\Controllers\Admin\VariationGroupController::class, 'edit'])->name('edit.variation.group');
    Route::post('admin/update-variation-group/{id}', [\App\Http\Controllers\Admin\VariationGroupController::class, 'update'])->name('update.variation.group');
    Route::get('admin/delete-variation-group/{id}', [\App\Http\Controllers\Admin\VariationGroupController::class, 'delete'])->name('delete.variation.group');
    // terms/category management routes
    Route::get('terms', ['as' => 'terms', 'uses' => '\App\Http\Controllers\Admin\TermController@terms'])->name('terms');
    Route::post('term_save', ['as' => 'term_save', 'uses' => '\App\Http\Controllers\Admin\TermController@store'])->name('term_save');
    Route::get('edit_term/{id}', ['as' => 'edit_term', 'uses' => '\App\Http\Controllers\Admin\TermController@edit_term'])->name('edit_term');
    Route::post('term/{id}/update', ['as' => 'term/{id}/update', 'uses' => '\App\Http\Controllers\Admin\TermController@term_update_save'])->name('term_update_save');
    Route::get('delete_term/{id}', ['as' => 'delete_term', 'uses' => '\App\Http\Controllers\Admin\TermController@destroy'])->name('delete_term');
    Route::get('terms/serialise', ['as' => 'term_serialise', 'uses' => '\App\Http\Controllers\Admin\TermController@termSerialise'])->name('term_serialise');
    Route::post('terms/serialise/update', ['as' => 'term_serialise_update', 'uses' => '\App\Http\Controllers\Admin\TermController@termSerialiseUpdate'])->name('term_serialise_update');

    Route::get('get_categories_on_search', ['as' => 'get_categories_on_search', 'uses' => '\App\Http\Controllers\Admin\TermController@get_categories_on_search'])->name('get_categories_on_search');
    // terms/category management routes close

    // Banks/category management routes
    Route::get('banks', ['as' => 'banks', 'uses' => '\App\Http\Controllers\Admin\BankController@banks'])->name('banks');
    Route::post('bank_save', ['as' => 'bank_save', 'uses' => '\App\Http\Controllers\Admin\BankController@store'])->name('bank_save');
    Route::get('edit_bank/{id}', ['as' => 'edit_bank', 'uses' => '\App\Http\Controllers\Admin\BankController@edit_bank'])->name('edit_bank');
    Route::post('bank/{id}/update', ['as' => 'bank/{id}/update', 'uses' => '\App\Http\Controllers\Admin\BankController@bank_update_save'])->name('bank_update_save');
    Route::get('delete_bank/{id}', ['as' => 'delete_bank', 'uses' => '\App\Http\Controllers\Admin\BankController@destroy'])->name('delete_bank');
    //Route::get('get_categories_on_search', ['as' => 'get_categories_on_search', 'uses' => '\App\Http\Controllers\Admin\BankController@get_categories_on_search'])->name('get_categories_on_search');
    // banks/category management routes close

    // products management routes

    // MultiplePricingPhoto
    Route::post('/multiplepricingphoto', '\App\Http\Controllers\Admin\ProductController@MultiplePricingPhoto');
    Route::post('products', ['as' => 'products', 'uses' => '\App\Http\Controllers\Admin\ProductController@products'])->name('products.post');
    Route::get('products', ['as' => 'products', 'uses' => '\App\Http\Controllers\Admin\ProductController@products'])->name('products.get');

    Route::get('products_express_delivery', ['as' => 'products_express_delivery', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_express_delivery'])->name('products_express_delivery.get');
    Route::post('products_express_delivery', ['as' => 'products_express_delivery', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_express_delivery'])->name('products_express_delivery.post');

    Route::get('products_enable_comment', ['as' => 'products_enable_comment', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_enable_comment'])->name('products_enable_comment.get');
    Route::post('products_enable_comment', ['as' => 'products_enable_comment', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_enable_comment'])->name('products_enable_comment.post');

    Route::get('products_enable_review', ['as' => 'products_enable_review', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_enable_review'])->name('products_enable_review.get');
    Route::post('products_enable_review', ['as' => 'products_enable_review', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_enable_review'])->name('products_enable_review.post');

    Route::get('products_new_arrival', ['as' => 'products_new_arrival', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_new_arrival'])->name('products_new_arrival.get');
    Route::post('products_new_arrival', ['as' => 'products_new_arrival', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_new_arrival'])->name('products_new_arrival.post');

    Route::get('products_best_selling', ['as' => 'products_best_selling', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_best_selling'])->name('products_best_selling.get');
    Route::post('products_best_selling', ['as' => 'products_best_selling', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_best_selling'])->name('products_best_selling.post');

    Route::get('products_recommended', ['as' => 'products_recommended', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_recommended'])->name('products_recommended.get');
    Route::post('products_recommended', ['as' => 'products_recommended', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_recommended'])->name('products_recommended.post');

    Route::get('products_disable_buy', ['as' => 'products_disable_buy', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_disable_buy'])->name('products_disable_buy.get');
    Route::post('products_disable_buy', ['as' => 'products_disable_buy', 'uses' => '\App\Http\Controllers\Admin\ProductController@products_disable_buy'])->name('products_disable_buy.post');

    Route::get('add_product', ['as' => 'add_product', 'uses' => '\App\Http\Controllers\Admin\ProductController@add_product'])->name('add_product');
    Route::post('product_save', ['as' => 'product_save', 'uses' => '\App\Http\Controllers\Admin\ProductController@store'])->name('product_save');
    Route::post('product_seo_save', ['as' => 'product_seo_save', 'uses' => '\App\Http\Controllers\Admin\ProductController@product_seo_save'])->name('product_seo_save');
    Route::get('edit_product/{id}', ['as' => 'edit_product', 'uses' => '\App\Http\Controllers\Admin\ProductController@edit_product'])->name('edit_product');
    Route::post('product/{id}/update', ['as' => 'product/{id}/update', 'uses' => '\App\Http\Controllers\Admin\ProductController@product_update_save'])->name('product_update_save');
    Route::post('other_information/{id}/update', ['as' => 'other_information/{id}/update', 'uses' => '\App\Http\Controllers\Admin\ProductController@product_update_other_information'])->name('product_update_other_information');
    Route::post('attribute_based_information/{id}/update', ['as' => 'attribute_based_information/{id}/update', 'uses' => '\App\Http\Controllers\Admin\ProductController@attribute_based_information'])->name('attribute_based_information');
    Route::post('add_product_variation', [ProductController::class, 'addProductVariation'])->name('add.product.variation');
    Route::post('update_product_variation/{id}', [ProductController::class, 'updateProductVariation'])->name('update.product.variation');
    Route::get('delete_product_variation/{id}', [ProductController::class, 'deleteProductVariation'])->name('delete.product.variation');

    /* Product Attributes By Nipun  2022*/
    Route::get('product-attribute-group', [ProductAttributesDataController::class, 'groupInndex'])->name('product.attribute.group');
    Route::post('product-attribute-group-store-update', [ProductAttributesDataController::class, 'groupStoreUpdate'])->name('product.attribute.group.store.update');
    Route::get('product-attribute-group-edit/{group_id}', [ProductAttributesDataController::class, 'groupInndex'])->name('product.attribute.group.edit');
    Route::delete('product-attribute-group-destroy/{group_id}', [ProductAttributesDataController::class, 'groupDestroy'])->name('product.attribute.group.destroy');


    Route::get('product-attribute-group-item/{group_id}', [ProductAttributesDataController::class, 'groupItemInndex'])->name('product.attribute.group.item');
    Route::post('product-attribute-group-item-store-update', [ProductAttributesDataController::class, 'groupItemStoreUpdate'])->name('product.attribute.group.item.store.update');
    Route::get('product-attribute-group-item-edit/{item_id}', [ProductAttributesDataController::class, 'groupItemInndex'])->name('product.attribute.group.edit2');
    Route::delete('product-attribute-group-item-destroy/{item_id}', [ProductAttributesDataController::class, 'groupItemDestroy'])->name('product.attribute.group.item.destroy');

    Route::post('/product-attribute-store', [ProductAttributesDataController::class, 'productAttributeStore'])->name('product.attribute.store');
    Route::post('/product-variation-store', [ProductAttributesDataController::class, 'productVariationStore'])->name('product.variation.store');
    /* End  */

    Route::get('get_products_on_search', ['as' => 'get_products_on_search', 'uses' => '\App\Http\Controllers\Admin\ProductController@get_products_on_search'])->name('get_products_on_search');

    Route::get('delete_product/{id}', ['as' => 'delete_product', 'uses' => '\App\Http\Controllers\Admin\ProductController@destroy'])->name('delete_product');
    Route::get('related_products_getter', ['as' => 'related_products_getter', 'uses' => '\App\Http\Controllers\Admin\ProductController@related_products_getter'])->name('related_products_getter');
    Route::get('add_related_products', ['as' => 'add_related_products', 'uses' => '\App\Http\Controllers\Admin\ProductController@add_related_products'])->name('add_related_products');
    Route::get('add_product_categories', ['as' => 'add_product_categories', 'uses' => '\App\Http\Controllers\Admin\ProductController@add_product_categories'])->name('add_product_categories');
    Route::get('add_product_images', ['as' => 'add_product_images', 'uses' => '\App\Http\Controllers\Admin\ProductController@add_product_images'])->name('add_product_images');
    Route::get('add_product_price_combination', ['as' => 'add_product_price_combination', 'uses' => '\App\Http\Controllers\Admin\ProductController@add_product_price_combination'])->name('add_product_price_combination');
    Route::get('get_colors_sizes', ['as' => 'get_colors_sizes', 'uses' => '\App\Http\Controllers\Admin\ProductController@get_colors_sizes'])->name('get_colors_sizes');
    Route::get('add_more_bank', ['as' => 'add_more_bank', 'uses' => '\App\Http\Controllers\Admin\ProductController@add_more_bank'])->name('add_more_bank');

    Route::get('save_variation', '\App\Http\Controllers\Admin\ProductController@save_variation')->name('save_variation');
    Route::get('save_emi_data', '\App\Http\Controllers\Admin\ProductController@save_emi_data')->name('save_emi_data');

    Route::get('delete_relatedproduct/{id}', ['as' => 'delete_relatedproduct', 'uses' => '\App\Http\Controllers\Admin\RelatedProductsController@destroy'])->name('delete_relatedproduct');
    Route::get('delete_productcategory/{id}', ['as' => 'delete_productcategory', 'uses' => '\App\Http\Controllers\Admin\ProductCategoriesController@destroy'])->name('delete_productcategory');
    Route::get('delete_productimage/{id}', ['as' => 'delete_productimage', 'uses' => '\App\Http\Controllers\Admin\ProductImagesController@destroy'])->name('delete_productimage');
    Route::get('delete_productpricecombination/{id}', ['as' => 'delete_productpricecombination', 'uses' => '\App\Http\Controllers\Admin\ProductpricecombinationController@destroy'])->name('delete_productpricecombination');
    Route::get('delete_pcomdata/{id}', ['as' => 'delete_productpricecombination', 'uses' => '\App\Http\Controllers\Admin\PcombinationdataController@destroy'])->name('delete_pcomdata');
    Route::get('delete_emi/{id}', ['as' => 'delete_emi', 'uses' => '\App\Http\Controllers\Admin\EmiController@destroy'])->name('delete_emi');
    Route::get('delete_product_videos_360/{id}', '\App\Http\Controllers\Admin\ProductController@deleteVideos360')->name('product.videos360.delete');
    Route::post('update_product_360_degree/{id}', '\App\Http\Controllers\Admin\ProductController@update360DegreeImage')->name('product.360degree.update');
    Route::post('update_product_youtube/{id}', '\App\Http\Controllers\Admin\ProductController@updateProductYoutube')->name('product.youtubevideo.update');
    Route::post('update_product_ar/{id}', '\App\Http\Controllers\Admin\ProductController@updateProductAR')->name('product.ar.update');
    Route::get('update_product_360_degree_position/{id}/{position}', '\App\Http\Controllers\Admin\ProductController@change360DegreePosition')->name('product.360degree.positionUpdate');
    Route::get('three_sixty_image_sorting', '\App\Http\Controllers\Admin\ProductController@imageSorting360')->name('product.imagesorting');

    Route::get('is_attgroup_active', ['as' => 'is_attgroup_active', 'uses' => '\App\Http\Controllers\Admin\ProductCategoriesController@is_attgroup_active'])->name('is_attgroup_active');
    Route::get('is_main_image', ['as' => 'is_main_image', 'uses' => '\App\Http\Controllers\Admin\ProductImagesController@is_main_image'])->name('is_main_image');
    Route::get('update_image_setup', ['as' => 'update_image_setup', 'uses' => '\App\Http\Controllers\Admin\ProductImagesController@update_image_setup'])->name('update_image_setup');

    // products export and import products
    Route::get('import_products_view', ['as' => 'import_products_view', 'uses' => '\App\Http\Controllers\Admin\ProductController@import_products_view'])->name('import_products_view');
    Route::post('import_products_save', ['as' => 'import_products', 'uses' => '\App\Http\Controllers\Admin\ProductController@import_products'])->name('import_products');
    Route::get('export_products', ['as' => 'export_products', 'uses' => '\App\Http\Controllers\Admin\ProductController@export_products'])->name('export_products');

    // product set routes
    Route::get('admin/product-set', [ProductController::class, 'productSetIndex'])->name('admin.product_set.index');
    Route::get('admin/product-set/create', [ProductController::class, 'productSetCreate'])->name('admin.product_set.create');
    Route::post('admin/product-set-store', [ProductController::class, 'productSetStore'])->name('admin.common.product_set.store');
    Route::get('admin/product-set-edit/{id}', [ProductController::class, 'productSetEdit'])->name('admin.product_set.edit');
    Route::post('admin/product-set-update/{id}', [ProductController::class, 'productSetUpdate'])->name('admin.common.product_set.update');
    Route::get('admin/product-set-delete/{id}', [ProductController::class, 'productSetDelete'])->name('admin.common.product_set.delete');
    Route::get('admin/product-set/product-search', [ProductController::class, 'productSearch'])->name('admin.product_set.product.search');
    Route::post('admin/product-set/info-store', [ProductController::class, 'productSetInfoStore'])->name('admin.product_set.info.store');
    Route::post('admin/product-set/info-update/{id}', [ProductController::class, 'productSetInfoUpdate'])->name('admin.product_set.info.update');
    Route::get('admin/product-set/info-delete/{id}', [ProductController::class, 'productSetInfoDelete'])->name('admin.product_set.info.delete');
    Route::post('admin/product-set/fabric-store', [ProductController::class, 'productSetFabricStore'])->name('admin.product_set.fabric.store');
    Route::post('admin/product-set/fabric-update/{id}', [ProductController::class, 'productSetFabricUpdate'])->name('admin.product_set.fabric.update');
    Route::get('admin/product-set/fabric-delete/{id}', [ProductController::class, 'productSetFabricDelete'])->name('admin.product_set.fabric.delete');

    // Interior Section Here

    Route::get('admin/interiors', [InteriorController::class, "interiorIindex"])->name('interior_index');
    Route::get('admin/interiors/create', [InteriorController::class, "create"])->name('admin.interior.create');
    Route::post('admin/interiors/store', [InteriorController::class, "store"])->name('admin.interior.store');
    Route::get('admin/interiors/edit/{id}', [InteriorController::class, "edit"])->name('admin.interior.edit');
    Route::post('admin/interiors/update/{id}', [InteriorController::class, "update"])->name('admin.interior.update');
    Route::get('admin/interiors/delete/{id}', [InteriorController::class, "destroy"])->name('admin.interior.delete');
    Route::get('admin/interiorsImage/delete/{id}', [InteriorController::class, "interiorImageDelete"])->name('admin.interiorImage.delete');
    Route::post('admin/interiorsImage/store/', [InteriorController::class, "interiorImageStore"])->name('admin.interiorImage.store');
    Route::post('admin/interiorsImage/update/{id}', [InteriorController::class, "interiorImageUpdate"])->name('admin.interiorImage.update');

    //Interior section end

    // showrooms export and import
    Route::get('import_showrooms_view', ['as' => 'import_showrooms_view', 'uses' => '\App\Http\Controllers\Admin\PostController@import_showrooms_view'])->name('import_showrooms_view');
    Route::post('import_showrooms_save', ['as' => 'import_showrooms', 'uses' => '\App\Http\Controllers\Admin\PostController@import_showrooms'])->name('import_showrooms');
    Route::get('export_showrooms', ['as' => 'export_showrooms', 'uses' => '\App\Http\Controllers\Admin\PostController@export_showrooms'])->name('export_showrooms');

    // import stock
    Route::get('import_stock_view', ['as' => 'import_stock_view', 'uses' => '\App\Http\Controllers\Admin\ProductController@import_stock_view'])->name('import_stock_view');
    Route::post('import_stock_save', ['as' => 'import_stocks', 'uses' => '\App\Http\Controllers\Admin\ProductController@import_stocks'])->name('import_stocks');
    //Route::get('export_showrooms', ['as' => 'export_showrooms', 'uses' => '\App\Http\Controllers\Admin\PostController@export_showrooms'])->name('export_showrooms');


    //ajax
    Route::get('/check_if_url_exists', ['as' => 'check_if_url_exists', 'uses' => '\App\Http\Controllers\Admin\ProductController@check_if_url_exists']);
    Route::get('/check_if_cat_url_exists', ['as' => 'check_if_cat_url_exists', 'uses' => '\App\Http\Controllers\Admin\TermController@check_if_cat_url_exists']);
    Route::get('get_product_json_data', ['as' => 'get_product_json_data', 'uses' => '\App\Http\Controllers\Admin\ProductController@get_product_json_data']);
    // products management routes close

    // Oreder view route start
    // Route::view('order/view',)
    // Oreder view route clouse
    // orders management routes
    Route::any('search_orders', ['as' => 'search_orders', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@orders'])->name('search_orders');
    Route::post('save_or_update_delivery_date', ['as' => 'save_or_update_delivery_date', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@save_or_update_delivery_date'])->name('save_or_update_delivery_date');
    Route::get('orders', ['as' => 'orders', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@orders'])->name('orders');
    Route::get('orders_single/{id}', ['as' => 'orders_single', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@orders_single'])->name('orders_single');
    Route::get('orders/prebooking', ['as' => 'orders', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@orders'])->name('orders.prebooking');
    Route::get('orders/date_between', ['as' => 'orders_date_between', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@date_between'])->name('orders_date_between');
    Route::get('orders/placed_cod', ['as' => 'placed_cash_on_delivery', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@placed_cash_on_delivery'])->name('placed_cash_on_delivery');
    Route::get('orders/placed_opo', ['as' => 'placed_online_payment', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@placed_online_payment'])->name('placed_online_payment');
    Route::get('orders/placed', ['as' => 'orders_placed', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@placed'])->name('orders_placed');
    Route::get('orders/production', ['as' => 'orders_production', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@production'])->name('orders_production');
    Route::get('orders/processing', ['as' => 'orders_processing', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@processing'])->name('orders_processing');
    Route::get('orders/distribution', ['as' => 'orders_distribution', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@distribution'])->name('orders_distribution');
    Route::get('orders/done', ['as' => 'orders_done', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@done'])->name('orders_done');
    Route::get('orders/refund', ['as' => 'orders_refund', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@refund'])->name('orders_refund');
    Route::get('orders/cod', ['as' => 'orders_cod', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@cash_on_delivery'])->name('orders_cod');
    Route::get('orders/deleted', ['as' => 'orders_deleted', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@deleted'])->name('orders_deleted');
    Route::get('orders/temporary', ['as' => 'orders_temporary', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@temporary'])->name('orders_temporary');

    Route::get('orders/move', ['as' => 'orders_move', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@move'])->name('orders_move');
    Route::get('orders/bulk_move', ['as' => 'orders_bulk_move', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@bulk_move'])->name('orders_bulk_move');

    Route::get('most_sold', ['uses' => '\App\Http\Controllers\Admin\SmartReport@most_selling_products'])->name('most_sold');
    Route::get('never_sold', ['uses' => '\App\Http\Controllers\Admin\SmartReport@never_sold_products'])->name('never_sold');

    // Orders/Exports
    Route::get('export_orders', ['as' => 'export_orders', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@export_orders'])->name('export_orders');
    Route::get('export_Search_orders/{column}/{keyword}/{formDate}/{toDate}', ['as' => 'export_Search_orders', 'uses' => '\App\Http\Controllers\Admin\OrdersManagement@exportSearchOrders'])->name('export_Search_orders');


    //Custom Order
    Route::get('orders/custom-order', ['uses' => '\App\Http\Controllers\Admin\OrdersManagement@customOrder'])->name('order.custom_order');
    Route::post('orders/custom-order/store', ['uses' => '\App\Http\Controllers\Admin\OrdersManagement@customOrderStore'])->name('order.custom_order_store');
    Route::post('order/createProduct', '\App\Http\Controllers\Admin\OrdersManagement@createProduct')->name('order.create.product');
    Route::get('order/updateCartProductPrice', ['uses' => '\App\Http\Controllers\Admin\OrdersManagement@customOrder'])->name('order.updateProductPrice');
    Route::get('order/updateDeliveryCharge', ['uses' => '\App\Http\Controllers\Admin\OrdersManagement@customOrder'])->name('order.updateDeliveryCharge');
    Route::get('orders/one-click-buy-now', ['uses' => '\App\Http\Controllers\Admin\OrdersManagement@oneClickBuyNow'])->name('order.one_click_buy_now');
    Route::post('orders/one-click-buy-now/update', ['uses' => '\App\Http\Controllers\Admin\OrdersManagement@oneClickBuyNowUpdate'])->name('order.one_click_buy_now_update');


    //Depot Management
    Route::get('our_depots', ['uses' => '\App\Http\Controllers\Admin\DepotController@index'])->name('depot.index');
    Route::get('our_depots/edit-{id}', ['uses' => '\App\Http\Controllers\Admin\DepotController@index'])->name('depot.edit');
    Route::delete('our_depots/delete{id}', ['uses' => '\App\Http\Controllers\Admin\DepotController@destroy'])->name('depot.destroy');
    Route::get('/get_district_by_division', '\App\Http\Controllers\Admin\DepotController@get_district_by_division')->name('get_district_by_division');
    Route::post('our_depots/store', ['uses' => '\App\Http\Controllers\Admin\DepotController@store'])->name('depot.store');
    Route::post('our_depots/update', ['uses' => '\App\Http\Controllers\Admin\DepotController@update'])->name('depot.update');
    // orders management routes close
    Route::post('staff_note_save', ['as' => 'staff_note_save', 'uses' => '\App\Http\Controllers\Admin\MessageController@store']);
    Route::post('customer_message_save', ['as' => 'customer_message_save', 'uses' => '\App\Http\Controllers\Admin\MessageController@customer_message_save']);

    // Comment Template
    Route::get('commentss', ['as' => 'commentss', 'uses' => '\App\Http\Controllers\Admin\CommentController@comments'])->name('commentss');
    Route::get('add_comment', ['as' => 'add_comment', 'uses' => '\App\Http\Controllers\Admin\CommentController@add_comment'])->name('add_comment');
    Route::post('comment_save', ['as' => 'comment_save', 'uses' => '\App\Http\Controllers\Admin\CommentController@store']);
    // Route::get('comment_save', ['as' => 'comment_save', 'uses' => '\App\Http\Controllers\Admin\CommentController@store']);
    Route::get('edit_comment/{id}', ['as' => 'edit_comment', 'uses' => '\App\Http\Controllers\Admin\CommentController@edit_comment'])->name('edit_comment');
    Route::post('comment/{id}/update', ['as' => 'comment/{id}/update', 'uses' => '\App\Http\Controllers\Admin\CommentController@comment_update_save'])->name('comment_update_save');
    Route::delete('comment_delete/{id}', ['as' => 'comment_delete', 'uses' => '\App\Http\Controllers\Admin\CommentController@destroy']);
    Route::get('quick_comment_approve/{id}', ['as' => 'quick_comment_approve', 'uses' => '\App\Http\Controllers\Admin\CommentController@quick_comment_approve']);

    Route::get('add_reply', ['as' => 'add_reply', 'uses' => '\App\Http\Controllers\Admin\CommentController@add_reply'])->name('add_reply');
    Route::post('reply_save', ['as' => 'reply_save', 'uses' => '\App\Http\Controllers\Admin\CommentController@reply_store'])->name('reply_save.post');
    // Route::get('reply_save', ['as' => 'reply_save', 'uses' => '\App\Http\Controllers\Admin\CommentController@reply_store'])->name('reply_save.get');
    Route::get('edit_reply/{id}', ['as' => 'edit_reply', 'uses' => '\App\Http\Controllers\Admin\CommentController@edit_reply'])->name('edit_reply');
    Route::post('reply/{id}/update', ['as' => 'reply/{id}/update', 'uses' => '\App\Http\Controllers\Admin\CommentController@reply_update_save'])->name('reply_update_save');
    Route::delete('reply_delete/{id}', ['as' => 'reply_delete', 'uses' => '\App\Http\Controllers\Admin\CommentController@destroy']);

    // Comment Template

    // newsletters routes
    Route::get('newsletters', ['as' => 'newsletters', 'uses' => '\App\Http\Controllers\Admin\NewsletterController@newsletters'])->name('newsletters');
    Route::post('newsletter_save', ['as' => 'newsletter_save', 'uses' => '\App\Http\Controllers\Admin\NewsletterController@store'])->name('newsletter_save');
    Route::get('edit_newsletter/{id}', ['as' => 'edit_newsletter', 'uses' => '\App\Http\Controllers\Admin\NewsletterController@edit_newsletter'])->name('edit_newsletter');
    Route::post('newsletter/{id}/update', ['as' => 'newsletter/{id}/update', 'uses' => '\App\Http\Controllers\Admin\NewsletterController@newsletter_update_save'])->name('newsletter_update_save');
    Route::delete('newsletter_delete/{id}', ['as' => 'newsletter_term', 'uses' => '\App\Http\Controllers\Admin\NewsletterController@destroy']);
    Route::get('newsletter_status/{id}/{action}', ['as' => 'newsletter_status', 'uses' => '\App\Http\Controllers\Admin\NewsletterController@newsletter_status'])->name('newsletter_status');

    // Orders/Exports
    Route::get('export_newsletters', ['as' => 'export_newsletters', 'uses' => '\App\Http\Controllers\Admin\NewsletterController@export_newsletters'])->name('export_newsletters');

    // attributes management routes
    Route::get('attributes', ['as' => 'attributes', 'uses' => '\App\Http\Controllers\Admin\AttributeController@attributes'])->name('attributes');
    Route::get('add_attributes/{id}', ['as' => 'add_attributes', 'uses' => '\App\Http\Controllers\Admin\AttributeController@add_attributes'])->name('add_attributes');
    Route::post('attribute_save', ['as' => 'attribute_save', 'uses' => '\App\Http\Controllers\Admin\AttributeController@store'])->name('attribute_save');
    Route::get('edit_attribute/{id}', ['as' => 'edit_attribute', 'uses' => '\App\Http\Controllers\Admin\AttributeController@edit_attribute'])->name('edit_attribute');
    Route::post('attribute/{id}/update', ['as' => 'attribute/{id}/update', 'uses' => '\App\Http\Controllers\Admin\AttributeController@attribute_update_save'])->name('attribute_update_save');
    Route::get('delete_attribute', ['uses' => '\App\Http\Controllers\Admin\AttributeController@destroy'])->name('delete_attribute');
    Route::get('sortable_update', ['uses' => '\App\Http\Controllers\Admin\AttributeController@sortable_update']);
    // attributes management routes close

    // attribute group management routes
    Route::get('add_attributes_groups/{id}', ['as' => 'add_attributes/{id}', 'uses' => '\App\Http\Controllers\Admin\AttributeController@add_attributes_groups'])->name('add_attributes_groups.get');
    Route::post('add_attributes_groups/{id}', ['as' => 'add_attributes/{id}', 'uses' => '\App\Http\Controllers\Admin\AttributeController@add_attributes_groups'])->name('add_attributes_groups.post');
    Route::get('attgroups', ['as' => 'attgroups', 'uses' => '\App\Http\Controllers\Admin\AttgroupController@attgroups'])->name('attgroups');
    Route::get('add_attgroup', ['as' => 'add_attgroup', 'uses' => '\App\Http\Controllers\Admin\AttgroupController@add_attgroup'])->name('add_attgroup.get');
    Route::post('add_attgroup', ['as' => 'add_attgroup', 'uses' => '\App\Http\Controllers\Admin\AttgroupController@add_attgroup'])->name('add_attgroup.post');

    Route::post('add_attgroup_save', ['as' => 'add_attgroup_save', 'uses' => '\App\Http\Controllers\Admin\AttgroupController@store'])->name('add_attgroup_save');
    Route::get('edit_att_group/{id}', ['as' => 'edit_att_group', 'uses' => '\App\Http\Controllers\Admin\AttgroupController@edit_att_group'])->name('edit_att_group');
    Route::post('attgroup/{id}/update', ['as' => 'attgroup/{id}/update', 'uses' => '\App\Http\Controllers\Admin\AttgroupController@attgroup_update_save'])->name('attgroup_update_save');
    Route::delete('attribute_delete/{id}', ['as' => 'attribute_delete', 'uses' => '\App\Http\Controllers\Admin\AttgroupController@destroy']);
    Route::get('delete_attgroup/{id}', ['uses' => '\App\Http\Controllers\Admin\AttgroupController@destroy'])->name('delete_attgroup');

    // attribute group management routes

    // Returs group management routes
    Route::get('all_returns', ['as' => 'all_returns', 'uses' => '\App\Http\Controllers\Admin\ReturnsController@all_returns'])->name('all_returns');
    Route::get('quick_returns_approve/{id}', ['as' => 'quick_returns_approve', 'uses' => '\App\Http\Controllers\Admin\ReturnsController@quick_returns_approve']);

    // Returs group management routes

    // Coupons  management routes
    Route::get('coupons', ['uses' => '\App\Http\Controllers\Admin\CouponController@coupons']);
    Route::get('vouchers', ['uses' => '\App\Http\Controllers\Admin\CouponController@coupons']);
    Route::post('coupon_save', ['as' => 'coupon_save', 'uses' => '\App\Http\Controllers\Admin\CouponController@store'])->name('coupon_save');
    Route::get('edit_coupon/{id}', ['as' => 'edit_coupon', 'uses' => '\App\Http\Controllers\Admin\CouponController@edit_coupon'])->name('edit_coupon');
    Route::post('coupon/{id}/update', ['as' => 'coupon/{id}/update', 'uses' => '\App\Http\Controllers\Admin\CouponController@coupon_update_save'])->name('coupon_update_save');
    Route::delete('coupon_delete/{id}', ['as' => 'delete_coupon', 'uses' => '\App\Http\Controllers\Admin\CouponController@destroy']);
    Route::get('coupon_status/{id}/{action}', ['as' => 'coupon_status', 'uses' => '\App\Http\Controllers\Admin\CouponController@coupon_status'])->name('coupon_status');
    // Coupons management routes close

    // Flash  management routes
    Route::get('flash_schedule', ['as' => 'flash_schedule', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@flash_schedule'])->name('flash_schedule');
    Route::get('flash_item/{id}', ['as' => 'flash_item', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@flash_item'])->name('flash_item');
    Route::post('flash_schedule_save', ['as' => 'flash_schedule_save', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@store'])->name('flash_schedule_save');
    Route::post('flash_item_save', ['as' => 'flash_item_save', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@flash_item_save'])->name('flash_item_save');
    Route::get('edit_flash_schedule/{id}', ['as' => 'edit_flash_schedule', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@edit_flash_schedule'])->name('edit_flash_schedule');
    Route::post('flash_schedule/{id}/update', ['as' => 'flash_schedule/{id}/update', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@flash_schedule_update_save'])->name('flash_schedule_update_save');
    Route::get('flash_schedule_status/{id}/{action}', ['as' => 'flash_schedule_status', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@flash_schedule_status'])->name('flash_schedule_status');
    Route::get('add_schedule_products', ['as' => 'add_schedule_products', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@add_schedule_products'])->name('add_schedule_products');
    // Route::get('store_flash_items', ['as' => 'store_flash_items', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@store_flash_items'])->name('store_flash_items');
    Route::post('store_flash_items', ['as' => 'store_flash_items', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@store_flash_items'])->name('store_flash_items');
    Route::get('delete_flash_item/{id}', ['as' => 'delete_flash_item', 'uses' => '\App\Http\Controllers\Admin\FlashManagerController@delete_flash_item'])->name('delete_flash_item');

    // Flash management routes close

    // setting management routes
    Route::get('settings', ['as' => 'settings', 'uses' => '\App\Http\Controllers\Admin\SettingController@settings'])->name('settings');
    Route::post('setting_save', ['as' => 'setting_save', 'uses' => '\App\Http\Controllers\Admin\SettingController@store'])->name('setting_save');
    Route::get('edit_setting/{id}', ['as' => 'edit_setting', 'uses' => '\App\Http\Controllers\Admin\SettingController@edit_setting'])->name('edit_setting');
    Route::post('setting/{id}/update', ['as' => 'setting/{id}/update', 'uses' => '\App\Http\Controllers\Admin\SettingController@setting_update_save'])->name('setting_update_save');
    Route::delete('setting_delete/{id}', ['as' => 'delete_setting', 'uses' => '\App\Http\Controllers\Admin\SettingController@destroy']);

    Route::get('homesettings', ['as' => 'homesettings', 'uses' => '\App\Http\Controllers\Admin\HomeSettingController@settings'])->name('settings');
    Route::post('homesetting_save', ['as' => 'homesetting_save', 'uses' => '\App\Http\Controllers\Admin\HomeSettingController@store'])->name('setting_save');
    Route::get('edit_homesetting/{id}', ['as' => 'edit_homesetting', 'uses' => '\App\Http\Controllers\Admin\HomeSettingController@edit_setting'])->name('edit_setting');
    Route::post('homesetting/{id}/update', ['as' => 'homesetting/{id}/update', 'uses' => '\App\Http\Controllers\Admin\HomeSettingController@setting_update_save'])->name('setting_update_save');
    Route::delete('homesetting_delete/{id}', ['as' => 'delete_homesetting', 'uses' => '\App\Http\Controllers\Admin\HomeSettingController@destroy']);

    Route::get('payment_settings', ['as' => 'payment_settings', 'uses' => '\App\Http\Controllers\Admin\PaymentSettingController@settings'])->name('payment_settings');
    Route::post('payment_setting_save', ['as' => 'payment_setting_save', 'uses' => '\App\Http\Controllers\Admin\PaymentSettingController@store'])->name('payment_setting_save');
    Route::get('payment_edit_setting/{id}', ['as' => 'payment_edit_setting', 'uses' => '\App\Http\Controllers\Admin\PaymentSettingController@edit_setting'])->name('payment_edit_setting');
    Route::post('payment_setting/{id}/update', ['as' => 'payment_setting/{id}/update', 'uses' => '\App\Http\Controllers\Admin\PaymentSettingController@setting_update_save'])->name('payment_setting');
    Route::delete('payment_setting_delete/{id}', ['as' => 'payment_setting_delete', 'uses' => '\App\Http\Controllers\Admin\PaymentSettingController@destroy']);

    //product Questions answer
    Route::get('questions', '\App\Http\Controllers\Admin\ProductQuestionController@questions')->name('questions');
    Route::get('questions_isActive/{id}', '\App\Http\Controllers\Admin\ProductQuestionController@questions_isActive')->name('question.isActive');
    Route::post('product_question_ans/{id}', '\App\Http\Controllers\Admin\ProductQuestionController@product_question_ans')->name('product_question_ans');
    //End product Questions answer


    //Notifications
    Route::get('notifications', '\App\Http\Controllers\Admin\NotificationController@index')->name('notifications_index');
    Route::post('notifications-read', '\App\Http\Controllers\Admin\NotificationController@readNotifications')->name('notifications_read');


    // setting management routes close

    // test
    Route::get('blank', ['as' => 'blank', 'uses' => '\App\Http\Controllers\BlankController@index'])->name('blank');


    /*District Crud*/
    Route::resource('admin_district', '\App\Http\Controllers\Admin\DistrictController');
    /*End District Crud*/

    // user related routes
    Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
    Route::get('/signout', '\App\Http\Controllers\Auth\LoginController@logout');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'outlet']], function () {

    Route::get('delivery/timespan', [DeliveryController::class, 'timespan'])->name('delivery.timespan');
    Route::post('delivery/timespan/store', [DeliveryController::class, 'timespanStore'])->name('delivery.timespan.store');
    Route::post('delivery/timespan/update/{id}', [DeliveryController::class, 'timespanUpdate'])->name('delivery.timespan.update');
    Route::get('delivery/timespan/status/{id}', [DeliveryController::class, 'timespanStatus'])->name('delivery.timespan.status');
    Route::get('delivery/timespan/delete/{id}', [DeliveryController::class, 'timespanDelete'])->name('delivery.timespan.delete');
    Route::get('delivery/category', [DeliveryController::class, 'categories'])->name('delivery.categories');
    Route::post('delivery/term/timespan/set', [DeliveryController::class, 'termsTimeSpan'])->name('delivery.termsTimeSpan');


    Route::get('slider', [AdminCommonController::class, 'sliders'])->name('common.slider');
    Route::post('slider/store', [AdminCommonController::class, 'sliderStore'])->name('common.slider.store');
    Route::post('slider/update/{id}', [AdminCommonController::class, 'sliderUpdate'])->name('common.slider.update');
    Route::get('slider/delete/{id}', [AdminCommonController::class, 'sliderDelete'])->name('common.slider.delete');

    Route::get('fabricPost', [OthersController::class, 'index'])->name('other.fabricPost');
    Route::get('edit-fabricPost', [OthersController::class, 'edit'])->name('edit.other.fabricPost');
    Route::post('fabricPost/store', [OthersController::class, 'fabricPostStore'])->name('other.fabricPost.store');
    Route::post('fabricPost/update/{id}', [OthersController::class, 'fabricPostUpdate'])->name('other.fabricPost.update');
    Route::get('fabricPost/delete/{id}', [OthersController::class, 'fabricPostDelete'])->name('other.fabricPost.delete');


    Route::get('catgallary', [AdminCommonController::class, 'catgallary'])->name('common.catgallary');
    Route::post('catgallary/store', [AdminCommonController::class, 'catgallaryStore'])->name('common.catgallary.store');
    Route::post('catgallary/update/{id}', [AdminCommonController::class, 'catgallaryUpdate'])->name('common.catgallary.update');
    Route::get('catgallary/delete/{id}', [AdminCommonController::class, 'catgallaryDelete'])->name('common.catgallary.delete');
    Route::post('seo-seetings-store', [AdminCommonController::class, 'seoSettings'])->name('common.seo.settings.store');
});


/** --------------------------------------------------------------------------------- */
/** --------------------------------------------------------------------------------- */
/** -------------- All the web frontend routes are in below part -------------------- */
/** --------------------------------------------------------------------------------- */
/** --------------------------------------------------------------------------------- */

// Main
// Route::get('/reset_password', '\App\Http\Controllers\Site\CommonController@reset_password')->name('reset_password');
// Route::post('/reset_password', '\App\Http\Controllers\Site\CommonController@reset_password')->name('reset_password');
// Route::get('/password_confirmation', '\App\Http\Controllers\Site\CommonController@password_confirmation')->name('password_confirmation');
// Route::post('/retrieve_password', '\App\Http\Controllers\Site\CommonController@retrieve_password')->name('retrieve_password');

// Contact Page
// Route::get('/contact', '\App\Http\Controllers\EmailController@contact')->name('contact');
// Route::post('/send_email', '\App\Http\Controllers\EmailController@send_email')->name('send_email');
// Route::get('/page/{id}/{slug}', '\App\Http\Controllers\Site\HomeController@get_webpage')->name('webpage');
// Route::get('/my_account', '\App\Http\Controllers\Site\CommonController@my_account')->name('my_account');
// Route::get('/profile_update', '\App\Http\Controllers\Site\CommonController@profile_update')->name('profile_update');
// Route::post('/profile_update', '\App\Http\Controllers\Site\CommonController@profile_update')->name('profile_update');

// Route::get('/login_now', '\App\Http\Controllers\Site\CommonController@login_now')->middleware('guest')->name('login_now');
// Route::get('/create_an_account', '\App\Http\Controllers\Site\CommonController@create_an_account')->middleware('guest')->name('create_an_account');
Route::post('/web_login', [CommonController::class, 'web_login'])->name('web_login');
// Route::post('/web_signup', '\App\Http\Controllers\Site\CommonController@web_signup')->name('web_signup');
// Route::get('/logout', 'Site\CommonController@web_logout')->name('web_logout');
// Route::post('/subscribe_email', '\App\Http\Controllers\Site\CommonController@subscribe_email')->name('subscribe_email');
// Route::get('/order_history', '\App\Http\Controllers\Site\CommonController@order_history')->name('order_history');
// Route::get('/return', '\App\Http\Controllers\Site\CommonController@return')->name('return');
// Route::get('/return_save', '\App\Http\Controllers\Site\CommonController@return_save')->name('return_save');
// Route::post('/return_save', '\App\Http\Controllers\Site\CommonController@return_save')->name('return_save');
// Route::get('/wishlist', '\App\Http\Controllers\Site\CommonController@wishlist')->name('wishlist');
// Route::get('/gift_voucher', '\App\Http\Controllers\Site\CommonController@gift_voucher')->name('gift_voucher');
// Route::get('/compare', '\App\Http\Controllers\Site\CommonController@compare')->name('compare');
// Route::get('/sitemap', '\App\Http\Controllers\Site\CommonController@sitemap')->name('sitemap');
// Route::get('/faq', '\App\Http\Controllers\Site\CommonController@faq')->name('faq');
// Route::get('/paymentmethod', '\App\Http\Controllers\Site\CommonController@paymentmethod')->name('paymentmethod');
// Route::get('/our_store', '\App\Http\Controllers\Site\CommonController@our_store')->name('our_store');
// Route::get('/shop_location', '\App\Http\Controllers\Site\CommonController@shop_location')->name('shop_location');
// Route::get('/shop_type', '\App\Http\Controllers\Site\CommonController@shop_type')->name('shop_type');
// Route::get('/order_information', '\App\Http\Controllers\Site\CommonController@order_information')->name('order_information');

// Route::post('/subscribe_email', '\App\Http\Controllers\Site\CommonController@subscribe_email')->name('subscribe_email');

/**
 * Shop Controller
 */
// Route::get('/product/{slug}', '\App\Http\Controllers\Site\ShopController@product_details')->name('product_details');
// Route::get('/p/{slug}', '\App\Http\Controllers\Site\ShopController@product_details')->name('product_details');
// Route::post('/save_review', '\App\Http\Controllers\Site\ShopController@save_review')->name('save_review');

// Route::get('/product/{id}/{slug}', '\App\Http\Controllers\Site\ShopController@product_details')->name('product_details');
// Route::get('/product/{id}', '\App\Http\Controllers\Site\ShopController@product_details')->name('product_details');
// Route::post('/apply_coupon_voucher', '\App\Http\Controllers\Site\ShopController@apply_coupon_voucher')->name('apply_coupon_voucher');

// Route::namespace('Site')->group(function () {
//     Route::get('track_order', 'ShopController@track_order')->name('track_order');
//     Route::post('track_order', 'ShopController@track_order_store');

//     Route::get('advertisements', 'CommonController@advertisementindex')->name('advertisements.index');
//     Route::get('advertisements/{advertisements}', 'CommonController@advertisementshow')->name('advertisements.show');

//     Route::get('testimonials', 'CommonController@testimonialindex')->name('testimonials.index');
//     Route::get('testimonials/{testimonials}', 'CommonController@testimonialshow')->name('testimonials.show');

//     Route::get('protfolios', 'CommonController@protfolioindex')->name('protfolios.index');
//     Route::get('protfolios/{protfolios}', 'CommonController@protfolioshow')->name('protfolios.show');

//     Route::get('store_location', 'ShopController@store_location');
//     Route::get('new_arrival', 'ShopController@new_arrival');

//     // News and events
//     Route::get('news_events', 'CommonController@newseventsindex')->name('newsevents.index');
//     Route::get('news_events/{news_event}', 'CommonController@newseventsshow')->name('newsevents.show');

//     // preload
//     Route::get('cookie', 'CommonController@preload')->name('cookie');
// });

// Social Login
// Route::get('login/facebook', 'Auth\LoginController@redirectToProvider');
// Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');

// Route::get('/main_search_product_ajax', '\App\Http\Controllers\Site\ShopController@main_search_product_ajax')->name('main_search_product_ajax');
// Route::get('/main_search_form', '\App\Http\Controllers\Site\ShopController@main_search_form')->name('main_search_form');

//Route::get('/category/{id}/{slug}', '\App\Http\Controllers\Site\ShopController@get_products_by_category')->name('get_products_by_category'); // removed
//Route::get('/category/{id}', '\App\Http\Controllers\Site\ShopController@get_products_by_category')->name('get_products_by_category'); // removed


// Route::get('/c/{slug}', '\App\Http\Controllers\Site\ShopController@search_product')->name('search_product'); // category based product lists
//Route::get('/c/{slug}', '\App\Http\Controllers\Site\ShopController@search_product')->name('search_product'); // category based product lists
// Route::get('search_url_make', '\App\Http\Controllers\Site\ShopController@search_url_make')->name('search_url_make'); // category based product lists
// Route::get('/category/{slug}', '\App\Http\Controllers\Site\ShopController@search_product')->name('search_product'); // category based product lists
// Route::get('/search', '\App\Http\Controllers\Site\ShopController@search_product')->name('search_product'); // category based product lists with search
// Route::get('/get_tab_data', '\App\Http\Controllers\Site\ShopController@get_tab_data')->name('get_tab_data'); // category based product lists with search

// Route::get('/change_combinition', '\App\Http\Controllers\Site\ShopController@change_combinition')->name('change_combinition');
// Route::get('/modify_variation', '\App\Http\Controllers\Site\ShopController@modify_variation')->name('modify_variation');
// Route::get('/get_size_price_data', '\App\Http\Controllers\Site\ShopController@get_size_price_data')->name('get_size_price_data');

// Route::get('/set_emi', '\App\Http\Controllers\Site\ShopController@set_emi')->name('set_emi');
// Route::get('/unset_emi', '\App\Http\Controllers\Site\ShopController@unset_emi')->name('unset_emi');
// Route::get('/add_to_cart', '\App\Http\Controllers\Site\ShopController@add_to_cart')->name('add_to_cart');
// Route::get('/add_to_compare', '\App\Http\Controllers\Site\ShopController@add_to_compare')->name('add_to_compare');
// Route::get('/add_to_wishlist', '\App\Http\Controllers\Site\ShopController@add_to_wishlist')->name('add_to_wishlist');
// Route::get('/view_compare', '\App\Http\Controllers\Site\ShopController@view_compare')->name('view_compare');
// Route::get('/view_wishlist', '\App\Http\Controllers\Site\ShopController@view_wishlist')->name('view_wishlist');
// Route::get('/remove_cart_item', '\App\Http\Controllers\Site\ShopController@remove_cart_item')->name('remove_cart_item');
// Route::get('/remove_compare_product', '\App\Http\Controllers\Site\ShopController@remove_compare_product')->name('remove_compare_product');
// Route::get('/remove_wishlist_product', '\App\Http\Controllers\Site\ShopController@remove_wishlist_product')->name('remove_wishlist_product');
// Route::post('/update_qty', '\App\Http\Controllers\Site\ShopController@update_qty')->name('update_qty');
// Route::get('/view_cart', '\App\Http\Controllers\Site\ShopController@view_cart')->name('view_cart');
// Route::get('/checkout/address', '\App\Http\Controllers\Site\ShopController@checkout_address')->name('checkout_address');
// Route::post('/checkout/delivery_address', '\App\Http\Controllers\Site\ShopController@checkout_delivery_address')->name('checkout_delivery_address');
// Route::get('/checkout/payment_method', '\App\Http\Controllers\Site\ShopController@checkout_payment_method')->name('checkout_payment_method');
// Route::post('/checkout/store_payment_method', '\App\Http\Controllers\Site\ShopController@store_payment_method')->name('store_payment_method');
// Route::get('/checkout/review_order', '\App\Http\Controllers\Site\ShopController@review_order')->name('review_order');
// Route::get('/checkout/pay_now', '\App\Http\Controllers\Site\ShopController@pay_now')->name('pay_now');
Route::get('/invoice', '\App\Http\Controllers\Site\ShopController@success')->name('success');
Route::post('/pdf/invoice', '\App\Http\Controllers\Site\ShopController@pdf_invoice')->name('pdf_invoice');
// Route::get('flash_products', '\App\Http\Controllers\Site\ShopController@flash_products')->name('flash_products');

// Route::get('/sc/{slug}', '\App\Http\Controllers\Site\HomeController@specific_category')->name('specific_category');
// Route::get('/affiliates', '\App\Http\Controllers\Site\HomeController@dealers')->name('dealers');

// Route::get('/sc/{id}/{slug}', '\App\Http\Controllers\Site\HomeController@specific_category')->name('specific_category');
// Route::get('/get_district_by_division', '\App\Http\Controllers\Site\HomeController@get_district_by_division')->name('get_district_by_division');
// Route::get('/get_thana_by_district', '\App\Http\Controllers\Site\HomeController@get_thana_by_district')->name('get_thana_by_district');
// Route::get('/get_showroom_by_thana', '\App\Http\Controllers\Site\HomeController@get_showroom_by_thana')->name('get_showroom_by_thana');

// // checkout start
// Route::post('/checkout/success', '\App\Http\Controllers\Site\ShopController@success')->name('success');
// Route::get('/checkout/success', '\App\Http\Controllers\Site\ShopController@success')->name('success');
// Route::post('/checkout/fail', '\App\Http\Controllers\Site\ShopController@fail')->name('fail');
// Route::post('/checkout/cancel', '\App\Http\Controllers\Site\ShopController@cancel')->name('checkout_cancel');
// // checkout end

// // Be a vendor
// Route::post('be_a_vendor', '\App\Http\Controllers\Site\CommonController@be_a_vendor')->name('be_a_vendor');
// Route::get('be_a_vendor', '\App\Http\Controllers\Site\CommonController@be_a_vendor')->name('be_a_vendor');
// Route::get('save_vendor', '\App\Http\Controllers\Site\CommonController@save_vendor')->name('save_vendor');
// Route::post('save_vendor', '\App\Http\Controllers\Site\CommonController@save_vendor')->name('save_vendor');
// Route::get('item_cart_load', '\App\Http\Controllers\Site\CommonController@item_cart_load')->name('item_cart_load');

// //Be a vendor


// // product question and answer
// Route::get('product_questions/{id}', '\App\Http\Controllers\Site\ShopController@product_questions')->name('product_questions');
// Route::post('product_question_post/{id}', '\App\Http\Controllers\Site\ShopController@product_question_post')->name('product_question_post');
// Route::get('product_rating/{id}', '\App\Http\Controllers\Site\ShopController@product_rating')->name('product_rating');
// Route::get('product_comments/{id}','\App\Http\Controllers\Site\ShopController@product_comments')->name('product_comments');
// Route::post('product_comments/{id}','\App\Http\Controllers\Site\ShopController@product_comment_save')->name('product_comment_save');
// // product question and answer End


// Route::get('/item_wishlist_load', '\App\Http\Controllers\Site\CommonController@item_wishlist_load')->name('item_wishlist_load');
// Route::get('/item_compare_load', '\App\Http\Controllers\Site\CommonController@item_compare_load')->name('item_compare_load');
// // Comment Template

// // Helping & Ajax Routes
// Route::get('/check_if_email_exists', '\App\Http\Controllers\Site\ShopController@check_if_email_exists')->name('check_if_email_exists');
// Route::get('/mini_cart', '\App\Http\Controllers\Site\ShopController@mini_cart')->name('mini_cart');
// Route::get('/mini_compare', '\App\Http\Controllers\Site\ShopController@mini_compare')->name('mini_compare');
// Route::get('/get_product_pricing', '\App\Http\Controllers\Site\ShopController@get_product_pricing')->name('get_product_pricing');

Route::get('/', [HomeController::class, 'login']);
Route::post('/login', [HomeController::class, 'login'])->name('login');

/** --------------------------------------------------------------------------------- */
/** --------------------------------------------------------------------------------- */
/** ---------------- All the web test routes are in below part ---------------------- */
/** --------------------------------------------------------------------------------- */
/** --------------------------------------------------------------------------------- */

// Tests
// Route::get('todos', '\App\Http\Controllers\TodoController@getAllTodos');
// Route::get('/home', [HomeController::class,'index'])->name('home');
// Route::get('/products/categories', '\App\Http\Controllers\Site\HomeController@all_data')->name('product_categories');

// // All Data
// Route::get('all_data', '\App\Http\Controllers\Site\HomeController@all_data')->name('all_data');
Route::get('phpinfo', [HomeController::class, 'phpInfo']);

// Utility Routes - Cache Management
Route::get('/clear-cache', [UtilityController::class, 'clearCache']);
Route::get('/optimize', [UtilityController::class, 'optimize']);
Route::get('/route-cache', [UtilityController::class, 'routeCache']);
Route::get('/route-clear', [UtilityController::class, 'routeClear']);
Route::get('/view-clear', [UtilityController::class, 'viewClear']);
Route::get('/config-clear', [UtilityController::class, 'configClear']);
Route::get('/config-cache', [UtilityController::class, 'configCache']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('custom-order/search_products', ['uses' => '\App\Http\Controllers\Admin\ProductController@search_product'])->name('custom_order_search_poduct');
Route::get('custom-order/select_products', ['uses' => '\App\Http\Controllers\Admin\ProductController@product_details_by_id'])->name('custom_order_select_poduct');
//Route::get('shop/add_to_cart',['uses' => '\App\Http\Controllers\Admin\ProductController@add_to_cart']);
//Route::get('shop/cart',['uses' => '\App\Http\Controllers\Admin\ProductController@cart']);
Route::get('view-etemp', function () {
//    $self_token = '16700495958290.mctgvy2k4ym';
//    $cart_token = "cart_".$self_token;
//    $pm_token = "paymethod_".$self_token;
//    $ud_token = "user_details".$self_token;
//    $exord_token = "existing_order_id".$self_token;
//
//    $session_data = function($key){
//       return App\Models\SessionData::where('session_key',$key)->first();
//    };
//    $cart_session = $session_data($cart_token);
//    $cart = ($cart_session->session_data??false) ? json_decode($cart_session->session_data) : null;
//
//    $pm_session = $session_data($pm_token);
//    $pm = ($pm_session->session_data??false) ? json_decode($pm_session->session_data) : null;
//
//    $ud_session = $session_data($ud_token);
//    $ud = ($ud_session->session_data??false) ? json_decode($ud_session->session_data) : null;
//
//
//    $data = [
//        'cart' => $cart,
//        'coupon_details' => null,
//        'user_details' => $ud,
//        'payment_method' => $pm,
//    ];
//    //dd($ud);
//    $rand = time().uniqid('rand');
//    $secret_key = time().uniqid('secret');
//    $user_id =  1;
//
//    $orders_master_attributes = order_master_create($data, $rand, $secret_key, $user_id);
//
//    $order_detail_created = order_detail_create($data, $rand, $secret_key, $user_id);
//    dump($orders_master_attributes);
//    dump($order_detail_created);
//    die();
//    $data = 13098;
//    $subject = "Thank You Order";
//    $address = 'nipun@tritiyo.com';
//    \App\Helpers\OrderMailHelper::send($data, $subject, $address, $cc_emails = false);
    return view('order-email-template');
});
Route::get('/tests', function () {
    return redirect("http://localhost:3000/redirect-from-ssl?order_random=3qX4LnE4ycbEncQ&order_key=5rOu6wFOdU9xqif9m1sKaKqptQj2Hqya33OcgVqu3CiofC4oE3&from=ssl")->with('params', ['d', 'w']);
});
