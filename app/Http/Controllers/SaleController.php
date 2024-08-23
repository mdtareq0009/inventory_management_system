<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\Stock;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class SaleController extends Controller
{

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

           
            $result = $query->get()->map(function ($salereturn) {
                return [
                    'salereturn' => $salereturn,
                    'invoice_text' => $salereturn->invoice_number . ' - ' . $salereturn->customer->name,
                    'display_name' => $salereturn->customer->customer_code . ' - ' . $salereturn->customer->name
                ];
            });

        
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
   


}