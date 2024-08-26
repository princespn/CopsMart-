<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResources(['category'=> 'API\Common\CategoryController']);
Route::get('category_all', 'API\Common\CategoryController@allCategory');
Route::apiResources(['category/{categoryId}/slab'=> 'API\Common\CategorySlabController']);
Route::apiResources(['category/{categoryId}/cat'=> 'API\Common\CategoryCatController']);
Route::get('category_by_id/{category_id}','API\Common\CategoryController@category_by_id');
Route::get('prod_category_by_id/{category_id}','API\Common\ProductController@category_by_id');
Route::apiResources(['subCategory'=> 'API\Common\SubCategoryController']);
Route::apiResources(['subCategory/{categoryId}/cat'=> 'API\Common\SubCategoryCatController']);
Route::apiResources(['mst_package'=> 'API\Common\MstPackageController']);
Route::apiResources(['marketing_box'=> 'API\Common\MarketingBoxController']);
Route::apiResources(['delivery_charges'=> 'API\Common\DeliveryChargesController']);
Route::apiResources(['offer_product'=> 'API\Common\OfferProductController']);
Route::get('loadVendor', 'API\Common\MarketingBoxController@loadVendor');
Route::get('loadVendorCat/{category_id}', 'API\Common\MarketingBoxController@loadVendorCat');
Route::apiResources(['product'=> 'API\Common\ProductController']);
Route::apiResources(['admin_product'=> 'API\Common\AdminProductController']);
Route::post('product_update/{id}', 'API\Common\ProductController@update');
Route::post('admin_product_update/{id}', 'API\Common\AdminProductController@update');
Route::get('product_all/','API\Common\ProductController@all');

//Route::apiResources(['category'=> 'API\Common\ProductController']);

Route::get('pro_category','API\Common\ProductController@category');
Route::get('sub_category_by_cat/{category_id}','API\Common\SubCategoryController@sub_category');
Route::get('super_sub_category_by_cat/{category_id}','API\Common\ProductController@sup_sub_category');

Route::get('sub_category_by_subcat/{sub_category_id}','API\Common\ProductController@sub_category');
Route::get('co_sub_category_by_subcat/{co_sub_category_id}','API\Common\ProductController@co_sub_category');
Route::get('package','API\Common\ProductController@package_list');

Route::get('product_package/{product_id}','API\Common\ProductController@package');
Route::get('product_datatable','API\Common\ProductController@dataTable');
Route::get('admin_product_datatable','API\Common\AdminProductController@dataTable');
Route::apiResources(['vendors'=> 'API\Common\VendorController']);
Route::apiResources(['vendor_product'=> 'API\Common\VendorProductController']);
Route::apiResources(['service_area'=> 'API\Common\ServiceAreaController']);
Route::get('vendor_products/{vendorId}','API\Common\VendorProductController@listByVendor');
Route::get('vendor_products/datatable/{vendorId}','API\Common\VendorProductController@dataTable');
Route::post('vendor_product_bulk/{vendorId}','API\Common\VendorProductController@createMultipleVendorProducts');
Route::post('orders_datatable','API\Common\OrderDatatableController@allOrders');
Route::get('orders','API\Common\OrderDatatableController@nonPaginated');
Route::get('orders_datatable','API\Common\OrderDatatableController@allOrders');
Route::get('orders/{orderId}','API\Common\OrderDatatableController@show');
Route::post('orders/{orderId}/assign_delivery_person','API\Common\OrderDatatableController@assignDeliveryPerson');
Route::get('order_status','API\Common\OrderStatusController@index');
Route::apiResources(['coupon' => 'API\Common\CouponController']);
Route::apiResources(['commodity_type' => 'API\Common\CommodityTypeController']);

Route::apiResources(['delivery_person'=> 'API\Common\DeliveryPersonController']);

Route::get('delivery_person/{deliveryPersonId}/wallet','API\Common\DeliveryPersonWalletController@getWalletTransaction');
Route::post('delivery_person/{deliveryPersonId}/wallet','API\Common\DeliveryPersonWalletController@addWalletTransaction');
Route::get('delivery_person/{deliveryPersonId}/wallet/balance','API\Common\DeliveryPersonWalletController@getBalance');


