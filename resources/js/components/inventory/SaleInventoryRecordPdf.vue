
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
.hidden {
    display: none;
}
</style>
<template>
    <div id="saleRecord">
	

	<div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: sales.length > 0 ? '' : 'none'}">
		
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
				<h3 class="text-center"> Sales Record</h3>
				<table  
					class="record-table" 
					v-if=" detail == 'with_details'" 
					style="display:none" 
					v-bind:style="{display: detail == 'with_details' ? '' : 'none'}"
					>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Customer Name</th>
							<th>Product Name</th>
							<th>Purchase Price</th>
							<th>Sale Price</th>
							<th>Quantity</th>
							<th>Total</th>
							<th :class="{ hidden: isHidden }">Action</th>
						</tr>
					</thead>
					<tbody v-for="(sale, sl) in sales" :key="sl">
    <!-- Main Purchase Row -->
							<tr>
								<td>{{ sale.sale.invoice_number }}</td>
								<td>{{ sale.sale.order_date }}</td>
								<td>{{ sale.display_name }}</td>
								<td>{{ sale.sale.sale_details[0].product.name }}</td>
								<td style="text-align:right;">
									{{ sale.sale.sale_details[0].purchase_rate }}
								</td>
								<td style="text-align:right;">
									{{ sale.sale.sale_details[0].sale_rate }}
								</td>
								<td style="text-align:center;">
									{{ sale.sale.sale_details[0].quantity }}
								</td>
								<td style="text-align:right;">
									{{ sale.sale.sale_details[0].total_amount }}
								</td>
								<td :class="{ hidden: isHidden }">
								
									<span v-if="role !== 'User'">
										<a href="javascript:;" title="Edit Sale" @click="checkReturnAndEdit(sale.sale)">
											<i class="fa fa-edit"></i>
										</a>
										<a href="javascript:;" title="Delete Sale" @click.prevent="deleteSale(sale.sale.id)">
											<i class="fa fa-trash"></i>
										</a>
									</span>
								</td>
							</tr>
							
							<!-- Additional Products Rows -->
							<tr v-for="(product, index) in sale.sale.sale_details.slice(1)" :key="index">
								<td v-if="index === 0" colspan="3" :rowspan="sale.sale.sale_details.length - 1"></td>
								<td>{{ product.product.name }}</td>
								<td style="text-align:right;">
									{{ product.purchase_rate }}
								</td>
								<td style="text-align:right;">
									{{ product.sale_rate }}
								</td>
								<td style="text-align:center;">
									{{ product.quantity }}
								</td>
								<td style="text-align:right;">
									{{ product.total_amount }}
								</td>
								<td :class="{ hidden: isHidden }"></td>
							</tr>
							
							<!-- Summary Row -->
							<tr style="font-weight:bold;">
								<td colspan="6" style="font-weight:normal;">
									<strong>Note:</strong> {{ sale.sale.remark }}
								</td>
								<td style="text-align:center;">
									Total Quantity: <br>
									{{ sale.sale.sale_details.reduce((prev, curr) => prev + parseFloat(curr.quantity), 0) }}
								</td>
								<td style="text-align:right;">
									Total: {{ sale.sale.total }}
								</td>
								<td :class="{ hidden: isHidden }"></td>
							</tr>
						</tbody>
				</table>

				<table 
					class="record-table" 
					v-if=" detail == 'without_details'" 
					style="display:none" 
					v-bind:style="{display: detail == 'without_details' ? '' : 'none'}"
					>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Customer Name</th>
							<th>Total</th>
							<th>Note</th>
							<th :class="{ hidden: isHidden }">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(sale,sl) in sales" :key='sl'>
							<td>{{ sale.sale.invoice_number }}</td>
							<td>{{ sale.sale.order_date }}</td>
							<td>{{ sale.display_name }}</td>
							<td style="text-align:right;">{{ sale.sale.total }}</td>
							<td style="text-align:left;">{{ sale.sale.remark }}</td>
							<td style="text-align:center;" :class="{ hidden: isHidden }">
								
								<span v-if="role != 'User'">
								<a href="javascript:" title="Edit Sale" @click="checkReturnAndEdit(sale.sale)"><i class="fa fa-edit"></i></a>
								<a href="" title="Delete Sale" @click.prevent="deleteSale(sale.sale.id)"><i class="fa fa-trash"></i></a>
								</span>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="3" style="text-align:right;">Total</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.sale.total)}, 0) }}</td>
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
export default {
    props: ['role','fromDate','toDate','customer_id','details'],
    data(){
			return {
				
				dateFrom: this.fromDate,
				dateTo: this.toDate,
				detail: this.details,
				sales: [],
				
			}
		},
        created(){
            this.getSaleRecord();
        },
		methods: {
			
		

			getSaleRecord(){
				let filter = {
					customer_id: this.customer_id,
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					with_details: this.details== 'with_details' ? true : false,
				}

                
				let url = '/get_sales';
				

				axios.post(url, filter)
				.then(res => {

						this.sales = res.data;
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},
			

        
			
		}

}
</script>