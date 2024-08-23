<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\Stock;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class InventoryController extends Controller
{

    
    ////Check Stock

    public function getProductStock(Request $request)
    {
        $stock = inventoryStock($request->productId);
       // dd($stock);
        return response()->json($stock);
    }




   
    public function stockInventory()
    {
        return view('admin.inventory.stock_inventory');
    }

    public function  getCurrentStockInventory(Request $request){
        
        $clauses = "";
        if(isset($request->stockType) && $request->stockType == 'low'){
            $clauses .= " and current_quantity <= reorder_level";
        }

       //dd($clauses);
        $stock = currentStock($clauses);
        $result['stock'] = $stock;
        return response()->json($result);
    }

    public function  getTotalStockInventory(Request $request){
        
     
        $branchId = session('branch_id');
        $clauses = "";
        if($request->categoryId && $request->categoryId != null){
            $clauses .= " and p.category_id  = '$request->categoryId'";
        }

        if($request->productId && $request->productId != null){
            $clauses .= " and p.id = '$request->productId'";
        }


        $stock =  DB::SELECT("
            SELECT
                p.*,
                pc.name as category_name,
                u.name as unit_name,
                (SELECT ifnull(sum(pd.quantity), 0) 
                    from purchase_details pd 
                    left join purchases pr on pr.id = pd.purchase_id
                    where pd.product_id = p.id
                    and pd.branch_id = '$branchId'
                    and pd.deleted_at is null
                    " . (isset($request->date) && $request->date != null ? " and pr.order_date <= '$request->date'" : "") . "
                ) as purchased_quantity,

                (SELECT ifnull(sum(prd.quantity), 0) 
                    from purchase_return_details prd 
                    left join purchase_returns pr on pr.id = prd.purchase_return_id 
                    where prd.product_id = p.id
                    and prd.branch_id = '$branchId'
                    and prd.deleted_at is null
                    " . (isset($request->date) && $request->date != null ? " and pr.return_date <= '$request->date'" : "") . "
                ) as purchased_return_quantity,
                        
                (SELECT ifnull(sum(sd.quantity), 0) 
                    from sale_details sd
                    left join sales s on s.id = sd.sale_id
                    where sd.product_id = p.id
                    and sd.branch_id  = '$branchId'
                    and sd.deleted_at is null
                    " . (isset($request->date) && $request->date != null ? " and s.order_date <= '$request->date'" : "") . "
                ) as sold_quantity,

                (SELECT ifnull(sum(isrud.quantity), 0) 
                    from sale_return_details isrud
                    left join sale_returns sr on sr.id = isrud.sale_return_id
                    where isrud.product_id = p.id
                    and isrud.branch_id  = '$branchId'
                    and isrud.deleted_at is null
                    " . (isset($request->date) && $request->date != null ? " and sr.return_date <= '$request->date'" : "") . "
                ) as sale_return_quantity,
  
                (SELECT (purchased_quantity + sale_return_quantity) - (sold_quantity  + purchased_return_quantity)) as current_quantity,
                (SELECT p.purchase_price * current_quantity) as stock_value
            from products p
            left join categories pc on pc.id = p.category_id
            left join units u on u.id = p.unit_id
            where p.deleted_at is null
            $clauses
        ");
      //  dd($stock);
        $result['stock'] = $stock;


        return response()->json($result);
    }


    public function pdfStock(Request $request)
    {
       
        $searchTypes = $request->query('searchTypes');

       
        $date = $request->query('date');
        
        $productId = $request->query('productId')??null;
        $catId = $request->query('catId')??null;
        
        
        
    $clauses = "";
    if($searchTypes == 'category'){
        if($catId != null){
            $clauses .= " and p.category_id  = '$catId'";
        }
    }
    if($searchTypes == 'product'){
        if($productId != null){
            $clauses .= " and p.id = '$productId'";
        }
    }
       

        
        $stock = totalStock($clauses,$date);
      
        $clausesData='';
        $currnert_stock = currentStock($clausesData);



        
        if($searchTypes=="current"){
            $pdf = Pdf::loadView('admin.pdfgenerate.currnet_stock',['result' => $currnert_stock]);
            return $pdf->download('currnet_stock.pdf');
        }else{
            $pdfdetails = Pdf::loadView('admin.pdfgenerate.total_stock',['result' => $stock]);
            return $pdfdetails->download('total_stock.pdf');
        }
    }



    public function pdfGeneratePurchaseRecord(Request $request)
    {

        $dateFrom = $request->query('dateFrom');
        $dateTo = $request->query('dateTo');
        $supplier_id = $request->query('supplier_id');
        $details = $request->query('recordType');

        $query = Purchase::with(['supplier', 'user','purchaseDetails.product'])
        ->whereNull('deleted_at')
        ->where('branch_id', session('branch_id'))
        ->when($dateFrom && $dateTo, function ($q) use ($request) {
            return $q->whereBetween('order_date', [$request->dateFrom, $request->dateTo]);
        })
        ->when($supplier_id, function ($q) use ($request) {
            return $q->where('supplier_id', $request->supplier_id);
        });

        // Execute the query and transform the results
        $result = $query->get()->map(function ($purchase) {
            return [
                'purchase' => $purchase,
                'invoice_text' => $purchase->invoice_number . ' - ' . $purchase->supplier->name,
                'display_name' => $purchase->supplier->supplier_code . ' - ' . $purchase->supplier->name
            ];
        });

    $total = $query->sum('total');
        if($details=="without_details"){
            $pdf = Pdf::loadView('admin.pdfgenerate.purcahse',['result' => $result, 'total'=>$total]);
            return $pdf->download('purcahserecord.pdf');
        }else{
            $pdfdetails = Pdf::loadView('admin.pdfgenerate.purcahse_details',['result' => $result, 'total'=>$total]);

            return $pdfdetails->download('purcahse_details.pdf');
        }
    }
    public function pdfGeneratePurchaseReturnRecord(Request $request)
    {

        $dateFrom = $request->query('dateFrom');
        $dateTo = $request->query('dateTo');
        $supplier_id = $request->query('supplier_id');
        $details = $request->query('recordType');

        $query = PurchaseReturn::with(['supplier', 'user','purchaseReturnDetails.product'])
                ->whereNull('deleted_at')
                ->where('branch_id', session('branch_id'))
                ->when($request->purchaseReturnId, function ($q) use ($request) {
                    return $q->where('id', $request->purchaseReturnId);
                })
                ->when($request->dateFrom && $request->dateTo, function ($q) use ($request) {
                    return $q->whereBetween('return_date', [$request->dateFrom, $request->dateTo]);
                })
                ->when($request->supplier_id, function ($q) use ($request) {
                    return $q->where('supplier_id', $request->supplier_id);
                });
           

            // Execute the query and transform the results
            $result = $query->get()->map(function ($purchasereturn) {
                return [
                    'purchasereturn' => $purchasereturn,
                    'invoice_text' => $purchasereturn->invoice_number . ' - ' . $purchasereturn->supplier->name,
                    'display_name' => $purchasereturn->supplier->supplier_code . ' - ' . $purchasereturn->supplier->name
                ];
            });

    $total = $query->sum('total_amount');
        if($details=="without_details"){
            $pdf = Pdf::loadView('admin.pdfgenerate.purcahsereturn',['result' => $result, 'total'=>$total]);
            return $pdf->download('purcahse_return_record.pdf');
        }else{
            $pdfdetails = Pdf::loadView('admin.pdfgenerate.purcahse_return_details',['result' => $result, 'total'=>$total]);

            return $pdfdetails->download('purcahse_return_details.pdf');
        }
    }

    public function pdfGenerateSale(Request $request)
    {

        $dateFrom = $request->query('dateFrom');
        $dateTo = $request->query('dateTo');
        $customer_id = $request->query('customer_id');
        $details = $request->query('recordType');

        $query = Sale::with(['customer', 'user','saleDetails.product'])
        ->whereNull('deleted_at')
        ->where('branch_id', session('branch_id'))
        ->when($request->saleId, function ($q) use ($request) {
            return $q->where('id', $request->saleId);
        })
        ->when($dateFrom && $dateTo, function ($q) use ($request) {
            return $q->whereBetween('order_date', [$request->dateFrom, $request->dateTo]);
        })
        ->when($request->customer_id, function ($q) use ($request) {
            return $q->where('customer_id', $request->customer_id);
        });
       

    // Execute the query and transform the results
    $result = $query->get()->map(function ($sale) {
        return [
            'sale' => $sale,
            'invoice_text' => $sale->invoice_number . ' - ' . $sale->customer->name,
            'display_name' => $sale->customer->customer_code . ' - ' . $sale->customer->name
        ];
    });

        $total = $query->sum('total');
        if($details=="without_details"){
            $pdf = Pdf::loadView('admin.pdfgenerate.sale',compact('result','total'));
            return $pdf->download('invoice.pdf');
        }else{
            $pdfdetails = Pdf::loadView('admin.pdfgenerate.sale_details',['result' => $result, 'total'=>$total]);

            return $pdfdetails->download('invoice.pdf');
        }
    }
    public function pdfGenerateSaleReturn(Request $request)
    {
        $dateFrom = $request->query('dateFrom');
        $dateTo = $request->query('dateTo');
        $customer_id = $request->query('customer_id');
        $details = $request->query('recordType');

        $query = SaleReturn::with(['customer', 'user','saleReturnDetails.product'])
                ->whereNull('deleted_at')
                ->where('branch_id', session('branch_id'))
                ->when($dateFrom && $dateTo, function ($q) use ($request) {
                    return $q->whereBetween('return_date', [$request->dateFrom, $request->dateTo]);
                })
                ->when($customer_id, function ($q) use ($request) {
                    return $q->where('customer_id', $request->customer_id);
                });

           
            $result = $query->get()->map(function ($salereturn) {
                return [
                    'salereturn' => $salereturn,
                    'invoice_text' => $salereturn->invoice_number . ' - ' . $salereturn->customer->name,
                    'display_name' => $salereturn->customer->customer_code . ' - ' . $salereturn->customer->name
                ];
            });

        $total = $query->sum('total_amount');
        if($details=="without_details"){
            $pdf = Pdf::loadView('admin.pdfgenerate.sale_return',compact('result','total'));
            return $pdf->download('sale_return.pdf');
        }else{
            $pdfdetails = Pdf::loadView('admin.pdfgenerate.sale_return_details',['result' => $result, 'total'=>$total]);

            return $pdfdetails->download('sale_return_details.pdf');
        }
    }
    public function pdfGenerateProduct(Request $request)
    {
            $result = Product::with(['category', 'unit'])
                    ->whereNull('deleted_at')
                    ->select('products.*')
                    ->selectRaw("concat(name, ' - ', product_code) as display_text")
                    ->orderByDesc('id')
                    ->get();
            // dd($getProduct);
            $pdf = Pdf::loadView('admin.pdfgenerate.product_list',compact('result'));
            return $pdf->download('product_list.pdf');
      
    }


}