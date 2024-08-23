
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
    <div id="app">
	

	<div class="row" style="margin-top:15px;">
		<div class="col-md-12" style="margin-bottom: 10px;">
			<div class="col-md-12" style="margin-bottom: 10px;">
			<a href="" @click.prevent="print">
				<button class="print-design">
					<i class="fa fa-print"></i> Print
				</button>
			</a>
			<button class="excel-design" @click="exportTableToExcel('reportContent', 'product_list','Product List')">
				<i class="fa fa-file-excel-o"></i> Export To Excel
   		 	</button>
			<button class="pdf-design" @click="navigateToPage">
				<i class="fa fa-file-pdf-o"></i> Export To PDF
   		 	</button>
		</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
				

				<table class="record-table" >
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
						<tr v-for="(product,sl) in products" :key='sl'>
							<td>{{ product.product_code }}</td>
							<td>{{ product.display_text }}</td>
							<td>{{ product.category.name }}</td>
							<td>{{ product.unit.name }}</td>
							<td>{{ product.purchase_price }}</td>
							<td>{{ product.sale_price }}</td>
							<td>{{ product.reorder_level }}</td>
						</tr>
					</tbody>
				</table>

				
			</div>
		</div>
	</div>
</div>
</template>

<script>
export default {
    props: ['role'],
    data(){
			return {
				products: [],
				campany : []
			}
		},
        created(){
            this.getCompanyInfo();
            this.getProduct();
        },
		methods: {
			
			getProduct(){
				axios.get('/get_products').then(res => {
					this.products = res.data;
				})
			},
			getCompanyInfo(){
                axios.get('/get_companies').then(res=>{
                    this.campany = res.data;
					console.log(this.campany);
                })
            },
			navigateToPage() {
				window.location.href = '/pdf_product_list';
			},
			getPurchaseRecord(){
				

				let url = '/get_products';
				

				axios.get(url)
				.then(res => {
						this.products = res.data;
						console.log(this.products);
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
			
			async print(){
				



				let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Product List</h3>
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

				


				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				// reportWindow.print();
				// reportWindow.close();
			}
		}

}
</script>