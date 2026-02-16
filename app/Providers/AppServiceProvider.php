<?php

namespace App\Providers;

use App\Repositories\Address\EloquentAddress;
use App\Repositories\Address\AddressInterface;
use App\Repositories\Attgroup\AttgroupInterface;
use App\Repositories\Attgroup\EloquentAttgroup;
use App\Repositories\Attribute\AttributeInterface;
use App\Repositories\Attribute\EloquentAttribute;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Bank\EloquentBank;
use App\Repositories\Comment\CommentInterface;
use App\Repositories\Comment\EloquentComment;
use App\Repositories\Coupon\CouponInterface;
use App\Repositories\Coupon\EloquentCoupon;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\Dashboard\EloquentDashboard;
use App\Repositories\Dealer\DealerInterface;
use App\Repositories\Dealer\EloquentDealer;
use App\Repositories\District\DistrictInterface;
use App\Repositories\District\EloquentDistrict;
use App\Repositories\Emi\EloquentEmi;
use App\Repositories\Emi\EmiInterface;
use App\Repositories\FlashItem\EloquentFlashItem;
use App\Repositories\FlashItem\FlashItemInterface;
use App\Repositories\FlashShedule\EloquentFlashShedule;
use App\Repositories\FlashShedule\FlashSheduleInterface;
use App\Repositories\HomeSetting\EloquentHomeSetting;
use App\Repositories\HomeSetting\HomeSettingInterface;
use App\Repositories\Message\EloquentMessage;
use App\Repositories\Message\MessageInterface;
use App\Repositories\TagGallery\EloquentTagGallery;
use App\Repositories\TagGallery\TagGalleryInterface;
use App\Repositories\Media\EloquentMedia;
use App\Repositories\Media\MediaInterface;
use App\Repositories\Newsletter\EloquentNewsletter;
use App\Repositories\Newsletter\NewsletterInterface;
use App\Repositories\OrdersDetail\EloquentOrdersDetail;
use App\Repositories\OrdersDetail\OrdersDetailInterface;
use App\Repositories\OrdersMaster\EloquentOrdersMaster;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\Page\EloquentPage;
use App\Repositories\Page\PageInterface;
use App\Repositories\PaymentSetting\EloquentPaymentSetting;
use App\Repositories\PaymentSetting\PaymentSettingInterface;
use App\Repositories\Pcombinationdata\EloquentPcombinationdata;
use App\Repositories\Pcombinationdata\PcombinationdataInterface;
use App\Repositories\Point\EloquentPoint;
use App\Repositories\Point\PointInterface;
use App\Repositories\Post\EloquentPost;
use App\Repositories\Post\PostInterface;
use App\Repositories\Product\EloquentProduct;
use App\Repositories\Product\ProductInterface;
use App\Repositories\ProductAttributesData\EloquentProductAttributesData;
use App\Repositories\ProductAttributesData\ProductAttributesDataInterface;
use App\Repositories\ProductCategories\EloquentProductCategories;
use App\Repositories\ProductCategories\ProductCategoriesInterface;
use App\Repositories\ProductImages\EloquentProductImages;
use App\Repositories\ProductImages\ProductImagesInterface;
use App\Repositories\Productpricecombination\EloquentProductpricecombination;
use App\Repositories\Productpricecombination\ProductpricecombinationInterface;
use App\Repositories\ProductQuestion\EloquentProductQuestion;
use App\Repositories\ProductQuestion\ProductQuestionInterface;
use App\Repositories\RelatedProducts\EloquentRelatedProducts;
use App\Repositories\RelatedProducts\RelatedProductsInterface;
use App\Repositories\Report\EloquentReport;
use App\Repositories\Report\ReportInterface;
use App\Repositories\Returns\EloquentReturns;
use App\Repositories\Returns\ReturnsInterface;
use App\Repositories\Review\EloquentReview;
use App\Repositories\Review\ReviewInterface;
use App\Repositories\Role\EloquentRole;
use App\Repositories\Role\RoleInterface;
use App\Repositories\Role_user\EloquentRole_user;
use App\Repositories\Role_user\Role_userInterface;
use App\Repositories\SessionData\EloquentSessionData;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\Setting\EloquentSetting;
use App\Repositories\Setting\SettingInterface;
use App\Repositories\Slider\EloquentSlider;
use App\Repositories\Slider\SliderInterface;
use App\Repositories\Temporaryorder\EloquentTemporaryorder;
use App\Repositories\Temporaryorder\TemporaryorderInterface;
use App\Repositories\Term\EloquentTerm;
use App\Repositories\Term\TermInterface;
use App\Repositories\Todo\EloquentTodo;
use App\Repositories\Todo\TodoInterface;
use App\Repositories\User\EloquentUser;
use App\Repositories\User\UserInterface;
use App\Repositories\Variation\EloquentVariation;
use App\Repositories\Variation\VariationInterface;
use App\Repositories\ProductSet\ProductSetInterface;
use App\Repositories\ProductSet\EloquentProductSet;
use App\Repositories\ReviewImage\ReviewImageInterface;
use App\Repositories\ReviewImage\EloquentReviewImage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TodoInterface::class, EloquentTodo::class);
        $this->app->singleton(MediaInterface::class, EloquentMedia::class);
        $this->app->singleton(UserInterface::class, EloquentUser::class);
        $this->app->singleton(DashboardInterface::class, EloquentDashboard::class);
        $this->app->singleton(TermInterface::class, EloquentTerm::class);
        $this->app->singleton(BankInterface::class, EloquentBank::class);
        $this->app->singleton(SettingInterface::class, EloquentSetting::class);
        $this->app->singleton(PaymentSettingInterface::class, EloquentPaymentSetting::class);
        $this->app->singleton(RoleInterface::class, EloquentRole::class);
        $this->app->singleton(Role_userInterface::class, EloquentRole_user::class);
        $this->app->singleton(PostInterface::class, EloquentPost::class);
        $this->app->singleton(ProductInterface::class, EloquentProduct::class);
        $this->app->singleton(RelatedProductsInterface::class, EloquentRelatedProducts::class);
        $this->app->singleton(AttributeInterface::class, EloquentAttribute::class);
        $this->app->singleton(AttgroupInterface::class, EloquentAttgroup::class);
        $this->app->singleton(VariationInterface::class, EloquentVariation::class);
        $this->app->singleton(PageInterface::class, EloquentPage::class);
        $this->app->singleton(DealerInterface::class, EloquentDealer::class);
        $this->app->singleton(TemporaryorderInterface::class, EloquentTemporaryorder::class);
        $this->app->singleton(OrdersMasterInterface::class, EloquentOrdersMaster::class);
        $this->app->singleton(OrdersDetailInterface::class, EloquentOrdersDetail::class);
        $this->app->singleton(CommentInterface::class, EloquentComment::class);
        $this->app->singleton(NewsletterInterface::class, EloquentNewsletter::class);
        $this->app->singleton(ProductCategoriesInterface::class, EloquentProductCategories::class);
        $this->app->singleton(ProductImagesInterface::class, EloquentProductImages::class);
        $this->app->singleton(ProductAttributesDataInterface::class, EloquentProductAttributesData::class);
        $this->app->singleton(HomeSettingInterface::class, EloquentHomeSetting::class);
        $this->app->singleton(ReturnsInterface::class, EloquentReturns::class);
        $this->app->singleton(ProductpricecombinationInterface::class, EloquentProductpricecombination::class);
        $this->app->singleton(PcombinationdataInterface::class, EloquentPcombinationdata::class);
        $this->app->singleton(ReviewInterface::class, EloquentReview::class);
        $this->app->singleton(CouponInterface::class, EloquentCoupon::class);
        $this->app->singleton(NewsletterInterface::class, EloquentNewsletter::class);
        $this->app->singleton(EmiInterface::class, EloquentEmi::class);
        $this->app->singleton(FlashSheduleInterface::class, EloquentFlashShedule::class);
        $this->app->singleton(FlashItemInterface::class, EloquentFlashItem::class);
        $this->app->singleton(ReportInterface::class, EloquentReport::class);
        $this->app->singleton(PointInterface::class, EloquentPoint::class);
        $this->app->singleton(ProductQuestionInterface::class, EloquentProductQuestion::class);
        $this->app->singleton(SliderInterface::class, EloquentSlider::class);
        $this->app->singleton(TagGalleryInterface::class, EloquentTagGallery::class);
        $this->app->singleton(ProductSetInterface::class, EloquentProductSet::class);
        $this->app->singleton(SessionDataInterface::class, EloquentSessionData::class);
        $this->app->singleton(AddressInterface::class, EloquentAddress::class);
        $this->app->singleton(ReviewImageInterface::class, EloquentReviewImage::class);
        $this->app->singleton(DistrictInterface::class, EloquentDistrict::class);
        $this->app->singleton(MessageInterface::class, EloquentMessage::class);


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
