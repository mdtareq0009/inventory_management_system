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
				<h3 style="text-align:center"> Current Stock</h3>
           
				<table class="record-table">
					<thead>
						<tr>
						
							<th>Product Id</th>
							<th>Product Name</th>
							<th>Category</th>
							<th>Purchased Quantity</th>
							<th>Purchase Returned Quantity</th>
							<th>Sale Quantity</th>
							<th>Sale Returned Quantity</th>
							<th>Current Quantity</th>
						</tr>
					</thead>
					<tbody>
                  
                        @foreach($result as $v)
						<tr>
							<td>{{ $v->product_code }}</td>
							<td>{{ $v->name }}</td>
							<td>{{ $v->category_name }}</td>
							<td>{{ number_format($v->purchased_quantity,2) }}</td>
							<td>{{ number_format($v->purchased_return_quantity,2) }}</td>
							<td>{{ number_format($v->sold_quantity,2) }}</td>
							<td>{{ number_format($v->sale_return_quantity,2) }}</td>
							<td style="text-align:right;">{{ number_format($v->current_quantity,2) }}</td>
						</tr>
                        @endforeach
					</tbody>
					
				</table>
         
            
			</div>
		</div>
	</div>