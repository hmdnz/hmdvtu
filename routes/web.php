<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\user\WalletController;
use App\Http\Controllers\user\DataController;
use App\Http\Controllers\user\AirtimeController;
use App\Http\Controllers\user\SMSController;
use App\Http\Controllers\user\CableController;
use App\Http\Controllers\user\OrdersController;
use App\Http\Controllers\user\DataCardController;
use App\Http\Controllers\user\ReferralController;
use App\Http\Controllers\user\EducationController;
use App\Http\Controllers\user\ElectricityController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\AdminsController;
use App\Http\Controllers\admin\BillersController;
use App\Http\Controllers\admin\WalletsController;
use App\Http\Controllers\admin\PackagesController;
use App\Http\Controllers\admin\ServicesController;
use App\Http\Controllers\admin\AdminOrdersController;
use App\Http\Controllers\admin\AdminReferralsController;
use App\Http\Controllers\admin\ProvidersController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\WalletWebhookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BusinessController::class, 'index'])->name('index');

Route::get('/account-deletion', function () {
    return view('account-deletion');
})->name('account-deletion');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');
Route::get('/auth/login', [AuthController::class, 'showLogin']);

// // User Routes
// Route::middleware(['auth', 'web'])->group(function () {
//     Route::get('/user/dashboard', 'UserController@dashboard')->name('user.dashboard');
// });

// errors
Route::get('/error-500', function () {
    abort(500, 'Something went wrong!');
});
Route::fallback(function () {
    return view('errors.404');
});
// User Routes
Route::group(['prefix' => 'user'], function () 
{
    Route::get('/signin', [AuthController::class, 'showLogin']);
    Route::post('/signin', [AuthController::class, 'login'])->name('user.login');
    Route::get('/signup', [AuthController::class, 'showRegistration'])->name('user.register');
    Route::post('/signup', [AuthController::class, 'register'])->name('user.signup');
    Route::get('/forget-password', [AuthController::class, 'showForgetPassword'])->name('user.forgetPassword');
    Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->name('user.forget');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('user.resetPassword');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('user.reset');
    Route::middleware(['auth:web'])->group(function(){
        Route::get('/verify', [AuthController::class, 'showVerifyPage'])->name('user.verify');
        Route::post('/verify-user', [AuthController::class, 'verifyUser'])->name('user.verifyUser');
        Route::get('/set-pin', [AuthController::class, 'showSetPin'])->name('user.showSetPin');
        Route::post('/set-pin', [AuthController::class, 'setPin'])->name('user.setPin');
        Route::post('/set-pins', [AuthController::class, 'setPins'])->name('user.setPins');
        Route::post('/verify-identity', [AuthController::class, 'verifyIdentity'])->name('user.verifyIdentity');
        Route::post('/sendOTP', [AuthController::class, 'sendVerificationCode'])->name('user.sendOTP');
        Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');
    });
    Route::middleware(['auth:web','checkUserStatus'])->group(function () {
        // user
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/profile', [UserController::class, 'index'])->name('user.show.profile');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('user.profile');
        Route::get('/password', [UserController::class, 'showPassword'])->name('user.show.password');
        Route::post('/password', [UserController::class, 'updatePassword'])->name('user.password');
        Route::get('/pin', [UserController::class, 'showPin'])->name('user.show.pin');
        Route::post('/pin', [UserController::class, 'updatePin'])->name('user.pin');
        Route::get('/logs', [UserController::class, 'logs'])->name('user.show.logs');
        // wallet
        Route::get('/wallet', [WalletController::class, 'index'])->name('user.wallet');
        Route::get('/wallet-topup', [WalletController::class, 'showWalletTopUp'])->name('user.walletTopUp');
        Route::post('/verify-payment', [WalletController::class, 'verifyPayment'])->name('user.verifyPayment');
        Route::get('/generate-rva', [WalletController::class, 'newRVA'])->name('user.newRVA');
        // services
        Route::get('/buy-airtime', [AirtimeController::class, 'index'])->name('user.buyAirtime');
        Route::post('/buy-airtime', [AirtimeController::class, 'buyAirtime'])->name('user.airtime');
        Route::post('/vend-airtime', [AirtimeController::class, 'vendAirtime'])->name('user.vendAirtime');
        Route::get('/buy-data', [DataController::class, 'index'])->name('user.buyData');
        Route::post('/buy-data', [DataController::class, 'buyData'])->name('user.data');
        Route::post('/vend-data', [DataController::class, 'vendData'])->name('user.vendData');
        Route::get('/buy-data-card', [DataCardController::class, 'index'])->name('user.buyDataCard');
        Route::post('/buy-data-card', [DataCardController::class, 'buyDataCard'])->name('user.dataCard');
        Route::get('/buy-sms', [SMSController::class, 'index'])->name('user.buySMS');
        Route::post('/buy-sms', [SMSController::class, 'buySMS'])->name('user.sms');
        Route::get('/buy-electricity', [ElectricityController::class, 'index'])->name('user.buyElectricity');
        Route::post('/buy-electricity', [ElectricityController::class, 'buyElectricity'])->name('user.electricity');
        Route::get('/buy-cable', [CableController::class, 'index'])->name('user.buyCable');
        Route::post('/buy-cable', [CableController::class, 'buyCable'])->name('user.cable');
        Route::get('/buy-education', [EducationController::class, 'index'])->name('user.buyEducation');
        // history
        Route::get('/payments', [WalletController::class, 'showPayments'])->name('user.payments');
        Route::get('/payments/{payment}', [WalletController::class, 'showPayment'])->name('user.showPayment');
        Route::get('/transactions', [WalletController::class, 'showTransactions'])->name('user.transactions');
        Route::get('/transactions/{transaction}', [WalletController::class, 'showTransaction'])->name('user.showTransaction');
        Route::get('/orders', [OrdersController::class, 'index'])->name('user.orders');
        Route::get('/orders/{reference}', [OrdersController::class, 'showOrder'])->name('user.showOrder');
        Route::get('/orders/verify/{order}', [OrdersController::class, 'verifyOrder'])->name('user.verifyOrder');
        Route::get('/referrals', [ReferralController::class, 'index'])->name('user.referrals');
        Route::post('/referrals/transfer', [ReferralController::class, 'transfer'])->name('user.referralsTransfer');
        // business settings
        Route::get('/checkService/{service}', [BusinessController::class, 'checkService'])->name('user.checkService');
        Route::get('/check-switches/{service}/{biller}', [BusinessController::class, 'checkSwitches'])->name('user.checkSwitches');
        Route::get('/get-announcement', [BusinessController::class, 'getAnnouncement'])->name('user.getAnnouncement');
        Route::get('/fetch-packages/{biller}/{category}/{type}', [BusinessController::class, 'fetchPackages'])->name('user.fetchPackages');
    });
});

