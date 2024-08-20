<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Branch;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

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

    public function databaseBackup(Request $request)
    {
        $method = $request->method();
        if($method == 'GET'){
            return view('admin.database_backup');
        }else{
            $filename = "backup-" . now()->format('Y-m-d-H-i-s') . ".zip";
            // Create backup folder and set permission if not exist.
            $storageAt = storage_path() . "/app/backup/";
            if(!File::exists($storageAt)) {
                File::makeDirectory($storageAt, 0755, true, true);
            }
            $command = "".env('DB_DUMP_PATH', 'mysqldump')." --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . $storageAt . $filename;
            $returnVar = NULL;
            $output = NULL;
            exec($command, $output, $returnVar);
            
            return response()->download($storageAt . $filename)->deleteFileAfterSend(true);
        }
    }
}
