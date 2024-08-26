<?php
use Illuminate\Http\Request;

Route::get('GetDataForRazorpay', 'API\Common\OrderController@GetDataForRazorpay');
Route::get('GetFetchDatadddd/{a}', 'API\Common\OrderController@GetFetchDatadddd');
Route::get('getnoti', 'API\Common\OrderController@getnoti');
Route::get('checkdata', 'API\Common\OrderController@checkdata');
Route::get('changehsn', 'API\Common\OrderController@changehsn');
Route::get('changeinv/{a}', 'API\Common\OrderController@changeinv');
//purchasevendor
Route::apiResources(['purchasevendor'=> 'API\Common\PurchaseVendorController']);
Route::get('purchasevendordata', 'API\Common\PurchaseVendorController@index');
Route::get('getpurchaseData/{a}', 'API\Common\PurchaseController@getpurchaseData');
Route::get('getdistrict/{a}', 'API\Common\PurchaseVendorController@getdistrict');
Route::get('getifscdata/{a}', 'API\Common\PurchaseVendorController@getifscdata');
Route::post('getaccounntverified', 'API\Common\PurchaseVendorController@getaccounntverified');
Route::get('getaccdetails/{a}/data/{ab}', 'API\Common\PurchaseVendorController@getaccdetails');
Route::get('loadPurchaseVendorList/{a}', 'API\Common\PurchaseVendorController@loadPurchaseVendorList');
Route::get('purchaseprofile/{a}', 'API\Common\PurchaseVendorController@show');
Route::post('uppurchasevendor/{a}', 'API\Common\PurchaseVendorController@update');
Route::get('some/{id}/getbarcodeDetails/{a}', 'API\Common\ProductController@getbarcodeDetails');
Route::get('some/{id}/getproDetails/{a}', 'API\Common\ProductController@getproDetails');
Route::get('some/{id}/getproDetailsNew', 'API\Common\ProductController@getproDetailsNew');
Route::post('GetproductData/', 'API\Common\ProductController@GetproductData');
Route::get('getcountstock/{a}/{b}', 'API\Common\ProductController@getcountstock');
//Brands
Route::apiResources(['brand'=> 'API\Common\BrandController']);
Route::get('brand_data/{a}', 'API\Common\BrandController@allCategory');
Route::get('brand_data_vendor/{a}', 'API\Common\BrandController@allCategory_vendor');
//Attribute And Tags
Route::apiResources(['attribute'=> 'API\Common\AttributeController']);
Route::apiResources(['size'=> 'API\Common\SizeController']);
Route::get('colorget/{a}', 'API\Common\ColorController@GetActive');
Route::get('sizeget/{a}', 'API\Common\SizeController@GetActive');
Route::post('GetSizeResult', 'API\Common\SizeController@GetSizeResult');
//fetchbothsizecolor
Route::post('StockColorFetch', 'API\Common\StockManagementController@StockColorFetch');
//
Route::post('GetColorResult', 'API\Common\ColorController@GetColorResult');
Route::post('GetStockResult', 'API\Common\ColorController@GetStockResult');
Route::post('GetStockData', 'API\Common\StockManagementController@GetStockData');
Route::post('GetSelstockdata', 'API\Common\StockManagementController@GetSelstockdata');
Route::get('newGetSelstockdata/{a}/{b}', 'API\Common\StockManagementController@newGetSelstockdata');
Route::post('GetonlyStockData', 'API\Common\StockManagementController@GetonlyStockData');
Route::post('AddStockData', 'API\Common\StockManagementController@AddStockData');
Route::get('getstockmanagedata/{a}/filter/{b}', 'API\Common\StockManagementController@getstockmanagedata');
Route::get('getstockshowdata/{a}/filter/{b}', 'API\Common\StockManagementController@getstockshowdata');

