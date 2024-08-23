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
<div class="row">
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
				<h3 style="text-align:center"> Purchase Details Record</h3>
           
				<table class="record-table">
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Supplier Name</th>
							<th>Product</th>
							<th>Quantity</th>
							<th>Rate</th>
							<th>Total</th>
							<th>Note</th>
						</tr>
					</thead>
					<tbody>
	
                        @foreach($result as $r)
						<tr>
							<td>{{ $r['invoice_text'] }}</td>
							<td>{{ $r['purchase']['order_date'] }}</td>
							<td>{{ $r['display_name'] }}</td>
							<td>{{ $r['purchase']['purchaseDetails'][0]['product']['name'] }}</td>
							<td style="text-align:center;">{{ $r['purchase']['purchaseDetails'][0]['quantity'] }}</td>
							<td style="text-align:right;">{{ $r['purchase']['purchaseDetails'][0]['purchase_rate'] }}</td>
							<td style="text-align:right;">{{ number_format($r['purchase']['purchaseDetails'][0]['total_amount'],2) }}</td>
							<td>Note: {{ $r['purchase']['remark'] }}</td>
						</tr>
						@foreach($r['purchase']['purchaseDetails']->slice(1) as $rd)
							<tr>
								<td colspan="3" ></td>
								<td>{{ $rd['product']['name'] }}</td>
								<td style="text-align:center;">{{ $rd['quantity'] }}</td>
								<td style="text-align:right;">{{ $rd['purchase_rate'] }}</td>
								<td style="text-align:right;">{{ number_format($rd['total_amount'],2) }}</td>
								<td></td>
							</tr>
                        @endforeach
                        @endforeach
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="6" style="text-align:right;">Total</td>
							<td style="text-align:right;">{{number_format($total,2)}}</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
         
            
			</div>
		</div>
	</div>