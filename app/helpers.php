<?php

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Admission;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Stock;
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
function generateSaleOrderInventoryCode()
{
    $code = "SAL00001";

    $total_count = Sale::withTrashed()->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'SAL' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function inventoryStock($productId) {
    $stockQuery = Stock::where('product_id', $productId)
    ->where('branch_id', session('branch_id'))
    ->get();
    
    $stockCount = $stockQuery->count();
    $stock = 0;
    if($stockCount != 0){
        $stockRow = $stockQuery->first();
        $stock = ($stockRow->purchase_quantity + $stockRow->sale_return_quantity) 
                - ($stockRow->purchase_return_quantity + $stockRow->sale_quantity);
    }

    return $stock;
}


function currentStock($clauses = '') {
    $stock = DB::select("
            SELECT * from(
                SELECT
                    ci.*,
                    ci.stock_quantity as current_quantity,
                    p.name as product_name,
                    p.product_code as product_code,
                    pc.name as category_name,
                    u.name as unit_name
                from stocks ci
                left join products p on p.id = ci.product_id
                left join categories pc on pc.id = p.category_id
                left join units u on u.id = p.unit_id
                where p.deleted_at is null
                and ci.branch_id = ?
            ) as tbl
            where 1 = 1
            $clauses
        ", [session('branch_id')]);

        return $stock;
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