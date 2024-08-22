<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Branch;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function dashboard()
    {
        session(['module' => 'Dashboard']);
        return view('admin.dashboard');
    }

    public function module($module)
    {
        session(['module' => $module]);
        return view('admin.dashboard');
    }

    

    public function branchChange(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        try {
            session(['branch_id' => $request->id]);
            return response()->json(['message' => 'Branch Changed!']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function getUsers()
    {
        $users = DB::select(
            "SELECT u.*,
            CASE
                WHEN u.status = 'a' THEN 'Active'
                ELSE 'Deactive'
            END as status_text,
            d.mobile as doctor_mobile,
            b.name as branch_name

            from users u
            left join branches b on b.id = u.branch_id
            where u.deleted_at is null
        ");

        return response()->json($users);
    }

    public function userActivity()
    {
        if(auth()->user()->role != 'Super Admin' || session('branch_id') != 1){
            return redirect()->route('dashboard');
        }
        return view('admin.user_activity');
    }

    public function getUserActivity(Request $request)
    {
        $clauses = "";
        if($request->dateFrom && $request->dateTo){
            $dateFrom   = $request->dateFrom.' 00:00:00';
            $dateTo     = $request->dateTo.' 23:59:59';
            $clauses .= " and ua.login_time between '$dateFrom' and '$dateTo'";
        }

        if($request->user_id != ''){
            $clauses .= " and ua.user_id = '$request->user_id'";
        }
        
        $result = DB::select("
            SELECT ua.*,
            u.username,
            u.role,
            b.name as branch_name

            from user_activities ua
            left join users u on u.id = ua.user_id
            left join branches b on b.id = ua.branch_id
            where 1 = 1
            $clauses
            order by ua.id desc
        ");

        return response()->json($result);
    }

    public function deleteUserActivity(Request $request){
        $res = ['success'=>false, 'message'=>''];
        try{
            if (isset($request->id) && $request->id != '') {
                UserActivity::where('id', $request->id)->delete();

                $res = ['success'=>true, 'message'=>'Data deleted'];
            }elseif (isset($request->mark_arr) && $request->mark_arr != []) {
                $ids = join("','",$request->mark_arr);
                DB::select("DELETE FROM user_activities where id in ('$ids')");

                $res = ['success'=>true, 'message'=>'Data deleted'];
            }else{
                $res = ['success'=>false, 'message'=>'Something went wrong!'];
            }

            
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        return response()->json($res);
    }

    public function getBranchInfo()
    {
        $company = CompanyProfile::first();
        $branch = Branch::where('id', session('branch_id'))->first();
    
        return response()->json([
            'logo' => '/'.$company->logo,
            'name' => $branch->name,
            'address' => $branch->address
        ]);
    }

    public function userAccess($id)
    {
        if(!in_array(auth()->user()->role, ['Super Admin', 'Admin'])){
            return redirect()->route('dashboard');
        }

        return view('admin.user_access', compact('id'));
    }

    public function addUserAccess(Request $request)
    {
        try {
            User::where('id', $request->user_id)->update(['permissions' => json_encode($request->access)]);
            return response()->json(['success' => true, 'message' => 'Success']);
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }
    }

    public function getUserAccess($id)
    {
        return response()->json(DB::table('users')->where('id', $id)->first()->permissions);
    }



    public function categoryEntry()
    {
        return view('admin.category_entry');
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        DB::beginTransaction();
        
        try {
            $data = (array) $request->all();

            $data['created_by'] = auth()->user()->id;
            $data['ip_address'] = $request->ip();
            $data['branch_id']  = session('branch_id');

            Category::create($data);

            DB::commit();
            return response()->json(['message' => 'Category Added']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function categoryUpdate(Request $request)
    {
        $request->validate([
            'id'   => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255']
        ]);

        DB::beginTransaction();
        
        try {
            $data = (array) $request->all();

            $data['updated_by'] = auth()->user()->id;
            $data['ip_address'] = $request->ip();

            Category::where('id', $request->id)->update($data);

            DB::commit();
            return response()->json(['message' => 'Category Updated']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function categoryDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();
        
        try {
            Category::where('id', $request->id)->delete();

            DB::commit();
            return response()->json(['message' => 'Category Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function getCategories(Request $request)
    {
        $categories = Category::whereNull('deleted_at')
                    ->orderByDesc('id')
                    ->lazy();

        return response()->json($categories);
    }


    public function unitEntry()
    {
        return view('admin.unit_entry');
    }

    public function unitStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        DB::beginTransaction();
        
        try {
            $data = (array) $request->all();
            $data['created_by'] = auth()->user()->id;
            $data['ip_address'] = $request->ip();
            $data['branch_id']  = session('branch_id');

            Unit::create($data);

            DB::commit();
            return response()->json(['message' => 'Unit Added']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function unitUpdate(Request $request)
    {
        $request->validate([
            'id'   => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255']
        ]);

        DB::beginTransaction();
        
        try {
            $data = (array) $request->all();
            $data['updated_by'] = auth()->user()->id;
            $data['ip_address'] = $request->ip();

            Unit::where('id', $request->id)->update($data);

            DB::commit();
            return response()->json(['message' => 'Unit Updated']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function unitDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();
        
        try {
            Unit::where('id', $request->id)->delete();

            DB::commit();
            return response()->json(['message' => 'Unit Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function getUnits(Request $request)
    {
        $units = Unit::whereNull('deleted_at')
        ->orderByDesc('id')
        ->lazy();

        return response()->json($units);
    }


    public function productEntry()
    {
        return view('admin.product_entry');
    }

    public function productStore(Request $request)
    {
        $product = json_decode($request->products);

        $validator = Validator::make((array) $product, [
            'product_code' => 'required|string|unique:products',
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|integer',
            'unit_id'         => 'required|integer',
            'reorder_level'   => 'required|numeric',
            'purchase_price'  => 'required|numeric',
            'sale_price'      => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
           
            $data                  = New Product();
            $data->name            = $product->name;
            $data->product_code    = $product->product_code;
            $data->category_id     = $product->category_id;
            $data->unit_id         = $product->unit_id;
            $data->purchase_price  = $product->purchase_price;
            $data->sale_price      = $product->sale_price;
            $data->reorder_level   = $product->reorder_level;
            $data->created_by      = auth()->user()->id;
            $data->ip_address      = $request->ip();
            $data->branch_id       = session('branch_id');
            $data->save(); 

            DB::commit();
            return response()->json(['message' => 'Product Added']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function productUpdate(Request $request)
    {
        $product = json_decode($request->products);

        $validator = Validator::make((array) $product, [
            'id'              => 'required|integer',
            'product_code'    => ['required','string',Rule::unique('products')->ignore($product->id,'id')],
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|integer',
            'unit_id'         => 'required|integer',
            'reorder_level'   => 'required|numeric',
            'purchase_price'  => 'required|numeric',
            'sale_price'      => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        
        try {
            $data=Product::find($product->id);
           
            $data->name            = $product->name;
            $data->product_code    = $product->product_code;
            $data->category_id     = $product->category_id;
            $data->unit_id         = $product->unit_id;
            $data->purchase_price  = $product->purchase_price;
            $data->sale_price      = $product->sale_price;
            $data->reorder_level   = $product->reorder_level;
            $data->updated_by      = auth()->user()->id;
            $data->ip_address      = $request->ip();
            $data->branch_id       = session('branch_id');
            $data->save(); 
            DB::commit();
            return response()->json(['message' => 'Product Updated']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function productDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();
        
        try {
            Product::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(['message' => 'Product Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
    public function getProducts(Request $request)
    {
        $getProduct = Product::with(['category', 'unit'])
                    ->whereNull('deleted_at')
                    ->select('products.*')
                    ->selectRaw("concat(name, ' - ', product_code) as display_text")
                    ->orderByDesc('id')
                    ->lazy();

        return response()->json($getProduct);
    }

    public function getProductCode()
    {
        return response()->json(generateProductCode());
    }
    public function customerEntry()
    {
        return view('admin.customer_entry');
    }

    public function customerStore(Request $request)
    {
        $customer = json_decode($request->customers);
       
        $validator = Validator::make((array) $customer, [
            'customer_code'     => 'required|string|unique:customers',
            'name'              => 'required|string|max:255',
            'owner_name'        => 'required|string|max:255',
            'mobile'            => 'required|string|max:13'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
           
            $data                  = New Customer();
            $data->name            = $customer->name;
            $data->customer_code   = $customer->customer_code;
            $data->owner_name      = $customer->owner_name;
            $data->mobile          = $customer->mobile;
            $data->address         = $customer->address;
            $data->remark          = $customer->remark;
            $data->created_by      = auth()->user()->id;
            $data->ip_address      = $request->ip();
            $data->branch_id       = session('branch_id');
            $data->save(); 

            DB::commit();
            return response()->json(['message' => 'Customer Added']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function customerUpdate(Request $request)
    {
        $customer = json_decode($request->customers);
       
        $validator = Validator::make((array) $customer, [
            'id'                => 'required|integer',
            'customer_code'     => ['required','string',Rule::unique('customers')->ignore($customer->id,'id')],
            'name'              => 'required|string|max:255',
            'owner_name'        => 'required|string|max:255',
            'mobile'            => 'required|string|max:13'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        
        try {
            $data=Customer::find($customer->id);
           
            $data->name            = $customer->name;
            $data->owner_name      = $customer->owner_name;
            $data->mobile          = $customer->mobile;
            $data->address         = $customer->address;
            $data->remark          = $customer->remark;
            $data->updated_by      = auth()->user()->id;
            $data->ip_address      = $request->ip();
            $data->branch_id       = session('branch_id');
            $data->save(); 
            DB::commit();
            return response()->json(['message' => 'Customer Updated']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function customerDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();
        
        try {
            Customer::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(['message' => 'Customer Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
    public function getCustomers(Request $request)
    {
        $getCustomer = Customer::whereNull('deleted_at')
                    ->select('customers.*')
                    ->selectRaw("concat(name, ' - ', customer_code) as display_text")
                    ->orderByDesc('id')
                    ->lazy();

        return response()->json($getCustomer);
    }

    public function getCustomerCode()
    {
        return response()->json(generateCustomerCode());
    }
    public function supplierEntry()
    {
        return view('admin.supplier_entry');
    }

    public function supplierStore(Request $request)
    {
        $supplier = json_decode($request->suppliers);
       
        $validator = Validator::make((array) $supplier, [
            'supplier_code'     => 'required|string|unique:suppliers',
            'name'              => 'required|string|max:255',
            'owner_name'        => 'required|string|max:255',
            'mobile'            => 'required|string|max:13'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
       
        DB::beginTransaction();
        
        try {
           
            $data                  = New Supplier();
            $data->name            = $supplier->name;
            $data->supplier_code   = $supplier->supplier_code;
            $data->owner_name      = $supplier->owner_name;
            $data->mobile          = $supplier->mobile;
            $data->address         = $supplier->address;
            $data->remark          = $supplier->remark;
            $data->created_by      = auth()->user()->id;
            $data->ip_address      = $request->ip();
            $data->branch_id       = session('branch_id');
            $data->save(); 

            DB::commit();
            return response()->json(['message' => 'supplier Added']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function supplierUpdate(Request $request)
    {
        $supplier = json_decode($request->suppliers);
       
        $validator = Validator::make((array) $supplier, [
            'id'                => 'required|integer',
            'supplier_code'     => ['required','string',Rule::unique('suppliers')->ignore($supplier->id,'id')],
            'name'              => 'required|string|max:255',
            'owner_name'        => 'required|string|max:255',
            'mobile'            => 'required|string|max:13'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        
        try {
            $data=Supplier::find($supplier->id);
           
            $data->name            = $supplier->name;
            $data->owner_name      = $supplier->owner_name;
            $data->mobile          = $supplier->mobile;
            $data->address         = $supplier->address;
            $data->remark          = $supplier->remark;
            $data->updated_by      = auth()->user()->id;
            $data->ip_address      = $request->ip();
            $data->branch_id       = session('branch_id');
            $data->save(); 
            DB::commit();
            return response()->json(['message' => 'Supplier Updated']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function supplierDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();
        
        try {
            Supplier::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(['message' => 'Supplier Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
    public function getSuppliers(Request $request)
    {
        $getCustomer = Supplier::whereNull('deleted_at')
                    ->select('suppliers.*')
                    ->selectRaw("concat(name, ' - ', supplier_code) as display_text")
                    ->orderByDesc('id')
                    ->lazy();

        return response()->json($getCustomer);
    }

    public function getSupplierCode()
    {
        return response()->json(generateSupplierCode());
    }

  
}
