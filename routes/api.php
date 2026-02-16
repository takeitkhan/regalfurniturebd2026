<?php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BkashController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\InteriorController;
use App\Http\Controllers\API\NagadController;
use App\Http\Controllers\API\OthersController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\StockController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CashOnDeliveryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([
    'prefix' => 'user'
], function () {

    Route::post('forgot-password-send-link', [ForgotPasswordController::class, 'callResetLink']);
    Route::post('reset-password', [ResetPasswordController::class, 'callReset']);

});

Route::group(['namespace' => 'API', 'prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {

    Route::get('add-to-wishlist', [UserController::class, 'addToWishlist']);
    Route::get('remove-wishlist', [UserController::class, 'removeWishlist']);
    Route::get('wishlist', [UserController::class, 'viewWishlist']);
    Route::get('info', [UserController::class, 'userInfo']);
    Route::get('reviews', [UserController::class, 'userReview']);
    Route::get('orders', [UserController::class, 'userOrder']);
    Route::get('address-store', [UserController::class, 'addressStore']);
    Route::get('address-list', [UserController::class, 'addressView']);
    Route::get('address-update', [UserController::class, 'addressUpdate']);
    Route::get('address-delete', [UserController::class, 'addressDelete']);
    Route::post('change-password', [UserController::class, 'changePassword']);
    Route::get('set-default-address', [UserController::class, 'setDefaultAddress']);
    Route::get('get-default-address', [UserController::class, 'getDefaultAddress']);
    Route::post('user-update', [UserController::class, 'userUpdate']);
    Route::get('sum-order', [UserController::class, 'sumOrderNumers']);


});

Route::group(['namespace' => 'API', 'prefix' => 'user'], function () {

    Route::post('subscribe', [UserController::class, 'userSubscribe']);
    Route::post('want-know-order-update', [UserController::class, 'userWantKnowOrderUpdate']);
    Route::post('get-user--order-complaint', [UserController::class, 'getUserOrderComplaint']);
    Route::get('delete-account', [UserController::class, 'deleteAccount']);

});

Route::group(['namespace' => 'API', 'prefix' => 'common'], function () {

    Route::get('sliders', [CommonController::class, 'sliders']);
    Route::get('main-search-product', [CommonController::class, 'main_search_product_ajax']);
    Route::get('flash-sales', [CommonController::class, 'flashSales']);
    Route::get('add-to-compare', [CommonController::class, 'addToCompare']);
    Route::get('compare', [CommonController::class, 'viewCompare']);
    Route::get('remove-compare', [CommonController::class, 'removeCompare']);
    Route::get('header', [CommonController::class, 'header']);
    Route::get('footer', [CommonController::class, 'footer']);
    Route::get('page', [CommonController::class, 'page']);
    Route::get('top-offers', [CommonController::class, 'topOffers']);
    Route::get('product-set', [CommonController::class, 'productSet']);
    Route::get('districts', [CommonController::class, 'districts']);
    Route::get('districts-by-diviison/{division_id}', [CommonController::class, 'districtsByDivision']);
    Route::get('showrooms', [CommonController::class, 'showrooms']);
    Route::get('all-terms', [CommonController::class, 'allTerms']);
    Route::get('send-email', [CommonController::class, 'send_email']);
    Route::get('showroom-chatbuy', [CommonController::class, 'getChatbuy']);
    Route::get('widget', [CommonController::class, 'witgetBYId']);

    Route::get('menu_items', [CommonController::class, 'menuItems']);
    Route::get('site-map', [CommonController::class, 'getSiteMap']);
    Route::post('submit-contact-form', [CommonController::class, 'submitContactForm']);
    Route::post('get-seo-content', [CommonController::class, 'getSeoContent']);

});

Route::group(['namespace' => 'API', 'prefix' => 'others'], function () {
    Route::get('fabric-post', [OthersController::class, 'getFabric']);
});


