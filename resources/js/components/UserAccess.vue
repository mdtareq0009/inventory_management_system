<style scoped>
* {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
}
h2{
    font-size: 16px;
    font-weight: bold;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    text-transform: uppercase;
    padding: 5px;
}

ul {
    list-style: none;
    margin-left: 17px;
}
</style>
<template>
    <div>
        <div class="row">
            <div class="col-md-12 text-center">
                <div>
                    <h2>User Access</h2>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-12">
                <input type="checkbox" @click="checkAll" id="selectAll"> <strong style="font-size: 16px;">Select All</strong>
            </div>
        </div>
        <div class="row" id="accessRow">
            <div class="col-md-3">
                <div class="group">
                    <input type="checkbox" id="front_desk" class="group-head" @click="onClickGroupHeads"> <strong>Front Desk</strong>
                    <ul ref="front_desk">
                        <li><input type="checkbox" class="access" value="outdoor_patient" v-model="access"> Outdoor Patient</li>
                        <li><input type="checkbox" class="access" value="patient_admission" v-model="access">Patient Admission</li>
                        <li><input type="checkbox" class="access" value="patient_admission_record" v-model="access">Patient Admission Record</li>
                        <li><input type="checkbox" class="access" value="patient_entry" v-model="access"> Patient Entry</li>
                        <li><input type="checkbox" class="access" value="patient_list" v-model="access"> Patient List</li>
                        <li><input type="checkbox" class="access" value="bill_entry" v-model="access"> Bill Entry</li>
                        <li><input type="checkbox" class="access" value="seat_shift" v-model="access"> Seat Shift</li>
                        <li><input type="checkbox" class="access" value="seat_status" v-model="access"> Seat Status</li>
                        <li><input type="checkbox" class="access" value="billtype_entry" v-model="access"> Bill Type Entry</li>
                        <li><input type="checkbox" class="access" value="appointment_report" v-model="access"> Appointment Report</li>
                        <li><input type="checkbox" class="access" value="slip_bill_search" v-model="access"> Slip Bill</li>
                        <li><input type="checkbox" class="access" value="release_slip_record" v-model="access"> Release Slip Record</li>
                    </ul>
                </div>

                <div class="group">
                    <input type="checkbox" id="doctor" class="group-head" @click="onClickGroupHeads"> <strong>Doctor Module</strong>
                    <ul ref="doctor">
                        <li><input type="checkbox" class="access" value="doctor_entry" v-model="access"> Doctor Entry</li>
                    </ul>
                </div>
                <div class="group">
                    <input type="checkbox" id="other" class="group-head" @click="onClickGroupHeads"> <strong>Others Module</strong>
                    <ul ref="other">
                        <li><input type="checkbox" class="access" value="driver_entry" v-model="access"> Driver Entry</li>
                        <li><input type="checkbox" class="access" value="ambulance_entry" v-model="access"> Amulance Entry</li>
                        <li><input type="checkbox" class="access" value="ambulance_bill" v-model="access"> Amulance Bill</li>
                        <li><input type="checkbox" class="access" value="ambulance_bill_record" v-model="access"> Amulance Bill Record</li>
                        <li><input type="checkbox" class="access" value="ot_schedule_entry" v-model="access">Schedule Entry</li>
                        <li><input type="checkbox" class="access" value="ot_schedule_pending_list" v-model="access">Schedule Pending</li>
                        <li><input type="checkbox" class="access" value="ot_schedule_complete_list" v-model="access">Schedule Complete</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">

                <div class="group">
                    <input type="checkbox" id="pathology" class="group-head" @click="onClickGroupHeads"> <strong>Pathology Module</strong>
                    <ul ref="pathology">
                        <li><input type="checkbox" class="access" value="test_entry" v-model="access"> Test Entry</li>
                        <li><input type="checkbox" class="access" value="test_receipt" v-model="access"> Test Receipt</li>
                        <li><input type="checkbox" class="access" value="test_receipt_record" v-model="access"> Test Receipt Record</li>
                    </ul>
                </div>

                <div class="group">
                    <input type="checkbox" id="inventory" class="group-head" @click="onClickGroupHeads"> <strong>Inventory Module</strong>
                    <ul ref="inventory">
                        <li><input type="checkbox" class="access" value="category_entry_inventory" v-model="access"> Inventory Category</li>
                        <li><input type="checkbox" class="access" value="unit_entry_inventory" v-model="access"> Inventory Unit</li>
                        <li><input type="checkbox" class="access" value="supplier_inventory_entry" v-model="access"> Inventory Supplier Entry </li>
                        <li><input type="checkbox" class="access" value="purchase_inventory" v-model="access"> Purchase Inventory Entry </li>
                        <li><input type="checkbox" class="access" value="purchase_inventory_record" v-model="access"> Purchase Inventory Record </li>
                        <li><input type="checkbox" class="access" value="purchase_inventory_returns" v-model="access"> Purchase Inventory Return </li>
                        <li><input type="checkbox" class="access" value="purchase_return_inventory_record" v-model="access"> Purchase Inventory Return Records </li>
                        <li><input type="checkbox" class="access" value="damage_inventory" v-model="access"> Damage Inventory Entry </li>
                        <li><input type="checkbox" class="access" value="damage_inventory_list" v-model="access"> Damage Inventory List </li>
                        <li><input type="checkbox" class="access" value="issue_inventory" v-model="access"> Issue Inventory Entry </li>
                        <li><input type="checkbox" class="access" value="issue_inventory_record" v-model="access"> Issue Inventory Record </li>
                        <li><input type="checkbox" class="access" value="supplier_payment_inventory" v-model="access"> Supplier Payment Inventory </li>
                        <li><input type="checkbox" class="access" value="supplier_payment_inventory_report" v-model="access"> Supplier Payment Inventory Report </li>
                        <li><input type="checkbox" class="access" value="supplier_due_list" v-model="access"> Due List </li>
                        <li><input type="checkbox" class="access" value="current_stock_inventory" v-model="access"> Current Stock </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="group">
                    <input type="checkbox" id="accounts" class="group-head" @click="onClickGroupHeads"> <strong>Accounts Module</strong>
                    <ul ref="accounts">
                        <li><input type="checkbox" class="access" value="account_entry" v-model="access"> Add Transaction Accounts</li>
                        <li><input type="checkbox" class="access" value="bank_account_entry" v-model="access"> Add Bank Accounts</li>
                        <li><input type="checkbox" class="access" value="cash_transaction_entry" v-model="access"> Add Cash Transaction</li>
                        <li><input type="checkbox" class="access" value="commission_payment" v-model="access"> Add Commissiom Payment</li>
                        <li><input type="checkbox" class="access" value="patient_payment_medicine" v-model="access">Outdoor Patient Payment</li>
                        <li><input type="checkbox" class="access" value="patient_due_list" v-model="access"> Patient Due List</li>
                        <li><input type="checkbox" class="access" value="commission_payment_ledger" v-model="access">Commissiom Ledger</li>
                        <li><input type="checkbox" class="access" value="bank_transaction_entry" v-model="access"> Add Bank Transaction</li>
                        <li><input type="checkbox" class="access" value="cash_transaction_report" v-model="access"> Cash Transaction Report</li>
                        <li><input type="checkbox" class="access" value="bank_transaction_report" v-model="access"> Bank Transaction Report</li>
                        <li><input type="checkbox" class="access" value="cash_view_report" v-model="access"> Cash View</li>
                        <li><input type="checkbox" class="access" value="cash_ledger_report" v-model="access"> Cash Ledger Report</li>
                        <li><input type="checkbox" class="access" value="bank_ledger_report" v-model="access"> Bank Ledger Report</li>
                        <li><input type="checkbox" class="access" value="cash_in_out_report" v-model="access"> Cash In Out</li>
                    </ul>
                </div>
                <div class="group">
                    <input type="checkbox" id="hrPayroll" class="group-head" @click="onClickGroupHeads"> <strong>HR & Payroll</strong>
                    <ul ref="hrPayroll">
                        <li><input type="checkbox" class="access" value="designation_entry" v-model="access"> Add Designation</li>
                        <li><input type="checkbox" class="access" value="department_entry" v-model="access"> Add Department</li>
                        <li><input type="checkbox" class="access" value="employee_entry" v-model="access"> Add Employee</li>
                        <li><input type="checkbox" class="access" value="employee_list" v-model="access"> Employee List</li>
                        <li><input type="checkbox" class="access" value="employee_active_list" v-model="access"> Active Employee List</li>
                        <li><input type="checkbox" class="access" value="employee_deactive_list" v-model="access"> Deactive Employee List</li>
                        <li><input type="checkbox" class="access" value="month_entry" v-model="access"> Add Month</li>
                        <li><input type="checkbox" class="access" value="salary_payment" v-model="access"> Salary Payment</li>
                        <li><input type="checkbox" class="access" value="salary_payment_report" v-model="access"> Salary Payment Report</li>
                    </ul>
                </div>
                
            </div>
            <div class="col-md-3">
                <div class="group">
                    <input type="checkbox" id="pharmacy" class="group-head" @click="onClickGroupHeads"> <strong>Pharmacy Module</strong>
                    <ul ref="pharmacy">
                        <li><input type="checkbox" class="access" value="medicine_entry" v-model="access"> Medicine Entry</li>
                        <li><input type="checkbox" class="access" value="supplier_pharmacy_entry" v-model="access"> Medicine Supplier Entry</li>
                        <li><input type="checkbox" class="access" value="category_entry_medicine" v-model="access"> Medicine Category</li>
                        <li><input type="checkbox" class="access" value="unit_entry_medicine" v-model="access"> Medicine Unit</li>
                        <li><input type="checkbox" class="access" value="brand_entry" v-model="access"> Brand Entry</li>
                        <li><input type="checkbox" class="access" value="generic_entry" v-model="access"> Generic Entry</li>
                        <li><input type="checkbox" class="access" value="purchase_medicine" v-model="access"> Medicine Purchase Entry</li>
                        <li><input type="checkbox" class="access" value="purchase_medicine_record" v-model="access"> Medicine Record</li>
                        <li><input type="checkbox" class="access" value="purchase_medicine_invoice" v-model="access"> Medicine Purchase Invioce</li>
                        <li><input type="checkbox" class="access" value="purchase_return_medicine" v-model="access"> Medicine Purchase Return Entry</li>
                        <li><input type="checkbox" class="access" value="purchase_return_medicine_record" v-model="access"> Medicine Purchase Return Record</li>
                        <li><input type="checkbox" class="access" value="damage_medicine" v-model="access"> Medicine Damage Entry</li>
                        <li><input type="checkbox" class="access" value="damage_medicine_list" v-model="access"> Damage Medicine List </li>
                        <li><input type="checkbox" class="access" value="sale_medicine" v-model="access"> Medicine Sale Entry</li>
                        <li><input type="checkbox" class="access" value="sale_medicine_record" v-model="access"> Medicine Sale Record</li>
                        <li><input type="checkbox" class="access" value="sale_return_medicine" v-model="access"> Medicine Sale Return Entry</li>
                        <li><input type="checkbox" class="access" value="sale_return_medicine_record" v-model="access"> Medicine Sale Return Record</li>
                        <li><input type="checkbox" class="access" value="supplier_payment_medicine" v-model="access"> Medicine Supplier Payment</li>
                        <li><input type="checkbox" class="access" value="supplier_payment_medicine_report" v-model="access"> Medicine Supplier Payment Report</li>
                        <li><input type="checkbox" class="access" value="medicine_supplier_due_list" v-model="access"> Medicine Supplier Due List</li>
                    </ul>
                </div>
                <div class="group">
                    <input type="checkbox" id="admin" class="group-head" @click="onClickGroupHeads"> <strong>Administrator</strong>
                    <ul ref="admin">
                        <li><input type="checkbox" class="access" value="agent_entry" v-model="access"> Agent Entry</li>
                        <li><input type="checkbox" class="access" value="floor_entry" v-model="access"> Floor Entry</li>
                        <li><input type="checkbox" class="access" value="room_entry" v-model="access"> Room Entry</li>
                        <li><input type="checkbox" class="access" value="seat_entry" v-model="access"> Seat Entry</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button class="btn btn-success" @click="addUserAccess">Save</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user_id'],
    data () {
        return {
            access: [],
        }
    },
    mounted(){
        let accessCheckboxes = document.querySelectorAll('.access');
        accessCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('click', () => {
                this.makeChecked();
            })
        })
    },
    async created(){
        await axios.get('/get_user_access/' + this.user_id).then(res => {
            let r = res.data;
            if(r != ''){
                this.access = JSON.parse(r);
            }
        })
        this.makeChecked();
    },
    methods: {
        makeChecked(){
            let groups = document.querySelectorAll('.group');
            groups.forEach(group => {
                let groupHead = group.querySelector('.group-head');
                let accessCheckboxes = group.querySelectorAll('ul li input').length;
                let checkedAccessCheckBoxes = group.querySelectorAll('ul li input:checked').length;
                if(accessCheckboxes == checkedAccessCheckBoxes){
                    groupHead.checked = true;
                } else {
                    groupHead.checked = false;
                }
            })

            let selectAllCheckbox = document.querySelector('#selectAll');
            let totalAccessCheckboxes = document.querySelectorAll('.access').length;
            let totalCheckedAccessCheckBoxes = document.querySelectorAll('.access:checked').length;

            if(totalAccessCheckboxes == totalCheckedAccessCheckBoxes){
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.checked = false;
            }
        },
        async onClickGroupHeads() {
            let groupHead = event.target;
            let ul = groupHead.parentNode.querySelector('ul');
            let accessCheckboxes = ul.querySelectorAll('li input');

            if(groupHead.checked){
                accessCheckboxes.forEach(checkbox => {
                    this.access.push(checkbox.value);
                })
            } else {
                accessCheckboxes.forEach(checkbox => {
                    let ind = this.access.findIndex(a => a == checkbox.value);
                    this.access.splice(ind, 1);
                })
            }
            this.access = this.access.filter((v, i, a) => a.indexOf(v) === i);
            await new Promise(r => setTimeout(r, 200));
            this.makeChecked();
        },
        async checkAll(){
            if(event.target.checked){
                let accessCheckboxes = document.querySelectorAll('.access');
                accessCheckboxes.forEach(checkbox => {
                    this.access.push(checkbox.value)
                })
            } else {
                this.access = [];
            }
            this.access = this.access.filter((v, i, a) => a.indexOf(v) === i);
            await new Promise(r => setTimeout(r, 200));
            this.makeChecked();
        },
        addUserAccess(){
            let data = {
                user_id: this.user_id,
                access: this.access
            }
            axios.post('/add_user_access', data).then(res => {
                let r = res.data;
                alert(r.message);
            })
        }
    }
}
</script>