Route::get('vendors/{vendorId}/wallet','API\Common\VendorWalletController@getWalletTransaction');
Route::post('vendors/{vendorId}/wallet','API\Common\VendorWalletController@addWalletTransaction');
Route::get('vendors/{vendorId}/wallet/balance','API\Common\VendorWalletController@getBalance');

Route::apiResources(['marketing_person'=> 'API\Common\MarketingPersonController']);

Route::get('marketing_person/{marketingPersonId}/wallet','API\Common\MarketingPersonWalletController@getWalletTransaction');
Route::post('marketing_person/{marketingPersonId}/wallet','API\Common\MarketingPersonWalletController@addWalletTransaction');
Route::get('marketing_person/{marketingPersonId}/wallet/balance','API\Common\MarketingPersonWalletController@getBalance');

Route::get('fcm', 'API\Common\ProfileController@fcm');
Route::get('dashboard_counts', 'API\Common\DashboardController@getDashboardCounts');

Route::get('order_status', 'API\Common\OrderStatusController@index');
Route::post('report/orders', 'API\Common\OrderReportController@orderReport');

Route::post('report/item_wise', 'API\Common\OrderReportController@itemWiseBWDates');
Route::post('report/profit', 'API\Common\OrderReportController@profitBWDates');
Route::post('report/delivery_wallet/{deliveryPersonId}', 'API\Common\OrderReportController@deliveryWalletBWDates');
Route::post('report/vendor_wallet/{vendorId}', 'API\Common\OrderReportController@vendorWalletBWDates');
Route::post('report/marketing_wallet/{marketingPersonId}', 'API\Common\OrderReportController@marketingWalletBWDates');

Route::apiResources(['splash'=> 'API\Common\SplashController']);
Route::get('delivery_person/{deliveryPersonId}/wallet','API\Common\DeliveryPersonWalletController@getWalletTransaction');
Route::get('delivery_person/{deliveryPersonId}/wallet/balance','API\Common\DeliveryPersonWalletController@getBalance');
Route::get('vendor/{deliveryPersonId}/wallet','API\Common\VendorWalletController@getWalletTransaction');
Route::get('vendor/{deliveryPersonId}/wallet/balance','API\Common\VendorWalletController@getBalance');
Route::get('marketing_person/{deliveryPersonId}/wallet','API\Common\MarketingPersonWalletController@getWalletTransaction');
Route::get('marketing_person/{deliveryPersonId}/wallet/balance','API\Common\MarketingPersonWalletController@getBalance');
Route::get('customer','API\Common\ProfileController@customersList');
Route::get('delivery_person/{deliveryPersonId}/commodity','API\Common\DeliveryPersonCommodityController@index');
Route::post('delivery_person/{deliveryPersonId}/commodity','API\Common\DeliveryPersonCommodityController@store');
Route::delete('delivery_person/{deliveryPersonId}/commodity/{id}','API\Common\DeliveryPersonCommodityController@destroy');
// Route::apiResources(['delivery_person_commodity/{deliveryPersonId}'=> 'API\Common\DeliveryPersonCommodityController']);
// Mobile Api
Route::get('getcoupon/{categoryId}', 'API\Common\MarketingPersonController@getCouponByCategory');
Route::get('getVendorStatus/{vendorId}', 'API\Common\VendorController@getVendorStatus');
Route::get('getDeliverBoyStatus/{deliveryBoyId}', 'API\Common\DeliveryPersonController@getDeliverBoyStatus');

