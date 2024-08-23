
<style scoped>
 label{
    font-size: 13px !important;
 }
.no-padding-right{
	padding: 0px !important;
}
.widget-box{
	margin:0px !important;
	border: 0px solid #fff !important;
}
.widget-header{
	border: 1px solid #ccc !important; 
	min-height: 26px !important; 
	background: #4f4f4f !important; 
	color:aliceblue !important; 
	font-weight: bolder !important;
    
}
.widget-body{
    padding-left:10px !important;
}
.widget-title{
	line-height: 25px !important;
}
td{
    border: 1px solid #9DB2BF;
}
.table-responsive .hover-tr{
    background-color: #E3F4F4 !important;
-webkit-transition: .5s all;   
    -webkit-transition-delay: .05s; 
    -moz-transition: .5s all;   
    -moz-transition-delay: .05s; 
    -ms-transition: .5s all;   
    -ms-transition-delay: .05s; 
    -o-transition: .5s all;   
    -o-transition-delay: .05s; 
    transition: .5s all;   
    transition-delay: .05s; 
}
.table-responsive .hover-tr:hover{
background-color: #f1f1f1 !important;
transition: 0s all;   
-webkit-transition-delay: 0s;
    -moz-transition-delay: 0s;
    -ms-transition-delay: 0s;
    -o-transition-delay: 0s;
    transition-delay: 0s;
}
</style>
<template>
    <div>
        <form @submit.prevent="save">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-lg-10 col-md-offset-1">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h5 class="widget-title">Suppler  Entry</h5>
                        </div>

                        <div class="widget-body" style="background-color: #f1f1f1;">
                            <div class="widget-main">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right"> Suppler Code </label>
                                            <div class="col-xs-8">
                                                <input type="text" name="employee_code" class="form-control" v-model="supplier.supplier_code" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right">  Name </label>
                                            <div class="col-xs-8">
                                                <input type="text" placeholder="Name" class="form-control" v-model="supplier.name" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right">  Owner Name </label>
                                            <div class="col-xs-8">
                                                <input type="text" max="13" placeholder="Owner Name" class="form-control" v-model="supplier.owner_name" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right">  Mobile </label>
                                            <div class="col-xs-8">
                                                <input type="text" max="13" placeholder="Mobile" class="form-control" v-model="supplier.mobile" required />
                                            </div>
                                        </div>
                                      
                                    </div>
                                    
                                    <div class="col-sm-6">
                                         <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right"> Address </label>
                                            <div class="col-xs-8">
                                                <input type="text" placeholder="Address" class="form-control" v-model="supplier.address" required/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right" for="remark"> Remarks </label>
                                            <div class="col-xs-8">
                                                <textarea v-model="supplier.remark" id="remark" class="form-control"  placeholder="Remarks"  cols="30" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="form-group row">
                                            <div class="col-xs-4 col-xs-offset-8">
                                                <input
                                                    type="submit"
                                                    class="btn btn-primary btn-sm"
                                                    value="Save"
                                                    v-bind:disabled="progress ? true : false"
                                                    style="color: #fff !important; margin-top: 0px; width: 100%; padding: 5px; font-weight: bold;"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </form>
        <br />
        <div class="row">
            <div class="col-sm-12 form-inline">
                <div class="form-group">
                    <input type="text" class="form-control" v-model="filter" placeholder="Filter" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <datatable class="table table-hover table-bordered" :columns="columns" :data="suppliers" :filter="filter" :per-page="per_page">
                        <template slot-scope="{ row }">
                            <tr class="hover-tr">
                                <td>{{ row.supplier_code }}</td>
                                <td>{{ row.name }}</td>
                                <td>{{ row.owner_name }}</td>
                                <td>{{ row.mobile }}</td>
                                <td>{{ row.address }}</td>
                                <td>{{ row.remark }}</td>
                                <td>
                                    <span v-if="role != 'General'">
                                        <a class="blue" href="javascript:" @click="editSupplier(row)">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <a class="red" href="javascript:" @click="deleteSupplier(row.id)">
                                            <i class="ace-icon fa fa-trash bigger-130"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </datatable>
                    <datatable-pager class="datatable-pagination" v-model="page" type="abbreviated"></datatable-pager>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    props: ['role'],
    data () {
        return {
            supplier: {
                id              : '',
                supplier_code  : '',
                name            : '',
                owner_name      : '',
                mobile          : '',
                address         : '',
                remark          : ''
            },

            suppliers      : [],

            columns: [
                { label: 'Supplier Code', field: 'supplier_code', align: 'center'},
                { label: 'Name', field: 'name', align: 'center' },
                { label: 'Owner Name', field: 'owner_name', align: 'center' },
                { label: 'Mobile', field: 'mobile', align: 'center' },
                { label: 'Address', field: 'address', align: 'center' },
                { label: 'Remark', field: 'remark', align: 'center' },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: '',
            progress: false
        }
    },
   
    created(){
        this.getSupplierCode();
        this.getSuppliers();
    },
    methods: {
        getSuppliers(){
            axios.get('/get_suppliers').then(res=>{
                this.suppliers = res.data;
            })
        },

        getSupplierCode(){
            axios.get('/get_supplier_code').then(res=>{
                this.supplier.supplier_code = res.data;
            })
        },
       

        save(){

            this.progress = true;

            let url = '/store-supplier';

            if(this.supplier.id != ''){
                url = '/update-supplier';
            }
            
            let fd = new FormData();
            fd.append('suppliers', JSON.stringify(this.supplier));
            axios.post(url, fd).then(res=>{
                this.progress = false;
                this.$toaster.success(res.data.message);
                this.clear();
                this.getSupplierCode();
                this.getSuppliers();
            }).catch(error=>{
                this.progress = false;
                let e = error.response.data;
                if(e.hasOwnProperty('message')){
                    this.$toaster.error(e.message);
                }else{
                    Object.entries(e).forEach(([key, val])=>{
                        this.$toaster.error(val[0]);
                    })
                }
            })
        },
        clear(){
        
            this.supplier = {
                id              : '',
                supplier_code  : '',
                name            : '',
                owner_name      : '',
                mobile          : '',
                address         : '',
                remark          : ''
            };
           
        },
        
     
        editSupplier(row){
             
            this.supplier = {
                id              : row.id,
                supplier_code   : row.supplier_code,
                name            : row.name,
                owner_name      : row.owner_name,
                mobile          : row.mobile,
                address         : row.address,
                remark          : row.remark
            }

        },
        deleteSupplier(id){
            Swal.fire({
                title: '<strong>Are you sure!</strong>',
                html: '<strong>Want to delete this?</strong>',
                showDenyButton: true,
                confirmButtonText: `Ok`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/delete-supplier', {id}).then(res=>{
                        let r = res.data;
                        Swal.fire({
                            icon: 'success',
                            title: r.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.clear();
                        this.getSupplierCode();
                        this.getSuppliers();
                    }).catch(error => {
                        let e = error.response.data;

                        if(e.hasOwnProperty('message')){
                            if(e.hasOwnProperty('errors')){
                                Object.entries(e.errors).forEach(([key, val])=>{
                                    this.$toaster.error(val[0]);
                                })
                            }else{
                                this.$toaster.error(e.message);
                            }
                        }else{
                            this.$toaster.error(e);
                        }
                    })
                }
            })
        }
    }
}
</script>