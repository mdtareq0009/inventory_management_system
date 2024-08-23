
<style scoped>
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
    <div id="stock">
	<div class="row">
		<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
			<div class="form-group" style="margin-top:10px;">
				<label class="col-sm-1 col-sm-offset-1 control-label no-padding-right"> Select Type </label>
				<div class="col-sm-2">
					<v-select v-bind:options="searchTypes" v-model="selectedSearchType" label="text" v-on:input="onChangeSearchType"></v-select>
				</div>
			</div>
	
			<div class="form-group" style="margin-top:10px;" v-if="selectedSearchType.value == 'category'">
				<div class="col-sm-2" style="margin-left:15px;">
					<v-select v-bind:options="categories" v-model="selectedCategory" label="name"></v-select>
				</div>
			</div>
	
			<div class="form-group" style="margin-top:10px;" v-if="selectedSearchType.value == 'product'">
				<div class="col-sm-2" style="margin-left:15px;">
					<v-select v-bind:options="products" v-model="selectedProduct" label="display_text"></v-select>
				</div>
			</div>

			<div class="form-group" style="margin-top:10px;" v-if="selectedSearchType.value != 'current'">
				<div class="col-sm-2" style="margin-left:15px;">
					<input type="date" class="form-control" v-model="date">
				</div>
			</div>
	
			<div class="form-group">
				<div class="col-sm-2"  style="margin-left:15px;">
					<input type="button" class="btn btn-primary" value="Show Report" v-on:click="getStock" style="margin-top:0px;border:0px;height:28px;">
				</div>
			</div>
		</div>
	</div>
	<div class="row" v-if="searchType != null" style="display:none" v-bind:style="{display: searchType == null ? 'none' : ''}">
		<div class="col-md-12">
			<a href="" @click.prevent="print">
				<button class="print-design">
					<i class="fa fa-print"></i> Print
				</button>
			</a>
			<button class="excel-design" @click="exportTableToExcel('stockContent', 'StockReport','Stock Report')">
				<i class="fa fa-file-excel-o"></i> Export To Excel
   		 	</button>
			<button class="pdf-design" @click="navigateToPage">
				<i class="fa fa-file-pdf-o"></i> Export To PDF
   		 	</button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive" id="stockContent">
				<table class="table table-bordered" v-if="searchType == 'current'" style="display:none" v-bind:style="{display: searchType == 'current' ? '' : 'none'}">
					<thead>
						<tr>
							<th>Product Id</th>
							<th>Product Name</th>
							<th>Category</th>
							<th>Current Quantity</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(product,sl) in stock" :key="sl">
							<td>{{ product.product_code }}</td>
							<td>{{ product.product_name }}</td>
							<td>{{ product.category_name }}</td>
							<td>{{ product.current_quantity }}</td>
						</tr>
					</tbody>
				</table>

				<table class="table table-bordered" 
						v-if="searchType != 'current' && searchType != null" 
						style="display:none;" 
						v-bind:style="{display: searchType != 'current' && searchType != null ? '' : 'none'}">
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
						<tr v-for="(product,sl) in stock" :key="sl">
							<td>{{ product.product_code  }}</td>
							<td>{{ product.name }}</td>
							<td>{{ product.category_name }}</td>
							<td>{{ product.purchased_quantity }}</td>
							<td>{{ product.purchased_return_quantity }}</td>
							<td>{{ product.sold_quantity }}</td>
							<td>{{ product.sale_return_quantity }}</td>
							<td>{{ product.current_quantity }}</td>
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
    props: ['role'],
    data(){
			return {
				searchTypes: [
					{text: 'Current Stock', value: 'current'},
					{text: 'Total Stock', value: 'total'},
					{text: 'Category Wise Stock', value: 'category'},
					{text: 'Product Wise Stock', value: 'product'},
					//{text: 'Brand Wise Stock', value: 'brand'}
				],
				selectedSearchType: {
					text: 'select',
					value: ''
				},
				searchType: null,
				date: moment().format('YYYY-MM-DD'),
				categories: [],
				selectedCategory: null,
				products: [],
				campany: [],
				selectedProduct: null,
				selectionText: '',

				stock: [],
				totalStockValue: 0.00
			}
		},
		filters: {
			decimal(value) {
				return value == null ? '0.00' : parseFloat(value).toFixed(2);
			}
		},
		created(){
			this.getBranchInfo();
		},
		methods:{

			navigateToPage() {
				const baseUrl = '/pdf_stock';
				let params = {
					searchTypes: this.selectedSearchType.value,
					date: this.date,
					productId: this.selectedProduct?this.selectedProduct.id:null,
					catId: this.selectedCategory?this.selectedCategory.id:null,
				}
				

			const queryString = Object.keys(params)
				.map(key => encodeURIComponent(key) + '=' + encodeURIComponent(params[key]))
				.join('&');

			window.location.href = `${baseUrl}?${queryString}`;
			},
			getStock(){
				this.searchType = this.selectedSearchType.value;
				let url = '';
				let parameters = {};

				if(this.searchType == 'current'){
					url = '/get_current_stock_inventory';
				} else {
					url = '/get_total_stock_inventory';
					parameters.date = this.date;
				}
				
				this.selectionText = "";

				if(this.searchType == 'category' && this.selectedCategory == null){
					alert('Select a category');
					return;
				} else if(this.searchType == 'category' && this.selectedCategory != null) {
					parameters.categoryId = this.selectedCategory.id;
					this.selectionText = "Category: " + this.selectedCategory.name;
				}
				
				if(this.searchType == 'product' && this.selectedProduct == null){
					alert('Select a product');
					return;
				} else if(this.searchType == 'product' && this.selectedProduct != null) {
					parameters.productId = this.selectedProduct.id;
					this.selectionText = "product: " + this.selectedProduct.display_text;
				}

				


				axios.post(url, parameters).then(res => {
					if(this.searchType == 'current'){
						this.stock = res.data.stock.filter((pro)=> pro.current_quantity != 0);
					}else{
						this.stock = res.data.stock;
					}
					this.totalStockValue = res.data.totalValue;
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
			getCompanyInfo(){
                axios.get('/get_companies').then(res=>{
                    this.campany = res.data;
                })
            },
			onChangeSearchType(){
				if(this.selectedSearchType.value == 'category' && this.categories.length == 0){
					this.getCategories();
				}  else if(this.selectedSearchType.value == 'product' && this.products.length == 0){
					this.getProducts();
				}
			},
			getCategories(){
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
			getProducts(){
				axios.post('/get_products', {isService: 'false'}).then(res => {
					this.products =  res.data;
				})
			},
			
			async print(){
				let reportContent = `
					<div class="container-fluid">
						<h4 style="text-align:center">${this.selectedSearchType.text} Report</h4 style="text-align:center">
						<h6 style="text-align:center">${this.selectionText}</h6>
					</div>
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#stockContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}, left=0, top=0`);
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

				reportWindow.document.body.innerHTML += reportContent;

				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}

}
</script>