//customer
Route::prefix('customer')->group(function () {

    Route::post('getNearestVendor', 'API\Common\VendorProductController@getVendorByCategoryAndLocation');
    Route::get('categories', 'API\Common\CategoryController@activeCategory');
    Route::post('marketingBox', 'API\Common\MarketingBoxController@marketingboxlist');
    Route::get('offerproduct', 'API\Common\OfferProductController@offerproductlist');
    Route::post('subcategories', 'API\Common\SubCategoryController@activeSubCategory');
    Route::post('vendorSubCategory', 'API\Common\SubCategoryController@vendorSubCategory');
    Route::post('productList', 'API\Common\ProductController@activeProducts');
    Route::post('vendorsList', 'API\Common\VendorController_0802@getActiveVendors');
    Route::post('vendor/{id}/product', 'API\Common\VendorProductController@productsByVendor');
    Route::get('vendor/{id}/product/{vendorProductId}', 'API\Common\VendorProductController@productDetailsByVendorProductId');
    Route::post('vendor/{id}/product_featured', 'API\Common\VendorProductController@featuredProductsByVendor');

    Route::post('logon', 'API\Common\ProfileController@logon');
    Route::get('resend_code/{verificationId}', 'API\Common\VerificationCodeController@resendCode');
    Route::post('verify_logon_otp', 'API\Common\ProfileController@verifyLogonOTP');
    Route::post('register', 'API\Common\ProfileController@register');

    Route::get('profile/{id}', 'API\Common\ProfileController@profile');
    Route::put('profile/{id}', 'API\Common\ProfileController@update');

    Route::get('profile/{id}/address', 'API\Common\UserAddressController@userAddress');
    Route::get('profile/{id}/address/default', 'API\Common\UserAddressController@getDefault');
    Route::get('profile/{id}/address/defaultt', 'API\Common\UserAddressController@getDefaultt');
    Route::post('address/{id}/default', 'API\Common\UserAddressController@setDefault');
    Route::post('save_user_address', 'API\Common\UserAddressController@saveUserAddress');
    Route::get('get_user_address/{id}', 'API\Common\UserAddressController@getUserAddress');
    Route::apiResources(['address'=> 'API\Common\UserAddressController']);



    Route::get('service_area', 'API\Common\ServiceAreaController@index');
    Route::post('cart_sync', 'API\Common\CartController@sync');
    Route::post('coupon_verify', 'API\Common\CouponController@verifyAndGet');

    Route::post('order_verification', 'API\Common\OrderController@verifyAndCalculateOrder');
    Route::post('place_order', 'API\Common\OrderController@placeOrder');
    Route::post('paytm_payment_status', 'API\Common\OrderController@paytmPaymentStatus');
    Route::get('profile/{userId}/orders', 'API\Common\OrderController@ordersByUser');


    Route::get('payment_method', 'API\Common\PaymentMethodController@getAll');
    Route::get('splash_and_version', 'API\Common\SplashController@splashWithAppVersion');
    Route::get('search/suggestions', 'API\Common\CustomerSearchController@suggestion');
    Route::post('search', 'API\Common\CustomerSearchController@search');
    
    Route::post('global_search', 'API\Common\CustomerSearchController@globalSearch');


    Route::get('profile/{id}/check_order_feedback', 'API\Common\OrderController@checkLastOrderFeedback');
    Route::post('profile/{id}/order/{orderId}/rating', 'API\Common\UserOrderRatingController@store');
    Route::get('adminProducts', 'API\Common\AdminProductController@adminProductListApi');
});