// Admin Routes
Route::group(['prefix' => 'admin'], function () {
    Route::get('/signin', [AdminController::class, 'showLoginForm'])->name('admin.signin');
    Route::post('/signin', [AdminController::class, 'login'])->name('admin.login');
    Route::get('/register', [AdminController::class, 'showRegistrationForm'])->name('admin.register'); 
    Route::post('/register', [AdminController::class, 'register']);
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // wallets
        Route::group(['prefix' => 'wallets'], function () {
            Route::get('/', [WalletsController::class, 'index'])->name('admin.wallets');
            Route::get('/virtual-accounts', [WalletsController::class, 'virtualAccounts'])->name('admin.virtualAccounts');
            Route::put('/virtual-accounts/activate', [WalletsController::class, 'activateVirtualAccount'])->name('admin.virtualAccounts.activate');
            Route::put('/virtual-accounts/deactivate', [WalletsController::class, 'deactivateVirtualAccount'])->name('admin.virtualAccounts.deactivate');
            Route::put('/virtual-accounts/delete', [WalletsController::class, 'deleteVirtualAccount'])->name('admin.virtualAccounts.delete');
            Route::get('/payments/{wallet}', [WalletsController::class, 'walletPayments'])->name('admin.walletPayments');
            Route::get('/virtual-accounts/{wallet}', [WalletsController::class, 'walletAccounts'])->name('admin.walletAccounts');
            Route::get('/transactions/{wallet}', [WalletsController::class, 'walletTransactions'])->name('admin.walletTransactions');
            Route::post('/wallet-topup', [WalletsController::class, 'walletTopUp'])->name('admin.walletTopUp');
        });
        // info
        Route::group(['prefix' => 'info'], function () {
            Route::get('/monnify', [InfoController::class, 'getMonnifyBalance'])->name('admin.info.MonnifyBalance');
            Route::get('/internal-total', [InfoController::class, 'getInternalTotal'])->name('admin.info.InternalTotal');
            Route::get('/bulksmsnigeria', [InfoController::class, 'getBulkSMSBalance'])->name('admin.info.BulkSMSBalance');
            Route::get('/easyaccess', [InfoController::class, 'getEasyAccessBalance'])->name('admin.info.EasyAccessBalance');
            Route::get('/alrahuzdata', [InfoController::class, 'getAlrahuzDataBalance'])->name('admin.info.AlrahuzDataBalance');
        });
        // admins
        Route::group(['prefix' => 'admins'], function () {
            Route::get('/', [AdminsController::class, 'index'])->name('admin.admins');
            Route::get('/add', [AdminsController::class, 'showAddAdmin'])->name('admin.admins.showAdd');
            Route::post('/add', [AdminsController::class, 'addAdmin'])->name('admin.admins.add');
            Route::get('/edit/{admin}', [AdminsController::class, 'showEditAdmin'])->name('admin.admins.edit');
            Route::post('/edit/{admin}', [AdminsController::class, 'editAdmin'])->name('admin.admins.edit');
        });
        // users
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UsersController::class, 'index'])->name('admin.users');
            Route::get('/view/{user}', [UsersController::class, 'showUser'])->name('admin.users.view');
            Route::get('/logs/{user}', [UsersController::class, 'userLogs'])->name('admin.users.logs');
            Route::post('/verify/{user}', [UsersController::class, 'verify'])->name('admin.users.verify');
            Route::post('/reset-password/{user}', [UsersController::class, 'resetPassword'])->name('admin.users.resetPassword');
            Route::post('/reset-pin/{user}', [UsersController::class, 'resetPin'])->name('admin.users.resetPin');
            Route::post('/delete/{user}', [UsersController::class, 'delete'])->name('admin.users.delete');
        });
        // services
        Route::group(['prefix' => 'services'], function () {
            Route::get('/', [ServicesController::class, 'index'])->name('admin.services');
            Route::post('/add', [ServicesController::class, 'addService'])->name('admin.services.add');
            Route::post('/edit', [ServicesController::class, 'editService'])->name('admin.services.edit');
            Route::get('/activate/{service}', [ServicesController::class, 'activate'])->name('admin.services.activate');
            Route::get('/deactivate/{service}', [ServicesController::class, 'deactivate'])->name('admin.services.deactivate');
            Route::post('/delete', [ServicesController::class, 'delete'])->name('admin.services.delete');

        });
        // providers
        Route::group(['prefix' => 'providers'], function () {
            Route::get('/', [ProvidersController::class, 'index'])->name('admin.providers');
            Route::post('/add', [ProvidersController::class, 'addProvider'])->name('admin.providers.add');
            Route::post('/edit', [ProvidersController::class, 'editProvider'])->name('admin.providers.edit');
            Route::get('/activate/{provider}', [ProvidersController::class, 'activate'])->name('admin.providers.activate');
            Route::get('/deactivate/{provider}', [ProvidersController::class, 'deactivate'])->name('admin.providers.deactivate');
            Route::post('/delete', [ProvidersController::class, 'delete'])->name('admin.providers.delete');
        });
        // billers
        Route::group(['prefix' => 'billers'], function () {
            Route::get('/', [BillersController::class, 'index'])->name('admin.billers');
            Route::post('/add', [BillersController::class, 'addBiller'])->name('admin.billers.add');
            Route::post('/edit', [BillersController::class, 'editBiller'])->name('admin.billers.edit');
            Route::get('/activate/{biller}', [BillersController::class, 'activate'])->name('admin.billers.activate');
            Route::get('/deactivate/{biller}', [BillersController::class, 'deactivate'])->name('admin.billers.deactivate');
            Route::post('/delete', [BillersController::class, 'delete'])->name('admin.billers.delete');
        });
        // packages
        Route::group(['prefix' => 'packages'], function () {
            Route::get('/', [PackagesController::class, 'index'])->name('admin.packages');
            Route::get('/add', [PackagesController::class, 'showAddPackage'])->name('admin.packages.showAdd');
            Route::post('/add', [PackagesController::class, 'addPackage'])->name('admin.packages.add');
            Route::get('/edit/{id}', [PackagesController::class, 'showEditPackage'])->name('admin.packages.showEdit');
            Route::post('/edit', [PackagesController::class, 'editPackage'])->name('admin.packages.edit');
            Route::get('/activate/{package}', [PackagesController::class, 'activate'])->name('admin.packages.activate');
            Route::get('/deactivate/{package}', [PackagesController::class, 'deactivate'])->name('admin.packages.deactivate');
            Route::post('/delete', [PackagesController::class, 'delete'])->name('admin.packages.delete');
        });
        // HISTORY
        Route::group(['prefix' => 'history'], function () {
            Route::get('/payments', [WalletsController::class, 'historyPayments'])->name('admin.historyPayments');
            Route::get('/payments/{payment}', [WalletsController::class, 'showPayment'])->name('admin.showPayment');
            Route::post('/payments/requery', [WalletsController::class, 'requeryPayment'])->name('admin.requeryPayment');
            Route::get('/transactions', [WalletsController::class, 'historyTransactions'])->name('admin.historyTransactions');
            Route::get('/transactions/{transaction}', [WalletsController::class, 'showTransaction'])->name('admin.showTransaction');
            Route::get('/orders', [AdminOrdersController::class, 'historyOrders'])->name('admin.historyOrders');
            Route::get('/orders/{order}', [AdminOrdersController::class, 'showOrder'])->name('admin.showOrder');
            Route::get('/orders/requery/{reference}', [AdminOrdersController::class, 'requeryOrder'])->name('admin.requeryOrder');
            Route::get('/referrals', [AdminReferralsController::class, 'index'])->name('admin.referrals');
        });
        // others
        Route::get('/categories', [AdminController::class, 'showCategories'])->name('admin.categories');
        Route::post('/update-category', [AdminController::class, 'updateCategory'])->name('admin.updateCategory');

        Route::get('/switches', [AdminController::class, 'showSwitches'])->name('admin.switches');
        Route::post('/switches/store', [AdminController::class, 'storeSwitches'])->name('admin.switches.store');
        Route::put('/switches/update/{id}', [AdminController::class, 'updateSwitches'])->name('admin.switches.update');
        Route::delete('/switches/delete/{id}', [AdminController::class, 'deleteSwitches'])->name('admin.switches.delete');
        
        Route::get('/announcements', [AdminController::class, 'showAnnouncements'])->name('admin.announcements');
        Route::post('/announcements/add', [AdminController::class, 'addAnnouncement'])->name('admin.addAnnouncement');
        Route::get('/announcements/{announcement}', [AdminController::class, 'showAnnouncement'])->name('admin.showAnnouncement');
        Route::post('/announcements/edit/{announcement}', [AdminController::class, 'editAnnouncement'])->name('admin.editAnnouncement');
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
})->middleware('redirect.admin.authenticated');

