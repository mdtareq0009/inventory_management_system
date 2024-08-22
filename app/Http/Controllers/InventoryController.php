<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Stock;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function purchaseOrderInventoryEntry()
    {
        $id = 0;
        $invoice = generatePurchaseOrderInventoryCode();
        return view('admin.inventory.purchase_order_inventory_entry',compact('id','invoice'));
    }


    public function purchaseStore(Request $request)
    {

 
        $purcahse = $request->purchase;
        $productCart = $request->cartProducts;
        $validator = Validator::make((array) $purcahse, [
            'invoice_number'  => 'required|string|unique:purchases',
            'supplier_id'     => 'required|integer',
            'order_date'      => 'required|date',
            'total'           => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
            $data                   = New Purchase();
            $data->invoice_number   = $purcahse['invoice_number'];
            $data->supplier_id      = $purcahse['supplier_id'];
            $data->order_date       = $purcahse['order_date'];
            $data->total            = $purcahse['total'];
            $data->remark           = $purcahse['note'];
            $data->created_by       = auth()->user()->id;
            $data->ip_address       = $request->ip();
            $data->branch_id        = session('branch_id');
            $data->save(); 

     
            foreach ($productCart as $value) {
                PurchaseDetail::create(array(
                    'purchase_id'      => $data->id,
                    'product_id'          => $value['productId'],
                    'quantity'         => $value['quantity'],
                    'purchase_rate'    => $value['purchase_price'],
                    'total_amount'     => $value['total'],
                    'created_by'       => auth()->user()->id,
                    'ip_address'       => $request->ip(),
                    'branch_id'        => session('branch_id')
                ));

                $stockCount = Stock::where('product_id', $value['productId'])
                                 ->where('branch_id', session('branch_id'))
                                 ->count();
               // dd($stockCount);
                if( $stockCount == 0){
                    Stock::create(array(
                        'product_id'        => $value['productId'],
                        'purchase_quantity' => $value['quantity'],
                        'stock_quantity'    => $value['quantity'],
                        'branch_id'         => session('branch_id')
                    ));
                }else{
                    Stock::where('product_id', $value['productId'])
                    ->where('branch_id', session('branch_id'))
                    ->update([
                        'purchase_quantity' => DB::raw('purchase_quantity + ' . $value['quantity']),
                        'stock_quantity' => DB::raw('stock_quantity + ' . $value['quantity']),
                    ]);
                    
                    /// Update Price
                    Product::where('id', $value['productId'])
                    ->update(['purchase_price' => $value['purchase_price']]);
                }
            }


            DB::commit();
            return response()->json(['message' => 'Purhcase Added','id'=> $data->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function getPurchase(Request $request)
    {
        $branchId = session('branch_id');
        $purchases = Purchase::with(['supplier', 'user', 'purchaseDetails.product.unit'])
            ->select('purchases.*')
            ->selectRaw("concat_ws(' - ', purchases.invoice_number, suppliers.name) as invoice_text")
            ->selectRaw("concat_ws(' - ', suppliers.supplier_code, suppliers.name) as display_name")
            ->select('suppliers.supplier_code as supplier_code')
            ->select('suppliers.name as supplier_name')
            ->select('suppliers.mobile as supplier_mobile')
            ->select('suppliers.address as supplier_address')
            ->select('users.name as user_name')
            ->select('suppliers.id as supplierId')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->leftJoin('users', 'users.id', '=', 'purchases.created_by')
            ->whereNull('purchases.deleted_at')
            ->where('purchases.branch_id', $branchId);
        
        // Apply dynamic conditions
        if ($request->filled('purchaseId')) {
            $purchases->where('purchases.id', $request->purchaseId);
        }
        
        if ($request->filled('dateFrom') && $request->filled('dateTo')) {
            $purchases->whereBetween('purchases.order_date', [$request->dateFrom, $request->dateTo]);
        }
        
        if ($request->filled('supplier_id')) {
            $purchases->where('purchases.supplier_id', $request->supplier_id);
        }

        // Execute the query
        $purchases = $purchases->get();

        if ($request->with_details) {
            foreach ($purchases as $purchase) {
                $purchase->purchaseDetails->map(function($detail) {
                    $detail->code = $detail->product->product_code;
                    $detail->product_name = $detail->product->name;
                    $detail->unit_name = $detail->product->unit->name;
                    $detail->display_text = $detail->product->name . ' - ' . $detail->product->product_code;
                });
            }
        }
    }


}