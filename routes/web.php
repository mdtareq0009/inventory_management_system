<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;
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

Route::middleware('auth')->group(function () {

    Route::middleware('access')->group(function () {
        Route::get('/category_entry', [CommonController::class, 'categoryEntry'])->name('category_entry');
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


});
