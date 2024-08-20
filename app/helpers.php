<?php

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Admission;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\DB;

function generatePatientCode(){

    $branchId = session('branch_id');
    $branchNo = strlen($branchId) < 10 ? '0' . $branchId : $branchId;

    $code = now()->format('y') . $branchNo . "00001";
    $year = now()->format('y');

    $total_count = DB::table('patients')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = $year. $branchNo . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function generateTestCode(){
    $code = "T00001";

    $total_count = DB::table('tests')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'T' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function generateDoctorCode()
{
    $code = "D00001";

    $total_count = DB::table('doctors')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'D' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function generateAgentCode()
{
    $code = "A00001";

    $total_count = DB::table('agents')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'A' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateReleaseSlipCode()
{
    $code = "RS00001";

    $total_count = DB::table('release_slips')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'RS' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateEmployeeCode()
{
    $code = "E00001";

    $total_count = DB::table('employees')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'E' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateMedicineCode()
{
    $code = "M00001";

    $total_count = DB::table('medicines')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'M' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateInstrumentCode()
{
    $code = "I00001";

    $total_count = DB::table('instruments')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'I' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateFloorCode()
{
    $code = "F00001";

    $total_count = DB::table('floors')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'F' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
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
function generateSupplierPharmacyCode()
{
    $code = "SM00001";

    $total_count = DB::table('suppliers')->where('use_for','Medicine')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'SM' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateDriverCode()
{
    $code = "D00001";

    $total_count = DB::table('drivers')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'D' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateAmbulanceCode()
{
    $code = "AM00001";

    $total_count = DB::table('ambulances')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'AM' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generatePurchaseOrderInventoryCode()
{
    $code = "PI00001";

    $total_count = DB::table('purchases')->where('use_for','Instrument')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'PI' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generatePurchaseMedicineCode()
{
    $code = "PM00001";

    $total_count = DB::table('purchases')->where('use_for','Medicine')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'PM' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function supplierDue($clauses = "", $date = null)
{
    $result = DB::select(
        "SELECT
            s.id,
            s.supplier_code,
            s.name,
            s.mobile,
            s.address,
            (SELECT ifnull(sum(pm.total), 0.00) from purchases pm
                where pm.supplier_id = s.id
                " . ($date == null ? "" : " and pm.order_date < '$date'") . "
                and pm.deleted_at is null
            ) as bill,
            (select ifnull(sum(pm2.paid), 0.00) from purchases pm2
                where pm2.supplier_id = s.id
                " . ($date == null ? "" : " and pm2.order_date < '$date'") . "
                and pm2.deleted_at is null
            ) as invoicePaid,
            0.00 as cashPaid,
            0.00 as cashReceived,
            0.00 as returned,
            (select invoicePaid + cashPaid) as paid,
            (select (bill + cashReceived) - (paid + returned)) as due
            from suppliers s
            where s.branch_id = ?
            and s.use_for = 'Medicine'
            $clauses
        ",[session('branch_id')]);
    return $result;
}
function patientDue($clauses = "", $date = null)
{
    $result = DB::select(
        "SELECT
            p.id,
            p.patient_code,
            p.name,
            p.mobile,
            p.address,
            (SELECT ifnull(sum(sm.total), 0.00) from sales sm
                where sm.patient_id  = p.id
                and sm.admission_id   is null
                " . ($date == null ? "" : " and sm.order_date < '$date'") . "
                and sm.deleted_at is null
            ) as bill,
            (SELECT ifnull(sum(sm2.paid), 0.00) from sales sm2
                where sm2.patient_id  = p.id
                and sm2.admission_id  is null
                " . ($date == null ? "" : " and sm2.order_date < '$date'") . "
                and sm2.deleted_at is null
            ) as invoicePaid,

            (SELECT ifnull(sum(ab.bill_amount), 0.00) from ambulance_bills ab
                where ab.patient_id  = p.id
                and ab.admission_id   is null
                " . ($date == null ? "" : " and ab.bill_date < '$date'") . "
                and ab.deleted_at is null
            ) as ambulacne_bill,

            (SELECT ifnull(sum(ab.paid), 0.00) from ambulance_bills ab
                where ab.patient_id  = p.id
                and ab.admission_id   is null
                " . ($date == null ? "" : " and ab.bill_date < '$date'") . "
                and ab.deleted_at is null
            ) as ambulacne_paid,
            
            (SELECT ifnull(sum(b.amount), 0.00) from bills b
                where b.patient_id  = p.id
                and b.admission_id is null
                and b.transaction_type   = 'Bill'
                " . ($date == null ? "" : " and b.bill_date < '$date'") . "
                and b.deleted_at is null
            ) as other_bills,
            
            (SELECT ifnull(sum(b.amount), 0.00) from bills b
                where b.patient_id  = p.id
                and b.admission_id  is null
                and b.transaction_type   = 'Received'
                " . ($date == null ? "" : " and b.bill_date < '$date'") . "
                and b.deleted_at is null
            ) as other_bills_paid,
            
            (SELECT ifnull(sum(tr.total), 0.00) from test_receipts tr
                where tr.patient_id  = p.id
                and tr.admission_id  is null
                " . ($date == null ? "" : " and tr.bill_date < '$date'") . "
                and tr.deleted_at is null
            ) as test_bill,

            (SELECT ifnull(sum(tr.paid), 0.00) from test_receipts tr
                where tr.patient_id  = p.id
                and tr.admission_id  is null
                " . ($date == null ? "" : " and tr.bill_date < '$date'") . "
                and tr.deleted_at is null
            ) as test_invoicePaid,

            (SELECT ifnull(sum(pp.amount), 0.00) from patient_payments pp
                where pp.patient_id  = p.id
                and pp.admission_id   is null
                " . ($date == null ? "" : " and pp.payment_date < '$date'") . "
                and pp.deleted_at is null
                and pp.transaction_type = 'Payment'
            ) as patient_paid,
            (SELECT ifnull(sum(pp.amount), 0.00) from patient_payments pp
                where pp.patient_id  = p.id
                and pp.admission_id   is null
                " . ($date == null ? "" : " and pp.payment_date < '$date'") . "
                and pp.deleted_at is null
                and pp.transaction_type = 'Received'
            ) as patient_recieved,
            (SELECT ifnull(sum(sr.total_amount), 0.00) from sale_returns sr
                where sr.patient_id  = p.id
                and sr.admission_id  is null
                " . ($date == null ? "" : " and sr.return_date < '$date'") . "
                and sr.deleted_at is null
            ) as returned,
            (select invoicePaid + other_bills_paid + ambulacne_paid + test_invoicePaid + patient_recieved) as paid,
            (select (bill + test_bill + other_bills + ambulacne_bill + patient_paid) - (paid + returned)) as due
            from patients p
            where p.branch_id = ?
            $clauses
        ",[session('branch_id')]);
    return $result;
}
function generateIssueInventoryCode()
{
    $code = "IS00001";

    $total_count = DB::table('issues')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'IS' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function generateSaleMedicineCode()
{
    $code = "MS00001";

    $total_count = DB::table('sales')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'MS' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateAmbulanceBillCode()
{
    $code = "AB00001";

    $total_count = DB::table('ambulance_bills')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'AB' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateDamageInventoryCode()
{
    $code = "D00001";

    $total_count = DB::table('damages')->where('use_for','Instrument')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'D' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateDamageMedicineCode()
{
    $code = "DM00001";

    $total_count = DB::table('damages')->where('use_for','Medicine')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'DM' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateAccountCode()
{
    $code = "A00001";

    $total_count = DB::table('accounts')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'A' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateBankTransactionCode()
{
    $code = "BT00001";

    $total_count = DB::table('bank_transactions')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'BT' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateCashTransactionCode()
{
    $code = "CT00001";

    $total_count = DB::table('cash_transactions')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'CT' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function generateSupplierPaymentInventoryCode()
{
    $code = "SPI00001";

    $total_count = DB::table('supplier_payments')->where('use_for','Instrument')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'SPI' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateSupplierPaymentMedicineCode()
{
    $code = "SPM00001";

    $total_count = DB::table('supplier_payments')->where('use_for','Medicine')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'SPM' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateCommissionPaymentCode()
{
    $code = "CP00001";

    $total_count = DB::table('commission_payments')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'CP' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generatePatientPaymentMedicineCode()
{
    $code = "PP00001";

    $total_count = DB::table('patient_payments')->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = 'PP' . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
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


function generateTestReceiptCode()
{
    $branchId = session('branch_id');
    $branchNo = strlen($branchId) < 10 ? '0' . $branchId : $branchId;

    $code = now()->format('y') . $branchNo . "00001";
    $year = now()->format('y');

    $total_count = DB::table('test_receipts')->where('invoice_number', 'like', "$year%")->where('branch_id', session('branch_id'))->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = $year. $branchNo . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateSalaryCode()
{
    $branchId = session('branch_id');
    $branchNo = strlen($branchId) < 10 ? '0' . $branchId : $branchId;

    $code = now()->format('y') . $branchNo . "00001";
    $year = now()->format('y');

    $total_count = DB::table('salaries')->where('transaction_number', 'like', "$year%")->where('branch_id', session('branch_id'))->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = $year. $branchNo . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateOtScheduleCode()
{
    $branchId = session('branch_id');
    $branchNo = strlen($branchId) < 10 ? '0' . $branchId : $branchId;

    $code = now()->format('y') . $branchNo . "00001";
    $year = now()->format('y');

    $total_count = DB::table('operation_schedules')->where('schedule_code', 'like', "$year%")->where('branch_id', session('branch_id'))->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = $year. $branchNo . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateAppointmentTrId()
{
    $branchId = session('branch_id');
    $branchNo = strlen($branchId) < 10 ? '0' . $branchId : $branchId;

    $code = now()->format('y') . $branchNo . "00001";
    $year = now()->format('y');

    $total_count = DB::table('appointments')->where('token_number', 'like', "$year%")->where('branch_id', session('branch_id'))->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = $year. $branchNo . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateAdmissionCode()
{
    $branchId = session('branch_id');
    $branchNo = strlen($branchId) < 10 ? '0' . $branchId : $branchId;

    $code = now()->format('y') . $branchNo . "00001";
    $year = now()->format('y');

    $total_count = DB::table('admissions')->where('admission_code', 'like', "$year%")->where('branch_id', session('branch_id'))->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = $year. $branchNo . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}
function generateBillsTransactionCode()
{
    $branchId = session('branch_id');
    $branchNo = strlen($branchId) < 10 ? '0' . $branchId : $branchId;

    $code = now()->format('y') . $branchNo . "00001";
    $year = now()->format('y');

    $total_count = DB::table('bills')->where('transaction_number', 'like', "$year%")->where('branch_id', session('branch_id'))->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = $year. $branchNo . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function generatePrescriptionCode(){
    $branchId = session('branch_id');
    $branchNo = strlen($branchId) < 10 ? '0' . $branchId : $branchId;

    $code = now()->format('y') . $branchNo . "00001";
    $year = now()->format('y');

    $total_count = DB::table('prescriptions')->where('prescription_code', 'like', "$year%")->where('branch_id', session('branch_id'))->count();

    if($total_count > 0){
        $new_code = $total_count + 1;
        $zeros = array('0', '00', '000', '0000');

        $code = $year. $branchNo . (strlen($new_code) > count($zeros) ? $new_code : $zeros[count($zeros) - strlen($new_code)] . $new_code);
    }
    return $code;
}

function inventoryStock($productId) {
    $stockQuery = DB::table('instrument_stocks')->where('instrument_id', $productId)->where('branch_id', session('branch_id'));
    
    $stockCount = $stockQuery->count();
    $stock = 0;
    if($stockCount != 0){
        $stockRow = $stockQuery->first();
        $stock = ($stockRow->purchase_quantity + $stockRow->issue_return_quantity) 
                - ($stockRow->purchase_return_quantity + $stockRow->issue_quantity + $stockRow->damage_quantity);
    }

    return $stock;
}
function medicineStock($productId=null) {
    $stockQuery = DB::table('medicine_stocks')->where('medicine_id', $productId)->where('branch_id', session('branch_id'));
    
    $stockCount = $stockQuery->count();
    $stock = 0;
    if($stockCount != 0){
        $stockRow = $stockQuery->first();
        $stock = ($stockRow->purchase_quantity + $stockRow->sales_return_quantity) 
                - ($stockRow->purchase_return_quantity + $stockRow->sales_quantity + $stockRow->damage_quantity);
    }

    return $stock;
}
function checkOtRoom($roomId,$seatId,$fromdate,$todate) {
    $fromdate= \Carbon\Carbon::parse($fromdate);
    $todate= \Carbon\Carbon::parse($todate);
   
    $roomCheckQuery = DB::table('operation_schedules as ots')
    ->where(function($query) use ($fromdate,$todate){
        $query->whereBetween('ots.time_from', [$fromdate,$todate])
              ->orWhereBetween('ots.time_to', [$fromdate,$todate])
              ->orWhereDate('ots.time_from','>=', $todate);
      })
      ->whereNull('ots.deleted_at')
      ->where('ots.room_id', $roomId)
      ->where('ots.bed_id', $seatId)
      ->where('ots.is_done', 0)
      ->where('ots.branch_id', session('branch_id'));
      //dd($roomCheckQuery->count());
    $roomCheckCount = $roomCheckQuery->count();
    return $roomCheckCount;
}
function admissionBills($admission_id, $date='') {

    // DB::table('bill_types as bt')
    // ->selectRaw("bt.name,IFNULL(sum(b.amount), 0) as bill_amount")
    // ->leftJoin('bills as b', 'b.bill_type_id', '=', 'bt.id')
    // ->whereNull('bt.deleted_at')
    // ->where('b.admission_id',$request->admissionId)
    // ->where('b.transaction_type','Bill')
    // ->get();
    $bills = DB::select("
                SELECT
                    bt.id,
                    bt.name,
                    IFNULL(sum(b.amount), 0) as bill_amount
                from bill_types bt
                left join bills b on b.bill_type_id = bt.id
                where b.deleted_at is null
                and b.transaction_type = 'Bill'
                and b.admission_id = $admission_id
                and b.branch_id = ?
                group by bt.name,bt.id
        ", [session('branch_id')]);

        return $bills;
}
function admissionBillPaid($admission_id, $date='') {

    // DB::table('bill_types as bt')
    // ->selectRaw("bt.name,IFNULL(sum(b.amount), 0) as bill_amount")
    // ->leftJoin('bills as b', 'b.bill_type_id', '=', 'bt.id')
    // ->whereNull('bt.deleted_at')
    // ->where('b.admission_id',$request->admissionId)
    // ->where('b.transaction_type','Bill')
    // ->get();
    $bills = DB::select("
                SELECT
                    IFNULL(sum(b.amount), 0) as paid_amount
                from bills b
                where b.deleted_at is null
                and b.transaction_type = 'Received'
                and b.admission_id = ". $admission_id . "
                and b.branch_id = ?
                group by b.admission_id
        ", [session('branch_id')]);

        return $bills;
}
function admissionTestBills($admission_id, $date='') {

    // DB::table('bill_types as bt')
    // ->selectRaw("bt.name,IFNULL(sum(b.amount), 0) as bill_amount")
    // ->leftJoin('bills as b', 'b.bill_type_id', '=', 'bt.id')
    // ->whereNull('bt.deleted_at')
    // ->where('b.admission_id',$request->admissionId)
    // ->where('b.transaction_type','Bill')
    // ->get();
    $testBills = DB::select("
                SELECT
                    tr.admission_id,
                    IFNULL(sum(tr.due), 0) as test_amount,
                    IFNULL(sum(tr.paid), 0) as paid_amount
                from test_receipts tr
                where tr.deleted_at is null
                and tr.admission_id = ".$admission_id."
                and tr.branch_id = ?
                group by tr.admission_id
        ", [session('branch_id')]);

        return $testBills;
}
function admissionPharmacyBills($admission_id, $date='') {
    $pharmacyBills = DB::select("
                SELECT
                    s.admission_id,
                    IFNULL(sum(s.due), 0) as sale_amount,
                    IFNULL(sum(s.paid), 0) as paid_amount
                from sales s
                where s.deleted_at is null
                and s.admission_id = ".$admission_id."
                and s.branch_id = ?
                group by s.admission_id
        ", [session('branch_id')]);

        return $pharmacyBills;
}

function admissionPatientPaymentBills($admission_id, $date='') {
    $pharmacyBills = DB::select("
                SELECT
                    pp.admission_id,
                    IFNULL(sum(pp.amount), 0) as paid_amount

                from patient_payments pp
                where pp.deleted_at is null
                and pp.admission_id = ".$admission_id."
                and pp.branch_id = ?
                group by pp.admission_id
        ", [session('branch_id')]);

        return $pharmacyBills;
}

function admissionSeatFirstBills($admission_id, $date='') {
    $admission= Admission::where('id',$admission_id)->first();
    if($admission->is_shift==1){
        $admisson_seat_bill = DB::select("
                SELECT
                    a.id,
                    IFNULL(sum(DATEDIFF(a.bed_release_date,a.admission_date)), 0) as admission_days,
                    IFNULL(sum(a.bed_rent * DATEDIFF(a.bed_release_date,a.admission_date)), 0) as bed_amount
                from  admissions a
                where a.deleted_at is null
                and a.id = ".$admission_id."
                and a.is_shift = 1
                and a.branch_id = ?
                group by a.id
                ", [session('branch_id')]);
        
    }else{
        $admisson_seat_bill = DB::select("
                SELECT
                    a.id,
                    IFNULL(sum(DATEDIFF(DATE_ADD('$date', INTERVAL 1 DAY),a.admission_date)), 0) as admission_days,
                    IFNULL(sum(a.bed_rent * DATEDIFF(DATE_ADD('$date', INTERVAL 1 DAY),a.admission_date)), 0) as bed_amount
                from  admissions a
                where a.deleted_at is null
                and a.id = ".$admission_id."
                and a.is_shift = 0
                and a.branch_id = ?
                group by a.id
                ", [session('branch_id')]);
       // $seatSheftBills = $admisson_seat_last->admission_bed_amount;
    }
    return $admisson_seat_bill;

}

function admissionSeatSheftBills($admission_id) {

        
        $seatSheftBills = DB::select("
                SELECT
                    bs.admission_id,
                    IFNULL(sum(DATEDIFF(bs.bed_release_date,bs.shift_date)), 0) as sheft_days,
                    IFNULL(sum(bs.bed_rent * DATEDIFF(bs.bed_release_date,bs.shift_date)), 0) as bed_amount
                from  bed_shifts bs
                where bs.deleted_at is null
                and bs.admission_id =".$admission_id."
                and bs.branch_id = ?
                and bs.is_shift = 1
                group by bs.admission_id
                ", [session('branch_id')]);
    return $seatSheftBills;

}

function seatSheftLastBills($admission_id, $date="") {
        $lastseatSheftBills = DB::select("
                SELECT
                    bs.admission_id,
                    IFNULL(sum(DATEDIFF('$date',bs.shift_date)), 0) as last_shift_days,
                    IFNULL(sum(bs.bed_rent * DATEDIFF('$date',bs.shift_date)), 0) as bed_amount
                from  bed_shifts bs
                where bs.admission_id =".$admission_id."
                and bs.branch_id = ?
                and bs.is_shift = 0
                group by bs.admission_id
                ", [session('branch_id')]); 
    return $lastseatSheftBills;
}

function admissionOTBills($admission_id, $date='') {
    $otBills = DB::select("
                SELECT
                    a.id,
                    IFNULL(sum(ot.amount), 0) as amount
                from  admissions a
                left join operation_schedules ot on ot.patient_id  = a.patient_id 
                where a.deleted_at is null
                and a.id = ".$admission_id."
                and a.branch_id = ?
                group by a.id
        ", [session('branch_id')]);
 
        return $otBills;
}
function getReleaseSlip($admission_id, $date='') {
    $ReleaseSlip = DB::select("
                SELECT
                    rs.*
                from release_slips rs 
                where rs.deleted_at is null
                and rs.admission_id = ?
                and rs.slip_date = ?
                and rs.branch_id = ?
        ", [$admission_id,$date,session('branch_id')]);
        return $ReleaseSlip;
}
function currentStock($clauses = '') {
    $stock = DB::select("
            SELECT * from(
                SELECT
                    ci.*,
                    ci.stock_quantity as current_quantity,
                    p.name as product_name,
                    p.instrument_code as product_code,
                    p.reorder_level,
                    p.purchase_price,
                    (SELECT (p.purchase_price * current_quantity)) as stock_value,
                    pc.name as category_name,
                    u.name as unit_name
                from instrument_stocks ci
                left join instruments p on p.id = ci.instrument_id
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
function currentMedicineStock($clauses = '') {
    $stock = DB::select("
            SELECT * from(
                SELECT
                    ci.*,
                    (SELECT (ci.purchase_quantity + ci.sales_return_quantity) - (ci.sales_quantity + ci.purchase_return_quantity + ci.damage_quantity)) as current_quantity,
                    m.name as product_name,
                    m.medicine_code as product_code,
                    m.reorder_level,
                    m.purchase_price,
                    (SELECT (m.purchase_price * current_quantity)) as stock_value,
                    pc.name as category_name,
                    u.name as unit_name
                from medicine_stocks ci
                left join medicines m on m.id = ci.medicine_id
                left join categories pc on pc.id = m.category_id
                left join units u on u.id = m.unit_id
                where m.deleted_at is null
                and ci.branch_id = ?
            ) as tbl
            where 1 = 1
            $clauses
        ", [session('branch_id')]);

        return $stock;
}
function totalStock($clauses = '',$branchId,$date='') {
    $stock = DB::SELECT("
                SELECT
                    p.*,
                    pc.name as category_name,
                    u.name as unit_name,
                    (SELECT ifnull(sum(pd.quantity), 0) 
                        from purchase_details pd 
                        left join purchases pr on pr.id = pd.purchase_id
                        where pd.item_id = p.id
                        and pd.branch_id = '$branchId'
                        and pd.use_for = 'Instrument'
                        and pd.deleted_at is null
                        " . (isset($date) && $date != null ? " and pr.order_date <= '$date'" : "") . "
                    ) as purchased_quantity,

                    (SELECT ifnull(sum(prd.quantity), 0) 
                        from purchase_return_details prd 
                        left join purchase_returns pr on pr.id = prd.purchase_return_id
                        where prd.item_id = p.id
                        and prd.branch_id = '$branchId'
                        and prd.use_for = 'Instrument'
                        and prd.deleted_at is null
                        " . (isset($date) && $date != null ? " and pr.return_date <= '$date'" : "") . "
                    ) as purchased_return_quantity,
                            
                    
                    (SELECT ifnull(sum(isud.quantity), 0) 
                        from issue_details isud
                        join issues isu on isu.id = isud.issue_id
                        where isud.instrument_id = p.id
                        and isud.branch_id  = '$branchId'
                        and isud.deleted_at is null
                        " . (isset($date) && $date != null ? " and isu.issue_date <= '$date'" : "") . "
                    ) as sold_quantity,
                            

                    (SELECT ifnull(sum(dm.quantity), 0) 
                        from damages dm
                        where dm.item_id  = p.id
                        and dm.deleted_at is null
                        and dm.use_for = 'Instrument'
                        and dm.branch_id = '$branchId'
                        " . (isset($date) && $date != null ? " and dm.damage_date <= '$date'" : "") . "
                    ) as damaged_quantity,
                
                            
                    (SELECT (purchased_quantity) - (sold_quantity +  damaged_quantity + purchased_return_quantity)) as current_quantity,
                    (SELECT p.purchase_price * current_quantity) as stock_value
                from instruments p
                left join categories pc on pc.id = p.category_id
                left join units u on u.id = p.unit_id
                where p.deleted_at is null
                $clauses
            ");

        return $stock;
}

function generatePrevioueCashLadger($date = null)
{
    $previouseBalance = DB::SELECT("
            select
            /* Received */
            (
                select ifnull(sum(a.received_amount), 0) from admissions a
                where a.deleted_at is null
                and a.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and a.admission_date  < '$date'") . "
            ) as admission_received,
            
            (
                select ifnull(sum(ab.paid), 0) from ambulance_bills ab
                where ab.deleted_at is null
                and ab.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and ab.bill_date  < '$date'") . "
            ) as ambullance_received,
            
            (
                select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.deleted_at is null
                and bt.branch_id = " . session('branch_id') . "
                and bt.transaction_type = 'Withdraw'
                " . ($date == null ? "" : " and bt.transaction_date  < '$date'") . "
            ) as bank_withdraw,
            
            (
                select ifnull(sum(b.amount), 0) from bills b
                where b.deleted_at is null
                and b.branch_id = " . session('branch_id') . "
                and b.transaction_type = 'Received'
                " . ($date == null ? "" : " and b.bill_date  < '$date'") . "
            ) as bill_recieved,
            (
                select ifnull(sum(ct.amount), 0) from cash_transactions ct
                where ct.deleted_at is null
                and ct.branch_id = " . session('branch_id') . "
                and ct.transaction_type = 'Received'
                " . ($date == null ? "" : " and ct.transaction_date  < '$date'") . "
            ) as cash_recieived,
            
           
            
            (
                select ifnull(sum(pp.amount), 0) from patient_payments pp
                where pp.deleted_at is null
                and pp.branch_id = " . session('branch_id') . "
                and pp.transaction_type = 'Received'
                and pp.payment_type != 'Bank'
                " . ($date == null ? "" : " and pp.payment_date  < '$date'") . "
            ) as patient_recieved,
            
            
            (
                select ifnull(sum(rs.amount), 0) from release_slips rs
                where rs.deleted_at is null
                and rs.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and rs.slip_date  < '$date'") . "
            ) as releaseslip_recieved,
            
            (
                select ifnull(sum(s.paid), 0) from sales s
                where s.deleted_at is null
                and s.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and s.order_date  < '$date'") . "
            ) as sale_recieved,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Instrument'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_inventory_recieved,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Medicine'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_medicine_recieved,
                
            (
                select ifnull(sum(tr.paid), 0) from test_receipts tr
                where tr.deleted_at is null
                and tr.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and tr.bill_date  < '$date'") . "
            ) as test_recieved,


            /* paid */
            (
                select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.deleted_at is null
                and bt.branch_id = " . session('branch_id') . "
                and bt.transaction_type = 'Deposit'
                " . ($date == null ? "" : " and bt.transaction_date  < '$date'") . "
            ) as bank_deposit,

            (
                select ifnull(sum(ct.amount), 0) from cash_transactions ct
                where ct.deleted_at is null
                and ct.branch_id = " . session('branch_id') . "
                and ct.transaction_type = 'Payment'
                " . ($date == null ? "" : " and ct.transaction_date  < '$date'") . "
            ) as cash_paid,

            (
                select ifnull(sum(cp.amount), 0) from commission_payments cp
                where cp.deleted_at is null
                and cp.branch_id = " . session('branch_id') . "
                and cp.transaction_type = 'Payment'
                and cp.payment_type != 'Bank'
                " . ($date == null ? "" : " and cp.payment_date  < '$date'") . "
            ) as commission_paid,

            (
                select ifnull(sum(pp.amount), 0) from patient_payments pp
                where pp.deleted_at is null
                and pp.branch_id = " . session('branch_id') . "
                and pp.transaction_type = 'payment'
                and pp.payment_type != 'Bank'
                " . ($date == null ? "" : " and pp.payment_date  < '$date'") . "
            ) as patient_paid,
            
            (
                select ifnull(sum(p.paid), 0) from purchases p
                where p.deleted_at is null
                and p.branch_id = " . session('branch_id') . "
                and p.use_for = 'Instrument'
                " . ($date == null ? "" : " and p.order_date  < '$date'") . "
            ) as inventory_purchase_paid,
            (
                select ifnull(sum(p.paid), 0) from purchases p
                where p.deleted_at is null
                and p.branch_id = " . session('branch_id') . "
                and p.use_for = 'Medicine'
                " . ($date == null ? "" : " and p.order_date  < '$date'") . "
            ) as medicine_purchase_paid,
            
            (
                select ifnull(sum(s.total_payment_amount), 0) from salaries s
                where s.deleted_at is null
                and s.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and s.payment_date  < '$date'") . "
            ) as salary_paid,
            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Payment'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Instrument'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_inventory_paid,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Payment'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Medicine'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_medicine_paid,
                
            
            
            /* total */
            (
                select admission_received + 
                ambullance_received + 
                bank_withdraw + 
                bill_recieved + 
                cash_recieived +  
                patient_recieved + 
                releaseslip_recieved + 
                sale_recieved + 
                supplier_inventory_recieved + 
                supplier_medicine_recieved +
                test_recieved
            ) as total_in,
            (
                select bank_deposit + 
                cash_paid + 
                commission_paid + 
                patient_paid + 
                inventory_purchase_paid + 
                medicine_purchase_paid + 
                salary_paid + 
                supplier_inventory_paid +
                supplier_medicine_paid

            ) as total_out,
            (
                select total_in - total_out
            ) as cash_balance
            ")[0];

            return $previouseBalance; 
}
function generateTodayCashIn($date=null)
{
    $previouseBalance = DB::SELECT("
            select
            (
                select ifnull(sum(a.received_amount), 0) from admissions a
                where a.deleted_at is null
                and a.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and a.admission_date  = '$date'") . "
            ) as admission_received,
            
            (
                select ifnull(sum(ab.paid), 0) from ambulance_bills ab
                where ab.deleted_at is null
                and ab.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and ab.bill_date  = '$date'") . "
            ) as ambullance_received,
            
            (
                select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.deleted_at is null
                and bt.branch_id = " . session('branch_id') . "
                and bt.transaction_type = 'Withdraw'
                " . ($date == null ? "" : " and bt.transaction_date  = '$date'") . "
            ) as bank_withdraw,
            
            (
                select ifnull(sum(b.amount), 0) from bills b
                where b.deleted_at is null
                and b.branch_id = " . session('branch_id') . "
                and b.transaction_type = 'Received'
                " . ($date == null ? "" : " and b.bill_date  = '$date'") . "
            ) as bill_recieved,
            (
                select ifnull(sum(ct.amount), 0) from cash_transactions ct
                where ct.deleted_at is null
                and ct.branch_id = " . session('branch_id') . "
                and ct.transaction_type = 'Received'
                " . ($date == null ? "" : " and ct.transaction_date = '$date'") . "
            ) as cash_recieived,
            
            
            (
                select ifnull(sum(pp.amount), 0) from patient_payments pp
                where pp.deleted_at is null
                and pp.branch_id = " . session('branch_id') . "
                and pp.transaction_type = 'Received'
                and pp.payment_type != 'Bank'
                " . ($date == null ? "" : " and pp.payment_date  = '$date'") . "
            ) as patient_recieved,
            
            
            (
                select ifnull(sum(rs.amount), 0) from release_slips rs
                where rs.deleted_at is null
                and rs.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and rs.slip_date  = '$date'") . "
            ) as releaseslip_recieved,
            
            (
                select ifnull(sum(s.paid), 0) from sales s
                where s.deleted_at is null
                and s.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and s.order_date  = '$date'") . "
            ) as sale_recieved,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Instrument'
                " . ($date == null ? "" : " and sp.payment_date  = '$date'") . "
            ) as supplier_inventory_recieved,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Medicine'
                " . ($date == null ? "" : " and sp.payment_date  = '$date'") . "
            ) as supplier_medicine_recieved,
                
            (
                select ifnull(sum(tr.paid), 0) from test_receipts tr
                where tr.deleted_at is null
                and tr.branch_id = " . session('branch_id') . "
                " . ($date == null ? "" : " and tr.bill_date  = '$date'") . "
            ) as test_recieved,


            /* total */
            (
                select admission_received + 
                ambullance_received + 
                bank_withdraw + 
                bill_recieved + 
                cash_recieived +  
                patient_recieved + 
                releaseslip_recieved + 
                sale_recieved + 
                supplier_inventory_recieved + 
                supplier_medicine_recieved +
                test_recieved
            ) as total_in
            ");

            return $previouseBalance; 
}
function generatePrevioueSupplierLadger($supplierId,$type,$date = null)
{
    $previouseBalance = DB::SELECT("
            select
            /* Received */
            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.transaction_type = 'Received'
                and sp.supplier_id = ".$supplierId."
                and sp.use_for = '$type'
                and sp.branch_id  = " . session('branch_id') . "
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_received,
          
            (
                select ifnull(sum(p.total), 0) from purchases p
                where p.deleted_at is null
                and p.supplier_id = ".$supplierId."
                and p.use_for = '$type'
                and p.branch_id  = " . session('branch_id') . "
                " . ($date == null ? "" : " and p.order_date  < '$date'") . "
            ) as purchase_bill,
          
            /* paid */
            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.transaction_type = 'Payment'
                and sp.supplier_id = ".$supplierId."
                and sp.use_for = '$type'
                and sp.branch_id  = " . session('branch_id') . "
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_paid,
            
            (
                select ifnull(sum(pr.total_amount), 0) from purchase_returns pr
                where pr.deleted_at is null
                and pr.supplier_id = ".$supplierId."
                and pr.use_for = '$type'
                and pr.branch_id  = " . session('branch_id') . "
                " . ($date == null ? "" : " and pr.return_date  < '$date'") . "
            ) as returned,

            (
                select ifnull(sum(p.paid), 0) from purchases p
                where p.deleted_at is null
                and p.supplier_id = ".$supplierId."
                and p.use_for = '$type'
                and p.branch_id  = " . session('branch_id') . "
                " . ($date == null ? "" : " and p.order_date  < '$date'") . "
            ) as purchase_paid,
          
            
            /* total */
            (
                select supplier_received + purchase_bill
            ) as total_in,
            (
                select supplier_paid + returned + purchase_paid
            ) as total_out,
            (
                select total_in - total_out
            ) as supplier_balance
            ")[0];

            return $previouseBalance; 
}
function generateCashInOut($fromDate,$toDate)
{


    $currentCash = DB::SELECT("
            select
            /* Received */
            (
                SELECT ifnull(sum(a.received_amount), 0) from admissions a
                where a.deleted_at is null
                and a.branch_id = " . session('branch_id') . "
                " . ($fromDate == null && $toDate == null ? "" : " and a.admission_date between '$fromDate' and '$toDate'") . "
                ) as admission_received,
            
            (
                select ifnull(sum(ab.paid), 0) from ambulance_bills ab
                where ab.deleted_at is null
                and ab.branch_id = " . session('branch_id') . "
                " . ($fromDate == null && $toDate == null ? "" : " and ab.bill_date between '$fromDate' and '$toDate'") . "
            ) as ambullance_received,
            
            (
                select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.deleted_at is null
                and bt.branch_id = " . session('branch_id') . "
                and bt.transaction_type = 'Withdraw'
                " . ($fromDate == null && $toDate == null ? "" : " and bt.transaction_date between '$fromDate' and '$toDate'") . "
            ) as bank_withdraw,
            
            (
                select ifnull(sum(b.amount), 0) from bills b
                where b.deleted_at is null
                and b.branch_id = " . session('branch_id') . "
                and b.transaction_type = 'Received'
                " . ($fromDate == null && $toDate == null ? "" : " and b.bill_date between '$fromDate' and '$toDate'") . "
            ) as bill_recieved,

            (
                select ifnull(sum(ct.amount), 0) from cash_transactions ct
                where ct.deleted_at is null
                and ct.branch_id = " . session('branch_id') . "
                and ct.transaction_type = 'Received'
                " . ($fromDate == null && $toDate == null ? "" : " and ct.transaction_date between '$fromDate' and '$toDate'") . "
            ) as cash_recieived,
            
         
            
            (
                select ifnull(sum(pp.amount), 0) from patient_payments pp
                where pp.deleted_at is null
                and pp.branch_id = " . session('branch_id') . "
                and pp.transaction_type = 'Received'
                and pp.payment_type != 'Bank'
                " . ($fromDate == null && $toDate == null ? "" : " and pp.payment_date between '$fromDate' and '$toDate'") . "
            ) as patient_recieved,
            
            
            (
                select ifnull(sum(rs.amount), 0) from release_slips rs
                where rs.deleted_at is null
                and rs.branch_id = " . session('branch_id') . "
                " . ($fromDate == null && $toDate == null ? "" : " and rs.slip_date between '$fromDate' and '$toDate'") . "
            ) as releaseslip_recieved,
            
            (
                select ifnull(sum(s.paid), 0) from sales s
                where s.deleted_at is null
                and s.payment_type = 'Cash'
                and s.branch_id = " . session('branch_id') . "
                " . ($fromDate == null && $toDate == null ? "" : " and s.order_date between '$fromDate' and '$toDate'") . "
            ) as sale_recieved,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Instrument'
                " . ($fromDate == null && $toDate == null ? "" : " and sp.payment_date between '$fromDate' and '$toDate'") . "
            ) as supplier_inventory_recieved,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Medicine'
                " . ($fromDate == null && $toDate == null ? "" : " and sp.payment_date between '$fromDate' and '$toDate'") . "
            ) as supplier_medicine_recieved,
                
            (
                select ifnull(sum(tr.paid), 0) from test_receipts tr
                where tr.deleted_at is null
                and tr.branch_id = " . session('branch_id') . "
                " . ($fromDate == null && $toDate == null ? "" : " and tr.bill_date between '$fromDate' and '$toDate'") . "
            ) as test_recieved,


            /* paid */
            (
                select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.deleted_at is null
                and bt.branch_id = " . session('branch_id') . "
                and bt.transaction_type = 'Deposit'
                " . ($fromDate == null && $toDate == null ? "" : " and bt.transaction_date between '$fromDate' and '$toDate'") . "
            ) as bank_deposit,

            (
                select ifnull(sum(ct.amount), 0) from cash_transactions ct
                where ct.deleted_at is null
                and ct.branch_id = " . session('branch_id') . "
                and ct.transaction_type = 'Payment'
                " . ($fromDate == null && $toDate == null ? "" : " and ct.transaction_date between '$fromDate' and '$toDate'") . "
            ) as cash_paid,

            (
                select ifnull(sum(cp.amount), 0) from commission_payments cp
                where cp.deleted_at is null
                and cp.branch_id = " . session('branch_id') . "
                and cp.transaction_type = 'Payment'
                and cp.payment_type != 'Bank'
                " . ($fromDate == null && $toDate == null ? "" : " and cp.payment_date between '$fromDate' and '$toDate'") . "
            ) as commission_paid,

            (
                select ifnull(sum(pp.amount), 0) from patient_payments pp
                where pp.deleted_at is null
                and pp.branch_id = " . session('branch_id') . "
                and pp.transaction_type = 'payment'
                and pp.payment_type != 'Bank'
                " . ($fromDate == null && $toDate == null ? "" : " and pp.payment_date between '$fromDate' and '$toDate'") . "
            ) as patient_paid,
            
            (
                select ifnull(sum(p.paid), 0) from purchases p
                where p.deleted_at is null
                and p.branch_id = " . session('branch_id') . "
                and p.use_for = 'Instrument'
                " . ($fromDate == null && $toDate == null ? "" : " and p.order_date between '$fromDate' and '$toDate'") . "
            ) as inventory_purchase_paid,
            (
                select ifnull(sum(p.paid), 0) from purchases p
                where p.deleted_at is null
                and p.payment_type = 'Cash'
                and p.branch_id = " . session('branch_id') . "
                and p.use_for = 'Medicine'
                " . ($fromDate == null && $toDate == null ? "" : " and p.order_date between '$fromDate' and '$toDate'") . "
            ) as medicine_purchase_paid,
            
            (
                select ifnull(sum(s.total_payment_amount), 0) from salaries s
                where s.deleted_at is null
                and s.branch_id = " . session('branch_id') . "
                " . ($fromDate == null && $toDate == null ? "" : " and s.payment_date between '$fromDate' and '$toDate'") . "
            ) as salary_paid,
            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Payment'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Instrument'
                " . ($fromDate == null && $toDate == null ? "" : " and sp.payment_date between '$fromDate' and '$toDate'") . "
            ) as supplier_inventory_paid,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.transaction_type = 'Payment'
                and sp.payment_type != 'Bank'
                and sp.use_for  != 'Medicine'
                " . ($fromDate == null && $toDate == null ? "" : " and sp.payment_date between '$fromDate' and '$toDate'") . "
            ) as supplier_medicine_paid,
                            
            
            /* total */
            (
                select admission_received + 
                ambullance_received + 
                bank_withdraw + 
                bill_recieved +  
                patient_recieved + 
                releaseslip_recieved + 
                sale_recieved + 
                supplier_inventory_recieved + 
                supplier_medicine_recieved +
                test_recieved
            ) as total_in,
            (
                select bank_deposit + 
                cash_paid + 
                commission_paid + 
                patient_paid + 
                inventory_purchase_paid + 
                medicine_purchase_paid + 
                salary_paid + 
                supplier_inventory_paid +
                supplier_medicine_paid

            ) as total_out,
            (
                select total_in - total_out
            ) as current_cash_balance
            ")[0];
        //print_r($currentCash);
            return $currentCash; 
}
function generatePrevioueBankLadger($accountId=null,$date = null)
{
    $bankBalance = DB::SELECT("
            select
                ba.*,
            (
                select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.deleted_at is null
                and bt.branch_id = " . session('branch_id') . "
                and bt.bank_account_id  = ba.id
                and bt.transaction_type = 'Deposit'
                " . ($date == null ? "" : " and bt.transaction_date  < '$date'") . "
            ) as bank_deposit,
          
            
            (
                select ifnull(sum(pp.amount), 0) from patient_payments pp
                where pp.deleted_at is null
                and pp.branch_id = " . session('branch_id') . "
                and pp.account_id  = ba.id
                and pp.transaction_type = 'Received'
                and pp.payment_type != 'Cash'
                " . ($date == null ? "" : " and pp.payment_date  < '$date'") . "
            ) as patient_recieved,
            
          
            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.account_id  = ba.id
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Cash'
                and sp.use_for  != 'Instrument'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_inventory_recieved,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.account_id  = ba.id
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Cash'
                and sp.use_for  != 'Medicine'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_medicine_recieved,
                
            /* paid */
           
            (
                select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.deleted_at is null
                and bt.branch_id = " . session('branch_id') . "
                and bt.bank_account_id  = ba.id
                and bt.transaction_type = 'Withdraw'
                " . ($date == null ? "" : " and bt.transaction_date  < '$date'") . "
            ) as bank_withdraw,

            (
                select ifnull(sum(cp.amount), 0) from commission_payments cp
                where cp.deleted_at is null
                and cp.branch_id = " . session('branch_id') . "
                and cp.account_id  = ba.id
                and cp.transaction_type = 'Payment'
                and cp.payment_type != 'Cash'
                " . ($date == null ? "" : " and cp.payment_date  < '$date'") . "
            ) as commission_paid,

            (
                select ifnull(sum(pp.amount), 0) from patient_payments pp
                where pp.deleted_at is null
                and pp.branch_id = " . session('branch_id') . "
                and pp.account_id  = ba.id
                and pp.transaction_type = 'payment'
                and pp.payment_type != 'Cash'
                " . ($date == null ? "" : " and pp.payment_date  < '$date'") . "
            ) as patient_paid,
            
            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.account_id  = ba.id
                and sp.transaction_type = 'Payment'
                and sp.payment_type != 'Cash'
                and sp.use_for  != 'Instrument'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_inventory_paid,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.account_id  = ba.id
                and sp.transaction_type = 'Payment'
                and sp.payment_type != 'Cash'
                and sp.use_for  != 'Medicine'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_medicine_paid,
                
            
            
            /* total */
            
            (
                select (bank_deposit +
                patient_recieved +  
                supplier_inventory_recieved + 
                supplier_medicine_recieved) - (bank_withdraw + 
                commission_paid + 
                patient_paid +  
                supplier_inventory_paid +
                supplier_medicine_paid)
            ) as bank_balance
            
            from bank_accounts ba
            where ba.deleted_at is null
            and ba.branch_id = " . session('branch_id') . "
            " . ($accountId == null ? "" : " and ba.id = '$accountId'") . "
            ");

            return $bankBalance; 
}
function generateBankLadger($accountId=null,$date = null)
{
    //print_r($accountId);
    $bankBalance = DB::SELECT("
            select
            (
                select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.deleted_at is null
                and bt.branch_id = " . session('branch_id') . "
                and bt.bank_account_id  = ".$accountId."
                and bt.transaction_type = 'Deposit'
                " . ($date == null ? "" : " and bt.transaction_date  < '$date'") . "
            ) as bank_deposit,
            
      
            (
                select ifnull(sum(pp.amount), 0) from patient_payments pp
                where pp.deleted_at is null
                and pp.branch_id = " . session('branch_id') . "
                and pp.account_id  = ".$accountId."
                and pp.transaction_type = 'Received'
                and pp.payment_type != 'Cash'
                " . ($date == null ? "" : " and pp.payment_date  < '$date'") . "
            ) as patient_recieved,
            
          
            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.account_id  = ".$accountId."
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Cash'
                and sp.use_for  != 'Instrument'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_inventory_recieved,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.account_id  = ".$accountId."
                and sp.transaction_type = 'Received'
                and sp.payment_type != 'Cash'
                and sp.use_for  != 'Medicine'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_medicine_recieved,
                
            /* paid */
           
            (
                select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.deleted_at is null
                and bt.branch_id = " . session('branch_id') . "
                and bt.bank_account_id  = ".$accountId."
                and bt.transaction_type = 'Withdraw'
                " . ($date == null ? "" : " and bt.transaction_date  < '$date'") . "
            ) as bank_withdraw,

            (
                select ifnull(sum(cp.amount), 0) from commission_payments cp
                where cp.deleted_at is null
                and cp.branch_id = " . session('branch_id') . "
                and cp.account_id  = ".$accountId."
                and cp.transaction_type = 'Payment'
                and cp.payment_type != 'Cash'
                " . ($date == null ? "" : " and cp.payment_date  < '$date'") . "
            ) as commission_paid,

            (
                select ifnull(sum(pp.amount), 0) from patient_payments pp
                where pp.deleted_at is null
                and pp.branch_id = " . session('branch_id') . "
                and pp.account_id  = ".$accountId."
                and pp.transaction_type = 'payment'
                and pp.payment_type != 'Cash'
                " . ($date == null ? "" : " and pp.payment_date  < '$date'") . "
            ) as patient_paid,
            
            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.account_id  = ".$accountId."
                and sp.transaction_type = 'Payment'
                and sp.payment_type != 'Cash'
                and sp.use_for  != 'Instrument'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_inventory_paid,

            (
                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                where sp.deleted_at is null
                and sp.branch_id = " . session('branch_id') . "
                and sp.account_id  = ".$accountId."
                and sp.transaction_type = 'Payment'
                and sp.payment_type != 'Cash'
                and sp.use_for  != 'Medicine'
                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
            ) as supplier_medicine_paid,
                
            
            
            /* total */
            (
                select 
                bank_deposit +
                patient_recieved +  
                supplier_inventory_recieved + 
                supplier_medicine_recieved
            ) as total_in,
            (
                select 
                bank_withdraw + 
                commission_paid + 
                patient_paid +  
                supplier_inventory_paid +
                supplier_medicine_paid

            ) as total_out,
            (
                select 
                total_in - total_out
            ) as bank_balance
            ")[0];

            return $bankBalance; 
}
function generateDoctorCommissionLadger($doctorId=null,$date = null,$type=null)
{
    // print_r($type);
    // exit;
    $commissionBalance = DB::SELECT("
             SELECT
            (
                SELECT ifnull(sum(a.fees), 0) from appointments a
                where a.deleted_at is null
                and a.branch_id = " . session('branch_id') . "
                and a.doctor_id  = ".$doctorId."
                " . ($date == null ? "" : " and a.appointment_date  < '$date'") . "
                ) as appointment_bill,

            (
                SELECT 
                ifnull(sum(cp.amount), 0) from commission_payments cp
                where cp.deleted_at is null
                and cp.branch_id = " . session('branch_id') . "
                and cp.reference_type = '$type'
                and cp.reference_id = ".$doctorId."
                and cp.transaction_type = 'Payment'
                " . ($date == null ? "" : " and cp.payment_date  < '$date'") . "
            ) as commission_paid,
            
            (
                SELECT ifnull(sum(cp.amount), 0) from commission_payments cp
                where cp.deleted_at is null
                and cp.branch_id = " . session('branch_id') . "
                and cp.reference_type = '$type'
                and cp.reference_id = ".$doctorId."
                and cp.transaction_type = 'Bill'
                " . ($date == null ? "" : " and cp.payment_date  < '$date'") . "
            ) as commission_bill,
            
            /* total */
            (
                select 
                appointment_bill +
                commission_bill
            ) as total_in,
            (
                select 
                commission_paid 

            ) as total_out,
            (
                select 
                total_in - total_out
            ) as commission_balance
            ")[0];

            return $commissionBalance; 
}
function generateAgentCommissionLadger($agentId=null,$date = null,$type=null)
{
    // print_r($type);
    // exit;
    $commissionBalance = DB::SELECT("
             SELECT
            (
                SELECT 
                ifnull(sum(cp.amount), 0) from commission_payments cp
                where cp.deleted_at is null
                and cp.branch_id = " . session('branch_id') . "
                and cp.reference_type = '$type'
                and cp.reference_id = ".$agentId."
                and cp.transaction_type = 'Payment'
                " . ($date == null ? "" : " and cp.payment_date  < '$date'") . "
            ) as commission_paid,
            
            (
                SELECT ifnull(sum(cp.amount), 0) from commission_payments cp
                where cp.deleted_at is null
                and cp.branch_id = " . session('branch_id') . "
                and cp.reference_type = '$type'
                and cp.reference_id = ".$agentId."
                and cp.transaction_type = 'Bill'
                " . ($date == null ? "" : " and cp.payment_date  < '$date'") . "
            ) as commission_bill,
            
            /* total */
            (
                select 
                commission_bill
            ) as total_in,
            (
                select 
                commission_paid 

            ) as total_out,
            (
                select 
                total_in - total_out
            ) as commission_balance
            ")[0];

            return $commissionBalance; 
}

function getSupplierDue($supplierId=null,$type,$date = null)
{
    //print_r($accountId);
    $due = DB::SELECT("
                            select
                            s.id,
                            s.name,
                            s.supplier_code,
                            s.address,
                            s.mobile,
                            s.owner_name,
                           
                            /* Received */
                            (
                                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                                where sp.deleted_at is null
                                and sp.transaction_type = 'Received'
                                " . ($supplierId == null ? "" : "  and sp.supplier_id = $supplierId") . "
                                " . ($type == null ? "" : "  and sp.use_for = '$type'") . "
                                and sp.branch_id  = " . session('branch_id') . "
                                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
                            ) as supplier_received,
                          
                            (
                                select ifnull(sum(p.total), 0) from purchases p
                                where p.deleted_at is null
                                " . ($supplierId == null ? "" : "  and p.supplier_id = $supplierId") . "
                                " . ($type == null ? "" : "  and p.use_for = '$type'") . "
                                and p.branch_id  = " . session('branch_id') . "
                                " . ($date == null ? "" : " and p.order_date  < '$date'") . "
                            ) as purchase_bill,
                          
                            /* paid */
                            (
                                select ifnull(sum(sp.amount), 0) from supplier_payments sp
                                where sp.deleted_at is null
                                and sp.transaction_type = 'Payment'
                                " . ($supplierId == null ? "" : "  and sp.supplier_id = $supplierId") . "
                                " . ($type == null ? "" : "  and sp.use_for = '$type'") . "
                                and sp.branch_id  = " . session('branch_id') . "
                                " . ($date == null ? "" : " and sp.payment_date  < '$date'") . "
                            ) as supplier_paid,
                            
                            (
                                select ifnull(sum(pr.total_amount), 0) from purchase_returns pr
                                where pr.deleted_at is null
                                " . ($supplierId == null ? "" : "  and pr.supplier_id = $supplierId") . "
                                " . ($type == null ? "" : "  and pr.use_for = '$type'") . "
                                and pr.branch_id  = " . session('branch_id') . "
                                " . ($date == null ? "" : " and pr.return_date  < '$date'") . "
                            ) as returned,
                
                            (
                                select ifnull(sum(p.paid), 0) from purchases p
                                where p.deleted_at is null
                                " . ($supplierId == null ? "" : "  and p.supplier_id = $supplierId") . "
                                " . ($type == null ? "" : "  and p.use_for = '$type'") . "
                                and p.branch_id  = " . session('branch_id') . "
                                " . ($date == null ? "" : " and p.order_date  < '$date'") . "
                            ) as purchase_paid,
                          
                            
                            /* total */
                            (
                                select supplier_received + purchase_bill
                            ) as total_in,
                            (
                                select supplier_paid + returned + purchase_paid
                            ) as total_out,
                            (
                                select total_in - total_out
                            ) as dueAmount

                            from suppliers s
                            where s.branch_id = " . session('branch_id') . "
                            " . ($supplierId == null ? "" : "  and s.id = $supplierId") . "
                            " . ($type == null ? "" : "  and s.use_for = '$type'") . "
                           
            ");

            

            return $due; 
}

// image upload 
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