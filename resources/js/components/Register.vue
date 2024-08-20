
<template>
    <div>
        <form @submit.prevent="saveUser">
            <div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom: 15px;">
                <div class="col-md-6">
                    <div class="form-group clearfix">
                        <label class="control-label col-md-4">Name:</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" v-model="user.name" required>
                        </div>
                    </div>


                    <div class="form-group clearfix">
                        <label class="control-label col-md-4">Email:</label>
                        <div class="col-md-7">
                            <input type="email" class="form-control" v-model="user.username" required>
                        </div>
                    </div>
                </div>	

                <div class="col-md-6">
                    <div class="form-group clearfix">
                        <label class="control-label col-md-4">Role:</label>
                        <div class="col-md-7">
                            <select class="form-control" v-model="user.role" required>
                                <option v-if="role == 'Admin'" value="Admin">Admin</option>
                                <option value="General">General</option>
                            </select>
                        </div>
                    </div>

                  

                    <div class="form-group clearfix">
                        <label class="control-label col-md-4">Password:</label>
                        <div class="col-md-7">
                            <input type="password" v-model="user.password" class="form-control">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="control-label col-md-4">Re-Password:</label>
                        <div class="col-md-7">
                            <input type="password" class="form-control" v-model="user.password_confirmation">
                            <span v-if="user.password != '' && user.password_confirmation != ''">
                                <span v-if="user.password === user.password_confirmation" style="color: green;">Password Match!</span>
                                <span v-else style="color: red;">Password Not Match!</span>
                            </span>
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
                <div class="table-responsive  record-table">
                    <datatable class="table table-hover table-bordered" :columns="columns" :data="users" :filter="filter" :per-page="per_page">
                        <template slot-scope="{ row }">
                            <tr>
                                <td>{{ row.name }}</td>
                                <td>{{ row.username }}</td>
                                <td><span class="label label-sm label-info arrowed arrowed-righ">{{ row.role }}</span></td>
                                <td v-if="row.status == 'a'"><span class="label label-sm label-success arrowed arrowed-righ">{{ row.status_text }}</span></td>
                                <td v-else><span class="label label-sm label-warning arrowed arrowed-righ">{{ row.status_text }}</span></td>
                                <td>
                                    <span v-if="role == 'Super Admin' || row.role != 'Super Admin'">
                                        <a class="blue" href="javascript:" @click="editUser(row)">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>

                                        <a v-if="row.status == 'a'" class="red" href="javascript:" @click="statusChange(row.id, 'd')" title="Deactive">
                                            <i class="fa fa-arrow-circle-down"></i>
                                        </a>

                                        <a v-else class="green" href="javascript:" @click="statusChange(row.id, 'a')" title="Active">
                                            <i class="fa fa-arrow-circle-up"></i>
                                        </a>

                                        <a v-if="['General'].includes(row.role)" target="_blank" title="User Access" class="blue" :href="'/user_access/'+row.id">
                                            <i class="fa fa-users"></i>
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
export default {
    props: ['role'],
    data () {
        return {
            user: {
                id                   : '',
                name                 : '',
                username             : '',
                role                 : '',
                password             : '',
                password_confirmation: '',
            },
            users: [],

            doctors       : [],
            selectedDoctor: null,

            columns: [
                { label: 'Name', field: 'name', align: 'center'},
                { label: 'Username', field: 'username', align: 'center' },
                { label: 'Role', field: 'role', align: 'center' },
                { label: 'Branch Name', field: 'branch_name', align: 'center' },
                { label: 'Status', field: 'status_text', align: 'center' },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        }
    },
    created(){
        this.getBranches();
        this.getDoctors();
        this.getUsers();
    },
    methods: {
        clearForm(){
            this.user = {
                id                   : '',
                name                 : '',
                username             : '',
                role                 : '',
                password             : '',
                password_confirmation: '',
            }

            this.selectedBranch = null;
        },
        getBranches(){
            axios.get('/get_branches').then(res=>{
                this.branches = res.data;
            })
        },
        getDoctors(){
            axios.get('/get_doctors').then(res=>{
                this.doctors = res.data;
            })
        },
        getUsers(){
            axios.get('/get_users').then(res=>{
                this.users = res.data;
            })
        },
        saveUser(){
            if(this.selectedBranch == null){
                alert('select branch');
                return;
            }

            if(this.user.id == '' && this.user.password == ''){
                alert('password required!');
                return;
            }

            if(this.user.password != '' && this.user.password !== this.user.password_confirmation){
                alert('password not match!');
                return;
            }

            if(this.user.role == 'Doctor'){
                if(this.selectedDoctor == null){
                    alert('select doctor');
                    return;
                }

                this.user.doctor_id = this.selectedDoctor.id;
            }else{
                this.user.doctor_id = null;
            }

            this.user.branch_id = 1;

            let url = '/register';
            if(this.user.id != ''){
                url = '/update-user';
            }

            axios.post(url, this.user).then(res=>{
                let r = res.data;
                if(r.success){
                    this.$toaster.success(r.message);
                    this.getUsers();
                    this.clearForm();
                }else{
                    console.log(r);
                }
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
        editUser(row){
            this.user = {
                id                   : row.id,
                name                 : row.name,
                username             : row.username,
                role                 : row.role,
                password             : '',
                password_confirmation: '',
            }

            this.selectedBranch = {
                id  : row.branch_id,
                name: row.branch_name,
            }

            if(row.doctor_id){
                this.selectedDoctor = {
                    id          : row.doctor_id,
                    display_name: `${row.doctor_code} - ${row.doctor_name}`
                }
            }else{
                this.selectedDoctor = null;
            }
        },
        statusChange(id, status){
            Swal.fire({
                title: '<strong>Are you sure!</strong>',
                html: 'Want to change this?',
                showDenyButton: true,
                confirmButtonText: `Ok`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/user-status-update', {id, status}).then(res=>{
                        let r = res.data;
                        if(r.success){
                            Swal.fire({
                                icon: 'success',
                                title: r.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.getUsers();
                        }else{
                            console.log(r);
                        }
                        
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