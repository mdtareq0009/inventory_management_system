<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Bed;
use App\Models\Branch;
use App\Models\CompanyProfile;
use App\Models\Floor;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use File;

class AdministrationController extends Controller
{

    
    public function getBranchCode()
    {
        return response()->json(generateBranchCode());
    }
    public function getBranches(Request $request)
    {
        $branches = DB::table('branches as a')
            ->whereNull('a.deleted_at');

        if ($request->status) {
            $branches->where('a.status', $request->status);
        }

        if ($request->mobile) {
            $branches->where('a.mobile', $request->mobile);
        }

        if ($request->code) {
            $branches->where('a.code', $request->code);
        }

        $branches->selectRaw("a.*, concat_ws(' - ', a.code, a.name) as display_name");

        $branches =  $branches->orderBy('a.id', 'desc')->lazy();

        return response()->json($branches);
    }
    
    public function companyProfile()
    {
        if (!in_array(auth()->user()->role, ['Admin', 'Super Admin']) || session('branch_id') != 1) {
            return redirect()->route('dashboard');
        }
        return view('admin.company_profile');
    }
    public function getCompanies()
    {
        $comapanys = DB::table('company_profiles')->first();

        return response()->json($comapanys);
    }

    public function companyProfileStore(Request $request)
    {
        $request->validate([
            'agent_code'         => ['required', 'string', 'unique:agents'],
            'name'               => ['required', 'string', 'max:255'],
            'mobile'             => ['required', 'string', 'size:11'],
            'commission_percent' => ['required', 'numeric'],
        ]);

        DB::beginTransaction();

        try {
            $data = (array) $request->all();
            $data['created_by']  = auth()->user()->id;
            $data['ip_address']  = $request->ip();
            $data['branch_id']  = session('branch_id');

            Agent::create($data);

            DB::commit();
            return response()->json(['message' => 'Agent Added']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function companyProfileUpdate(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = CompanyProfile::first();
            $logo = $data->logo;
            $path = public_path('images/company_profile_org');
            if (!File::exists($path)) {
                FIle::makeDirectory($path, 0777, true);
            }
            if ($request->hasFile('image')) {
                if (!empty($logo) && file_exists($logo))
                    unlink($logo);
                $data->logo = imageUpload($request, 'image', 'images/company_profile_org');
            }
            $data->name    = $request->name;
            $data->email   = $request->email;
            $data->phone   = $request->phone;
            $data->address = $request->address;
            $data->save();


            DB::commit();
            return response()->json(['message' => 'Company Profile Updated']);
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function branchUpdate(Request $request)
    {


        DB::beginTransaction();

        try {
            $data = Branch::where('id', $request->id)->where('status', 'a')->first();

            $data->name       = $request->name;
            $data->code       = $request->code;
            $data->address    = $request->address;
            $data->updated_by = auth()->user()->id;
            $data->ip_address = $request->ip();
            $data->save();


            DB::commit();
            return response()->json(['message' => 'Branch Update']);
        } catch (\Exception $e) {
            // return $e;
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
    public function branchStore(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = new Branch();
            $data->name       = $request->name;
            $data->code       = $request->code;
            $data->address    = $request->address;
            $data->created_by = auth()->user()->id;
            $data->ip_address = $request->ip();
            $data->save();
            DB::commit();
            return response()->json(['message' => 'Branch Added']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
    public function branchDelete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DB::beginTransaction();

        try {
            Branch::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(['message' => 'Branch Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
  

   

}
