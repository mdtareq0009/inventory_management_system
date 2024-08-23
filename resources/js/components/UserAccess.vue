<style scoped>
* {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
}
h2{
    font-size: 16px;
    font-weight: bold;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    text-transform: uppercase;
    padding: 5px;
}

ul {
    list-style: none;
    margin-left: 17px;
}
</style>
<template>
    <div>
        <div class="row">
            <div class="col-md-12 text-center">
                <div>
                    <h2>User Access</h2>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-12">
                <input type="checkbox" @click="checkAll" id="selectAll"> <strong style="font-size: 16px;">Select All</strong>
            </div>
        </div>
        <div class="row" id="accessRow">
           
            <div class="col-md-3">
                <div class="group">
                    <input type="checkbox" id="inventory" class="group-head" @click="onClickGroupHeads"> <strong>Inventory Module</strong>
                    <ul ref="inventory">
                        <li><input type="checkbox" class="access" value="purchase_entry" v-model="access"> Purchase Entry</li>
                        <li><input type="checkbox" class="access" value="sale_entry" v-model="access"> Sales Entry</li>
                        <li><input type="checkbox" class="access" value="purcahse_return_entry" v-model="access"> Purchase Return Entry</li>
                        <li><input type="checkbox" class="access" value="sale_return_entry" v-model="access"> Sale Reuturn Entry</li>
                    </ul>
                </div>

                <div class="group">
                    <input type="checkbox" id="admin" class="group-head" @click="onClickGroupHeads"> <strong>Administrator</strong>
                    <ul ref="admin">
                        <li><input type="checkbox" class="access" value="category_entry" v-model="access"> Category Entry</li>
                        <li><input type="checkbox" class="access" value="unit_entry" v-model="access"> Unit Entry</li>
                        <li><input type="checkbox" class="access" value="customer_entry" v-model="access"> Customer Entry</li>
                        <li><input type="checkbox" class="access" value="supplier_entry" v-model="access"> Supplier Entry</li>
                        <li><input type="checkbox" class="access" value="product_entry" v-model="access"> Product Entry</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
              
                <div class="group">
                    <input type="checkbox" id="reports" class="group-head" @click="onClickGroupHeads"> <strong>Records And Reports</strong>
                    <ul ref="reports">
                        <li><input type="checkbox" class="access" value="purchase_record" v-model="access"> Purchase Record</li>
                        <li><input type="checkbox" class="access" value="purchase_return_record" v-model="access"> Purchase Return Record </li>
                        <li><input type="checkbox" class="access" value="sale_record" v-model="access"> Sale Record</li>
                        <li><input type="checkbox" class="access" value="sale_return_record" v-model="access"> Sale Return Record</li>
                        <li><input type="checkbox" class="access" value="stock" v-model="access"> Stock</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button class="btn btn-success" @click="addUserAccess">Save</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user_id'],
    data () {
        return {
            access: [],
        }
    },
    mounted(){
        let accessCheckboxes = document.querySelectorAll('.access');
        accessCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('click', () => {
                this.makeChecked();
            })
        })
    },
    async created(){
        await axios.get('/get_user_access/' + this.user_id).then(res => {
            let r = res.data;
            if(r != ''){
                this.access = JSON.parse(r);
            }
        })
        this.makeChecked();
    },
    methods: {
        makeChecked(){
            let groups = document.querySelectorAll('.group');
            groups.forEach(group => {
                let groupHead = group.querySelector('.group-head');
                let accessCheckboxes = group.querySelectorAll('ul li input').length;
                let checkedAccessCheckBoxes = group.querySelectorAll('ul li input:checked').length;
                if(accessCheckboxes == checkedAccessCheckBoxes){
                    groupHead.checked = true;
                } else {
                    groupHead.checked = false;
                }
            })

            let selectAllCheckbox = document.querySelector('#selectAll');
            let totalAccessCheckboxes = document.querySelectorAll('.access').length;
            let totalCheckedAccessCheckBoxes = document.querySelectorAll('.access:checked').length;

            if(totalAccessCheckboxes == totalCheckedAccessCheckBoxes){
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.checked = false;
            }
        },
        async onClickGroupHeads() {
            let groupHead = event.target;
            let ul = groupHead.parentNode.querySelector('ul');
            let accessCheckboxes = ul.querySelectorAll('li input');

            if(groupHead.checked){
                accessCheckboxes.forEach(checkbox => {
                    this.access.push(checkbox.value);
                })
            } else {
                accessCheckboxes.forEach(checkbox => {
                    let ind = this.access.findIndex(a => a == checkbox.value);
                    this.access.splice(ind, 1);
                })
            }
            this.access = this.access.filter((v, i, a) => a.indexOf(v) === i);
            await new Promise(r => setTimeout(r, 200));
            this.makeChecked();
        },
        async checkAll(){
            if(event.target.checked){
                let accessCheckboxes = document.querySelectorAll('.access');
                accessCheckboxes.forEach(checkbox => {
                    this.access.push(checkbox.value)
                })
            } else {
                this.access = [];
            }
            this.access = this.access.filter((v, i, a) => a.indexOf(v) === i);
            await new Promise(r => setTimeout(r, 200));
            this.makeChecked();
        },
        addUserAccess(){
            let data = {
                user_id: this.user_id,
                access: this.access
            }
            axios.post('/add_user_access', data).then(res => {
                let r = res.data;
                alert(r.message);
            })
        }
    }
}
</script>