//makeabill
Route::post('AddMakeabill', 'API\Common\MakeabillController@AddMakeabill');
Route::get('getmakabilldata/{a}/user/{b}', 'API\Common\MakeabillController@getfetchdata');
Route::get('deletemakeabill/{a}', 'API\Common\MakeabillController@destroy');
Route::get('deleteorderproduct/{a}', 'API\Common\MakeabillController@deleteorderproduct');
Route::post('resetmakeabill', 'API\Common\MakeabillController@resetmakeabill');
Route::post('addOrder', 'API\Common\MakeabillController@addOrder');
Route::post('editorders/{a}', 'API\Common\MakeabillController@orderedit');
Route::get('Reportmake/{a}', 'API\Common\OrderController@Reportmake');
Route::get('Reportmakenew/{a}', 'API\Common\OrderController@Reportmakenew');
//end
// delivery person
Route::get('delivery_person_list', 'API\Common\DeliveryPersonController@DeliveryList');
Route::get('delivery_charges/{category_id}','API\Common\DeliveryChargesController@getdeliverycharge');
// //////add vendor customer
Route::post('addVcustomer','API\Common\ProfileController@addVcustomer');
Route::get('customerdata/{a}','API\Common\ProfileController@customersList');
Route::get('customerlistdata/{a}','API\Common\ProfileController@customerlistdata');
Route::get('profile/{a}','API\Common\ProfileController@profile');
// Route::post('UpdateCustomer/{id}', 'API\Common\ProductController@update');
Route::post('UpdateCustomer/{id}', 'API\Common\ProfileController@UpdateCustomer');
Route::post('customercsv', 'API\Common\ProfileController@customercsv');
Route::post('productcsv', 'API\Common\ProductController@productcsv');
Route::post('UpdateDeliveryBoy/{id}', 'API\Common\DeliveryPersonController@UpdateDeliveryBoy');
Route::post('DeletCustomer/{id}', 'API\Common\ProfileController@DeletCustomer');
Route::get('vendorgetonlineoffline/{a}','API\Common\VendorController@vendorgetonlineoffline');
Route::get('vendordetails/{a}','API\Common\VendorController@show');
Route::post('storeOnline','API\Common\VendorController@storeOnline');
Route::post('fetchcustomer','API\Common\ProfileController@fetchcustomer');
Route::get('getemptyCartData/{a}','API\Common\OrderController@getemptyCartData');
//////////customer 
//Banner Data---------------------------------------
//add
Route::post('AddTopBanner','API\Common\AddBannerController@AddTopBanner');
Route::post('AddBottomBanner','API\Common\AddBannerController@AddBottomBanner');
//list
Route::get('TopBannerData/{id}','API\Common\AddBannerController@TopBannerData');
Route::get('BottomBannerData/{id}','API\Common\AddBannerController@BottomBannerData');
//update
Route::post('UpdateTopBanner/{id}','API\Common\AddBannerController@UpdateTopBanner');
Route::post('UpdateBottomBanner/{id}','API\Common\AddBannerController@UpdateBottomBanner');
//delete
Route::post('deleteTopbanner/{id}','API\Common\AddBannerController@deleteTopbanner');
Route::post('deleteBottombanner/{id}','API\Common\AddBannerController@deleteBottombanner');
// End Banner Data---------------------------------------
// Remove Stock
Route::post('addremovestock', 'API\Common\RemoveStockController@store');
Route::get('getremovestockmanagedata/{a}', 'API\Common\RemoveStockController@dataTable');


// end remove stock
////////////////////purchase
Route::post('AddPurchase', 'API\Common\PurchaseController@AddPurchase');
Route::post('resettmppurchase', 'API\Common\PurchaseController@resettmppurchase');
Route::get('deletetmppurchase/{a}', 'API\Common\PurchaseController@deletetmppurchase');
Route::post('addpurchasedata', 'API\Common\PurchaseController@addpurchasedata');
Route::post('AddPurchasePayment', 'API\Common\PurchaseController@AddPurchasePayment');
Route::get('loadtmppurchase/{id}', 'API\Common\PurchaseController@loadtmppurchase');
Route::get('getpendingamount/{id}', 'API\Common\PurchaseController@getpendingamount');
Route::get('getotheramount/{id}', 'API\Common\PurchaseController@getotheramount');
Route::get('getpurchasepayment/{id}', 'API\Common\PurchaseController@getpurchasepayment');
// //////////////////
Route::apiResources(['color'=> 'API\Common\ColorController']);
Route::get('attribute_data/{a}', 'API\Common\AttributeController@allCategory');
Route::get('attributes_data/{a}', 'API\Common\AttributeController@AttributeVendor');
Route::get('tags_data/{a}', 'API\Common\AttributeController@TagsVendor');
Route::get('supercategory/{a}', 'API\Common\CategoryController@supercategory');
Route::get('superSubcategory/{a}', 'API\Common\CategoryController@superSubcategory');
Route::get('category_by_subcat/{a}','API\Common\SubCategoryController@sub_sub_category');

// orderdata
Route::get('order_datatable/{a}','API\Common\OrderController@order_datatable');
Route::get('order_datatable_delwise/{a}','API\Common\OrderController@order_datatable_delwise');
Route::get('order_online_datatable/{a}','API\Common\OrderController@order_online_datatable');
Route::get('order_count/{a}','API\Common\OrderController@order_count');
Route::get('acceptorder/{a}','API\Common\OrderController@acceptorder');
Route::get('packageorder/{a}','API\Common\OrderController@packageorder');
Route::get('outfordelivery/{a}','API\Common\OrderController@outfordelivery');
Route::get('deliveredorder/{a}','API\Common\OrderController@deliveredorder');
Route::get('orderdetails/{a}','API\Common\OrderController@orderdetails');
Route::get('getgetneworderdata/{a}', 'API\Common\OrderController@getgetneworderdata');
//end

// deliverycharges
Route::get('getdeliverycharges/{a}','API\Common\DeliveryChargesController@getdeliverycharges');
Route::post('storeDeliveryCharges','API\Common\DeliveryChargesController@storeDeliveryCharges');
// end

