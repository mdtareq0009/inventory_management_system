<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AdministrationController;

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

require __DIR__ . '/auth.php';

Route::get('/general_register', [CommonController::class, 'generalRegister'])->name('general-register');
Route::post('/general_user_store', [CommonController::class, 'generalUserstore']);

Route::middleware('auth')->group(function () {

    Route::middleware('access')->group(function () {
        Route::get('/category_entry', [CommonController::class, 'categoryEntry'])->name('category_entry');
        Route::get('/unit_entry', [CommonController::class, 'unitEntry'])->name('unit_entry');
        Route::get('/product_entry', [CommonController::class, 'productEntry'])->name('product_entry');
        Route::get('/customer_entry', [CommonController::class, 'customerEntry'])->name('customer_entry');
        Route::get('/supplier_entry', [CommonController::class, 'supplierEntry'])->name('supplier_entry');

        Route::get('/purchase_entry', [PurchaseController::class, 'purchaseOrderInventoryEntry'])->name('purchase_entry');
        Route::get('/purcahse_return_entry', [PurchaseController::class, 'purchaseReturnInventoryEntry'])->name('purcahse_return_entry');
        Route::get('/purchase_record', [PurchaseController::class, 'purchaseInventoryRecord'])->name('purchase_record');
        Route::get('/purchase_return_record', [PurchaseController::class, 'purchaseReturnInventoryRecord'])->name('purchase_return_record');
        
        Route::get('/sale_entry', [SaleController::class, 'saleOrderInventoryEntry'])->name('sale_entry');
        Route::get('/sale_return_entry', [SaleController::class, 'saleReturnInventoryEntry'])->name('sale_return_entry');
        Route::get('/sale_record', [SaleController::class, 'saleInventoryRecord'])->name('sale_record');
        Route::get('/sale_return_record', [SaleController::class, 'saleReturnInventoryRecord'])->name('sale_return_record');
       
        Route::get('/stock', [InventoryController::class, 'stockInventory'])->name('stock');
        Route::get('/product_list', [PurchaseController::class, 'productList'])->name('product_list');
        Route::get('/pdf_product_list', [InventoryController::class, 'pdfGenerateProduct'])->name('pdf_product_list');
        ///Generate PDF
        Route::get('/pdf_sale', [InventoryController::class, 'pdfGenerateSale'])->name('pdf-sale');
        Route::get('/pdf_sale_return', [InventoryController::class, 'pdfGenerateSaleReturn'])->name('pdf-sale-return');
        Route::get('/pdf_stock', [InventoryController::class, 'pdfStock'])->name('pdf-stock');
        Route::get('/pdf_purchase', [InventoryController::class, 'pdfGeneratePurchaseRecord'])->name('pdf-purchase');
        Route::get('/pdf_purchase_return', [InventoryController::class, 'pdfGeneratePurchaseReturnRecord'])->name('pdf-purchase-return');
       });

    Route::get('/', [CommonController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/dashboard', [CommonController::class, 'dashboard']);
    Route::get('/module/{module}', [CommonController::class, 'module'])->name('module');

    //user
    Route::get('/get_users', [CommonController::class, 'getUsers']);
    Route::get('/user_activity', [CommonController::class, 'userActivity'])->name('user_activity');
    Route::post('/get_user_activity', [CommonController::class, 'getUserActivity']);
    Route::post('/delete_user_activity', [CommonController::class, 'deleteUserActivity']);
    Route::get('/user_access/{id}', [CommonController::class, 'userAccess'])->name('user_access');
    Route::post('/add_user_access', [CommonController::class, 'addUserAccess']);
    Route::get('/get_user_access/{id}', [CommonController::class, 'getUserAccess']);
    //Company Profile
    Route::get('/company_profile', [AdministrationController::class, 'companyProfile'])->name('company_profile');
    Route::post('/store-companyprofile', [AdministrationController::class, 'companyProfileStore']);
    Route::post('/update-companyprofile', [AdministrationController::class, 'companyProfileUpdate']);
    Route::match(['get', 'post'],'/get_companies', [AdministrationController::class, 'getCompanies']);
    // Branch 
    Route::match(['get', 'post'],'/get_branches', [AdministrationController::class, 'getBranches']);
    Route::match(['get', 'post'],'/get_branches_code', [AdministrationController::class, 'getBranchCode']);
    Route::post('/store-branch', [AdministrationController::class, 'branchStore']);
    Route::post('/update-branch', [AdministrationController::class, 'branchUpdate']);
    Route::post('/delete-branch', [AdministrationController::class, 'branchDelete']);

      ////Category 
      Route::post('/store-category', [CommonController::class, 'categoryStore']);
      Route::post('/update-category', [CommonController::class, 'categoryUpdate']);
      Route::post('/delete-category', [CommonController::class, 'categoryDelete']);
      Route::match(['get', 'post'],'/get_categories', [CommonController::class, 'getCategories']);

      ////Unit
      Route::post('/store-unit', [CommonController::class, 'unitStore']);
      Route::post('/update-unit', [CommonController::class, 'unitUpdate']);
      Route::post('/delete-unit', [CommonController::class, 'unitDelete']);
      Route::match(['get', 'post'],'/get_units', [CommonController::class, 'getUnits']);

      ////product 
      Route::post('/store-product', [CommonController::class, 'productStore']);
      Route::post('/update-product', [CommonController::class, 'productUpdate']);
      Route::post('/delete-product', [CommonController::class, 'productDelete']);
      Route::match(['get', 'post'],'/get_products', [CommonController::class, 'getProducts']);
      Route::get('/get_product_code', [CommonController::class, 'getProductCode']);
      ////Customer 
      Route::post('/store-customer', [CommonController::class, 'customerStore']);
      Route::post('/update-customer', [CommonController::class, 'customerUpdate']);
      Route::post('/delete-customer', [CommonController::class, 'customerDelete']);
      Route::match(['get', 'post'],'/get_customers', [CommonController::class, 'getCustomers']);
      Route::get('/get_customer_code', [CommonController::class, 'getCustomerCode']);
      ////Supplier 
      Route::post('/store-supplier', [CommonController::class, 'supplierStore']);
      Route::post('/update-supplier', [CommonController::class, 'supplierUpdate']);
      Route::post('/delete-supplier', [CommonController::class, 'supplierDelete']);
      Route::match(['get', 'post'],'/get_suppliers', [CommonController::class, 'getSuppliers']);
      Route::get('/get_supplier_code', [CommonController::class, 'getSupplierCode']);
      
      ////Purchase  
      Route::post('/store-purchase', [PurchaseController::class, 'purchaseStore']);
      Route::post('/update-purchase', [PurchaseController::class, 'purchaseUpdate']);;
      Route::post('/get_purchase', [PurchaseController::class, 'getPurchase']);
      Route::post('/delete-purchase', [PurchaseController::class, 'purchaseDelete']);
      Route::get('/purchase_entry/{id}', [PurchaseController::class, 'purchaseOrderEdit']);

      ////Purchase Return
      Route::post('/store-purchase-return', [PurchaseController::class, 'purchaseReturnStore']);
      Route::post('/update-purchase-return', [PurchaseController::class, 'purchaseReturnUpdate']);;
      Route::post('/get_purchase_return', [PurchaseController::class, 'getPurchaseReturn']);
      Route::post('/delete-purchase-return', [PurchaseController::class, 'purchaseReturnDelete']);
      Route::get('/purchase_return_entry/{id}', [PurchaseController::class, 'purchaseReturnOrderEdit']);

      ////Sales  
      Route::post('/store-sale', [SaleController::class, 'saleStore']);
      Route::post('/update-sale', [SaleController::class, 'saleUpdate']);;
      Route::post('/get_sales', [SaleController::class, 'getSales']);
      Route::post('/delete-sale', [SaleController::class, 'saleDelete']);
      Route::get('/sale_entry/{id}', [SaleController::class, 'saleOrderEdit']);
      
      ////Sales return
      Route::post('/store-sale-return', [SaleController::class, 'saleReturnStore']);
      Route::post('/update-sale-return', [SaleController::class, 'saleReturnUpdate']);;
      Route::post('/get_sales_return', [SaleController::class, 'getReturnSales']);
      Route::post('/delete-sale-return', [SaleController::class, 'saleReturnDelete']);
      Route::get('/sale_return_entry/{id}', [SaleController::class, 'saleReturnOrderEdit']);
      
      
      
      
      /////Stock
      Route::post('/get_stock', [InventoryController::class, 'getProductStock']);
      Route::match(['get', 'post'],'/get_current_stock_inventory', [InventoryController::class, 'getCurrentStockInventory']);
      Route::match(['get', 'post'],'/get_total_stock_inventory', [InventoryController::class, 'getTotalStockInventory']);



});
