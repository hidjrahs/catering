<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryMenuApiController;
use App\Http\Controllers\Api\CostControlingApiController;
use App\Http\Controllers\Api\CostStructureApiController;
use App\Http\Controllers\Api\CustomersApiController;
use App\Http\Controllers\Api\CustomerServicesApiController;
use App\Http\Controllers\Api\EmployesApiController;
use App\Http\Controllers\Api\IngredientsApiController;
use App\Http\Controllers\Api\KitchenApiController;
use App\Http\Controllers\Api\ManagementStokApiController;
use App\Http\Controllers\Api\MenuCateringApiController;
use App\Http\Controllers\Api\PacketMenuApiController;
use App\Http\Controllers\Api\PurchasingApiController;
use App\Http\Controllers\Api\RefWilayahApiController;
use App\Http\Controllers\Api\SuppliersApiController;
use App\Http\Controllers\CategoryMenuController;
use App\Http\Controllers\CostControlingController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\CustomerServicesController;
use App\Http\Controllers\EmployesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IngredientsController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManagementStokController;
use App\Http\Controllers\MenuCateringController;
use App\Http\Controllers\PacketMenuController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\RefWilayahController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\PanduanController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Route::get('/', [HomeController::class,'previewMenu']);
    Route::get('/', [LoginController::class,'index']);
    Route::get('/login', [LoginController::class,'index'])->name('login');
    Route::post('/login', [AuthController::class,'login'])->name('web.login');
    // Route::middleware(['web', 'throttle:10,1', 'validate.csrf.refresh'])->get('/refresh-csrf',[HomeController::class,'refresh_csrf']);
});
Route::middleware(['vpn.restrict','auth:web'])->group(function () {
    Route::get('/home', [HomeController::class,'index'])->name('home');
    Route::get('/home', [HomeController::class,'index'])->name('home');
    Route::group(['prefix' => 'customer_service', 'as' => 'customer_service'], function () {
        Route::get('/', [CustomerServicesController::class,'index']);
        Route::get('/list_orders', [CustomerServicesController::class,'list_orders'])->name('.order');
        // Route::get('/cek_export', [CustomerServicesController::class,'cek_export'])->name('.export');
    });
    
    Route::get('/cost_controling', [CostControlingController::class,'index'])->name('cost_controling');
    Route::get('/purchasing', [PurchasingController::class,'index'])->name('purchasing');
    Route::get('/management_stok', [ManagementStokController::class,'index'])->name('management_stok');
    Route::get('/kitchen', [KitchenController::class,'index'])->name('kitchen');

    // Route::get('/kitchen/test', [KitchenController::class,'test'])->name('kitchen.test');
    
    Route::get('/pengguna', [CategoryMenuController::class,'index'])->name('pengguna');
    Route::get('/role_assignment', [CategoryMenuController::class,'index'])->name('role_assignment');
    Route::get('/backup_restores', [CategoryMenuController::class,'index'])->name('backup_restores');
    Route::get('/backup_restores', [CategoryMenuController::class,'index'])->name('backup_restores');
    Route::get('/profile_bussines', [CategoryMenuController::class,'index'])->name('profile_bussines');
    Route::get('/menus_sidebar', [CategoryMenuController::class,'index'])->name('menus_sidebar');

    Route::get('/customers', [CustomersController::class,'index'])->name('customers');
    Route::get('/suppliers', [SuppliersController::class,'index'])->name('suppliers');
    Route::get('/ingredients', [IngredientsController::class,'index'])->name('ingredients');
    Route::get('/category_menus', [CategoryMenuController::class,'index'])->name('category_menus');
    Route::get('/packet_menus', [PacketMenuController::class,'index'])->name('packet_menus');
    
    Route::group(['prefix' => 'menus_catering', 'as' => 'menus_catering'], function () {
        Route::get('/', [MenuCateringController::class,'index']);
        Route::get('/import', [MenuCateringController::class,'import'])->name('.import');
        Route::get('/run-queue', [MenuCateringController::class,'runQueue'])->name('.run-queue');
        Route::get('/generate', [MenuCateringApiController::class,'generate'])->name('.generate');
    });
    Route::get('/employes', [EmployesController::class,'index'])->name('employes');
    Route::get('/ref_wilayah', [RefWilayahController::class,'index'])->name('ref_wilayah');

    // Panduan Pengguna
    Route::get('/panduan', [PanduanController::class,'index'])->name('panduan.index');
    Route::get('/panduan/{folder}/{file}', [PanduanController::class,'show'])->name('panduan.show');

    // Route::get('/home', [HomeController::class,'index']);
    Route::get('/logout', [AuthController::class,'logout'])->name('web.logout');

    Route::middleware(['webjson'])->group(function () {
        Route::group(['prefix' => 'web', 'as' => 'web.'],function () {
            Route::group(['prefix' => 'customer_service', 'as' => 'customer_service.'], function () {
                Route::get('/', [CustomerServicesApiController::class,'index'])->name('all-paginate');
                Route::post('/', [CustomerServicesApiController::class,'store'])->name('store');
                Route::get('/export/{refId}', [CustomerServicesApiController::class,'export'])->name('export');
                Route::get('/export_rincian/{refId}', [CustomerServicesApiController::class,'exportrincian'])->name('export_rincian');
                Route::get('/{refId}', [CustomerServicesApiController::class,'show'])->name('detail');
                Route::put('/{refId}', [CustomerServicesApiController::class,'update'])->name('update');
                Route::post('/deletes', [CustomerServicesApiController::class,'destroy'])->name('destroy');
            });
            Route::group(['prefix' => 'cost_controling', 'as' => 'cost_controling.'], function () {
                Route::get('/', [CostControlingApiController::class,'index'])->name('all-paginate');
                Route::post('/', [CostControlingApiController::class,'verify'])->name('verify');
                Route::post('/update', [CostControlingApiController::class,'update'])->name('update');
                Route::get('/export/{refId}', [CostControlingApiController::class,'export'])->name('export');
                Route::get('/exportsr/{refId}', [CostControlingApiController::class,'exportsr'])->name('exportsr');
                Route::get('/{refId}', [CostControlingApiController::class,'show'])->name('detail');
            });
            Route::group(['prefix' => 'kitchen', 'as' => 'kitchen.'], function () {
                Route::get('/', [KitchenApiController::class,'index'])->name('all-paginate');
                Route::get('/export/{refId}', [KitchenApiController::class,'export'])->name('export');
                Route::get('/{refId}', [KitchenApiController::class,'show'])->name('detail');
            });
            Route::group(['prefix' => 'purchasing', 'as' => 'purchasing.'], function () {
                Route::get('/', [PurchasingApiController::class,'index'])->name('all-paginate');
                Route::get('/batch', [PurchasingApiController::class,'batch'])->name('all-batch');
                Route::post('/', [PurchasingApiController::class,'store'])->name('store');
                Route::post('/batch', [PurchasingApiController::class,'batch_report'])->name('batch');
                Route::get('/export/{refId}', [PurchasingApiController::class,'export'])->name('export');
                Route::post('/{refId}', [PurchasingApiController::class,'update'])->name('update');
                Route::get('/{refId}', [PurchasingApiController::class,'show'])->name('detail');
            });
            Route::group(['prefix' => 'management_stok', 'as' => 'management_stok.'], function () {
                Route::get('/', [ManagementStokApiController::class,'index'])->name('all-paginate');
                // Route::post('/', [CostControlingApiController::class,'verify'])->name('verify');
                // Route::get('/{refId}', [CostControlingApiController::class,'show'])->name('detail');
            });
            Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
                Route::get('/', [CustomersApiController::class,'index'])->name('all-paginate');
                Route::post('/', [CustomersApiController::class,'store'])->name('store');
                Route::get('/search', [CustomersApiController::class,'search'])->name('search');
                Route::get('/{refId}', [CustomersApiController::class,'show'])->name('detail');
                Route::put('/{refId}', [CustomersApiController::class,'update'])->name('update');
                Route::post('/deletes', [CustomersApiController::class,'destroy'])->name('destroy');
            });
            Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
                Route::get('/', [SuppliersApiController::class,'index'])->name('all-paginate');
                Route::post('/', [SuppliersApiController::class,'store'])->name('store');
                Route::get('/search', [SuppliersApiController::class,'search'])->name('search');
                Route::get('/{refId}', [SuppliersApiController::class,'show'])->name('detail');
                Route::put('/{refId}', [SuppliersApiController::class,'update'])->name('update');
                Route::post('/deletes', [SuppliersApiController::class,'destroy'])->name('destroy');
            });
            Route::group(['prefix' => 'ingredients', 'as' => 'ingredients.'], function () {
                Route::get('/', [IngredientsApiController::class,'index'])->name('all-paginate');
                Route::post('/', [IngredientsApiController::class,'store'])->name('store');
                Route::get('/search', [IngredientsApiController::class,'search'])->name('search');
                Route::get('/{refId}', [IngredientsApiController::class,'show'])->name('detail');
                Route::put('/{refId}', [IngredientsApiController::class,'update'])->name('update');
                Route::post('/deletes', [IngredientsApiController::class,'destroy'])->name('destroy');
            });
            Route::group(['prefix' => 'category_menus', 'as' => 'category_menus.'], function () {
                Route::get('/', [CategoryMenuApiController::class,'index'])->name('all-paginate');
                Route::post('/', [CategoryMenuApiController::class,'store'])->name('store');
                Route::get('/search', [CategoryMenuApiController::class,'search'])->name('search');
                Route::get('/getall', [CategoryMenuApiController::class,'getall'])->name('all-data');
                Route::get('/{refId}', [CategoryMenuApiController::class,'show'])->name('detail');
                Route::put('/{refId}', [CategoryMenuApiController::class,'update'])->name('update');
                Route::post('/deletes', [CategoryMenuApiController::class,'destroy'])->name('destroy');
            });
            Route::group(['prefix' => 'packet_menus', 'as' => 'packet_menus.'], function () {
                Route::get('/', [PacketMenuApiController::class,'index'])->name('all-paginate');
                Route::post('/', [PacketMenuApiController::class,'store'])->name('store');
                Route::get('/search', [PacketMenuApiController::class,'search'])->name('search');
                Route::get('/search_all', [PacketMenuApiController::class,'search_all'])->name('search-all');
                Route::get('/{refId}', [PacketMenuApiController::class,'show'])->name('detail');
                Route::put('/{refId}', [PacketMenuApiController::class,'update'])->name('update');
                Route::post('/deletes', [PacketMenuApiController::class,'destroy'])->name('destroy');
            });
            Route::group(['prefix' => 'menus_catering', 'as' => 'menus_catering.'], function () {
                Route::get('/', [MenuCateringApiController::class,'index'])->name('all-paginate');
                Route::post('/', [MenuCateringApiController::class,'store'])->name('store');
                Route::get('/search', [MenuCateringApiController::class,'search'])->name('search');
                Route::get('/select', [MenuCateringApiController::class,'select'])->name('select');
                Route::post('/deletes', [MenuCateringApiController::class,'destroy'])->name('destroy');
                Route::get('/generate', [MenuCateringApiController::class,'generate'])->name('generate');
                Route::get('/batch', [MenuCateringApiController::class,'list_batch'])->name('batch-paginate');
                Route::post('/batch', [MenuCateringApiController::class,'store_batch'])->name('store_batch');
                Route::get('/batch_preview', [MenuCateringApiController::class,'list_batch_preview'])->name('all-preview');
                Route::get('/check_batch/{refId}', [MenuCateringApiController::class,'check_batch'])->name('check_batch');

                Route::post('/recipe', [MenuCateringApiController::class,'recipe'])->name('recipe');
                Route::delete('/batch/{refId}', [MenuCateringApiController::class,'destroy_batch'])->name('batch-delete');

                Route::get('/{refId}', [MenuCateringApiController::class,'show'])->name('detail');
                Route::put('/{refId}', [MenuCateringApiController::class,'update'])->name('update');
            });
            Route::group(['prefix' => 'employes', 'as' => 'employes.'], function () {
                Route::get('/', [EmployesApiController::class,'index'])->name('all-paginate');
                Route::post('/', [EmployesApiController::class,'store'])->name('store');
                // Route::get('/search', [EmployesApiController::class,'search'])->name('search');
                Route::get('/{refId}', [EmployesApiController::class,'show'])->name('detail');
                Route::put('/{refId}', [EmployesApiController::class,'update'])->name('update');
                Route::post('/deletes', [EmployesApiController::class,'destroy'])->name('destroy');
            });
            Route::group(['prefix' => 'ref_wilayah', 'as' => 'ref_wilayah.'], function () {
                Route::get('/', [RefWilayahApiController::class,'index'])->name('all-paginate');
                Route::post('/', [RefWilayahApiController::class,'store'])->name('store');
                Route::get('/search', [RefWilayahApiController::class,'search'])->name('search');
                Route::get('/province-city', [RefWilayahApiController::class,'provinceCity'])->name('search_city');
                Route::get('/district-vilage', [RefWilayahApiController::class,'districtVilage'])->name('search_vilage');
                Route::get('/{refId}', [RefWilayahApiController::class,'show'])->name('detail');
                Route::post('/{refId}', [RefWilayahApiController::class,'update'])->name('update');
                Route::post('/deletes', [RefWilayahApiController::class,'destroy'])->name('destroy');
            });
            Route::group(['prefix' => 'cost_stucture', 'as' => 'cost_stucture.'], function () {
                // Route::get('/', [PacketMenuApiController::class,'index'])->name('all-paginate');
                // Route::post('/', [PacketMenuApiController::class,'store'])->name('store');
                Route::get('/search', [CostStructureApiController::class,'search'])->name('search');
                // Route::get('/search_all', [PacketMenuApiController::class,'search_all'])->name('search-all');
                // Route::get('/{refId}', [PacketMenuApiController::class,'show'])->name('detail');
                // Route::put('/{refId}', [PacketMenuApiController::class,'update'])->name('update');
                // Route::post('/deletes', [PacketMenuApiController::class,'destroy'])->name('destroy');
            });
        });
    });

});