
<style scoped>
    .v-select{
		margin-top:-2.5px;
        float: right;
        min-width: 180px;
        margin-left: 5px;
	}
</style>
<template>
    <div>
        <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
            <div class="col-md-12">
                <form class="form-inline" @submit.prevent="getSearchResult">
                    
                    <div class="form-group" style="display:none;" v-bind:style="{display: users.length > 0 ? '' : 'none'}">
                        <label>User</label>
                        <v-select :options="users" v-model="selectedUser" label="name"></v-select>
                    </div>

                    <div class="form-group">
                        <input type="date" class="form-control" v-model="dateFrom">
                    </div>

                    <div class="form-group">
                        <input type="date" class="form-control" v-model="dateTo">
                    </div>

                    <div class="form-group" style="margin-top: -5px;">
                        <input type="submit" value="Search">
                    </div>
                </form>
            </div>
        </div>

        <div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: results.length > 0 ? '' : 'none'}">
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div>
                    <input type="checkbox" v-model="mark_all" style="margin-left: 15px;" @change="markAll"> Mark All
                    <div style="display: inline-block; float: right;">
                        <a class="btn btn-xs btn-info" href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
                        <a class="btn btn-xs btn-danger" href="" @click.prevent="markDelete"><i class="fa fa-trash"></i> Delete</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive" id="reportContent">
                    <table class="record-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Sl</th>
                                <th>User Name</th>
                                <th>Role</th>
                                <th>Login Time</th>
                                <th>Logout Time</th>
                                <th>IP</th>
                                <th>Branch</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(result, sl) in results" :key="sl">
                                <td><input type="checkbox" v-model="result.action" style="margin-left: 15px;"></td>
                                <td>{{ sl + 1 }}</td>
                                <td>{{ result.username }}</td>
                                <td>{{ result.role }}</td>
                                <td style="color:green">{{ result.login_time | formatDateTime }}</td>
                                <td style="color:red">{{ result.logout_time | formatDateTime }}</td>
                                <td>{{ result.ip_address }}</td>
                                <td>{{ result.branch_name }}</td>
                                <td style="text-align:center;">
                                    <a href="" title="Delete Record" @click.prevent="deleteRecord(result.id)"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    data(){
        return {
            dateFrom: moment().format('YYYY-MM-DD'),
            dateTo: moment().format('YYYY-MM-DD'),
            users: [],
            selectedUser: null,
            results: [],
            mark_all : false,
            branch: {},
        }
    },
    filters: {
        formatDateTime(dt) {
            return dt == '' || dt == null ? '' : moment(dt).format('DD-MM-YYYY h:mm:ss a');
        }
    },
    created(){
        this.getUsers();
        this.getBranchInfo();
    },
    methods: {
        getBranchInfo(){
            axios.get('/get_branch_info').then(res=>{
                this.branch = res.data;
            })
        },
        markAll(){
            if (this.mark_all) {
                this.results.map( (item) => {
                    item.action = true;
                    return item;
                })
            }else{
                this.results.map( (item) => {
                    item.action = false;
                    return item;
                })
            }
        },
        markDelete(){
            let mark_arr = [];

            this.results.forEach(item => {
                if (item.action) {
                    mark_arr.push(item.id);
                }
            })
            if (mark_arr.length == 0) {
                alert('Please mark atleast one item');
                return;
            }

            let deleteConf = confirm('Are you sure?');
            if(deleteConf == false){
                return;
            }

            axios.post('delete_user_activity', {mark_arr}).then( res => {
                let r = res.data;
                alert(r.message);
                if(r.success){
                    this.getSearchResult();
                }
            })

        },
        getUsers(){
            axios.get('/get_users').then(res => {
                this.users = res.data;
            })
        },
        getSearchResult(){
            let filter = {
                dateFrom 	: this.dateFrom,
                dateTo 		: this.dateTo,
                user_id 	: this.selectedUser == null ? null : this.selectedUser.User_SlNo
            }

            axios.post('/get_user_activity', filter).then( res => {
                this.results = res.data;
                this.results.map( (item) => {
                    item.action = false;
                    return item;
                })
                $('input[type="checkbox"]').prop('checked', false);
            });
        },
        deleteRecord(id){
            let deleteConf = confirm('Are you sure?');
            if(deleteConf == false){
                return;
            }
            axios.post('/delete_user_activity', {id})
            .then(res => {
                let r = res.data;
                alert(r.message);
                if(r.success){
                    this.getSearchResult();
                }
            })
            .catch(error => {
                if(error.response){
                    alert(`${error.response.status}, ${error.response.statusText}`);
                }
            })
        },
        async print(){
            let dateText = '';
            if(this.dateFrom != '' && this.dateTo != ''){
                dateText = `Statement from <strong>${this.dateFrom}</strong> to <strong>${this.dateTo}</strong>`;
            }

            let userText = '';
            if(this.selectedUser != null && this.selectedUser.name != ''){
                userText = `<strong>User: </strong> ${this.selectedUser.name}`;
            }

            let reportContent = `
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <h3>User Activity</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            ${userText}
                        </div>
                        <div class="col-xs-6 text-right">
                            ${dateText}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            ${document.querySelector('#reportContent').innerHTML}
                        </div>
                    </div>
                </div>
            `;

            var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
            reportWindow.document.write(`
                <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-2"><img src="${this.branch.logo}" alt="Logo" style="height:80px;" /></div>
                        <div class="col-xs-10" style="padding-top:20px;">
                            <strong style="font-size:18px;">${this.branch.name}</strong><br>
                            <p style="white-space: pre-line;">${this.branch.address}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div style="border-bottom: 4px double #454545;margin-top:7px;margin-bottom:7px;"></div>
                        </div>
                    </div>
                </div>
            `);

            reportWindow.document.head.innerHTML += `
                <style>
                    .record-table{
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .record-table thead{
                        background-color: #0097df;
                        color:white;
                    }
                    .record-table th, .record-table td{
                        padding: 3px;
                        border: 1px solid #454545;
                    }
                    .record-table th{
                        text-align: center;
                    }
                </style>
            `;
            reportWindow.document.body.innerHTML += reportContent;

            let rows = reportWindow.document.querySelectorAll('.record-table tr');
            rows.forEach(row => {
                row.firstChild.remove();
                row.lastChild.remove();
            })

            reportWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            reportWindow.print();
            await new Promise(resolve => setTimeout(resolve, 1000));
            reportWindow.close();
        }
    }
}
</script>