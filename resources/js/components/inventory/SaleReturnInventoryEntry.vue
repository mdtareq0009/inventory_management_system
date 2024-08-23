
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
	background: #146C94 !important; 
	color:aliceblue !important; 
	font-weight: bolder !important;
    
}
.widget-body{
    padding-left:10px !important;
}
.widget-title{
	line-height: 25px !important;
}

</style>
<template>
    <div class="row" id="purchase">
        <div class="col-xs-12 col-md-3 col-lg-3" style="
    	border: 1px solid #d1d1d1;
    	padding: 0px;
		margin-bottom: 5px;
		">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Invoice Details</h4>
			
			</div>

			<div class="widget-body" style="background-color: #FFE7CE;">
				<div class="widget-main">
					<div class="row">
						<div class="col-xs-12">
							<div class="table-responsive">
								<table style="color:#000;margin-bottom: 0px;">
									<tr>
										<td style="width: 800px;">
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right">Invoice no</label>
												<div class="col-xs-8">
													<input type="text" id="invoice" class="form-control" name="invoice" v-model="salereturn.invoice_number" readonly/>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right">Return Date </label>
												<div class="col-xs-8">
													<input class="form-control" id="saleDate"  type="date" v-model="salereturn.return_date" v-bind:disabled="role == 'User' ? true : false" />
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label class="col-xs-4 control-label no-padding-right"> Customer </label>
                                            <div class="col-xs-7">
                                                <v-select v-bind:options="customers" style="width:100%" v-model="selectedCustomer" placeholder="Select Customer" label="display_text"></v-select>
                                            </div>
                                            <div class="col-xs-1" style="padding: 0;">
                                                <a href="/customer_entry" title="Add New Customer" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
                                            </div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-group">
                                            <label class="col-xs-4 control-label no-padding-right"> Total Amount </label>
                                            <div class="col-xs-8">
                                                <input type="number"  id="total" class="form-control" v-model="salereturn.total" readonly />
                                            </div>
                                        </div>
										</td>
									</tr>
									<tr>
										<td>
                                            <div class="form-group">
                                                <div class="col-xs-6" style="padding-right:0px">
                                                </div>
												<div class="col-xs-6" style="padding-left:0px;display:flex">
													<input type="button" class="btn btn-success" value="Sale" v-on:click="save" v-bind:disabled="saleOnProgress == true ? true : false" style="background:#000;color:#fff;padding:0px;margin-right:5px;width:70%;">
                                                    <input type="button" class="btn btn-info" onclick="window.location = '/sale_inventory'" value="New Sale" style="background:#000;color:#fff;padding:0px;width:70%;">
												</div>
											</div>
										</td>
									</tr>
									
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-md-9 col-lg-9">
		<div class="widget-box">
			<div class="widget-header">
				<h5 class="widget-title">Product Information</h5>
			
			</div>

			<div class="widget-body" style="background-color: #AEE2FF;">
				<div class="widget-main">
					<div class="row">
						<div class="col-sm-12">
							<form v-on:submit.prevent="addToCart">
								<div class="form-group">
									<label class="col-xs-2 control-label no-padding-right"> Product </label>
									<div class="col-xs-4">
                                        
										<v-select v-bind:options="products"  v-model="selectedProduct" label="display_text" v-on:input="onChangeProduct"></v-select>
									</div>
									<div class="col-xs-1" style="padding: 0;">
										<a href="/instrument_entry" title="Add New Product" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
									</div>
                                    <label class="col-xs-2 control-label no-padding-right"> Sale Rate </label>
									<div class="col-xs-3">
										<input type="text" id="saleRate" class="form-control" placeholder="Sale Rate" v-model="selectedProduct.sale_price" v-on:input="productTotal" required/>
									</div>
								</div>


								<div class="form-group">
									<label class="col-xs-2 control-label no-padding-right"> Quantity </label>
									<div class="col-xs-5">
										<input type="text" step="0.01" id="quantity" style="width:80%" name="quantity" class="form-control" placeholder="Quantity" ref="quantity" v-model="selectedProduct.quantity" v-on:input="productTotal" required/>
									</div>
									<label class="col-xs-2 control-label no-padding-right"> Total Amount </label>
									<div class="col-xs-3">
										<input type="text" id="productTotal" name="productTotal" class="form-control" readonly v-model="selectedProduct.total"/>
									</div>
								</div>

								<div class="form-group">
									
									<label class="col-xs-4 control-label no-padding-right"> </label>
									<div class="col-xs-8">
										<button type="submit" class="btn btn-default pull-right">Add Cart</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px;padding-right: 0px;">
			<div class="table-responsive">
				<table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
					<thead>
						<tr style="background: #526D82;">
							<th style="width:4%;color:#fff;font-weight: 500">SL</th>
							<th style="width:20%;color:#fff;font-weight: 500">Product Name</th>
							<th style="width:8%;color:#fff;font-weight: 500">Sale Rate</th>
							<th style="width:5%;color:#fff;font-weight: 500">Quantity</th>
							<th style="width:13%;color:#fff;font-weight: 500">Total Amount</th>
							<th style="width:5%;color:#fff;font-weight: 500">Action</th>
						</tr>
					</thead>
					<tbody style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
						<tr v-for="(product, sl) in cart" :key="sl">
							<td>{{ sl + 1}}</td>
							<td>{{ product.name }}</td>
							<td>{{ product.sale_price }}</td>
							<td>{{ product.quantity }}</td>
							<td>{{ product.total }}</td>
							<td><a href="" v-on:click.prevent="removeFromCart(sl)"><i class="fa fa-trash"></i></a></td>
						</tr>

						<tr>
							<td colspan="7"></td>
						</tr>

						<tr style="font-weight: bold;">
							<td colspan="4">Note</td>
							<td colspan="3">Total</td>
						</tr>

						<tr>
							<td colspan="4"><textarea style="width: 100%;font-size:13px;" placeholder="Note" v-model="salereturn.note"></textarea></td>
							<td colspan="3" style="padding-top: 15px;font-size:18px;">{{ salereturn.total }}</td>
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
    props: ['role','id','invoice'],
    data(){
		
			return{
				salereturn: {
					id              : '',
					invoice_number  : '',
					return_date      : moment().format('YYYY-MM-DD'),
					customer_id     : '',
					total           : 0.00,
					note            : ''
				},
				
				branches: [],
				
				customers: [],
				selectedCustomer: null,
				products: [],
				selectedProduct: {
					id            : '',
					display_text  : 'Select Product',
					name          : '',
					quantity      : '',
					purchase_price: '',
					sale_price	  : '',
					total         : ''
				},
				cart: [],
				saleOnProgress: false
			}
		},
		async created(){
			this.salereturn.id= this.id;
			this.salereturn.invoice_number = this.invoice;
			await this.getCustomers();
			this.getProducts();
			if(this.id != 0){
            	this.getSaleReturn();
        	}
		},
		methods:{
			
			async getCustomers(){
				await axios.get('/get_customers').then(res => {
					this.customers = res.data;
				})
			},

			getProducts(){
				axios.post('/get_products').then(res=>{
					this.products = res.data;
				})
			},


			productTotal(){
				this.selectedProduct.total = this.selectedProduct.quantity * this.selectedProduct.sale_price;
			},
			addToCart(){
				
				let product = {
					productId     : this.selectedProduct.id,
					name          : this.selectedProduct.name,
					return_rate   : this.selectedProduct.sale_price,
					quantity      : this.selectedProduct.quantity,
					total         : this.selectedProduct.total
				}

				if(product.productId == ''){
					alert('Select Product');
					return;
				}

				if(product.quantity == 0 || product.quantity == ''){
					alert('Enter quantity');
					return;
				}

				if(product.sale_rate == 0 || product.sale_rate == ''){
					alert('Enter sales rate');
					return;
				}
				
				
				let cartInd = this.cart.findIndex(p => p.productId == product.productId);
				if(cartInd > -1){
					this.cart.splice(cartInd, 1);
				}

				this.cart.push(product);
				this.clearSelectedProduct();
				this.calculateTotal();
                
			},
			async removeFromCart(ind){
				
				this.cart.splice(ind, 1);
				this.calculateTotal();
			},
			clearSelectedProduct(){
				this.selectedProduct = {
					id            : '',
					display_text  : 'Select Product',
					name          : '',
					quantity      : '',
					purchase_price: '',
					sale_price    : '',
					total         : ''
				};
			},
			calculateTotal(){
				this.salereturn.total = this.cart.reduce((prev, curr) => { return prev + parseFloat(curr.total); }, 0).toFixed(2);
			},

			async onChangeProduct(){
				
				setTimeout(() =>{
				this.$refs.quantity.focus();
				}, 500);  
					
			},
		
			save(){
				if(this.selectedCustomer == null){
					alert('Select Customer');
					return;
				}

				if(this.salereturn.return_date == ''){
					alert('Enter Sale Return date');
					return;
				}

				if(this.cart.length == 0){
					alert('Cart is empty');
					return;
				}

				

				this.salereturn.customer_id      = this.selectedCustomer.id;

				this.saleOnProgress = true;

				let data = {
					salereturn: this.salereturn,
					cartProducts: this.cart
				}

				

				let url = '/store-sale-return';
				if(this.salereturn.id != 0){
					console.log(this.salereturn.id);
					url = '/update-sale-return';
				}

                Swal.fire({
                title: '<strong>Are you sure!</strong>',
                html: '<strong>Want to Proccess this?</strong>',
                showDenyButton: true,
                confirmButtonText: `Ok`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(url, data).then(async res=>{
                        let r = res.data;
                        Swal.fire({
                            icon: 'success',
                            title: r.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        await new Promise(r => setTimeout(r, 1000));
                        
                        window.location = '/sale_return_entry';
                        
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


			},

		 async	getSaleReturn(){
			await axios.post('/get_sales_return', {saleReturnId: this.id}).then(res => {
					let salesreturns = res.data[0];
					console.log(salesreturns.salereturn);
					this.selectedCustomer = {
						id           : salesreturns.customerId,
						display_text : salesreturns.display_name,
						mobile       : salesreturns.supplier_mobile,
						address      : salesreturns.supplier_address,
					}
					this.salereturn.invoice_number       = salesreturns.salereturn.invoice_number,
					this.salereturn.return_date          = salesreturns.salereturn.return_date;
					this.salereturn.supplier_id          = salesreturns.supplierId;
					this.salereturn.total                = salesreturns.salereturn.total_amount;
					this.salereturn.note                 = salesreturns.salereturn.remark;


			
					
					salesreturns.salereturn.sale_return_details.forEach(item => {
                    let cart = {
					id            : item.id,
					productId     : item.product_id,
					name          : item.product.name,
					quantity      : item.quantity,
					return_rate	  : item.return_rate,
					total         : item.total_amount
                    }
                    this.cart.push(cart);
                })
				})
			}
		
		}
	}

</script>