Route::group(['namespace' => 'API', 'prefix' => 'home'], function () {

    Route::get('settings', [HomeController::class, 'settings']);
    Route::get('sliders', [HomeController::class, 'sliders']);
    Route::get('top-category', [HomeController::class, 'topCategory']);
    Route::get('top-offers', [HomeController::class, 'topOffers']);
    Route::get('prebookings', [HomeController::class, 'prebookings']);
    Route::get('new-arrivals', [HomeController::class, 'newArrivals']);
    Route::get('flash-sales', [HomeController::class, 'flashSales']);
    Route::get('tag-gallary', [HomeController::class, 'tagGallary']);
    Route::get('news-events', [HomeController::class, 'newsEvents']);
    Route::get('blog-posts', [HomeController::class, 'blogPost']);
    Route::get('blog-details', [HomeController::class, 'blogDetails']);
    Route::get('terms', [HomeController::class, 'allTerms']);
    Route::get('product-set', [HomeController::class, 'ProductSet']);

});

Route::group(['namespace' => 'API', 'prefix' => 'category'], function () {

    Route::get('info', [CategoryController::class, 'categoryInfo']);
    Route::get('sub-category', [CategoryController::class, 'subCategory']);
    Route::get('recommended', [CategoryController::class, 'recommended']);
    Route::get('products', [CategoryController::class, 'products']);
    Route::get("filters", [CategoryController::class, 'getFilter']);
    Route::get("tag-gallery", [CategoryController::class, 'tagGallery']);
    Route::get("similar-products", [ProductController::class, 'similarProducts']);
    Route::get('product-set', [CategoryController::class, 'productSet']);

});

Route::group(['namespace' => 'API', 'prefix' => 'product'], function () {

    Route::get("similar-products", [ProductController::class, 'similarProducts']);
    Route::get("similar-products", [ProductController::class, 'similarProducts']);
    Route::get('same-category-products', [ProductController::class, 'sameCategory']);
    Route::get("simple-info", [ProductController::class, 'simpleInfo']);
    Route::get("info", [ProductController::class, 'info']);
    Route::get("images", [ProductController::class, 'image']);
    Route::get("same-category-products", [ProductController::class, 'sameCategoryProductList']);
    Route::get("goes-well-with", [ProductController::class, 'goesWellWith']);
    Route::get("other-also-see", [ProductController::class, 'otherAlsoSee']);
    Route::get('product-set-products', [ProductController::class, 'productSetProducts']);
    Route::get('product-set-info', [ProductController::class, 'productSetInfo']);
    Route::get('product-set-additional-info', [ProductController::class, 'productSetAdditionalInfo']);
    Route::get('product-set-fabrics', [ProductController::class, 'productSetFabric']);
    Route::get('recent-views-product', [ProductController::class, 'getRecentViews']);
    Route::post('product-set-qty', [ProductController::class, 'productSetQty']);
    Route::get('360degreeImages', [ProductController::class, 'DegreeImages']);
    Route::get('product-variation', [ProductController::class, 'getProductVariaton']);

    Route::get('get-product-attribute/{product_id}', [ProductController::class, 'getProoductAttribute']);
    Route::get('get-product-variation/{product_id}', [ProductController::class, 'getProoductVariation']);
    Route::get('get-product-variation-another/{product_id}', [ProductController::class, 'getProoductVariationAnother']);
    Route::any('get-customer-selected-product-variation',
        [ProductController::class, 'getCustomerSelectProductVariation']);

    Route::get('comment-index', [ProductController::class, 'commentIndex']);
    Route::get('comment-store', [ProductController::class, 'commentStore'])->middleware('auth:sanctum');
    Route::get('comment-delete', [ProductController::class, 'commentUpdate'])->middleware('auth:sanctum');
    Route::get('comment-update', [ProductController::class, 'commentDelete'])->middleware('auth:sanctum');
    Route::any('single-product-review/{id}', [ProductController::class, 'singleProductReview']);
    Route::post('single-product-review-store', [ProductController::class, 'singleProductReviewStore']);
    Route::get('review-store', [ProductController::class, 'reviewStore'])->middleware('auth:sanctum');
    Route::get('review-view', [ProductController::class, 'reviewView']);
    Route::get('review-update', [ProductController::class, 'reviewUpdate'])->middleware('auth:sanctum');
    Route::get('review-delete', [ProductController::class, 'reviewDelete'])->middleware('auth:sanctum');

    Route::post('question-store', [ProductController::class, 'questionStore'])->middleware('auth:sanctum');
    Route::post('question-update', [ProductController::class, 'questionUpdate'])->middleware('auth:sanctum');
    Route::get('question-delete', [ProductController::class, 'questionDelete'])->middleware('auth:sanctum');
    Route::get('question-index', [ProductController::class, 'questionIndex']);
    Route::post('review-image-upload', [ProductController::class, 'imageUpload']);
    Route::get('check-stock', [StockController::class, 'checkStock']);

});

