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

class InventoryController extends Controller
{

    ////Purchase
    public function purchaseOrderInventoryEntry()
    {
        $id = 0;
        $invoice = generatePurchaseOrderInventoryCode();
        return view('admin.inventory.purchase_order_inventory_entry',compact('id','invoice'));
    }

    public function purchaseInventoryRecord()
    {
        return view('admin.inventory.purchase_inventory_record');
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

                }
            }


            DB::commit();
            return response()->json(['message' => 'Purhcase Added','id'=> $data->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }


    public function purchaseUpdate(Request $request)
    {
        $purcahse = $request->purchase;
        $productCart = $request->cartProducts;
        $validator   = Validator::make((array) $purcahse, [
            'id'              => 'required|integer',
            'invoice_number'  => ['required','string',Rule::unique('purchases')->ignore($purcahse['id'],'id')],
            'supplier_id'     => 'required|integer',
            'order_date'      => 'required|date',
            'total'           => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
            $data                   = Purchase::find($purcahse['id']);
            $data->invoice_number   = $purcahse['invoice_number'];
            $data->supplier_id      = $purcahse['supplier_id'];
            $data->order_date       = $purcahse['order_date'];
            $data->total            = $purcahse['total'];
            $data->remark           = $purcahse['note'];
            $data->updated_by       = auth()->user()->id;
            $data->ip_address       = $request->ip();
            $data->branch_id        = session('branch_id');
            $data->save(); 

            $oldPurchaseDetails = PurchaseDetail::where('purchase_id',$purcahse['id'])->get();

            $deletePurchaseDetails=PurchaseDetail::where('purchase_id',$purcahse['id']);
            $deletePurchaseDetails->forceDelete();

            
            foreach ($oldPurchaseDetails as $value) {
                Stock::where('product_id', $value['productId'])
                ->where('branch_id', session('branch_id'))
                ->update([
                    'purchase_quantity' => DB::raw('purchase_quantity - ' . $value['quantity']),
                    'stock_quantity' => DB::raw('stock_quantity - ' . $value['quantity']),
                ]);
            }
            
            foreach ($productCart as $value) {
                PurchaseDetail::create(array(
                    'purchase_id'      => $data->id,
                    'product_id'          => $value['productId'],
                    'quantity'         => $value['quantity'],
                    'purchase_rate'    => $value['purchase_price'],
                    'total_amount'     => $value['total'],
                    'updated_by'       => auth()->user()->id,
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

                }
            }


            DB::commit();
            return response()->json(['message' => 'Purchase Updated','id'=> $data->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function purchaseOrderEdit($id)
    {
        $id = $id;
        $purs = Purchase::findOrFail($id);
        $invoice = $purs->invoice_number;
        return view('admin.inventory.purchase_order_inventory_entry',compact('id','invoice'));
    }

    public function getPurchase(Request $request)
    {
        $query = Purchase::with(['supplier', 'user','purchaseDetails.product'])
                ->whereNull('deleted_at')
                ->where('branch_id', session('branch_id'))
                ->when($request->purchaseId, function ($q) use ($request) {
                    return $q->where('id', $request->purchaseId);
                })
                ->when($request->dateFrom && $request->dateTo, function ($q) use ($request) {
                    return $q->whereBetween('order_date', [$request->dateFrom, $request->dateTo]);
                })
                ->when($request->supplier_id, function ($q) use ($request) {
                    return $q->where('supplier_id', $request->supplier_id);
                })
                ->when($request->userId, function ($q) use ($request) {
                    return $q->where('created_by', $request->userId);
                });

            // Execute the query and transform the results
            $result = $query->get()->map(function ($purchase) {
                return [
                    'purchase' => $purchase,
                    'invoice_text' => $purchase->invoice_number . ' - ' . $purchase->supplier->name,
                    'display_name' => $purchase->supplier->supplier_code . ' - ' . $purchase->supplier->name,
                    'supplier_code' => $purchase->supplier->supplier_code,
                    'supplier_name' => $purchase->supplier->name,
                    'supplier_mobile' => $purchase->supplier->mobile,
                    'supplier_address' => $purchase->supplier->address,
                    'user_name' => $purchase->user->name,
                    'supplierId' => $purchase->supplier->id,
                ];
            });

            // Add purchase details if requested
           

            // Return the final result
            return  response()->json($result);
    }

    public function purchaseDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();
        
        try {

            $purchase = Purchase::where('id', $request->id)->first();
            if($purchase->deleted_at != null){
                return response()->json(['message' => 'Purchase not found']);
                exit;
            }

          
            $purchaseDetails = PurchaseDetail::where('purchase_id', $request->id)->get();
            foreach($purchaseDetails as $detail) {
                $stock = inventoryStock($detail->product_id);
                if($detail->quantity > $stock) {
                    return response()->json(['success'=>false,'message' => 'Product out of stock, Purchase can not be deleted']);
                    exit;
                }
            }

            foreach($purchaseDetails as $product){
                Stock::where('product_id', $product->product_id)
                ->where('branch_id', session('branch_id'))
                ->decrement('purchase_quantity', $product->quantity);
                
                Stock::where('product_id', $product->product_id)
                ->where('branch_id', session('branch_id'))
                ->decrement('stock_quantity', $product->quantity);
            }

            Purchase::where('id', $request->id)->delete();
            PurchaseDetail::where('purchase_id', $request->id)->delete();

            DB::commit();
            return response()->json(['message' => 'Purchase Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
    public function purchaseReturnInventoryEntry()
    {
        $id = 0;
        $invoice = generatePurchaseReturnInventoryCode();
        return view('admin.inventory.purchase_return_inventory_entry',compact('id','invoice'));
    }

    public function purchaseReturnInventoryRecord()
    {
        return view('admin.inventory.purchase_return_inventory_record');
    }


    public function purchaseReturnStore(Request $request)
    {

        $purchasereturn = $request->purchasereturn;
        $productCart = $request->cartProducts;
        $validator   = Validator::make((array) $purchasereturn, [
            'id'              => 'required|integer',
            'invoice_number'  => 'required|string|unique:purchase_returns',
            'supplier_id'     => 'required|integer',
            'return_date'     => 'required|date',
            'total'           => 'required|numeric',
        ]);
        

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
            $data                   = New PurchaseReturn();
            $data->invoice_number   = $purchasereturn['invoice_number'];
            $data->supplier_id      = $purchasereturn['supplier_id'];
            $data->return_date      = $purchasereturn['return_date'];
            $data->total_amount     = $purchasereturn['total'];
            $data->remark           = $purchasereturn['note'];
            $data->created_by       = auth()->user()->id;
            $data->ip_address       = $request->ip();
            $data->branch_id        = session('branch_id');
            $data->save(); 

     
            foreach ($productCart as $value) {
                PurchaseReturnDetail::create(array(
                    'purchase_return_id'=> $data->id,
                    'product_id'       => $value['productId'],
                    'quantity'         => $value['quantity'],
                    'return_rate'      => $value['return_rate'],
                    'total_amount'     => $value['total'],
                    'created_by'       => auth()->user()->id,
                    'ip_address'       => $request->ip(),
                    'branch_id'        => session('branch_id')
                ));

                $stockCount = Stock::where('product_id', $value['productId'])
                                 ->where('branch_id', session('branch_id'))
                                 ->count();
               // dd($stockCount);
                if( $stockCount != 0){
                    Stock::where('product_id', $value['productId'])
                    ->where('branch_id', session('branch_id'))
                    ->update([
                        'purchase_return_quantity' => DB::raw('purchase_return_quantity + ' . $value['quantity']),
                        'stock_quantity' => DB::raw('stock_quantity - ' . $value['quantity']),
                    ]);

                }
            }


            DB::commit();
            return response()->json(['message' => 'Purhcase Return Added','id'=> $data->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }


    public function purchaseReturnUpdate(Request $request)
    {
        $purchasereturn = $request->purchasereturn;
        $productCart = $request->cartProducts;
        $validator   = Validator::make((array) $purchasereturn, [
            'id'              => 'required|integer',
            'invoice_number'  => ['required','string',Rule::unique('purchase_returns')->ignore($purchasereturn['id'],'id')],
            'supplier_id'     => 'required|integer',
            'return_date'     => 'required|date',
            'total'           => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
            $data                   = PurchaseReturn::find($purchasereturn['id']);
            $data->invoice_number   = $purchasereturn['invoice_number'];
            $data->supplier_id      = $purchasereturn['supplier_id'];
            $data->return_date      = $purchasereturn['return_date'];
            $data->total_amount     = $purchasereturn['total'];
            $data->remark           = $purchasereturn['note'];
            $data->updated_by       = auth()->user()->id;
            $data->ip_address       = $request->ip();
            $data->branch_id        = session('branch_id');
            $data->save(); 

            $oldPurchaseReDetails = PurchaseReturnDetail::where('purchase_return_id',$purchasereturn['id'])->get();

            $deletePurchaseDetails=PurchaseReturnDetail::where('purchase_return_id',$purchasereturn['id']);
            $deletePurchaseDetails->forceDelete();

            
            foreach ($oldPurchaseReDetails as $value) {
                Stock::where('product_id', $value['productId'])
                ->where('branch_id', session('branch_id'))
                ->update([
                    'purchase_return_quantity' => DB::raw('purchase_return_quantity - ' . $value['quantity']),
                    'stock_quantity' => DB::raw('stock_quantity + ' . $value['quantity']),
                ]);
            }
            
            foreach ($productCart as $value) {
                PurchaseReturnDetail::create(array(
                    'purchase_return_id'=> $data->id,
                    'product_id'       => $value['productId'],
                    'quantity'         => $value['quantity'],
                    'return_rate'      => $value['return_rate'],
                    'total_amount'     => $value['total'],
                    'updated_by'       => auth()->user()->id,
                    'ip_address'       => $request->ip(),
                    'branch_id'        => session('branch_id')
                ));

                $stockCount = Stock::where('product_id', $value['productId'])
                                 ->where('branch_id', session('branch_id'))
                                 ->count();
               // dd($stockCount);
                if( $stockCount != 0){
                 
                    Stock::where('product_id', $value['productId'])
                    ->where('branch_id', session('branch_id'))
                    ->update([
                        'purchase_return_quantity' => DB::raw('purchase_return_quantity + ' . $value['quantity']),
                        'stock_quantity' => DB::raw('stock_quantity - ' . $value['quantity']),
                    ]);

                }
            }


            DB::commit();
            return response()->json(['message' => 'Purchase Return Updated','id'=> $data->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function purchaseReturnOrderEdit($id)
    {
        $id = $id;
        $purs = PurchaseReturn::findOrFail($id);
        $invoice = $purs->invoice_number;
        return view('admin.inventory.purchase_return_inventory_entry',compact('id','invoice'));
    }

    public function getPurchaseReturn(Request $request)
    {
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
                    'display_name' => $purchasereturn->supplier->supplier_code . ' - ' . $purchasereturn->supplier->name,
                    'supplier_code' => $purchasereturn->supplier->supplier_code,
                    'supplier_name' => $purchasereturn->supplier->name,
                    'supplier_mobile' => $purchasereturn->supplier->mobile,
                    'supplier_address' => $purchasereturn->supplier->address,
                    'user_name' => $purchasereturn->user->name,
                    'supplierId' => $purchasereturn->supplier->id,
                ];
            });

            // Add purchase details if requested
           

            // Return the final result
            return  response()->json($result);
    }

    public function purchaseReturnDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();
        
        try {

            $purchasereturn = PurchaseReturn::where('id', $request->id)->first();
            if($purchasereturn->deleted_at != null){
                return response()->json(['message' => 'Purchase Return not found']);
                exit;
            }

          
            $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_return_id', $request->id)->get();
            foreach($purchaseReturnDetails as $detail) {
                $stock = inventoryStock($detail->product_id);
                if($detail->quantity > $stock) {
                    return response()->json(['success'=>false,'message' => 'Product out of stock, Purchase return can not be deleted']);
                    exit;
                }
            }

            foreach($purchaseReturnDetails as $product){
                Stock::where('product_id', $product->product_id)
                ->where('branch_id', session('branch_id'))
                ->decrement('purchase_return_quantity', $product->quantity);
                
                Stock::where('product_id', $product->product_id)
                ->where('branch_id', session('branch_id'))
                ->increment('stock_quantity', $product->quantity);
            }

            PurchaseReturn::where('id', $request->id)->delete();
            PurchaseReturnDetail::where('purchase_return_id', $request->id)->delete();

            DB::commit();
            return response()->json(['message' => 'Purchase Return Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
    ////Sale
    public function saleOrderInventoryEntry()
    {
        $id = 0;
        $invoice = generateSaleOrderInventoryCode();
        return view('admin.inventory.sale_order_entry',compact('id','invoice'));
    }

    public function saleInventoryRecord()
    {
        return view('admin.inventory.sale_inventory_record');
    }


    public function saleStore(Request $request)
    {

        $sale = $request->sale;
        $productCart = $request->cartProducts;
        $validator = Validator::make((array) $sale, [
            'invoice_number'  => 'required|string|unique:sales',
            'customer_id'     => 'required|integer',
            'order_date'      => 'required|date',
            'total'           => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
            $data                   = New Sale();
            $data->invoice_number   = $sale['invoice_number'];
            $data->customer_id      = $sale['customer_id'];
            $data->order_date       = $sale['order_date'];
            $data->total            = $sale['total'];
            $data->remark           = $sale['note'];
            $data->created_by       = auth()->user()->id;
            $data->ip_address       = $request->ip();
            $data->branch_id        = session('branch_id');
            $data->save(); 

     
            foreach ($productCart as $value) {
                SaleDetail::create(array(
                    'sale_id'          => $data->id,
                    'product_id'       => $value['productId'],
                    'quantity'         => $value['quantity'],
                    'purchase_rate'    => $value['purchase_price'],
                    'sale_rate'        => $value['sale_rate'],
                    'total_amount'     => $value['total'],
                    'created_by'       => auth()->user()->id,
                    'ip_address'       => $request->ip(),
                    'branch_id'        => session('branch_id')
                ));

                $stockCount = Stock::where('product_id', $value['productId'])
                                 ->where('branch_id', session('branch_id'))
                                 ->count();
               // dd($stockCount);
                if( $stockCount != 0){

                    Stock::where('product_id', $value['productId'])
                    ->where('branch_id', session('branch_id'))
                    ->update([
                        'sale_quantity' => DB::raw('sale_quantity + ' . $value['quantity']),
                        'stock_quantity' => DB::raw('stock_quantity - ' . $value['quantity']),
                    ]);
            }
        }


            DB::commit();
            return response()->json(['message' => 'Sale Added']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }


    public function saleUpdate(Request $request)
    {
        $sale = $request->sale;
        $productCart = $request->cartProducts;
        $validator   = Validator::make((array) $sale, [
            'id'              => 'required|integer',
            'invoice_number'  => ['required','string',Rule::unique('sales')->ignore($sale['id'],'id')],
            'customer_id'     => 'required|integer',
            'order_date'      => 'required|date',
            'total'           => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
            $data                   = Sale::find($sale['id']);
            $data->invoice_number   = $sale['invoice_number'];
            $data->customer_id      = $sale['customer_id'];
            $data->order_date       = $sale['order_date'];
            $data->total            = $sale['total'];
            $data->remark           = $sale['note'];
            $data->updated_by       = auth()->user()->id;
            $data->ip_address       = $request->ip();
            $data->branch_id        = session('branch_id');
            $data->save(); 

            $oldSaleDetails = SaleDetail::where('sale_id',$sale['id'])->get();

            $deleteSaleDetails=SaleDetail::where('sale_id',$sale['id']);
            $deleteSaleDetails->forceDelete();

            
            foreach ($oldSaleDetails as $value) {
                Stock::where('product_id', $value['productId'])
                ->where('branch_id', session('branch_id'))
                ->update([
                    'sale_quantity' => DB::raw('sale_quantity - ' . $value['quantity']),
                    'stock_quantity' => DB::raw('stock_quantity + ' . $value['quantity']),
                ]);
            }
            
            foreach ($productCart as $value) {
                SaleDetail::create(array(
                    'sale_id'          => $data->id,
                    'product_id'       => $value['productId'],
                    'quantity'         => $value['quantity'],
                    'purchase_rate'    => $value['purchase_price'],
                    'sale_rate'        => $value['sale_rate'],
                    'total_amount'     => $value['total'],
                    'updated_by'       => auth()->user()->id,
                    'ip_address'       => $request->ip(),
                    'branch_id'        => session('branch_id')
                ));

                $stockCount = Stock::where('product_id', $value['productId'])
                                 ->where('branch_id', session('branch_id'))
                                 ->count();
               // dd($stockCount);
                if( $stockCount != 0){
                   
                    Stock::where('product_id', $value['productId'])
                    ->where('branch_id', session('branch_id'))
                    ->update([
                        'sale_quantity' => DB::raw('sale_quantity + ' . $value['quantity']),
                        'stock_quantity' => DB::raw('stock_quantity - ' . $value['quantity']),
                    ]);

                }
            }


            DB::commit();
            return response()->json(['message' => 'Sale Updated','id'=> $data->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function saleOrderEdit($id)
    {
        $id = $id;
        $sales = Sale::findOrFail($id);
        $invoice = $sales->invoice_number;
        return view('admin.inventory.sale_order_entry',compact('id','invoice'));
    }

    public function getSales(Request $request)
    {
        $query = Sale::with(['customer', 'user','saleDetails.product'])
                ->whereNull('deleted_at')
                ->where('branch_id', session('branch_id'))
                ->when($request->saleId, function ($q) use ($request) {
                    return $q->where('id', $request->saleId);
                })
                ->when($request->dateFrom && $request->dateTo, function ($q) use ($request) {
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
                    'display_name' => $sale->customer->customer_code . ' - ' . $sale->customer->name,
                    'customer_code' => $sale->customer->customer_code,
                    'customer_name' => $sale->customer->name,
                    'customer_mobile' => $sale->customer->mobile,
                    'customer_address' => $sale->customer->address,
                    'user_name' => $sale->user->name,
                    'customerId' => $sale->customer->id,
                ];
            });

            // Add purchase details if requested
           

            // Return the final result
            return  response()->json($result);
    }






    public function saleDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();
        
        try {

            $sale = Sale::where('id', $request->id)->first();
            if($sale->deleted_at != null){
                return response()->json(['message' => 'Sale not found']);
                exit;
            }

          
            $saleDetails = SaleDetail::where('sale_id', $request->id)->get();
            foreach($saleDetails as $detail) {
                $stock = inventoryStock($detail->product_id);
                if($detail->quantity > $stock) {
                    return response()->json(['success'=>false,'message' => 'Product out of stock, Sale can not be deleted']);
                    exit;
                }
            }

            foreach($saleDetails as $product){
                Stock::where('product_id', $product->product_id)
                ->where('branch_id', session('branch_id'))
                ->decrement('sale_quantity', $product->quantity);
                
                Stock::where('product_id', $product->product_id)
                ->where('branch_id', session('branch_id'))
                ->increment('stock_quantity', $product->quantity);
            }

            Sale::where('id', $request->id)->delete();
            SaleDetail::where('sale_id', $request->id)->delete();

            DB::commit();
            return response()->json(['message' => 'Sale Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

 ////Sale
    public function saleReturnInventoryEntry()
    {
        $id = 0;
        $invoice = generateSaleReturnInventoryCode();
        return view('admin.inventory.sale_return_entry',compact('id','invoice'));
    }

    public function saleReturnInventoryRecord()
    {
        return view('admin.inventory.sale_return_inventory_record');
    }


    public function saleReturnStore(Request $request)
    {

        $salereturn = $request->salereturn;
        $productCart = $request->cartProducts;
        $validator = Validator::make((array) $salereturn, [
            'invoice_number'  => 'required|string|unique:sale_returns',
            'customer_id'     => 'required|integer',
            'return_date'      => 'required|date',
            'total'           => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
            $data                   = New SaleReturn();
            $data->invoice_number   = $salereturn['invoice_number'];
            $data->customer_id      = $salereturn['customer_id'];
            $data->return_date      = $salereturn['return_date'];
            $data->total_amount     = $salereturn['total'];
            $data->remark           = $salereturn['note'];
            $data->created_by       = auth()->user()->id;
            $data->ip_address       = $request->ip();
            $data->branch_id        = session('branch_id');
            $data->save(); 

     
            foreach ($productCart as $value) {
                SaleReturnDetail::create(array(
                    'sale_return_id'   => $data->id,
                    'product_id'       => $value['productId'],
                    'quantity'         => $value['quantity'],
                    'return_rate'      => $value['return_rate'],
                    'total_amount'     => $value['total'],
                    'created_by'       => auth()->user()->id,
                    'ip_address'       => $request->ip(),
                    'branch_id'        => session('branch_id')
                ));

                $stockCount = Stock::where('product_id', $value['productId'])
                                 ->where('branch_id', session('branch_id'))
                                 ->count();
               // dd($stockCount);
                if( $stockCount != 0){
                    Stock::where('product_id', $value['productId'])
                    ->where('branch_id', session('branch_id'))
                    ->update([
                        'sale_return_quantity' => DB::raw('sale_return_quantity + ' . $value['quantity']),
                        'stock_quantity' => DB::raw('stock_quantity + ' . $value['quantity']),
                ]);
            }
        }


            DB::commit();
            return response()->json(['message' => 'Sale Return Added']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }


    public function saleReturnUpdate(Request $request)
    {
        $salereturn = $request->salereturn;
        $productCart = $request->cartProducts;
        $validator   = Validator::make((array) $salereturn, [
            'id'              => 'required|integer',
            'invoice_number'  => ['required','string',Rule::unique('sale_returns')->ignore($salereturn['id'],'id')],
            'customer_id'     => 'required|integer',
            'return_date'     => 'required|date',
            'total'           => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
            $data                   = SaleReturn::find($salereturn['id']);
            $data->invoice_number   = $salereturn['invoice_number'];
            $data->customer_id      = $salereturn['customer_id'];
            $data->return_date      = $salereturn['return_date'];
            $data->total_amount     = $salereturn['total'];
            $data->remark           = $salereturn['note'];
            $data->updated_by       = auth()->user()->id;
            $data->ip_address       = $request->ip();
            $data->branch_id        = session('branch_id');
            $data->save(); 

            $oldSaleDetails = SaleReturnDetail::where('sale_return_id',$salereturn['id'])->get();

            $deleteSaleDetails=SaleReturnDetail::where('sale_return_id',$salereturn['id']);
            $deleteSaleDetails->forceDelete();

            
            foreach ($oldSaleDetails as $value) {
                Stock::where('product_id', $value['productId'])
                ->where('branch_id', session('branch_id'))
                ->update([
                    'sale_return_quantity' => DB::raw('sale_return_quantity - ' . $value['quantity']),
                    'stock_quantity' => DB::raw('stock_quantity - ' . $value['quantity']),
                ]);
            }
            
            foreach ($productCart as $value) {
                SaleReturnDetail::create(array(
                    'sale_return_id'   => $data->id,
                    'product_id'       => $value['productId'],
                    'quantity'         => $value['quantity'],
                    'return_rate'      => $value['return_rate'],
                    'total_amount'     => $value['total'],
                    'updated_by'       => auth()->user()->id,
                    'ip_address'       => $request->ip(),
                    'branch_id'        => session('branch_id')
                ));

                $stockCount = Stock::where('product_id', $value['productId'])
                ->where('branch_id', session('branch_id'))
                ->count();
                    // dd($stockCount);
                    if( $stockCount != 0){
                    Stock::where('product_id', $value['productId'])
                    ->where('branch_id', session('branch_id'))
                    ->update([
                        'sale_return_quantity' => DB::raw('sale_return_quantity + ' . $value['quantity']),
                        'stock_quantity' => DB::raw('stock_quantity + ' . $value['quantity']),
                    ]);
                    }
            }


            DB::commit();
            return response()->json(['message' => 'Sale Return Updated','id'=> $data->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function saleReturnOrderEdit($id)
    {
        $id = $id;
        $salesreturn = SaleReturn::findOrFail($id);
        $invoice = $salesreturn->invoice_number;
        return view('admin.inventory.sale_return_entry',compact('id','invoice'));
    }

    public function getReturnSales(Request $request)
    {
        $query = SaleReturn::with(['customer', 'user','saleReturnDetails.product'])
                ->whereNull('deleted_at')
                ->where('branch_id', session('branch_id'))
                ->when($request->saleReturnId, function ($q) use ($request) {
                    return $q->where('id', $request->saleReturnId);
                })
                ->when($request->dateFrom && $request->dateTo, function ($q) use ($request) {
                    return $q->whereBetween('return_date', [$request->dateFrom, $request->dateTo]);
                })
                ->when($request->customer_id, function ($q) use ($request) {
                    return $q->where('customer_id', $request->customer_id);
                });

            // Execute the query and transform the results
            $result = $query->get()->map(function ($salereturn) {
                return [
                    'salereturn' => $salereturn,
                    'invoice_text' => $salereturn->invoice_number . ' - ' . $salereturn->customer->name,
                    'display_name' => $salereturn->customer->customer_code . ' - ' . $salereturn->customer->name,
                    'customer_code' => $salereturn->customer->customer_code,
                    'customer_name' => $salereturn->customer->name,
                    'customer_mobile' => $salereturn->customer->mobile,
                    'customer_address' => $salereturn->customer->address,
                    'user_name' => $salereturn->user->name,
                    'customerId' => $salereturn->customer->id,
                ];
            });

            // Add purchase details if requested
           

            // Return the final result
            return  response()->json($result);
    }




    

    public function saleReturnDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();
        
        try {

            $saleReturn = SaleReturn::where('id', $request->id)->first();
            if($saleReturn->deleted_at != null){
                return response()->json(['message' => 'Sale Return not found']);
                exit;
            }

          
            $saleReturnDetails = SaleReturnDetail::where('sale_return_id', $request->id)->get();
            foreach($saleReturnDetails as $detail) {
                $stock = inventoryStock($detail->product_id);
                if($detail->quantity > $stock) {
                    return response()->json(['success'=>false,'message' => 'Product out of stock, Sale Return can not be deleted']);
                    exit;
                }
            }

            foreach($saleReturnDetails as $product){
                Stock::where('product_id', $product->product_id)
                ->where('branch_id', session('branch_id'))
                ->decrement('sale_quantity', $product->quantity);
                
                Stock::where('product_id', $product->product_id)
                ->where('branch_id', session('branch_id'))
                ->decrement('stock_quantity', $product->quantity);
            }

            SaleReturn::where('id', $request->id)->delete();
            SaleReturnDetail::where('sale_return_id', $request->id)->delete();

            DB::commit();
            return response()->json(['message' => 'Sale Return Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
    ////Check Stock

    public function getProductStock(Request $request)
    {
        $stock = inventoryStock($request->productId);
       // dd($stock);
        return response()->json($stock);
    }




    public function pdfGenerate(Request $request)
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
            'display_name' => $sale->customer->customer_code . ' - ' . $sale->customer->name,
            'customer_code' => $sale->customer->customer_code,
            'customer_name' => $sale->customer->name,
            'customer_mobile' => $sale->customer->mobile,
            'customer_address' => $sale->customer->address,
            'user_name' => $sale->user->name,
            'customerId' => $sale->customer->id,
        ];
    });

    $total = $query->sum('total');
        if($details=="without_details"){
            $pdf = Pdf::loadView('admin.inventory.sale_inventory_record_pdf',compact('result','dateFrom','total','dateTo','customer_id'));
            return $pdf->download('invoice.pdf');
        }else{
            $pdfdetails = Pdf::loadView('admin.inventory.sale_inventory_record_pdf_details',['result' => $result, 'total'=>$total]);

            return $pdfdetails->download('invoice.pdf');
        }
        

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
        $result['totalValue'] = array_sum(
            array_map(function($product){
                return $product->stock_value;
            }, $stock));


        return response()->json($result);
    }


}