// webhooks
Route::group(['prefix' => 'webhook'], function () {
    Route::post('/rva-transfer', [WalletWebhookController::class, 'handleWebhook'])->middleware('verifyWebhook');
    Route::post('/alrahuzdata', [WebhookController::class, 'alrahuzdataWebhook']);
    Route::post('/easyaccessapi', [WebhookController::class, 'eassyaccessWebhook']);
    Route::post('/easyaccessapi', [WebhookController::class, 'eassyaccessWebhook']);
});

// others
Route::group(['prefix' => 'others'], function () {
    Route::get('/banks', [BusinessController::class, 'getBanks'])->name('getBanks');
    Route::post('/verify-meter', [ElectricityController::class, 'verifyMeter'])->name('verifyMeter');
    Route::get('/verify-iuc/{iuc}/{biller}', [CableController::class, 'verifyIUC'])->name('verfyIUC');
});

// Identity verification/kyc
Route::group(['prefix' => 'kyc'], function () {
    Route::post('/bvn/information', [UserController::class, 'verifyInformation'])->name('verifyInformation');
    Route::post('/bvn/account', [UserController::class, 'verifyAccount'])->name('verifyAccount');
});


// testings
Route::group(['prefix' => 'testing'], function () {
    Route::get('/balance', [WalletController::class, 'walletBalance']);
});