//notification
Route::post('SendNotification','API\Common\NotificationController@SendNotification');
//end
//--------------------------------------------------------------------------------
//product
Route::get('getproduct/{category_id}','API\Common\ProductController@getproduct');
Route::get('orderproductview/{category_id}','API\Common\ProductController@orderproductview');

Route::apiResources(['category'=> 'API\Common\CategoryController']);
Route::apiResources(['admin_category'=> 'API\Common\AdminCategoryController']);
Route::get('category_all', 'API\Common\CategoryController@allCategoryid');
Route::get('category_all_data', 'API\Common\CategoryController@allCategory');
Route::get('Category_datatable/{a}', 'API\Common\CategoryController@dataindex');
Route::get('admin_category_all', 'API\Common\AdminCategoryController@allCategory');
Route::apiResources(['category/{categoryId}/slab'=> 'API\Common\CategorySlabController']);
Route::apiResources(['category/{categoryId}/cat'=> 'API\Common\CategoryCatController']);
Route::apiResources(['admin_category/{categoryId}/subcat'=> 'API\Common\AdminSubCategoryController']);
Route::get('category_by_id/{category_id}','API\Common\CategoryController@category_by_id');
Route::get('prod_category_by_id/{category_id}','API\Common\ProductController@category_by_id');
Route::post('subCategoryPost','API\Common\SubCategoryController@subCategoryPost');
Route::apiResources(['subCategory'=> 'API\Common\SubCategoryController']);
Route::apiResources(['subCategory/{categoryId}/cat'=> 'API\Common\SubCategoryCatController']);
Route::apiResources(['mst_package'=> 'API\Common\MstPackageController']);
Route::apiResources(['marketing_box'=> 'API\Common\MarketingBoxController']);
Route::apiResources(['delivery_charges'=> 'API\Common\DeliveryChargesController']);
Route::apiResources(['offer_product'=> 'API\Common\OfferProductController']);
Route::get('loadVendor', 'API\Common\MarketingBoxController@loadVendor');
Route::get('loadVendorCat/{category_id}', 'API\Common\MarketingBoxController@loadVendorCat');
Route::get('loadProductCat/{category_id}', 'API\Common\ProductController@loadProductCat');
Route::apiResources(['product'=> 'API\Common\ProductController']);
Route::apiResources(['admin_product'=> 'API\Common\AdminProductController']);
Route::post('product_update/{id}', 'API\Common\ProductController@update');
Route::post('admin_product_update/{id}', 'API\Common\AdminProductController@update');
Route::get('product_all/','API\Common\ProductController@all');
Route::get('productall','API\Common\ProductController@all');
Route::get('productlist/{id}','API\Common\ProductController@productlist');
Route::get('admin_product_all/','API\Common\AdminProductController@all');
//Route::apiResources(['category'=> 'API\Common\ProductController']);
Route::get('pro_category','API\Common\ProductController@category');
Route::get('fpro_category','API\Common\ProductController@franscategory');
Route::get('get_service_area','API\Common\AdminProductController@service_area');
Route::get('admin_pro_category','API\Common\AdminProductController@category');
Route::get('admin_sub_category_by_cat/{category_id}','API\Common\AdminProductController@sub_category');
Route::get('sub_category_by_cat/{category_id}','API\Common\SubCategoryController@sub_category');
Route::get('super_sub_category_by_cat/{category_id}','API\Common\ProductController@sup_sub_category');
Route::get('sub_category_by_subcat/{sub_category_id}','API\Common\ProductController@sub_category');
Route::get('co_sub_category_by_subcat/{co_sub_category_id}','API\Common\ProductController@co_sub_category');
Route::get('package','API\Common\ProductController@package_list');
Route::get('product_package/{product_id}','API\Common\ProductController@package');
Route::get('Franchisee','API\Common\ProfileController@Franchisee');
Route::get('FranchiseeList','API\Common\ProfileController@FranchiseeList');
Route::post('updateFranchisee/{id}', 'API\Common\ProfileController@updateFranchisee');
// //
// Route::get('product_datatable/{id}','API\Common\ProductController@dataTable');
Route::get('product_datatable/{id}/cat/{cat}/subcat/{subcat}/subsub/{ssub}','API\Common\ProductController@dataTable');
Route::get('productlist/{id}','API\Common\ProductController@productlist');
Route::post('recomendproduct','API\Common\ProductController@recomendproduct');
Route::post('updaterecomendproduct/{id}','API\Common\ProductController@updaterecomendproduct');
Route::get('productrecomended/{a}','API\Common\ProductController@productrecomended');
// //
Route::get('admin_product_datatable','API\Common\AdminProductController@dataTable');
Route::get('getvendor/{a}','API\Common\VendorController@getvendor');
Route::post('updatevendor/{a}','API\Common\VendorController@updatevendor');
Route::apiResources(['vendors'=> 'API\Common\VendorController']);
Route::apiResources(['admin_service_area'=> 'API\Common\AdminServiceAreaController']);
Route::apiResources(['vendor_product'=> 'API\Common\VendorProductController']);
Route::apiResources(['service_area'=> 'API\Common\ServiceAreaController']);
Route::get('vendor_products/{vendorId}','API\Common\VendorProductController@listByVendor');
Route::get('vendor_products/datatable/{vendorId}','API\Common\VendorProductController@dataTable');
Route::post('vendor_product_bulk/{vendorId}','API\Common\VendorProductController@createMultipleVendorProducts');
Route::get('service_area_products/{vendorId}','API\Common\ServiceAreaProductController@listByServiceArea');
Route::post('service_area_products_update/{vendorId}','API\Common\ServiceAreaProductController@update');
Route::get('service_area_products/datatable/{vendorId}','API\Common\ServiceAreaProductController@dataTable');
Route::post('service_area_products_bulk/{vendorId}','API\Common\ServiceAreaProductController@createMultipleVendorProducts');
Route::post('orders_datatable','API\Common\OrderDatatableController@allOrders');
Route::get('orders','API\Common\OrderDatatableController@nonPaginated');
Route::get('orders_datatable','API\Common\OrderDatatableController@allOrders');
Route::get('orders/{orderId}','API\Common\OrderDatatableController@show');
Route::post('orders/{orderId}/assign_delivery_person','API\Common\OrderDatatableController@assignDeliveryPerson');
Route::post('orders/{orderId}/DeliveryRemove','API\Common\OrderDatatableController@DeliveryRemove');
Route::post('admin_orders/{orderId}/change_order_status','API\Common\AdminOrderDatatableController@changeOrderStatus');
Route::get('order_status','API\Common\OrderStatusController@index');
Route::apiResources(['coupon' => 'API\Common\CouponController']);
Route::apiResources(['earning' => 'API\Common\EarningController']);
Route::apiResources(['suggession' => 'API\Common\SuggessionController']);
Route::apiResources(['commodity_type' => 'API\Common\CommodityTypeController']);
Route::post('admin_orders_datatable','API\Common\AdminOrderDatatableController@allOrders');
Route::get('admin_orders','API\Common\AdminOrderDatatableController@nonPaginated');
Route::get('admin_orders_datatable','API\Common\AdminOrderDatatableController@allOrders');
Route::get('admin_orders/{orderId}','API\Common\AdminOrderDatatableController@show');
Route::apiResources(['delivery_person'=> 'API\Common\DeliveryPersonController']);
Route::get('delivery_person/{deliveryPersonId}/wallet','API\Common\DeliveryPersonWalletController@getWalletTransaction');
Route::post('delivery_person/{deliveryPersonId}/wallet','API\Common\DeliveryPersonWalletController@addWalletTransaction');
Route::get('delivery_person/{deliveryPersonId}/wallet/balance','API\Common\DeliveryPersonWalletController@getBalance');
Route::get('vendors/{vendorId}/wallet','API\Common\VendorWalletController@getWalletTransaction');
Route::post('vendors/{vendorId}/wallet','API\Common\VendorWalletController@addWalletTransaction');
Route::get('vendors/{vendorId}/wallet/balance','API\Common\VendorWalletController@getBalance');
Route::get('topvendors','API\Common\VendorController@TopVendors');
Route::apiResources(['marketing_person'=> 'API\Common\MarketingPersonController']);
Route::get('marketing_person/{marketingPersonId}/wallet','API\Common\MarketingPersonWalletController@getWalletTransaction');
Route::post('marketing_person/{marketingPersonId}/wallet','API\Common\MarketingPersonWalletController@addWalletTransaction');
Route::get('marketing_person/{marketingPersonId}/wallet/balance','API\Common\MarketingPersonWalletController@getBalance');
Route::get('fcm', 'API\Common\ProfileController@fcm');
Route::get('dashboard_counts/{a}','API\Common\DashboardController@getDashboardCounts');
Route::get('order_status','API\Common\OrderStatusController@index');
Route::post('report/orders','API\Common\OrderReportController@orderReport');
//report


