
<style scoped>
	#searchForm select{
		padding:0;
		border-radius: 4px;
	}
	#searchForm .form-group{
		margin-right: 5px;
	}
	#searchForm *{
		font-size: 13px;
	}
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
	th{
		background-color: #146C94;
	color:#fff !important;
	}
	.table-responsive tr{
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
	.table-responsive tr:hover{
	background-color: #62CDFF !important;
	transition: 0s all;   
	-webkit-transition-delay: 0s;
		-moz-transition-delay: 0s;
		-ms-transition-delay: 0s;
		-o-transition-delay: 0s;
		transition-delay: 0s;
}
</style>
<template>
    <div id="purchaseRecord">
	<div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
		<div class="col-md-12">
			<form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
				<div class="form-group">
					<label>Search Type</label>
					<select class="form-control" v-model="searchType" @change="onChangeSearchType">
						<option value="">All</option>
						<option value="supplier">By Supplier</option>
						<option value="category">By Category</option>
						<option value="quantity">By Quantity</option>
						<option value="user">By User</option>
					</select>
				</div>

				<div class="form-group row" style="display:none;" v-bind:style="{display: searchType == 'supplier' && suppliers.length > 0 ? '' : 'none'}">
					<label class="col-md-4" style="margin-top:5px;">Supplier</label>
					<v-select v-bind:options="suppliers" v-model="selectedSupplier" label="display_name"></v-select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'quantity' && products.length > 0 ? '' : 'none'}">
					<label style="margin-top:5px;">Instrument</label>
					<v-select v-bind:options="products" v-model="selectedProduct" label="display_text"></v-select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'category' && categories.length > 0 ? '' : 'none'}">
					<label style="margin-top:5px;">Category</label>
					<v-select v-bind:options="categories" v-model="selectedCategory" label="name"></v-select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'user' && users.length > 0 ? '' : 'none'}">
					<label style="margin-top:5px;">User</label>
					<v-select v-bind:options="users" v-model="selectedUser" label="name"></v-select>
				</div>

				<div class="form-group" v-bind:style="{display: searchTypesForRecord.includes(searchType) ? '' : 'none'}">
					<label>Record Type</label>
					<select class="form-control" v-model="recordType" @change="purchases = []">
						<option value="without_details">Without Details</option>
						<option value="with_details">With Details</option>
					</select>
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

	<div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: purchases.length > 0 ? '' : 'none'}">
		<div class="col-md-12" style="margin-bottom: 10px;">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
				<table 
					class="record-table" 
					v-if="(searchTypesForRecord.includes(searchType)) && recordType == 'with_details'" 
					style="display:none" 
					v-bind:style="{display: (searchTypesForRecord.includes(searchType)) && recordType == 'with_details' ? '' : 'none'}"
					>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Supplier Name</th>
							<th>Product Name</th>
							<th>Price</th>
							<th>Quantity</th>
							<th>Total</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody v-for="(purchase,sl) in purchases" :key='sl'>
							<tr>
								<td>{{ purchase.invoice_number }}</td>
								<td>{{ purchase.order_date }}</td>
								<td>{{ purchase.display_name }}</td>
								<td>{{ purchase.purchaseDetails[0].display_text }}</td>
								<td style="text-align:right;">{{ purchase.purchaseDetails[0].purchase_rate }}</td>
								<td style="text-align:center;">{{ purchase.purchaseDetails[0].quantity }}</td>
								<td style="text-align:right;">{{ purchase.purchaseDetails[0].total_amount }}</td>
								<td style="text-align:center;">
									<a href="" title="Purchase Invoice" v-bind:href="'/purchase_invoice_print/'+ purchase.id" target="_blank"><i class="fa fa-file-text"></i></a>
									<span v-if="role != 'User'">
									<a href="javascript:" title="Edit Purchase" @click="checkReturnAndEdit(purchase)"><i class="fa fa-edit"></i></a>
									<a href="" title="Delete Purchase" @click.prevent="deletePurchase(purchase.id)"><i class="fa fa-trash"></i></a>
									</span>
								</td>
							</tr>
							<tr v-for="(product, sl) in purchase.purchaseDetails.slice(1)" :key='sl'>
								<td colspan="3" v-bind:rowspan="purchase.purchaseDetails.length - 1" v-if="sl == 0"></td>
								<td>{{ product.display_text }}</td>
								<td style="text-align:right;">{{ product.purchase_rate }}</td>
								<td style="text-align:center;">{{ product.quantity }}</td>
								<td style="text-align:right;">{{ product.total_amount }}</td>
								<td></td>
							</tr>
							<tr style="font-weight:bold;">
								<td colspan="5" style="font-weight:normal;"><strong>Note: </strong>{{ purchase.remark }}</td>
								<td style="text-align:center;">Total Quantity<br>{{ purchase.purchaseDetails.reduce((prev, curr) => {return prev + parseFloat(curr.quantity)}, 0) }}</td>
								<td style="text-align:right;">
									Total: {{ purchase.total }}<br>
									Paid: {{ purchase.paid }}<br>
									Due: {{ purchase.due }}
								</td>
								<td></td>
							</tr>
					</tbody>
				</table>

				<table 
					class="record-table" 
					v-if="(searchTypesForRecord.includes(searchType)) && recordType == 'without_details'" 
					style="display:none" 
					v-bind:style="{display: (searchTypesForRecord.includes(searchType)) && recordType == 'without_details' ? '' : 'none'}"
					>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Supplier Name</th>
							<th>Total</th>
							<th>Note</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(purchase,sl) in purchases" :key='sl'>
							<td>{{ purchase.invoice_number }}</td>
							<td>{{ purchase.order_date }}</td>
							<td>{{ purchase.display_name }}</td>
							<td style="text-align:right;">{{ purchase.total }}</td>
							<td style="text-align:left;">{{ purchase.remark }}</td>
							<td style="text-align:center;">
								<a href="" title="Purchase Invoice" v-bind:href="'/purchase_invoice_print/'+ purchase.id" target="_blank"><i class="fa fa-file-text"></i></a>
								<span v-if="role != 'User'">
								<a href="javascript:" title="Edit Purchase" @click="checkReturnAndEdit(purchase)"><i class="fa fa-edit"></i></a>
								<a href="" title="Delete Purchase" @click.prevent="deletePurchase(purchase.id)"><i class="fa fa-trash"></i></a>
								</span>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="3" style="text-align:right;">Total</td>
							<td style="text-align:right;">{{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.subtotal)}, 0) }}</td>
							<td style="text-align:right;">{{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.vat_amount)}, 0) }}</td>
							<td style="text-align:right;">{{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.discount_amount)}, 0) }}</td>
							<td style="text-align:right;">{{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.transport_cost)}, 0) }}</td>
							<td style="text-align:right;">{{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.total)}, 0) }}</td>
							<td style="text-align:right;">{{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.paid)}, 0) }}</td>
							<td style="text-align:right;">{{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.due)}, 0) }}</td>
							<td></td>
							<td></td>
						</tr>
					</tfoot>
				</table>

				<table 
					class="record-table" 
					v-if="searchTypesForDetails.includes(searchType)"  
					style="display:none;" 
					v-bind:style="{display: searchTypesForDetails.includes(searchType) ? '' : 'none'}"
					>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Supplier Name</th>
							<th>Product Name</th>
							<th>Purchases Rate</th>
							<th>Quantity</th>
						</tr>
					</thead>
					<tbody>
						<tr  v-for="(purchase,sl) in purchases" :key='sl'>
							<td>{{ purchase.invoice_number }}</td>
							<td>{{ purchase.order_date }}</td>
							<td>{{ purchase.display_name }}</td>
							<td>{{ purchase.display_text }}</td>
							<td style="text-align:right;">{{ purchase.purchase_rate }}</td>
							<td style="text-align:right;">{{ purchase.quantity }}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="5" style="text-align:right;">Total Quantity</td>
							<td style="text-align:right;">{{ purchases.reduce((prev, curr) => { return prev + parseFloat(curr.quantity)}, 0) }}</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
