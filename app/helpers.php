<?php

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Admission;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\DB;



function generateProductCode()
{
    $code = "P00001";

    $total_count =Product::withTrashed()->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'P' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateCustomerCode()
{
    $code = "C00001";

    $total_count =Customer::withTrashed()->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'C' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateSupplierCode()
{
    $code = "S00001";

    $total_count =Supplier::withTrashed()->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'S' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function generateBranchCode()
{
    $code = "B00001";

    $total_count = DB::table('branches')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'B' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateSupplierInventoryCode()
{
    $code = "SI00001";

    $total_count = DB::table('suppliers')->where('use_for','Instrument')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'SI' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}


function generatePurchaseOrderInventoryCode()
{
    $code = "PUR00001";

    $total_count = Purchase::withTrashed()->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'PUR' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function checkPermissions($permission)
{
    if(array_search($permission, auth()->user()->permissions) > -1 || in_array(auth()->user()->role, ['Admin', 'Super Admin'])){
        return true;
    } else {
        return false;
    }
}

function companyProfile()
{
    return CompanyProfile::first();
}

function branchInfo()
{
    return Branch::find(session('branch_id'));
}