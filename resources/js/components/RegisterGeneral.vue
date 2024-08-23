<template>
   
    <div class="container">
	<div class="contant">
		<div class="login">
			<div class="right-cont">
				<div class="login-form">	
			<form @submit.prevent="saveUser">
            <div class="row" style="width:100%; margin:0px auto; margin-top: 150px;">
                <div class="col-md-6 col-md-offset-3" style="background: beige;border-radius: 25px;padding-bottom: 15px;">
                    <h5 class="text-center green">New User Registration</h5>
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
                            <input type="submit" class="btn btn-success btn-sm" style="padding:2px;" value="Register">
                        </div>
                    </div>
                </div>	

            </div>
        </form>
					
				</div>
			</div>
	
			<div class="clr"></div>
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
                role                 : 'General',
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
              
                { label: 'Status', field: 'status_text', align: 'center' },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        }
    },
    created(){
    
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
      
       saveUser(){
          

            if(this.user.id == '' && this.user.password == ''){
                alert('password required!');
                return;
            }

            if(this.user.password != '' && this.user.password !== this.user.password_confirmation){
                alert('password not match!');
                return;
            }

      

            this.user.branch_id = 1;

            let url = '/general_user_store';
            

            axios.post(url, this.user).then(res=>{
                let r = res.data;
                if(r.success){
                    this.$toaster.success(r.message);
                    new Promise(r => setTimeout(r, 1000));
                    window.location = '/';
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
        
    }
}
</script>