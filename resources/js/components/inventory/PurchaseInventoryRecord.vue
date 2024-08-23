
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
						<option value="supplier">By Supplier</option>
					</select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'supplier' && suppliers.length > 0 ? '' : 'none'}">
					<label>Supplier</label>
					<v-select v-bind:options="suppliers" v-model="selectedSupplier" label="name"></v-select>
				</div>

				<div class="form-group" v-bind:style="{display: searchTypesForRecord.includes(searchType) ? '' : 'none'}">
					<label>Record Type</label><br>
					<select class="form-control" v-model="recordType" @change="purchases = []">
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

	<div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: purchases.length > 0 ? '' : 'none'}">
		<div class="col-md-12" style="margin-bottom: 10px;">
			<div class="col-md-12" style="margin-bottom: 10px;">
			<a href="" @click.prevent="print">
				<button class="print-design">
					<i class="fa fa-print"></i> Print
				</button>
			</a>
			<button class="excel-design" @click="exportTableToExcel('reportContent', 'Purchase Record','Purchase Return Record')">
				<i class="fa fa-file-excel-o"></i> Export To Excel
   		 	</button>
			<button class="pdf-design" @click="navigateToPage">
				<i class="fa fa-file-pdf-o"></i> Export To PDF
   		 	</button>
		</div>
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
					<tbody v-for="(purchase, sl) in purchases" :key="sl">
    <!-- Main Purchase Row -->
							<tr>
								<td>{{ purchase.purchase.invoice_number }}</td>
								<td>{{ purchase.purchase.order_date }}</td>
								<td>{{ purchase.display_name }}</td>
								<td>{{ purchase.purchase.purchase_details[0].product.name }}</td>
								<td style="text-align:right;">
									{{ purchase.purchase.purchase_details[0].purchase_rate }}
								</td>
								<td style="text-align:center;">
									{{ purchase.purchase.purchase_details[0].quantity }}
								</td>
								<td style="text-align:right;">
									{{ purchase.purchase.purchase_details[0].total_amount }}
								</td>
								<td style="text-align:center;">
									<a :href="'/purchase_invoice_print/' + purchase.purchase.id" target="_blank" title="Purchase Invoice">
										<i class="fa fa-file-text"></i>
									</a>
									<span v-if="role !== 'General'">
										<a href="javascript:;" title="Edit Purchase" @click="checkReturnAndEdit(purchase.purchase)">
											<i class="fa fa-edit"></i>
										</a>
										<a href="javascript:;" title="Delete Purchase" @click.prevent="deletePurchase(purchase.purchase.id)">
											<i class="fa fa-trash"></i>
										</a>
									</span>
								</td>
							</tr>
							
							<!-- Additional Products Rows -->
							<tr v-for="(product, index) in purchase.purchase.purchase_details.slice(1)" :key="index">
								<td v-if="index === 0" colspan="3" :rowspan="purchase.purchase.purchase_details.length - 1"></td>
								<td>{{ product.product.name }}</td>
								<td style="text-align:right;">
									{{ product.purchase_rate }}
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
									<strong>Note:</strong> {{ purchase.remark }}
								</td>
								<td style="text-align:center;">
									Total Quantity: <br>
									{{ purchase.purchase.purchase_details.reduce((prev, curr) => prev + parseFloat(curr.quantity), 0) }}
								</td>
								<td style="text-align:right;">
									Total: {{ purchase.purchase.total }}
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
							<td>{{ purchase.purchase.invoice_number }}</td>
							<td>{{ purchase.purchase.order_date }}</td>
							<td>{{ purchase.display_name }}</td>
							<td style="text-align:right;">{{ purchase.purchase.total }}</td>
							<td style="text-align:left;">{{ purchase.purchase.remark }}</td>
							<td style="text-align:center;">
								<!-- <a  title="Purchase Invoice" v-bind:href="'/purchase_invoice_print/'+ purchase.purchase.id" target="_blank"><i class="fa fa-file-text"></i></a> -->
								<span v-if="role != 'General'">
								<a href="javascript:" title="Edit Purchase" @click="checkReturnAndEdit(purchase.purchase)"><i class="fa fa-edit"></i></a>
								<a href="" title="Delete Purchase" @click.prevent="deletePurchase(purchase.purchase.id)"><i class="fa fa-trash"></i></a>
								</span>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="3" style="text-align:right;">Total</td>
							<td style="text-align:right;">{{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.purchase.total)}, 0) }}</td>
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
				suppliers: [],
				selectedSupplier: null,
				purchases: [],
				campany: [],
				searchTypesForRecord: ['', 'user', 'supplier']
			}
		},
        created(){
            this.getCompanyInfo();
        },
		methods: {
			checkReturnAndEdit(purchase){
						location.replace('/purchase_entry/'+purchase.id);
			},
			onChangeSearchType(){
				this.purchases = [];

				 if(this.searchType == 'supplier'){
					this.getSuppliers();
				}
			},
			getCompanyInfo(){
                axios.get('/get_companies').then(res=>{
                    this.campany = res.data;
                })
            },
		
			getSuppliers(){
				axios.get('/get_suppliers').then(res => {
					this.suppliers = res.data;
				})
			},
			
		
			getSearchResult(){
				if(this.searchType != 'user'){
					this.selectedUser = null;
				}


				if(this.searchType != 'supplier'){
					this.selectedSupplier = null;
				}

				
					this.getPurchaseRecord();
				
			},

			navigateToPage() {
				const baseUrl = '/pdf_purchase';
				let params = {
					customer_id: this.selectedSupplier == null ? '' : this.selectedSupplier.id,
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					recordType : this.recordType
				}
				

			const queryString = Object.keys(params)
				.map(key => encodeURIComponent(key) + '=' + encodeURIComponent(params[key]))
				.join('&');

			window.location.href = `${baseUrl}?${queryString}`;
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
						console.log(this.purchases);
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},

			exportTableToExcel(transactionsTable, filename = '',headerText = ''){
				const dataType = 'application/vnd.ms-excel';
				const tableSelect = document.getElementById(transactionsTable);

				// Ensure tableSelect exists
				if (!tableSelect) {
					console.error('Table element not found');
					return;
				}
				
				// Add inline styles to ensure borders and padding
				const style = `
					<style>
						table, th, td {
							border: 1px solid gray;
							border-collapse: collapse;
						}
						th, td {
							padding: 5px;
							text-align: left;
						}
						
					</style>
				`;
				
				const headerHTML = headerText ? `<div class="header" >${headerText}</div>` : '';
    
				// Combine header and table HTML
				const tableHTML = style + headerHTML + tableSelect.outerHTML;

				// Specify file name
				filename = filename ? filename + '.xls' : 'excel_data.xls';
				
				// Create download link element
				const downloadLink = document.createElement('a');
				document.body.appendChild(downloadLink);

				if (navigator.msSaveOrOpenBlob) {
					// For IE and Edge
					const blob = new Blob(['\ufeff', tableHTML], { type: dataType });
					navigator.msSaveOrOpenBlob(blob, filename);
				} else {
					// For other browsers
					downloadLink.href = 'data:' + dataType + ', ' + encodeURIComponent(tableHTML);
					downloadLink.download = filename;
					
					// Trigger the download
					downloadLink.click();
				}
				
				// Clean up
				document.body.removeChild(downloadLink);
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
                    axios.post('/delete-purchase',{id: purchaseId}).then(res=>{
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

				

				let supplierText = '';
				if(this.selectedSupplier != null && this.selectedSupplier.id != ''){
					supplierText = `<strong>Supplier: </strong> ${this.selectedSupplier.name}<br>`;
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
								 ${supplierText} 
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