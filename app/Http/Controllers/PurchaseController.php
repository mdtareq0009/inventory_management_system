<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\Stock;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class PurchaseController extends Controller
{

    ////Product List
    public function productList()
    {
        return view('admin.inventory.product_list');
    }
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
    


}