Route::post('report/gstreport','API\Common\OrderReportController@gstreport');
Route::post('report/gstreportcsv','API\Common\OrderReportController@gstreportcsv');

Route::post('report/gstrebatereport','API\Common\OrderReportController@gstrebatereport');
Route::post('report/salereportnew','API\Common\OrderReportController@salereportnew');


Route::post('report/gstreport','API\Common\OrderReportController@gstreport');
Route::post('report/gstreportcsv','API\Common\OrderReportController@gstreportcsv');
Route::post('report/hsnreport','API\Common\OrderReportController@hsnnewreport');
Route::post('report/dayreport','API\Common\OrderReportController@dayreport');
Route::post('report/overallreport','API\Common\OrderReportController@overallreport');
Route::post('report/purchasegst','API\Common\OrderReportController@purchasegst');
Route::post('report/salereport','API\Common\OrderReportController@salereport');
Route::post('report/productwisereport','API\Common\OrderReportController@productwisereport');
Route::post('report/profitreport','API\Common\OrderReportController@profitreport');
//end
Route::post('report/item_wise', 'API\Common\OrderReportController@itemWiseBWDates');
Route::post('report/profit', 'API\Common\OrderReportController@profitBWDates');
Route::post('report/delivery_wallet/{deliveryPersonId}', 'API\Common\OrderReportController@deliveryWalletBWDates');
Route::post('report/vendor_wallet/{vendorId}', 'API\Common\OrderReportController@vendorWalletBWDates');
Route::post('report/marketing_wallet/{marketingPersonId}', 'API\Common\OrderReportController@marketingWalletBWDates');
Route::post('dailyreport/{PersonId}', 'API\Common\OrderReportController@dailyreport');
Route::post('FetchLayout', 'API\Common\ProductController@FetchLayout');
Route::get('couponfetch/{PersonId}', 'API\Common\OrderController@Couponsearch');
Route::get('couponfetch2/{PersonId}/fetch/{UserId}', 'API\Common\OrderController@Couponsearchnew');
Route::apiResources(['splash'=> 'API\Common\SplashController']);
Route::get('delivery_person/{deliveryPersonId}/wallet','API\Common\DeliveryPersonWalletController@getWalletTransaction');
Route::get('delivery_person/{deliveryPersonId}/wallet/balance','API\Common\DeliveryPersonWalletController@getBalance');
Route::get('vendor/{deliveryPersonId}/wallet','API\Common\VendorWalletController@getWalletTransaction');
Route::get('vendor/{deliveryPersonId}/wallet/balance','API\Common\VendorWalletController@getBalance');
Route::get('marketing_person/{deliveryPersonId}/wallet','API\Common\MarketingPersonWalletController@getWalletTransaction');
Route::get('marketing_person/{deliveryPersonId}/wallet/balance','API\Common\MarketingPersonWalletController@getBalance');
Route::get('delivery_person/{deliveryPersonId}/commodity','API\Common\DeliveryPersonCommodityController@index');
Route::post('delivery_person/{deliveryPersonId}/commodity','API\Common\DeliveryPersonCommodityController@store');
Route::delete('delivery_person/{deliveryPersonId}/commodity/{id}','API\Common\DeliveryPersonCommodityController@destroy');
// Route::apiResources(['delivery_person_commodity/{deliveryPersonId}'=> 'API\Common\DeliveryPersonCommodityController']);
// Mobile Api
Route::get('getcoupon/{categoryId}', 'API\Common\MarketingPersonController@getCouponByCategory');
Route::get('getVendorStatus/{vendorId}', 'API\Common\VendorController@getVendorStatus');
Route::get('getDeliverBoyStatus/{deliveryBoyId}', 'API\Common\DeliveryPersonController@getDeliverBoyStatus');
Route::get('service_area_test', 'API\Common\ServiceAreaController@service_area_test');
Route::post('report/franchisee_wallet/{vendor_id}', 'API\Common\OrderReportController@franchisee_wallet');
Route::post('report/franch_wallet/{vendor_id}', 'API\Common\OrderReportController@franch_wallet');
Route::get('franch_wallet/{vendorId}/wallet/balance','API\Common\OrderReportController@getBalanceFranch');
Route::get('franchisee_wallet/{vendorId}/wallet/balance','API\Common\OrderReportController@getBalanceFran');
Route::post('franchisee_wallet/{vendorId}/wallet','API\Common\OrderReportController@addWalletTransaction');
//review
Route::get('getdeliveryReview/{a}','API\Common\ReviewController@getdeliveryReview');
//customer
Route::prefix('customer')->group(function () {
    Route::post('getNearestVendor', 'API\Common\VendorProductController@getVendorByCategoryAndLocation');
    Route::get('getVendorNewlist', 'API\Common\VendorProductController@getVendorNewlist');
    Route::get('categories', 'API\Common\CategoryController@activeCategory');
    Route::get('emptyCartData/{a}','API\Common\OrderController@emptyCartData');
    Route::post('marketingBox', 'API\Common\MarketingBoxController@marketingboxlist');
    Route::get('offerproduct', 'API\Common\OfferProductController@offerproductlist');
    Route::post('subcategories', 'API\Common\SubCategoryController@activeSubCategory');
    Route::post('vendorSubCategory', 'API\Common\SubCategoryController@vendorSubCategory');
    Route::post('vendorSubCategoryPagination', 'API\Common\SubCategoryController@vendorSubCategoryPagination');
    Route::post('productList', 'API\Common\ProductController@activeProducts');
    Route::post('adminProductList', 'API\Common\AdminProductController@activeProducts');
    Route::post('adminSearchPagination', 'API\Common\AdminProductController@adminSearchPagination');
    Route::post('vendorsList', 'API\Common\VendorController@getActiveVendors');
    Route::post('vendorsListPagination', 'API\Common\VendorController@getActiveVendorsPagination');
    Route::post('vendor/{id}/filter', 'API\Common\VendorProductController@filterByVendor');
    Route::post('vendor/{id}/productPagination', 'API\Common\VendorProductController@productsByVendorPagination');
    Route::get('vendor/{id}/product/{vendorProductId}', 'API\Common\VendorProductController@productDetailsByVendorProductId');
    Route::get('productDetails/{ProductId}', 'API\Common\VendorProductController@productDetailsByProductId');   
    Route::get('productBarcode/{ProductId}', 'API\Common\VendorProductController@productBarcode');   
    Route::get('vendor/{id}/product_featured', 'API\Common\VendorProductController@featuredProductsByVendor');
    Route::post('vendor/{id}/product_featuredPagination', 'API\Common\VendorProductController@featuredProductsByVendorPagination');
    Route::get('resend_code/{verificationId}', 'API\Common\VerificationCodeController@resendCode');
    Route::post('verify_logon_otp', 'API\Common\ProfileController@verifyLogonOTP');
    Route::post('register', 'API\Common\ProfileController@register');
    Route::get('profile/{id}', 'API\Common\ProfileController@profile');
    //viewprofile
    Route::get('viewprofile/{id}', 'API\Common\ProfileController@viewprofile');
    Route::get('Getdata/{id}', 'API\Common\ProfileController@Getdata');
    Route::put('profile/{id}', 'API\Common\ProfileController@update');
    Route::post('get_checksum', 'API\Common\OrderController@get_checksum');
    Route::get('profile/{id}/address/default', 'API\Common\UserAddressController@getDefault');
    Route::get('profile/{id}/address/defaultt', 'API\Common\UserAddressController@getDefaultt');
    Route::get('get_user_address/{id}', 'API\Common\UserAddressController@getUserAddress');
    Route::apiResources(['address'=> 'API\Common\UserAddressController']);
    Route::post('ordertime', 'API\Common\OrderController@ordertime');
    Route::get('service_area', 'API\Common\ServiceAreaController@index');
    Route::post('cart_sync', 'API\Common\CartController@sync');
    Route::post('admin_cart_sync', 'API\Common\CartController@admin_sync');
    Route::post('coupon_verify', 'API\Common\CouponController@verifyAndGet');
    Route::post('order_verification', 'API\Common\OrderController@verifyAndCalculateOrder');
    Route::post('place_order', 'API\Common\OrderController@placeOrder');
    Route::post('admin_place_order', 'API\Common\AdminOrderController@placeOrder');
    Route::post('admin_order_verification', 'API\Common\AdminOrderController@verifyAndCalculateOrder');
    Route::post('paytm_payment_status', 'API\Common\OrderController@paytmPaymentStatus');
    Route::post('cancelorder', 'API\Common\OrderController@cancelOrder');
    Route::post('admin_cancelorder', 'API\Common\AdminOrderController@adminCancelOrder');
    Route::get('profile/{userId}/orders', 'API\Common\OrderController@ordersByUser');
    Route::post('profile/{userId}/ordersnew', 'API\Common\OrderController@ordersByUserNew');
    Route::get('profile/{userId}/admin_orders', 'API\Common\AdminOrderController@ordersByUser');
    Route::get('splash_and_version', 'API\Common\SplashController@splashWithAppVersion');
    Route::get('search/suggestions', 'API\Common\CustomerSearchController@suggestion');
    Route::get('search', 'API\Common\CustomerSearchController@search');
    Route::post('searchPagination', 'API\Common\CustomerSearchController@searchPagination');
    Route::post('global_search', 'API\Common\CustomerSearchController@globalSearch');
    Route::post('global_searchPagination', 'API\Common\CustomerSearchController@globalSearchPagination');
    Route::get('profile/{id}/check_order_feedback', 'API\Common\OrderController@checkLastOrderFeedback');
    Route::post('profile/{id}/order/{orderId}/rating', 'API\Common\UserOrderRatingController@store');
    Route::get('adminProducts', 'API\Common\AdminProductController@adminProductListApi');
    Route::get('admin_product_detail/{serviceAreaProductId}', 'API\Common\AdminProductController@adminProductDetail'); 
    //new api
    Route::get('vendor/banner/{id}', 'API\Common\VendorController@BannerImage');
    Route::get('category/{a}', 'API\Common\CategoryController@activeCatCategory');
    Route::get('subcategory/{cataid}', 'API\Common\SubCategoryController@SubCatget');
    Route::get('subsubcategory/{cataid}', 'API\Common\SubCategoryController@Getsubcata');
    Route::post('subsubcategory/{cataid}', 'API\Common\SubCategoryController@NewGetsubcata');
    Route::post('getPriceData', 'API\Common\VendorProductController@getPriceData');
    Route::post('AddCartData', 'API\Common\VendorProductController@AddCartData');
    Route::post('AddCartDatanew', 'API\Common\VendorProductController@AddCartDatanew');
    Route::post('AddMakeabillApp', 'API\Common\VendorProductController@AddMakeabillApp');
    Route::get('CartProduct/{a}', 'API\Common\VendorProductController@CartProduct');
    Route::get('CartProductnew/{a}', 'API\Common\VendorProductController@CartProductnew');
    Route::post('UpdateCartData', 'API\Common\VendorProductController@UpdateCartData'); 
    Route::post('UpdateCartDataNew', 'API\Common\VendorProductController@UpdateCartDataNew');
    Route::post('save_user_address', 'API\Common\UserAddressController@saveUsAddress');
    Route::post('UpdateAddress/{a}', 'API\Common\UserAddressController@AddressupUpdate');
    Route::get('profile/{id}/address', 'API\Common\UserAddressController@userAddress');
    Route::get('deleteaddress/{id}', 'API\Common\UserAddressController@DeleteAddress');
    Route::post('logon', 'API\Common\ProfileController@LoginCheck');
    Route::get('payment_method', 'API\Common\PaymentMethodController@getAll');
    Route::get('paytm_setting', 'API\Common\PaymentMethodController@getpaytm');
    Route::get('razorpay_setting', 'API\Common\PaymentMethodController@getrazor');
    Route::get('RemoveCart/{id}', 'API\Common\VendorProductController@RemoveCart');
    Route::post('vendor/{id}/product', 'API\Common\VendorProductController@productsByVendor');
    Route::post('vendor/{id}/productNew', 'API\Common\VendorProductController@productsByVendorNew');
    Route::post('vendor/{id}/productsByVendorrevise', 'API\Common\VendorProductController@productsByVendorrevise');
    Route::get('orderidg', 'API\Common\PaymentMethodController@getOrderId');
    Route::get('address/{id}/default', 'API\Common\UserAddressController@setDefault');
    Route::get('delivery_type/{id}/pincode/{pin}', 'API\Common\DeliveryPersonController@Getdeliverytype');
    Route::get('profile/{id}/address/default', 'API\Common\UserAddressController@getDefault');
    Route::get('OrderDetails/{id}', 'API\Common\OrderController@CustOrderDetails');
    Route::post('brandproduct/{id}', 'API\Common\VendorProductController@productsByBrand');
    Route::post('productsByBrandnew/{id}', 'API\Common\VendorProductController@productsByBrandnew');
    Route::post('addwishlist', 'API\Common\WishlistController@addwishlist');
    Route::get('RemoveWishlist/{id}', 'API\Common\WishlistController@RemoveWishlist');
    Route::get('WishlistProduct/{id}', 'API\Common\VendorProductController@WishlistProduct');
    
    Route::post('WishlistProductNew/{id}', 'API\Common\VendorProductController@WishlistProductNew');
    Route::post('WishlistProductNewSeva/{id}', 'API\Common\VendorProductController@WishlistProductNewSeva');
    Route::post('addCustomrequest', 'API\Common\CustomRequestController@addCustomrequest');
    Route::get('getCustomrequest/{a}', 'API\Common\CustomRequestController@getCustomrequest');
    Route::get('brand/{a}', 'API\Common\BrandController@brandId');
    Route::post('NewBrandList/{a}', 'API\Common\BrandController@NewBrandList');
    Route::post('forgetpassword', 'API\Common\ProfileController@forgetpassword');
    Route::post('verify_logon_otp', 'API\Common\ProfileController@verifyLogonOTP');
    Route::post('changepass', 'API\Common\ProfileController@changepass');
    Route::post('EditCustomer/{id}', 'API\Common\ProfileController@UpdateCustomer');
    Route::get('getCustomer/{id}', 'API\Common\ProfileController@getCustomer');
    Route::get('notificationlist/{a}', 'API\Common\NotificationController@notificationlist');
    Route::get('taglist/{a}', 'API\Common\AttributeController@TagsVendor');
    Route::post('getRecommended/{a}', 'API\Common\VendorProductController@getRecommended');
    Route::post('getAllProduct/{a}', 'API\Common\VendorProductController@getAllProduct');
    Route::post('GlobalSearch/{a}', 'API\Common\VendorProductController@GlobalSearch');
    Route::post('storedeliveryreview', 'API\Common\ReviewController@storedeliveryreview');
   
    Route::post('storeproductreview', 'API\Common\ReviewController@storeproductreview');
    Route::post('savelastsearch', 'API\Common\VendorProductController@savelastsearch');
    Route::post('lastSearch/{a}', 'API\Common\VendorProductController@lastSearch');
    Route::post('lastSearchnew/{a}', 'API\Common\VendorProductController@lastSearchnew');
    Route::post('TrendingProduct/{a}', 'API\Common\VendorProductController@TrendingProduct');
    Route::post('TrendingProductNew/{a}', 'API\Common\VendorProductController@TrendingProductNew');
    Route::post('getRecommendedNew/{a}', 'API\Common\VendorProductController@getRecommendedNew');
    Route::post('LogoutUser', 'API\Common\ProfileController@Logouttoken');
    Route::get('clearnotification/{a}', 'API\Common\NotificationController@clearnotification');
    Route::get('DeleteRequest/{a}', 'API\Common\CustomRequestController@DeleteRequest');
    // test
    Route::get('getnotification/{id}', 'API\Common\OrderController@CheckNotification');
    Route::post('Checkproduct/{i}', 'API\Common\VendorProductController@Checkproduct');
    Route::post('getchecksumnew', 'API\Common\PaytmController@get_checksum');
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
    Route::get('profiletest/{vendorId}/order', 'API\Common\VendorOrderController@indextest');//aniket
    Route::get('time/{vendorId}/order/{orderId}', 'API\Common\VendorOrderController@time');
    
      
    Route::get('profile/{vendorId}/order/{orderId}', 'API\Common\VendorOrderController@show');
    Route::put('profile/{vendorId}/order/{orderId}', 'API\Common\VendorOrderController@update');
    Route::get('order_status', 'API\Common\VendorStatusController@index');

    // Profile and open/close status update
    Route::get('profile/{vendorId}', 'API\Common\VendorController@show');
    Route::get('Permission/{vendorId}', 'API\Common\VendorController@Permission');
    Route::post('profile/{vendorId}/status', 'API\Common\VendorController@statusUpdate');
    Route::post('profile/{vendorId}/timeupdate', 'API\Common\VendorController@VendorUpdatetime');

    //search routes
    Route::get('profile/{vendorId}/search/suggestions', 'API\Common\VendorSearchController@suggestion');
    Route::get('profile/{vendorId}/search', 'API\Common\VendorSearchController@search');

    Route::get('profile/{vendorId}/wallet','API\Common\VendorWalletController@getWalletTransaction');
    Route::get('profile/{vendorId}/wallet/balance','API\Common\VendorWalletController@getBalance');
    Route::get('app_version', 'API\Common\GlobalSettingController@vendorMinVersion');

});

