
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
													<input type="text" id="invoice" class="form-control" name="invoice" v-model="purchasereturn.invoice_number" readonly/>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right">Return Date </label>
												<div class="col-xs-8">
													<input class="form-control" id="purchaseDate" name="purchaseDate" type="date" v-model="purchasereturn.return_date" v-bind:disabled="role == 'User' ? true : false" />
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label class="col-xs-4 control-label no-padding-right"> Supplier </label>
                                            <div class="col-xs-7">
                                                <v-select v-bind:options="suppliers" style="width:100%" v-model="selectedSupplier" placeholder="Select Supplier" label="display_text"></v-select>
                                            </div>
                                            <div class="col-xs-1" style="padding: 0;">
                                                <a href="/supplier_entry" title="Add New Supplier" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
                                            </div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-group">
                                            <label class="col-xs-4 control-label no-padding-right"> Total Amount </label>
                                            <div class="col-xs-8">
                                                <input type="number"  id="total" class="form-control" v-model="purchasereturn.total" readonly />
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
													<input type="button" class="btn btn-success" value="Pur.Return" v-on:click="save" v-bind:disabled="purchaseReturnOnProgress == true ? true : false" style="background:#000;color:#fff;padding:0px;margin-right:5px;width:70%;">
                                                    <input type="button" class="btn btn-info" onclick="window.location = '/purchase_inventory'" value="New Purch.." style="background:#000;color:#fff;padding:0px;width:70%;">
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
	<div class="col-xs-12 col-md-8 col-lg-8">
		<div class="widget-box">
			<div class="widget-header">
				<h5 class="widget-title">Supplier & Product Information</h5>
			
			</div>

			<div class="widget-body" style="background-color: #AEE2FF;">
				<div class="widget-main">
					<div class="row">
						<div class="col-sm-12">
							<form v-on:submit.prevent="addToCart">
								<div class="form-group">
									<label class="col-xs-2 control-label no-padding-right"> Product </label>
									<div class="col-xs-4">
                                        
										<v-select v-bind:options="products"  v-model="selectedProduct" label="display_text" @input="onChangeProduct()"></v-select>
									</div>
									<div class="col-xs-1" style="padding: 0;">
										<a href="/product_entry" title="Add New Product" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
									</div>
                                    <label class="col-xs-2 control-label no-padding-right"> Pur. Rate </label>
									<div class="col-xs-3">
										<input type="text" id="purchaseRate" name="purchaseRate" class="form-control" placeholder="Pur. Rate" v-model="selectedProduct.purchase_price" v-on:input="productTotal" required/>
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
							<th style="width:8%;color:#fff;font-weight: 500">Pur. Rate</th>
							<th style="width:5%;color:#fff;font-weight: 500">Quantity</th>
							<th style="width:13%;color:#fff;font-weight: 500">Total Amount</th>
							<th style="width:5%;color:#fff;font-weight: 500">Action</th>
						</tr>
					</thead>
					<tbody style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
						<tr v-for="(product, sl) in cart" :key="sl">
							<td>{{ sl + 1}}</td>
							<td>{{ product.name }}</td>
							<td>{{ product.purchase_price }}</td>
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
							<td colspan="4"><textarea style="width: 100%;font-size:13px;" placeholder="Note" v-model="purchasereturn.note"></textarea></td>
							<td colspan="3" style="padding-top: 15px;font-size:18px;">{{ purchasereturn.total }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-1 col-md-1" style="background-color: #acbac5;padding: 8px;border-radius: 5px;box-sizing:border-box;border:3px solid #fff;border-top-style: none;height:80px">
			<div>
				<div  style="display:none;text-align:center;margin-top:8px" v-bind:style="{color: productStock > 0 ? 'green' : 'red', display: selectedProduct.id == '' ? 'none' : ''}">
									{{ productStockText }}
				</div>
				<input type="text" id="productStock"  v-model="productStock" readonly style="border:none;font-size:20px;width:100%;text-align:center;color:green;padding:3px;background: none !important;"><br>
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
				purchasereturn: {
					id              : '',
					invoice_number  : '',
					return_date      : moment().format('YYYY-MM-DD'),
					supplier_id     : '',
					total           : 0.00,
					note            : ''
				},
				
				branches: [],
				
				suppliers: [],
				selectedSupplier: null,
				products: [],
				selectedProduct: {
					id            : '',
					display_text  : 'Select Product',
					name          : '',
					quantity      : '',
					purchase_price: '',
					total         : ''
				},
				productStockText: '',
				productStock: '',
				cart: [],
				purchaseReturnOnProgress: false
			}
		},
		async created(){
			this.purchasereturn.id= this.id;
			this.purchasereturn.invoice_number = this.invoice;
			await this.getSuppliers();
			this.getProducts();
			if(this.id != 0){
            	this.getPurchaseReturn();
        	}
		},
		methods:{
			
			async getSuppliers(){
				await axios.get('/get_suppliers').then(res => {
					this.suppliers = res.data;
				})
			},
			getProducts(){
				axios.post('/get_products').then(res=>{
					this.products = res.data;
				})
			},
				
			async onChangeProduct(){
                if (this.selectedProduct == null) {
					this.selectedProduct = {
						id            : '',
						display_text  : 'Select Product',
						name          : '',
						quantity      : '',
						purchase_price: '',
						total         : ''
					};
					this.productStockText= '';
					this.productStock= '';
					return true
				}

				if(this.selectedProduct !=null){
					this.productStock = await axios.post('/get_stock', {productId: this.selectedProduct.id}).then(res => {
						return res.data;
					})
					this.productStockText = this.productStock > 0 ? "Available Stock" : "Stock Unavailable";
				}
				setTimeout(() =>{
				this.$refs.quantity.focus();
				}, 500);
				
			},
			productTotal(){
				this.selectedProduct.total = this.selectedProduct.quantity * this.selectedProduct.purchase_price;
			},
			addToCart(){
				
				let product = {
					productId     : this.selectedProduct.id,
					name          : this.selectedProduct.name,
					return_rate   : this.selectedProduct.purchase_price,
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
				
				if(product.quantity > this.productStock){
					alert('Stock unavailable');
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
					total         : ''
				}

					this.productStockText= '';
					this.productStock= '';
			},
			calculateTotal(){
				this.purchasereturn.total = this.cart.reduce((prev, curr) => { return prev + parseFloat(curr.total); }, 0).toFixed(2);
			},
		
			save(){
				if(this.selectedSupplier == null){
					alert('Select Supplier');
					return;
				}

				if(this.purchasereturn.return_date == ''){
					alert('Enter Purchase Return date');
					return;
				}

				if(this.cart.length == 0){
					alert('Cart is empty');
					return;
				}

				this.purchasereturn.supplier_id      = this.selectedSupplier.id;

				this.purchaseReturnOnProgress = true;

				let data = {
					purchasereturn: this.purchasereturn,
					cartProducts: this.cart
				}

				

				let url = '/store-purchase-return';
				if(this.purchasereturn.id != 0){
					console.log(this.purchasereturn.id);
					url = '/update-purchase-return';
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
                        
                        window.location = '/purcahse_return_entry';
                        
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

		 async	getPurchaseReturn(){
			await axios.post('/get_purchase_return', {purchaseId: this.id}).then(res => {
					let purchasedreturns = res.data[0];
					console.log(purchasedreturns);
					this.selectedSupplier = {
						id           : purchasedreturns.supplierId,
						display_text : purchasedreturns.display_name,
						mobile       : purchasedreturns.supplier_mobile,
						address      : purchasedreturns.supplier_address,
					}
					this.purchasereturn.invoice_number       = purchasedreturns.purchasereturn.invoice_number,
					this.purchasereturn.return_date           = purchasedreturns.purchasereturn.return_date;
					this.purchasereturn.supplier_id          = purchasedreturns.supplierId;
					this.purchasereturn.total                = purchasedreturns.purchasereturn.total_amount;
					this.purchasereturn.note                 = purchasedreturns.purchasereturn.remark;


					
					
                purchasedreturns.purchasereturn.purchase_return_details.forEach(item => {
                    let cart = {
					id            : item.id,
					productId     : item.product_id,
					name          : item.product.name,
					quantity      : item.quantity,
					return_rate   : item.return_rate,
					total         : item.total_amount
                    }
                    this.cart.push(cart);
                })
				})
			}
		
		}
	}

</script>