Route::group(['namespace' => 'API', 'as' => 'admin.'], function () {
    Route::get("auth-api-testing", [HomeController::class, 'test']);
});


Route::middleware('auth:sanctum')->get('/auth/user', [AuthController::class, 'user']);
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('otp-generate', [AuthController::class, 'otpGenerate']);
});


Route::group(['namespace' => 'API', 'prefix' => 'cart'], function () {

    Route::post("test", [CartController::class, 'test']);
    Route::get("view", [CartController::class, 'getCart']);
    Route::get("add-to-cart", [CartController::class, 'addToCart']);
    Route::post("update", [CartController::class, 'updateCart']);
    Route::get("remove-cart-item", [CartController::class, 'removeCartItem']);
    Route::get('checkout-address', [CartController::class, 'checkoutAddress']);
    Route::post('checkout-delivery-address', [CartController::class, 'checkoutDelivreyAddress']);
    Route::post('store-payment-method', [CartController::class, 'storePaymentMethod']);
    Route::get('order-details', [CartController::class, 'orderDetails']);
    Route::get('track-order', [CartController::class, 'trackOrder']);
    Route::post('coupon-apply', [CartController::class, 'couponApply']);
    Route::post('add-to-cart-multi', [CartController::class, 'addCartMulti']);
    Route::post('set-paying-amount', [CartController::class, 'setPayingAmount']);
    Route::get('get-delivery-charge', [CartController::class, 'getDeliveryCharge']);
    Route::get('count-notification', [CartController::class, 'countNotification']);
    Route::get('get-payment-gateway', [CartController::class, 'getPaymentGateway']);
    Route::post('one-click-buy', [CartController::class, 'oneClickBuyNow'])->name('one_click_buy');
});


Route::group(['namespace' => 'API', 'prefix' => 'interior'], function () {

    Route::get('all', [InteriorController::class, 'all']);
    Route::get('show', [InteriorController::class, 'show']);
    Route::get('category', [InteriorController::class, 'category']);
    Route::get('parent_cats', [InteriorController::class, 'parent_cats']);

});

Route::group(['namespace' => 'API'], function () {
    //->middleware('cors')
    Route::prefix('nagad')->group(function () {
        Route::get('pay-via-ajax', [NagadController::class, 'payViaAjax']);
        Route::get('receive-nagad-data', [NagadController::class, 'receiveNagadData']);
        Route::post('existing-order-pay', [NagadController::class, 'existingOrderPay']);
        Route::post('success', [NagadController::class, 'success']);
        Route::post('fail', [NagadController::class, 'fail']);
        Route::post('ipn', [NagadController::class, 'ipn']);
    });

    //Bkash

    // Checkout (URL) User Part
    Route::post('/bkash/create', [BkashController::class, 'create'])->name('url-create');
    Route::get('/bkash/callback', [BkashController::class, 'callback'])->name('url-callback');

    // Checkout (URL) Admin Part
//    Route::post('/bkash/refund', [BkashController::class, 'refund'])->name('url-post-refund');
//    Route::get('/bkash/refund', [BkashController::class, 'refund'])->name('url-post-refund-get');


});

Route::post('cod-place-order', [CashOnDeliveryController::class, 'placeOrder'])->name('cod_place_order');
Route::get('/con', function () {
    return view('emails.contact-form-email-template');
});


Route::any('/user-location', [CommonController::class, 'getUserLocation']);
Route::get('/maximum', [StockController::class, 'getMaximumProductArriveTime']);
