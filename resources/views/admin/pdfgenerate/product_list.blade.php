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
				<h3 style="text-align:center"> Product</h3>

                
				<table class="record-table">
					<thead>
						<tr>
							<th>Product Code</th>
							<th>Name</th>
							<th>Category</th>
							<th>Unit</th>
							<th>Purchase Price</th>
							<th>Sale Price</th>
							<th>Reorder Level</th>
						</tr>
					</thead>
					<tbody>
                        @foreach($result as $v)
						<tr>
							<td style="text-align:center;">{{ $v->product_code }}</td>
							<td>{{ $v->display_text }}</td>
							<td>{{ $v->category->name}}</td>
							<td>{{ $v->unit->name}}</td>
							<td style="text-align:center;">{{ $v->purchase_price}}</td>
							<td style="text-align:center;">{{ $v->sale_price}}</td>
							<td style="text-align:center;">{{ $v->reorder_level}}</td>
						</tr>
                        @endforeach
					</tbody>
					
				</table>
         
            
			</div>
		</div>
	</div>