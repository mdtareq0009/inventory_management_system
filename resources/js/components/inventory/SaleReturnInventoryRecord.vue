
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

.excel-design{
	margin-left: 5px;
    background: #008a00;
    padding: 5px;
    border: none;
    /* outline: aliceblue; */
    color: white;
    border-radius: 5px;
}
.print-design{
	margin-left: 5px;
    background: #f51010;
    padding: 5px;
    border: none;
    /* outline: aliceblue; */
    color: white;
    border-radius: 5px;
}
.pdf-design{
	margin-left: 5px;
    background: #106ff5;
    padding: 5px;
    border: none;
    /* outline: aliceblue; */
    color: white;
    border-radius: 5px;
}
</style>
<template>
    <div id="purchaseRecord">
	<div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;background: aquamarine;">
		<div class="col-md-12">
			<form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
				<div class="form-group">
					<label>Search Type</label><br>
					<select class="form-control" v-model="searchType" @change="onChangeSearchType">
						<option value="">All</option>
						<option value="customer">By Customer</option>
					</select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'customer' && customers.length > 0 ? '' : 'none'}">
					<label>Customer</label>
					<v-select v-bind:options="customers" v-model="selectedCustomer" label="name"></v-select>
				</div>

				<div class="form-group" v-bind:style="{display: searchTypesForRecord.includes(searchType) ? '' : 'none'}">
					<label>Record Type</label><br>
					<select class="form-control" v-model="recordType" @change="salereturns = []">
						<option value="without_details">Without Details</option>
						<option value="with_details">With Details</option>
					</select>
				</div>

				<div class="form-group">
					<label>From Date</label><br>
					<input type="date" class="form-control" v-model="dateFrom">
				</div>

				<div class="form-group">
					<label>To Date</label><br>
					<input type="date" class="form-control" v-model="dateTo">
				</div>

				<div class="form-group" style="margin-top: 18px;">
					<input type="submit" value="Search">
				</div>
			</form>
		</div>
	</div>

	<div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: salereturns.length > 0 ? '' : 'none'}">
		<div class="col-md-12" style="margin-bottom: 10px;">
			<a href="" @click.prevent="print"><button class="print-design">
					<i class="fa fa-print"></i> Print
				</button>
				</a>
			<button class="excel-design" @click="exportTableToExcel('reportContent', 'Purchase Return Record','Purchase Return Record')">
				<i class="fa fa-file-excel-o"></i> Export To Excel
   		 	</button>
			<button class="pdf-design" @click="navigateToPage">
				<i class="fa fa-file-pdf-o"></i> Export To PDF
   		 	</button>
			
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
							<th>Return Date</th>
							<th>Customer Name</th>
							<th>Product Name</th>
							<th>Return Price</th>
							<th>Quantity</th>
							<th>Total</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody v-for="(salereturn, sl) in salereturns" :key="sl">
    <!-- Main Purchase Row -->
							<tr>
								<td>{{ salereturn.salereturn.invoice_number }}</td>
								<td>{{ salereturn.salereturn.return_date }}</td>
								<td>{{ salereturn.display_name }}</td>
								<td>{{ salereturn.salereturn.sale_return_details[0].product.name }}</td>
								<td style="text-align:right;">
									{{ salereturn.salereturn.sale_return_details[0].return_rate }}
								</td>
								<td style="text-align:center;">
									{{ salereturn.salereturn.sale_return_details[0].quantity }}
								</td>
								<td style="text-align:right;">
									{{ salereturn.salereturn.sale_return_details[0].total_amount }}
								</td>
								<td>
								
									<span v-if="role !== 'General'">
										<a href="javascript:;" title="Edit Sale Return" @click="checkReturnAndEdit(salereturn.salereturn)">
											<i class="fa fa-edit"></i>
										</a>
										<a href="javascript:;" title="Delete Sale Return" @click.prevent="deleteSaleReturn(salereturn.salereturn.id)">
											<i class="fa fa-trash"></i>
										</a>
									</span>
								</td>
							</tr>
							
							<!-- Additional Products Rows -->
							<tr v-for="(product, index) in salereturn.salereturn.sale_return_details.slice(1)" :key="index">
								<td v-if="index === 0" colspan="3" :rowspan="salereturn.salereturn.sale_return_details.length - 1"></td>
								<td>{{ product.product.name }}</td>
								
								<td style="text-align:right;">
									{{ product.return_rate }}
								</td>
								<td style="text-align:center;">
									{{ product.quantity }}
								</td>
								<td style="text-align:right;">
									{{ product.total_amount }}
								</td>
								<td></td>
							</tr>
							
							<!-- Summary Row -->
							<tr style="font-weight:bold;">
								<td colspan="5" style="font-weight:normal;">
									<strong>Note:</strong> {{ salereturn.salereturn.remark }}
								</td>
								<td style="text-align:center;">
									Total Quantity: <br>
									{{ salereturn.salereturn.sale_return_details.reduce((prev, curr) => prev + parseFloat(curr.quantity), 0) }}
								</td>
								<td style="text-align:right;">
									Total: {{ salereturn.salereturn.total_amount }}
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
							<th>Return Date</th>
							<th>Customer Name</th>
							<th>Total</th>
							<th>Note</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(salereturn, sl) in salereturns" :key="sl">
							<td>{{ salereturn.salereturn.invoice_number }}</td>
							<td>{{ salereturn.salereturn.return_date }}</td>
							<td>{{ salereturn.display_name }}</td>
							<td style="text-align:right;">{{ salereturn.salereturn.total_amount }}</td>
							<td style="text-align:left;">{{ salereturn.salereturn.remark }}</td>
							<td style="text-align:center;">
								
								<span v-if="role != 'General'">
								<a href="javascript:" title="Edit Sale" @click="checkReturnAndEdit(salereturn.salereturn)"><i class="fa fa-edit"></i></a>
								<a href="" title="Delete Sale" @click.prevent="deleteSaleReturn(salereturn.salereturn.id)"><i class="fa fa-trash"></i></a>
								</span>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="3" style="text-align:right;">Total</td>
							<td style="text-align:right;">{{ salereturns.reduce((prev, curr)=>{return prev + parseFloat(curr.salereturn.total_amount)}, 0) }}</td>
							<td></td>
							<td></td>
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
				customers: [],
				selectedCustomer: null,
				salereturns: [],
				campany: [],
				searchTypesForRecord: ['', 'user', 'customer']
			}
		},
        created(){
            this.getCompanyInfo();
        },
		methods: {
			
			
			checkReturnAndEdit(saleretrun){
						location.replace('/sale_return_entry/'+saleretrun.id);
			},
			onChangeSearchType(){
				this.sales = [];

				 if(this.searchType == 'customer'){
					this.getCustomers();
				}
			},
			getCompanyInfo(){
                axios.get('/get_companies').then(res=>{
                    this.campany = res.data;
                })
            },
            getBranchInfo(){
                axios.get('/get_branches').then(res=>{
                    this.branch = res.data;
                })
            },
		
			getCustomers(){
				axios.get('/get_customers').then(res => {
					this.customers = res.data;
					console.log(this.customers);
				})
			},
			
		
			getSearchResult(){
				


				if(this.searchType != 'customer'){
					this.selectedCustomer = null;
				}

				
					this.getSaleRecord();
				
			},
			getSaleRecord(){
				let filter = {
					customer_id: this.selectedCustomer == null ? '' : this.selectedCustomer.id,
					dateFrom: this.dateFrom,
					dateTo: this.dateTo
				}

                if(this.recordType == 'with_details'){
                    filter.with_details = true;
                }

				let url = '/get_sales_return';
				

				axios.post(url, filter)
				.then(res => {
						this.salereturns = res.data;
						console.log(this.salereturns);
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},

			navigateToPage() {
				const baseUrl = '/pdf_sale_return';
				let params = {
					customer_id: this.selectedCustomer == null ? '' : this.selectedCustomer.id,
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					recordType : this.recordType
				}
				

			const queryString = Object.keys(params)
				.map(key => encodeURIComponent(key) + '=' + encodeURIComponent(params[key]))
				.join('&');

			window.location.href = `${baseUrl}?${queryString}`;
			},
			

			deleteSaleReturn(salesReturnId){
            Swal.fire({
                title: '<strong>Are you sure!</strong>',
                html: '<strong>Want to delete this?</strong>',
                showDenyButton: true,
                confirmButtonText: `Ok`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/delete-sale-return',{id: salesReturnId}).then(res=>{
                        let r = res.data;
                        Swal.fire({
                            icon: 'success',
                            title: r.message,
                            showConfirmButton: false,
                            timer: 3000
                        })
                        this.getSaleRecord();
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

				

				let customerText = '';
				if(this.selectedCustomer != null && this.selectedCustomer.id != ''){
					customerText = `<strong>Customer: </strong> ${this.selectedCustomer.name}<br>`;
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
								 ${customerText} 
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
                        <div class="col-xs-2"><img src="${this.campany.logo}" alt="Logo" style="height:80px;" /></div>
                        <div class="col-xs-10" style="padding-top:20px;">
                            <strong style="font-size:18px;">${this.campany.name}</strong><br>
                            <p style="white-space: pre-line;">${this.campany.address}</p>
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