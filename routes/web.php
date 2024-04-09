<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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


Route::get('/test/price-rule', [App\Http\Controllers\TestController::class, 'index']);
Route::get('/test/custom-login', [App\Http\Controllers\TestController::class, 'customLogin']);
Route::get('/test/create-unique-code', [App\Http\Controllers\TestController::class, 'createUniqueCode']);
Route::get('/test/dump-visitors', [App\Http\Controllers\TestController::class, 'dumpVisitors']);


// Auth Routes
Route::get('/auth/redirect', function () {
    return Socialite::driver('shopify')->scopes([
        'write_orders', 'read_customers', 'write_script_tags', 'write_discounts', 'write_price_rules', 'read_all_orders', 'read_products'
    ])->stateless()->redirect();
})->name('Shopify.Redirect');
Route::get('/auth/callback',
    [App\Http\Controllers\Auth\ShopifyController::class, 'callback'])->name('Shopify.CallBack');
Route::get('/auth/login', function () {
    return view('auth/login');
})->name('login');
Route::post('/auth/login', function () {
    return view('auth/login');
})->name('postLogin');
Route::get('/auth/logout',)->name('logout');
Route::get('/', function () {
    return redirect()->away('https://www.simplepost.co');
})->name('home');

Route::get('auth/shopify/install',
    [App\Http\Controllers\Auth\ShopifyController::class, 'install'])->name('Shopify.Install');

Route::get('/getting-started', [App\Http\Controllers\GettingStartedController::class, 'index'])->name('gettingStarted');
Route::post('/get-qrcode', [App\Http\Controllers\GettingStartedController::class, 'getQRCode'])->name('getQRCode');

// analytics-dashboard
Route::get('/analytics-dashboard/{campaign_id?}', [App\Http\Controllers\AnalyticsDashboardController::class, 'analyticsDashboard'])->name('analyticsDashboard');
Route::post('/prepare/analytics-data', [App\Http\Controllers\AnalyticsDashboardController::class, 'prepareAnalyticsData'])->name('prepareAnalyticsData');

Route::get('/all-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('getAllOrders');
Route::post('/all-orders-data/{shop}', [App\Http\Controllers\OrderController::class, 'data'])->name('getAllOrdersData');

Route::get('/all-products', [App\Http\Controllers\ProductsController::class, 'index'])->name('getAllProducts');
Route::post('/all-products-data/{shop}', [App\Http\Controllers\ProductsController::class, 'data'])->name('getAllProductsData');

Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account');

// manualCampaigns
Route::group(['prefix' => 'manual-campaigns', 'as' => 'manualCampaigns'],function(){
    Route::get('/', [App\Http\Controllers\ManualCampaignController::class, 'index']);
    Route::post('/draw-data', [App\Http\Controllers\ManualCampaignController::class, 'draw'])->name('.draw-data');    
    Route::post('/start-campaign', [App\Http\Controllers\ManualCampaignController::class, 'startCampaign'])->name('.startCampaign');    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/campaign/thumbnail',
        [App\Http\Controllers\CampaignController::class, 'getThumbnail'])->name('getCampaignThumbnail');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/campaign', [App\Http\Controllers\CampaignController::class, 'index'])->name('campaign');
    Route::get('/campaign/create/{project_id}',
        [App\Http\Controllers\CampaignController::class, 'createCampaign'])->where('project_id',
        '[A-Za-z0-9]+')->name('createCampaign');
    Route::post('/campaign/start/{project_id}',
        [App\Http\Controllers\CampaignController::class, 'startCampaign'])->where('project_id',
        '[A-Za-z0-9]+')->name('startCampaign');
    Route::get('/campaign/restart/{project_id}',
        [App\Http\Controllers\CampaignController::class, 'restartCampaign'])->where('project_id',
        '[A-Za-z0-9]+')->name('restartCampaign');
    Route::get('/campaign/stop/{project_id}',
        [App\Http\Controllers\CampaignController::class, 'stopCampaign'])->where('project_id',
        '[A-Za-z0-9]+')->name('stopCampaign');
    Route::post('/campaign/save',
        [App\Http\Controllers\CampaignController::class, 'startCampaign'])->name('saveCampaign');
    Route::get('/campaign/select-audience/{project_id}',
        [App\Http\Controllers\CampaignController::class, 'selectAudience'])->where('project_id',
        '[A-Za-z0-9]+')->name('selectAudience');
    Route::get('/campaign/select-postcard',
        [App\Http\Controllers\CampaignController::class, 'selectPostcard'])->name('selectPostcard');
    Route::get('/campaign/design-postcard/{project_id}',
        [App\Http\Controllers\CampaignController::class, 'designPostcard'])->where('project_id',
        '[A-Za-z0-9]+')->name('designPostcard'); //This is hardcoded in some JS
    Route::get('/campaign/view',
        [App\Http\Controllers\CampaignController::class, 'viewCampaigns'])->name('viewCampaigns');

    // automated-retargeting
    Route::get('/automated-retargeting',[App\Http\Controllers\AutomatedRetargetingController::class, 'index'])->name('automated-retargeting.index');

    // campaign-overview
    Route::group(['prefix' => 'campaign-overview', 'as' => 'campaign-overview'], function(){
        Route::get('/',[App\Http\Controllers\CampaignOverviewController::class, 'index'])->name('.index');
        Route::post('/campaign/start/{project_id}', [App\Http\Controllers\CampaignOverviewController::class, 'startCampaign'])->where('project_id','[A-Za-z0-9]+')->name('.startCampaign');
        Route::get('/campaign/restart/{project_id}', [App\Http\Controllers\CampaignOverviewController::class, 'restartCampaign'])->where('project_id','[A-Za-z0-9]+')->name('.restartCampaign');
        Route::get('/campaign/stop/{project_id}', [App\Http\Controllers\CampaignOverviewController::class, 'stopCampaign'])->where('project_id','[A-Za-z0-9]+')->name('.stopCampaign');
    });

    //Shop Links
    Route::post('/shop/subscription/',
        [App\Http\Controllers\ShopController::class, 'startSubscription'])->name('startSubscription');
    Route::get('/shop/subscription/form/{shop_id}',
        [App\Http\Controllers\ShopController::class, 'viewSubscriptionForm'])->where('shop_id',
        '[A-Za-z0-9]+')->name('subscriptionForm');
    Route::get('/portal/{shop_id}',
        [App\Http\Controllers\ShopController::class, 'redirectToPortal'])
        ->where('shop_id', '[A-Za-z0-9]+')
        ->name('subscriptionPortal');
});


//GDRP
Route::get('/customers/data_request', [App\Http\Controllers\Shopify\GdprController::class, 'customerDataRequest']);
Route::get('/customers/redact', [App\Http\Controllers\Shopify\GdprController::class, 'customerDataRequest']);
Route::get('/shop/redact', [App\Http\Controllers\Shopify\GdprController::class, 'customerDataRequest']);

Route::post('/customers/data_request', [App\Http\Controllers\Shopify\GdprController::class, 'customerDataRequest'])->name('GdrpCustomerData');
Route::post('/customers/redact', [App\Http\Controllers\Shopify\GdprController::class, 'customerDataRequest'])->name('GdrpRedact');
Route::post('/shop/redact', [App\Http\Controllers\Shopify\GdprController::class, 'customerDataRequest'])->name('GdrpShopRedact');

