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
use App\Models\PurchaseReturn;
use App\Models\SaleReturn;
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
function generatePurchaseReturnInventoryCode()
{
    $code = "PR00001";

    $total_count = PurchaseReturn::withTrashed()->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'PR' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
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
function generateSaleReturnInventoryCode()
{
    $code = "SR00001";

    $total_count = SaleReturn::withTrashed()->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'SR' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
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
            order by product_id asc
        ", [session('branch_id')]);

        return $stock;
}
function totalStock($clauses = '',$date='') {
    
   
    $stock =  DB::select("
   
    SELECT
        p.*,
        pc.name as category_name,
        u.name as unit_name,
        (SELECT ifnull(sum(pd.quantity), 0) 
            from purchase_details pd 
            left join purchases pr on pr.id = pd.purchase_id
            where pd.product_id = p.id
            and pd.branch_id = ".session('branch_id')."
            and pd.deleted_at is null
            " . (isset($date) && $date != null ? " and pr.order_date <= '$date'" : "") . "
        ) as purchased_quantity,

        (SELECT ifnull(sum(prd.quantity), 0) 
            from purchase_return_details prd 
            left join purchase_returns pr on pr.id = prd.purchase_return_id 
            where prd.product_id = p.id
            and prd.branch_id = ".session('branch_id')."
            and prd.deleted_at is null
            " . (isset($date) && $date != null ? " and pr.return_date <= '$date'" : "") . "
        ) as purchased_return_quantity,
                
        (SELECT ifnull(sum(sd.quantity), 0) 
            from sale_details sd
            left join sales s on s.id = sd.sale_id
            where sd.product_id = p.id
            and sd.branch_id  = ".session('branch_id')."
            and sd.deleted_at is null
            " . (isset($date) && $date != null ? " and s.order_date <= '$date'" : "") . "
        ) as sold_quantity,

        (SELECT ifnull(sum(isrud.quantity), 0) 
            from sale_return_details isrud
            left join sale_returns sr on sr.id = isrud.sale_return_id
            where isrud.product_id = p.id
            and isrud.branch_id  = ".session('branch_id')."
            and isrud.deleted_at is null
            " . (isset($date) && $date != null ? " and sr.return_date <= '$date'" : "") . "
        ) as sale_return_quantity,

        (SELECT (purchased_quantity + sale_return_quantity) - (sold_quantity  + purchased_return_quantity)) as current_quantity,
        (SELECT p.purchase_price * current_quantity) as stock_value
    from products p
    left join categories pc on pc.id = p.category_id
    left join units u on u.id = p.unit_id
    where p.deleted_at is null
    $clauses
");




return $stock;
}

function imageUpload($request, $name, $directory)
{
    $doUpload = function ($image) use ($directory) {
        $name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $extention = $image->getClientOriginalExtension();
        $imageName = $name . '_' . uniqId() . '.' . $extention;
        $image->move($directory, $imageName);
        return $directory . '/' . $imageName;
    };

    if (!empty($name) && $request->hasFile($name)) {
        $file = $request->file($name);
        if (is_array($file) && count($file)) {
            $imagesPath = [];
            foreach ($file as $key => $image) {
                $imagesPath[] = $doUpload($image);
            }
            return $imagesPath;
        } else {
            return $doUpload($file);
        }
    }

    return false;
}

function checkPermissions($permission)
{
    if(array_search($permission, auth()->user()->permissions) > -1 || in_array(auth()->user()->role, ['Admin', 'General'])){
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