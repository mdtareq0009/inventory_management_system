
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
                            <h5 class="widget-title">Product  Entry</h5>
                        </div>

                        <div class="widget-body" style="background-color: #f1f1f1;">
                            <div class="widget-main">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right"> Product Code </label>
                                            <div class="col-xs-8">
                                                <input type="text" name="employee_code" class="form-control" v-model="product.product_code" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right"> product Name </label>
                                            <div class="col-xs-8">
                                                <input type="text" placeholder="Product Name" class="form-control" v-model="product.name" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right"> Category </label>
                                            <div class="col-xs-7">
                                                <v-select :options="categories" label="name" v-model="selectedCategory"></v-select>
                                            </div>
                                            <div class="col-xs-1" style="padding: 0; margin-left: -10px;">
                                                <a href="/category_entry" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right"> Unit </label>
                                            <div class="col-xs-7">
                                                <v-select :options="units" label="name" v-model="selectedUnit"></v-select>
                                            </div>
                                            <div class="col-xs-1" style="padding: 0; margin-left: -10px;">
                                                <a href="/unit_entry" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                         <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right"> Re-order Level </label>
                                            <div class="col-xs-8">
                                                <input type="number" placeholder="Re-order Level" class="form-control" v-model="product.reorder_level" required/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right"> Purchase Price </label>
                                            <div class="col-xs-8">
                                                <input type="number" step="0.01" placeholder="Purchase Price"  class="form-control" v-model="product.purchase_price" required/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-4 control-label no-padding-right"> Sale Price </label>
                                            <div class="col-xs-8">
                                                <input type="number" step="0.01" placeholder="Sale Price"  class="form-control" v-model="product.sale_price" required/>
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
                    <datatable class="table table-hover table-bordered" :columns="columns" :data="products" :filter="filter" :per-page="per_page">
                        <template slot-scope="{ row }">
                            <tr class="hover-tr">
                                <td>{{ row.product_code }}</td>
                                <td>{{ row.name }}</td>
                                <td>{{ row.category.name }}</td>
                                <td>{{ row.unit.name }}</td>
                                <td>{{ row.reorder_level }}</td>
                                <td>{{ row.purchase_price }}</td>
                                <td>{{ row.sale_price }}</td>
                                <td>
                                    <span v-if="role != 'General'">
                                        <a class="blue" href="javascript:" @click="editProduct(row)">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <a class="red" href="javascript:" @click="deleteProduct(row.id)">
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
            product: {
                id              : '',
                product_code   : '',
                category_id     : '',
                unit_id         : '',
                name            : '',
                purchase_price  : '',
                sale_price      : '',
                reorder_level   : 0,
            },

            products      : [],

            categories      : [],
            selectedCategory: null,          

            units       : [],
            selectedUnit: null,

            columns: [
                { label: 'Product Code', field: 'medicine_code', align: 'center'},
                { label: 'Name', field: 'name', align: 'center' },
                { label: 'Category', field: 'category_name', align: 'center' },
                { label: 'Unit', field: 'unit_name', align: 'center' },
                { label: 'Re-order Level', field: 'reorder_level', align: 'center' },
                { label: 'Purchase Price', field: 'purchase_price', align: 'center' },
                { label: 'Sale Price', field: 'sale_price', align: 'center' },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: '',
            progress: false
        }
    },
   
    created(){
        this.getCategories();
        this.getUnits();
        this.getProductCode();
        this.getProducts();
    },
    methods: {

        getCategories(){
            axios.get('/get_categories').then(res=>{
                this.categories = res.data;
            })
        },

        getUnits(){
            axios.get('/get_units').then(res=>{
                this.units = res.data;
            })
        },

        getProducts(){
            axios.get('/get_products').then(res=>{
                this.products = res.data;
            })
        },

        getProductCode(){
            axios.get('/get_product_code').then(res=>{
                this.product.product_code = res.data;
            })
        },
       

        save(){

            if(this.selectedCategory == null){
                alert('Select Category');
                return;
            }
            
            if(this.selectedUnit == null){
                alert('Select Unit');
                return;
            }
           

            this.progress = true;

            this.product.category_id = this.selectedCategory.id;
            this.product.unit_id = this.selectedUnit.id;


            let url = '/store-product';

            if(this.product.id != ''){
                url = '/update-product';
            }
            
            let fd = new FormData();
            fd.append('products', JSON.stringify(this.product));
            axios.post(url, fd).then(res=>{
                this.progress = false;
                this.$toaster.success(res.data.message);
                this.clear();
                this.getProductCode();
                this.getProducts();
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
        
            this.product = {
                id             : '',
                product_code: '',
                category_id    : '',
                unit_id        : '',
                name           : '',
                purchase_price : 0,
                sale_price     : 0,
                reorder_level  : 0,
            };
           this.selectedCategory = null;
           this.selectedUnit     = null;
        },
        
     
        editProduct(row){
             
            
            this.selectedCategory = {
                id  : row.category_id,
                name: row.category.name
            }

          
            this.selectedUnit = {
                id  : row.unit_id,
                name: row.unit.name
            }
             
          
            this.product = {
                id              : row.id,
                product_code    : row.product_code,
                name            : row.name,
                purchase_price  : row.purchase_price,
                sale_price      : row.sale_price,
                reorder_level   : row.reorder_level
            }

        },
        deleteProduct(id){
            Swal.fire({
                title: '<strong>Are you sure!</strong>',
                html: '<strong>Want to delete this?</strong>',
                showDenyButton: true,
                confirmButtonText: `Ok`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/delete-product', {id}).then(res=>{
                        let r = res.data;
                        Swal.fire({
                            icon: 'success',
                            title: r.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.clear();
                        this.getProductCode();
                        this.getProducts();
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