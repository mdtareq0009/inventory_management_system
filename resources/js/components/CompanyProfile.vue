<style scoped>
#brandImage {
		height: 100%;
		width: 100%;
	}
    #companys .custom-file-upload {
		border: 1px solid #ccc;
		display: inline-block;
		padding: 5px 12px;
		cursor: pointer;
		margin-top: 5px;
		background-color: #298db4;
		border: none;
		color: white;
	}
	#companys .custom-file-upload:hover{
		background-color: #41add6;
	}
</style>
<template>
    <div id="companys">
        <div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom: 15px;">
            <div class="col-md-6 col-md-offset-3">
                <form @submit.prevent="saveCompanyProfile">
                    <div class="form-group clearfix">
                        <label class="control-label col-md-12">Company Logo:</label>
                        <div class="col-md-12">
                            <div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
						        <img id="brandImage" v-if="companyimageUrl == '' || companyimageUrl == null" src="/images/no_image.jpg" style="height:100px;width:100px;">
						        <img id="brandImage" v-if="companyimageUrl != '' && companyimageUrl != null" v-bind:src="companyimageUrl" style="height:100px;width:100px;">
                            </div>
                            <div style="text-align:center;">						      
						            <input type="file" name='image' @change="previewImage"/>					           
					        </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-md-12">Name:</label>
                        <div class="col-md-12">
                            <input type="text" name="name" class="form-control" v-model="companys.name" required>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-md-12">Phone:</label>
                        <div class="col-md-12">
                            <input type="text" name="phone" class="form-control" v-model="companys.phone" required>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-md-12">Email:</label>
                        <div class="col-md-12">
                            <input type="email" name="email" class="form-control" v-model="companys.email" required>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-md-12">Address:</label>
                        <div class="col-md-12">
                            <textarea  class="form-control" name="address"  rows="5" v-model="companys.address"></textarea>
                            
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-md-3 col-md-offset-9">
                            <input type="submit" class="btn btn-success btn-sm" value="Update">
                        </div>
                    </div>
                </form>
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
                company: {
                    compnayId: 0,
                    name    : '',
                    phone   : '',
                    email   : '',
                    address : ''
                },
                branch: {
                    branchId    : 0,
                    display_name: '',
                    code        : this.getBranchCode(),
                    address     : ''
                },
                companys           : [],
                branches           : [],
                companyimageUrl    : '',
                companyselectedFile: null,
                columns            : [
                    { label: 'S/L No', field: 'sl_no', align: 'center'},
                    { label: 'Name', field: 'display_name', align: 'center'},
                    { label: 'Address', field: 'address', align: 'center'},
                    { label: 'Action', align: 'center', filterable: false }
                ],
            page    : 1,
            per_page: 10,
            filter  : ''
        }
    },
    created(){
        this.getCompanies();
        this.getBranches();
    },
    methods: {
        previewImage(event){
				if(event.target.files.length > 0){
					this.companyselectedFile = event.target.files[0];
					this.companyimageUrl     = URL.createObjectURL(this.companyselectedFile);
				} else {
					this.companyselectedFile = null;
					this.companyimageUrl     = '';
				}
		},

        clearForm(){
            this.branch = {
                    branchId    : 0,
                    display_name: '',
                    code        : this.getBranchCode(),
                    address     : ''
            }
        },

        getBranchCode(){
            axios.get('/get_branches_code').then(res=>{
                this.branch.code = res.data;
            })
        },

        getBranches(){
            axios.get('/get_branches').then(res=>{
                this.branches = res.data;
                this.branch.branch_logo = this.branches.branch_logo;
            })
        },

        getCompanies(){
            axios.get('/get_companies').then(res=>{
                this.companys = res.data;
                this.companyimageUrl = this.companys.logo;
            })
        },

        saveCompanyProfile(event){
                let url = '/store-companyprofile';
				if(this.companys.id != 0){
					url = '/update-companyprofile';
				}

				if(this.companys.id == 0 && this.companyselectedFile == null){
					alert('Company Image Required');
					return;
				}


				let fd = new FormData(event.target);
				axios.post(url, fd)
				.then(res=>{
                    console.log(res.data);
					let r = res.data;
					this.$toaster.success(r.message);
                    this.getCompanies();
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

        saveBranch(e){

                let url = '/store-branch';
				if(this.branch.branchId != 0){
					url = '/update-branch';
				}

				
				let fd = new FormData(e.target);
				axios.post(url, fd)
				.then(res=>{
                    console.log(res.data);
					let r = res.data;
					this.$toaster.success(r.message);
                    this.clearForm();
                    this.getBranches();
                    this.getBranchCode();
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

        editBranch(row){
            console.log(row.id);

            this.branch = {
                branchId: row.id,
                name    : row.name,
                code    : row.code,
                address : row.address
            };
        },

        deleteBranch(id){
            Swal.fire({
                title: '<strong>Are you sure!</strong>',
                html: '<strong>Want to delete this?</strong>',
                showDenyButton: true,
                confirmButtonText: `Ok`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/delete-branch', {id}).then(res=>{
                        let r = res.data;
                        Swal.fire({
                            icon: 'success',
                            title: r.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.clearForm();
                        this.getBranches();
                        this.getBranchCode();
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