</template>

<script>
import moment from 'moment';
export default {
    props: ['role'],
    data(){
			return {
				searchType: '',
				recordType: 'without_details',
				dateFrom: moment().format('YYYY-MM-DD'),
				dateTo: moment().format('YYYY-MM-DD'),
				suppliers: [],
				selectedSupplier: null,
				products: [],
				selectedProduct: null,
				users: [],
				selectedUser: null,
				categories: [],
				selectedCategory: null,
				purchases: [],
				searchTypesForRecord: ['', 'user', 'supplier'],
				searchTypesForDetails: ['quantity', 'category']
			}
		},
        created(){
            this.getBranchInfo();
        },
		methods: {
			checkReturnAndEdit(purchase){
				axios.get('/check_purchase_inventroy_return/' + purchase.invoice_number).then(res=>{
					if(res.data.found){
						alert('Unable to edit. Purchase return found!');
					}else{
						location.replace('/purchase_inventory/'+purchase.id);
					}
				})
			},
			onChangeSearchType(){
				this.purchases = [];
				if(this.searchType == 'quantity'){
					this.getProducts();
				} 
				else if(this.searchType == 'user'){
					this.getUsers();
				}
				else if(this.searchType == 'category'){
					this.getCategories();
				}
				else if(this.searchType == 'supplier'){
					this.getSuppliers();
				}
			},
            getBranchInfo(){
                axios.get('/get_branch_info').then(res=>{
                    this.branch = res.data;
                })
            },
			getProducts(){
				axios.get('/get_products').then(res => {
					this.products = res.data;
				})
			},
			getSuppliers(){
				axios.get('/get_suppliers').then(res => {
					this.suppliers = res.data;
				})
			},
			getUsers(){
				axios.get('/get_users').then(res => {
					this.users = res.data;
				})
			},
			getCategories(){
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
			getSearchResult(){
				if(this.searchType != 'user'){
					this.selectedUser = null;
				}

				if(this.searchType != 'quantity'){
					this.selectedProduct = null;
				}

				if(this.searchType != 'category'){
					this.selectedCategory = null;
				}

				if(this.searchType != 'supplier'){
					this.selectedSupplier = null;
				}

				if(this.searchTypesForRecord.includes(this.searchType)) {
					this.getPurchaseRecord();
				} else {
					this.getPurchaseDetails();
				}
			},
			getPurchaseRecord(){
				let filter = {
					userId: this.selectedUser == null || this.selectedUser.name == '' ? '' : this.selectedUser.id,
					supplier_id: this.selectedSupplier == null ? '' : this.selectedSupplier.id,
					dateFrom: this.dateFrom,
					dateTo: this.dateTo
				}

                if(this.recordType == 'with_details'){
                    filter.with_details = true;
                }

				let url = '/get_purchase';
				

				axios.post(url, filter)
				.then(res => {
						this.purchases = res.data;
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},
			getPurchaseDetails(){
				let filter = {
					categoryId: this.selectedCategory == null || this.selectedCategory.id == '' ? '' : this.selectedCategory.id,
					productId: this.selectedProduct == null || this.selectedProduct.id == '' ? '' : this.selectedProduct.id,
					dateFrom: this.dateFrom,
					dateTo: this.dateTo
				}

				axios.post('/get_purchase_inventory_details', filter)
				.then(res => {
					this.purchases = res.data;
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},

        deletePurchase(purchaseId){
            Swal.fire({
                title: '<strong>Are you sure!</strong>',
                html: '<strong>Want to delete this?</strong>',
                showDenyButton: true,
                confirmButtonText: `Ok`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/delete-purchaseinventory',{id: purchaseId}).then(res=>{
                        let r = res.data;
                        Swal.fire({
                            icon: 'success',
                            title: r.message,
                            showConfirmButton: false,
                            timer: 3000
                        })
                        this.getPurchaseRecord();
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
			async print(){
				let dateText = '';
				if(this.dateFrom != '' && this.dateTo != ''){
					dateText = `Statement from <strong>${this.dateFrom}</strong> to <strong>${this.dateTo}</strong>`;
				}

				let userText = '';
				if(this.selectedUser != null && this.selectedUser.id != '' && this.searchType == 'user'){
					userText = `<strong>Sold by: </strong> ${this.selectedUser.name}`;
				}

				let supplierText = '';
				if(this.selectedSupplier != null && this.selectedSupplier.id != '' && this.searchType == 'quantity'){
					supplierText = `<strong>Supplier: </strong> ${this.selectedSupplier.name}<br>`;
				}

				let productText = '';
				if(this.selectedProduct != null && this.selectedProduct.id != '' && this.searchType == 'quantity'){
					productText = `<strong>Product: </strong> ${this.selectedProduct.name}`;
				}

				let categoryText = '';
				if(this.selectedCategory != null && this.selectedCategory.id != '' && this.searchType == 'category'){
					categoryText = `<strong>Category: </strong> ${this.selectedCategory.name}`;
				}


				let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Purchase Record</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								${userText} ${supplierText} ${productText} ${categoryText}
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

				if(this.searchType == '' || this.searchType == 'user'){
					let rows = reportWindow.document.querySelectorAll('.record-table tr');
					rows.forEach(row => {
						row.lastChild.remove();
					})
				}


				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}

}
</script>