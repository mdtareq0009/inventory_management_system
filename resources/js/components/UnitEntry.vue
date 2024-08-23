<template>
    <div>
        <form @submit.prevent="saveUnit">
            <div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom: 15px;">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group clearfix">
                        <label class="control-label col-md-4">Name:</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" v-model="unit.name" required>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-md-7 col-md-offset-4">
                            <input type="submit" class="btn btn-success btn-sm" value="Save">
                        </div>
                    </div>
                </div>	
            </div>
        </form>

        <div class="row">
            <div class="col-sm-12 form-inline">
                <div class="form-group">
                    <input type="text" class="form-control" v-model="filter" placeholder="Filter">
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <datatable class="table table-hover table-bordered" :columns="columns" :data="units" :filter="filter" :per-page="per_page">
                        <template slot-scope="{ row, index }">
                            <tr>
                                <td>{{ index + 1 }}</td>
                                <td>{{ row.name }}</td>
                                <td>
                                    <span v-if="role != 'General'">
                                        <a class="blue" href="javascript:" @click="editUnit(row)">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <a class="red" href="javascript:" @click="deleteUnit(row.id)">
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
            unit: {
                id  : '',
                name: ''
            },
           units: [],

            columns: [
                { label: 'SL NO', field: 'sl', align: 'center'},
                { label: 'Name', field: 'name', align: 'center'},
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        }
    },
    created(){
        this.getUnit();
    },
    methods: {
        clearForm(){
            this.unit = {
                id  : '',
                name: ''
            }
        },
        getUnit(){
            axios.get('/get_units').then(res=>{
                this.units = res.data;
            })
        },
        saveUnit(){

            let url = '/store-unit';
            if(this.unit.id != ''){
                url = '/update-unit';
            }

            axios.post(url, this.unit).then(res=>{
                let r = res.data;
                this.$toaster.success(r.message);
                this.getUnit();
                this.clearForm();
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
        },
        editUnit(row){
            this.inventoryunit = {
                id                : row.id,
                name              : row.name
            }
        },
        deleteUnit(id){
            Swal.fire({
                title: '<strong>Are you sure!</strong>',
                html: '<strong>Want to delete this?</strong>',
                showDenyButton: true,
                confirmButtonText: `Ok`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/delete-unit', {id}).then(res=>{
                        let r = res.data;
                        Swal.fire({
                            icon: 'success',
                            title: r.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.getUnit();
                        
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