// vendor
Route::prefix('vendor')->group(function () {


    Route::post('logon', 'API\Common\VendorController@logon');
    Route::get('resend_code/{verificationId}', 'API\Common\VerificationCodeController@resendCode');
    Route::post('verify_logon_otp', 'API\Common\VendorController@verifyLogonOTP');
    
    Route::post('save_vendor_product', 'API\Common\VendorController@saveVendorProduct');
    Route::get('delete_vendor_product/{vendorProductId}', 'API\Common\VendorController@deleteVendorProduct');
    // products and categories
    Route::get('categories', 'API\Common\CategoryController@allCategory');
    Route::get('profile/{vendorId}/sub_categories', 'API\Common\VendorProductController@VendorSubCategories');
    Route::get('profile/{vendorId}/product', 'API\Common\VendorProductController@productsByVendorAll');
    Route::put('profile/{vendorId}/product/{vendorProductId}', 'API\Common\VendorProductController@updateForVendor');

    // Order Related endpoints
    Route::get('profile/{vendorId}/order', 'API\Common\VendorOrderController@index');
    Route::get('profile/{vendorId}/order/{orderId}', 'API\Common\VendorOrderController@show');
    Route::put('profile/{vendorId}/order/{orderId}', 'API\Common\VendorOrderController@update');
    Route::get('order_status', 'API\Common\VendorStatusController@index');

    // Profile and open/close status update
    Route::get('profile/{vendorId}', 'API\Common\VendorController@show');
    Route::post('profile/{vendorId}/status', 'API\Common\VendorController@statusUpdate');

    //search routes
    Route::get('profile/{vendorId}/search/suggestions', 'API\Common\VendorSearchController@suggestion');
    Route::get('profile/{vendorId}/search', 'API\Common\VendorSearchController@search');

    Route::get('profile/{vendorId}/wallet','API\Common\VendorWalletController@getWalletTransaction');
    Route::get('profile/{vendorId}/wallet/balance','API\Common\VendorWalletController@getBalance');
    Route::get('app_version', 'API\Common\GlobalSettingController@vendorMinVersion');

});

// Delivery Api
Route::prefix('delivery')->group(function () {

    Route::post('logon', 'API\Common\DeliveryPersonController@logon');
    Route::get('resend_code/{verificationId}', 'API\Common\VerificationCodeController@resendCode');
    Route::post('verify_logon_otp', 'API\Common\DeliveryPersonController@verifyLogonOTP');

    // Order Related endpoints
    Route::get('profile/{deliveryPersonId}/order', 'API\Common\DeliveryPersonOrderController@index');
    Route::get('profile/{deliveryPersonId}/order/{orderId}', 'API\Common\DeliveryPersonOrderController@show');
    Route::put('profile/{deliveryPersonId}/order/{orderId}', 'API\Common\DeliveryPersonOrderController@update');
    Route::get('profile/{deliveryPersonId}/order/{orderId}/create_pickup_otp', 'API\Common\DeliveryPersonOrderController@createPickedOTP');
    Route::post('profile/{deliveryPersonId}/order/{orderId}/verify_pickup_otp', 'API\Common\DeliveryPersonOrderController@verifyPickedOTP');
    Route::get('order_status', 'API\Common\DeliveryStatusController@index');

    // Profile and open/close status update
    Route::get('profile/{deliveryPersonId}', 'API\Common\DeliveryPersonController@show');
    Route::put('profile/{deliveryPersonId}/status', 'API\Common\DeliveryPersonController@statusUpdate');

    Route::get('profile/{deliveryPersonId}/wallet','API\Common\DeliveryPersonWalletController@getWalletTransaction');
    Route::get('profile/{deliveryPersonId}/wallet/balance','API\Common\DeliveryPersonWalletController@getBalance');

    Route::get('app_version', 'API\Common\GlobalSettingController@deliveryMinVersion');


});

Route::prefix('marketing')->group(function () {

    Route::post('logon', 'API\Common\MarketingPersonController@logon');
    Route::get('resend_code/{verificationId}', 'API\Common\VerificationCodeController@resendCode');
    Route::post('verify_logon_otp', 'API\Common\MarketingPersonController@verifyLogonOTP');

    // Order Related endpoints

    Route::get('profile/{marketingPersonId}/coupon', 'API\Common\MarketingPersonController@couponByMarkeingPerson');

    // Profile and open/close status update
    Route::get('profile/{marketingPersonId}', 'API\Common\MarketingPersonController@show');

    Route::get('profile/{deliveryPersonId}/wallet','API\Common\MarketingPersonWalletController@getWalletTransaction');
    Route::get('profile/{deliveryPersonId}/wallet/balance','API\Common\MarketingPersonWalletController@getBalance');


    Route::get('app_version', 'API\Common\GlobalSettingController@marketingMinVersion');

});
