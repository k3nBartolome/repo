<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\User\PermissionController;
use App\Http\Controllers\API\User\RoleController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassStaffingController;
use App\Http\Controllers\DateRangeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\SiteController;
use  App\Http\Controllers\CapEmailController;
use Illuminate\Http\Request;
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

// Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('/logout/{id}', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'role_permission:admin,user,budget,sourcing,remx'])->group(function () {
    // User Routes
    Route::post('/create_user', [UserController::class, 'store']);
    Route::put('/update_user/{id}', [UserController::class, 'update']);
    Route::delete('/delete_user/{id}', [UserController::class, 'destroy']);
    Route::get('/show_user/{id}', [UserController::class, 'show']);
    Route::get('/list_user', [UserController::class, 'index']);
    Route::put('/update_user/profile/{id}/', [UserController::class, 'update_profile']);

    // Role Routes
    Route::get('/list_role', [RoleController::class, 'index']);
    Route::post('/create_role', [RoleController::class, 'store']);
    Route::put('/update_role/{id}', [RoleController::class, 'update']);
    Route::delete('/delete_role/{id}', [RoleController::class, 'destroy']);
    Route::get('/show_role/{id}', [RoleController::class, 'show']);

    // Permission Routes
    Route::get('/list_permission', [PermissionController::class, 'index']);
    Route::post('/create_permission', [PermissionController::class, 'store']);
    Route::put('/update_permission/{id}', [PermissionController::class, 'update']);
    Route::delete('/delete_permission/{id}', [PermissionController::class, 'destroy']);
    Route::get('/show_permission/{id}', [PermissionController::class, 'show']);

    // Site Routes
    Route::get('sites', [SiteController::class, 'index']);
    Route::get('sites2', [SiteController::class, 'index2']);
    Route::get('sites3', [SiteController::class, 'index3']);
    Route::get('sites4', [SiteController::class, 'index4']);
    Route::get('sites5', [SiteController::class, 'index5']);
    Route::get('sites6', [SiteController::class, 'index6']);
    Route::get('sites7', [SiteController::class, 'index7']);
    Route::get('sites8', [SiteController::class, 'index8']);
    Route::get('sites/{id}', [SiteController::class, 'show']);
    Route::post('sites', [SiteController::class, 'store']);
    Route::post('sites2', [SiteController::class, 'store2']);
    Route::post('sites3', [SiteController::class, 'store3']);
    Route::post('sites4', [SiteController::class, 'store4']);
    Route::post('sites5', [SiteController::class, 'store5']);
    Route::post('sites6', [SiteController::class, 'store6']);
    Route::post('sites7', [SiteController::class, 'store7']);
    Route::post('sites8', [SiteController::class, 'store8']);
    Route::put('sites/{id}', [SiteController::class, 'update']);
    Route::put('sites_activate/{id}', [SiteController::class, 'activate']);
    Route::put('sites_deactivate/{id}', [SiteController::class, 'deactivate']);
    Route::delete('sites/{id}', [SiteController::class, 'destroy']);

    // Program Routes
    Route::get('programs', [ProgramController::class, 'index']);
    Route::get('programs2', [ProgramController::class, 'index2']);
    Route::get('programs3', [ProgramController::class, 'index3']);
    Route::get('programs4', [ProgramController::class, 'index4']);
    Route::get('programs5', [ProgramController::class, 'index5']);
    Route::get('programs6', [ProgramController::class, 'index6']);
    Route::get('programs7', [ProgramController::class, 'index7']);
    Route::get('programs8', [ProgramController::class, 'index8']);
    Route::get('programs/{id}', [ProgramController::class, 'show']);
    Route::post('programs', [ProgramController::class, 'store']);
    Route::put('programs/{id}', [ProgramController::class, 'update']);
    Route::put('programs_activate/{id}', [ProgramController::class, 'activate']);
    Route::put('programs_deactivate/{id}', [ProgramController::class, 'deactivate']);
    Route::delete('programs/{id}', [ProgramController::class, 'destroy']);
    Route::get('programs_selected/{siteId}', [ProgramController::class, 'indexBySite']);
    Route::get('programs_select/{siteIds}', [ProgramController::class, 'perSite']);

    // Classes Routes
    Route::get('classes', [ClassesController::class, 'index']);
    Route::get('classes/{id}', [ClassesController::class, 'show']);
    Route::delete('classes/{id}', [ClassesController::class, 'destroy']);
    Route::get('classesall', [ClassesController::class, 'classesAll']);
    Route::get('cstat', [ClassesController::class, 'cstat']);

    Route::get('classesallindia', [ClassesController::class, 'classesAllInd']);
    Route::get('classesalljam', [ClassesController::class, 'classesAllJam']);
    Route::get('classesallgua', [ClassesController::class, 'classesAllGua']);
    Route::put('classes/pushedback/{id}', [ClassesController::class, 'pushedback']);
    Route::put('classes/edit/{id}', [ClassesController::class, 'edit']);
    Route::put('classes/cancel/{id}', [ClassesController::class, 'cancel']);
    Route::post('classes', [ClassesController::class, 'store']);
    Route::get('check', [ClassesController::class, 'checkCombinationExistence']);
    Route::get('transaction/{id}', [ClassesController::class, 'transaction']);
    Route::get('perxfilter', [ClassesController::class, 'perxFilter']);
    Route::get('sr_compliance', [ClassesController::class, 'srCompliance']);
    Route::get('sr_filter', [ClassesController::class, 'srFilter']);
    Route::get('sr_export', [ClassesController::class, 'srExport']);
    Route::get('sr_date', [ClassesController::class, 'srDate']);
    Route::get('perx_date', [ClassesController::class, 'perxDate']);
    Route::get('sr_site', [ClassesController::class, 'srSite']);
    Route::get('perx_site', [ClassesController::class, 'perxSite']);
    Route::get('export', [ClassesController::class, 'exportFilteredData']);
    Route::get('export2', [ClassesController::class, 'dashboardClassesExport']);
    Route::get('export3', [ClassesController::class, 'dashboardClassesExport3']);
    Route::get('export4', [ClassesController::class, 'dashboardClassesExport4']);
    Route::get('mps', [ClassStaffingController::class, 'mps']);
    Route::get('mpsweek', [ClassStaffingController::class, 'mpsWeek']);
    Route::get('mpsmonth', [ClassStaffingController::class, 'mpsMonth']);
    Route::get('mpssite', [ClassStaffingController::class, 'mpsSite']);
    Route::get('programs_selected', [ProgramController::class, 'perSite']);
    Route::get('classesdashboard', [ClassesController::class, 'dashboardClasses']);
    Route::get('siteclasses', [ClassesController::class, 'dashboardSiteClasses']);
    Route::get('classesdashboard2', [ClassesController::class, 'dashboardClasses2']);
    Route::get('classesdashboard3', [ClassesController::class, 'dashboardClasses3']);
    Route::get('classesdashboard4', [ClassesController::class, 'dashboardClasses4']);
    Route::post('render', [CapEmailController::class, 'sendEmail']);
    // chart
    Route::get('countstatus', [ClassesController::class, 'countStatus']);
    Route::get('sumtotaltarget', [ClassesController::class, 'sumTotalTarget']);

    // Class Staffing
    Route::post('classesstaffing', [ClassStaffingController::class, 'store']);
    Route::get('classesstaffing/{id}', [ClassStaffingController::class, 'show']);
    Route::put('updateclassesstaffing/{id}', [ClassStaffingController::class, 'update']);
    Route::get('classesstaffing', [ClassStaffingController::class, 'index']);
    Route::get('classesstaffing2', [ClassStaffingController::class, 'index2']);
    Route::get('classestransaction/{id}', [ClassStaffingController::class, 'transaction']);
    Route::get('export-to-excel', [ClassStaffingController::class, 'exportToExcel']);

    // DateRange
    Route::get('daterange', [DateRangeController::class, 'index']);
    Route::get('daterange_selected/{monthId}', [DateRangeController::class, 'indexByMonth']);
    Route::get('daterange_select/{monthId}', [DateRangeController::class, 'byMonth']);
    Route::get('daterange_selected', [DateRangeController::class, 'perMonth']);
    Route::get('months', [DateRangeController::class, 'getMonth']);
    //Items
    Route::post('items', [ItemsController::class, 'store']);
    Route::post('items_site_supply', [ItemsController::class, 'storeSiteSupply']);
    Route::get('items', [ItemsController::class, 'index']);
    Route::get('itemsboth', [ItemsController::class, 'indexboth']);
    Route::get('itemsboth3', [ItemsController::class, 'indexboth3']);
    Route::get('itemsboth2', [ItemsController::class, 'indexboth2']);
    Route::get('itemseparate', [ItemsController::class, 'indexseparate']);
    Route::get('items2', [ItemsController::class, 'index2']);
    Route::get('siteinventory', [ItemsController::class, 'index3']);
    Route::get('siteinventoryall', [ItemsController::class, 'indexAllSite']);
    Route::get('items_selected/{siteId}', [ItemsController::class, 'index4']);
    Route::get('items_selected2/{siteId}', [ItemsController::class, 'index5']);
    //Inventory
    Route::post('inventory', [InventoryController::class, 'requestItem']);
    Route::post('transfer', [InventoryController::class, 'transferItem']);
    Route::put('inventory/cancel/{id}', [InventoryController::class, 'cancelledItem']);
    Route::put('inventory/denied/{id}', [InventoryController::class, 'deniedItem']);
    //Route::put('inventory/transfer/{id}', [InventoryController::class, 'transferItem']);
    Route::put('inventory/received/{id}', [InventoryController::class, 'receivedItem']);
    Route::put('inventory/transfer/{id}', [InventoryController::class, 'receivedTransfer']);
    Route::put('inventory/approved/{id}', [InventoryController::class, 'approvedItem']);
    Route::get('inventory', [InventoryController::class, 'index']);
    Route::get('inventoryall', [InventoryController::class, 'indexAll']);
    Route::get('inventoryalltransaction', [InventoryController::class, 'indexAllTransaction']);
    Route::get('inventory/approved', [InventoryController::class, 'approved']);
    Route::get('inventory/approved/received', [InventoryController::class, 'approvedReceived']);
    Route::get('inventory/approved/pending', [InventoryController::class, 'approvedPending']);
    Route::get('inventory/denied', [InventoryController::class, 'denied']);
    Route::get('inventory/cancelled', [InventoryController::class, 'cancelled']);
    Route::get('inventory/allstatus', [InventoryController::class, 'allstatus']);
    Route::get('inventory/alltransfer', [InventoryController::class, 'alltransfer']);
    Route::get('inventory/allrequest', [InventoryController::class, 'request']);
    Route::post('award', [InventoryController::class, 'awardNormalItem']);
    Route::post('award2', [InventoryController::class, 'awardPremiumItem']);
    // Purchase Request
    Route::post('purchase', [PurchaseRequestController::class, 'store']);
    Route::get('purchase', [PurchaseRequestController::class, 'index']);
    Route::get('purchase2', [PurchaseRequestController::class, 'index2']);
    Route::get('purchase3', [PurchaseRequestController::class, 'index3']);
    Route::put('purchase/approved/{id}', [PurchaseRequestController::class, 'approvedPurchase']);
    Route::put('purchase/denied/{id}', [PurchaseRequestController::class, 'deniedPurchase']);
    // Awarded
    Route::get('awarded/normal', [AwardController::class, 'awardedNormal']);
    Route::get('awarded/premium', [AwardController::class, 'awardedPremium']);
    Route::get('awarded/both', [AwardController::class, 'awardedBoth']);
});