// Delivery Api
Route::prefix('delivery')->group(function () {
    // Route::post('logon', 'API\Common\DeliveryPersonController@logon');
    Route::get('resend_code/{verificationId}', 'API\Common\VerificationCodeController@resendCode');
    Route::post('verify_logon_otp', 'API\Common\DeliveryPersonController@verifyLogonOTP');

    // Order Related endpoints
    Route::get('dailyorder/{deliveryPersonId}', 'API\Common\DeliveryPersonOrderController@dailyorder');
    Route::get('profile/{deliveryPersonId}/order/{orderId}', 'API\Common\DeliveryPersonOrderController@show');
    Route::put('profile/{deliveryPersonId}/order/{orderId}', 'API\Common\DeliveryPersonOrderController@update');
    Route::get('profile/{deliveryPersonId}/order/{orderId}/create_pickup_otp', 'API\Common\DeliveryPersonOrderController@createPickedOTP');
    Route::post('profile/{deliveryPersonId}/order/{orderId}/verify_pickup_otp', 'API\Common\DeliveryPersonOrderController@verifyPickedOTP');
    Route::get('order_status', 'API\Common\DeliveryStatusController@index');
    Route::get('time/{deliveryPersonId}/order/{orderId}', 'API\Common\DeliveryPersonOrderController@deliverytime');
    // Profile and open/close status update
    Route::get('profile/{deliveryPersonId}', 'API\Common\DeliveryPersonController@show');
    Route::get('TopDeliveryBoys', 'API\Common\DeliveryPersonController@TopDeliveryBoys');
    Route::get('sendsms', 'API\Common\DeliveryPersonOrderController@sendsmstest');
    Route::get('sendnotification', 'API\Common\DeliveryPersonOrderController@sendnotificationtest');
    Route::put('profile/{deliveryPersonId}/LatLong', 'API\Common\DeliveryPersonController@LatLong');
    Route::get('profile/{deliveryPersonId}/wallet','API\Common\DeliveryPersonWalletController@getWalletTransaction');
    Route::get('profile/{deliveryPersonId}/wallet/balance','API\Common\DeliveryPersonWalletController@getBalance');
    Route::get('app_version', 'API\Common\GlobalSettingController@deliveryMinVersion');
    
    // newapi
    Route::post('logon', 'API\Common\DeliveryPersonController@LoginCheck');
    Route::post('profile/{deliveryPersonId}/status', 'API\Common\DeliveryPersonController@GoOnline');
    Route::get('profile/get/{deliveryPersonId}', 'API\Common\DeliveryPersonController@gerprofile');
    Route::get('profile/{deliveryPersonId}/order', 'API\Common\DeliveryPersonOrderController@index');
    Route::get('OrderDetails/{id}', 'API\Common\DeliveryPersonOrderController@OrderDetails');
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


//admin api
Route::get('admin/vendorlist/{id}','API\Common\VendorController@vendorlist');
Route::get('vendorlist/{id}','API\Common\VendorController@fetch_vendorlist');