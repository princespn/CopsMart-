<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Service Area Product</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Service Area Add Bulk Product | {{ vendor != null ? vendor.name : ''}}</h3>

                        <div class="card-tools">
                           

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="col-xs-12">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-md-2 col-sm-12 col-xs-12 col-form-label">Select Category</label>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <select v-if="filteredSubCategories.length>0" @change="filterProducts" class="form-control">
                                            <option value="">All</option>
                                            <option  v-for="sc in filteredSubCategories" :key="sc.id" :value="sc.id">{{sc.name}}</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" class="btn  btn-warning" @click="addAllFilteredProducts"> <span> <i class="fa fa-plus"></i></span> Add All </button>
                            </div>

                            </div>
                        </div>


                        <div class="row">


                        </div>



                        <div class="row">

                            <form @submit.prevent="createVendorProductBulk">
                                <div class="col-sm-12">
                                    <table class="table table-responsive table-bordered col-sm-12">
                                        <thead>
                                            <th>
                                                <input type="checkbox" v-model="all" @click="mainCheckBox">
                                            </th>
                                            <th>Product</th>
                                            <th>Package</th>
                                            <th>MRP</th>
                                            <th>Selling Price</th>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in bulkForm.items" :key="index">
                                                <td>
                                                    <input type="checkbox" v-model.lazy="bulkForm.items[index].selected" :value="true" >
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly :class="{ 'is-invalid': bulkForm.errors.has('items.'+index+'.product_id')   }" :value="item.name">
                                                    <has-error :form="bulkForm" :field="'items.'+index+'.product_id'"></has-error>
                                                    <!-- <has-error :form="bulkForm" :field="'items.'+index+'.vendor_id'"></has-error> -->
                                                </td>
                                                <td>
                                                    {{item.package_name}}
                                                </td>
                                                <td>
                                                    <input type="number" min="0" class="form-control" v-model.lazy="bulkForm.items[index].mrp" :class="{ 'is-invalid': bulkForm.errors.has('items.'+index+'.mrp') }">
                                                    <has-error :form="bulkForm" :field="'items.'+index+'.mrp'" ></has-error>
                                                    </td>
                                                <td>
                                                    <input type="number" min="0" class="form-control" v-model.lazy="bulkForm.items[index].sell_price" :class="{ 'is-invalid': bulkForm.errors.has('items.'+index+'.sell_price') }" >
                                                    <has-error :form="bulkForm" :field="'items.'+index+'.sell_price'" ></has-error>
                                                </td>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-sm-12">

                                    <button type="submit" class="btn btn-primary">Save </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div v-if="isLoading" class="overlay dark">
                        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div><!-- /.row -->




    </div>
</template>

<script>
    import { moment } from 'moment';
    import $ from "jquery";
    export default {
        data() {
            return {
                form : new Form( {
                    id: null,
                    package_id: null,
                    product_id: null,
                    admin_service_area_id: null,
                    sell_price: null,
                    mrp: null,
                    is_active:true,
                }),
                bulkForm : new Form({
                    items:[]
                }),
                vendorProducts: [],
                selectedProduct: null,
                products: [],
                filteredProducts:[],
                vendor:{},
                editMode: false,
                vendorId: null,
                isLoading: false,
                filteredSubCategories:[],
                subCategories: [],
                all: true,

            }
        },
        methods :{
            createVendorProduct(){
                this.$Progress.start();
                this.form.post('/api/service_area_products').then( ()=>{
                    Fire.$emit('LoadVendorProduct');
                    $('#vendorProductNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'VendorProduct Created successfully'
                    });
                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });

                // this.loadVendorProductList();
            },
            updateVendorProduct(){
                this.$Progress.start();
                this.form.put('/api/service_area_products/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadVendorProduct');
                    $('#vendorProductNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadVendorProductList() {
                this.isLoading = true;
                axios.get("/api/service_area_products/"+this.vendorId).then( ({data}) => {
                    // console.log(data);
                    // data = JSON.stringify(data);
                    // console.log(data);
                    this.vendorProducts = data;
                    this.isLoading = false;
                }).catch(err =>{
                    console.log(err);
                    this.isLoading = false;
                });
            },
            deleteVendorProduct(id){
                // sweet alert modal
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        // send delete request
                        console.log(result)
                    if(result.value){
                        this.isLoading = true;
                        this.form.delete('/api/service_area_products/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'VendorProduct has been deleted.',
                            'success'
                            );
                            this.isLoading = false;
                            Fire.$emit('LoadVendorProduct');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'VendorProduct can not  be deleted.',
                            'danger'
                            );
                            this.isLoading = false;
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.form.vendor_id = this.vendorId;
                this.editMode = false;
            },
            editForm(data){
                this.form.reset();
                this.form.fill(data);
                this.editMode = true;
            },
            changeActiveStatus(vendorProduct){
                this.editForm(vendorProduct);
                this.form.is_active = !this.form.is_active ;
                this.updateVendorProduct();
            },
            createVendorProductBulk(){
                this.removeNonSelected();
                this.$Progress.start();
                this.bulkForm.post('/api/service_area_products_bulk/'+this.vendorId).then( ()=>{
                    Fire.$emit('LoadVendorProduct');
                    $('#vendorProductBulk').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Multiple Vendor Product Created successfully'
                    });
                    this.bulkForm.items = [];
                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });

                this.loadVendorProductList();
            },
            loadProductList() {
                axios.get("/api/admin_product_all").then( ({ data }) => {this.products = data; this.bulkForm.items = this.products;} );
            },
            setSelectedProduct(id){
                this.selectedProduct = id;
            },
            setFormProductId(id){
                this.form.product_id = id;
            },
            addBulkProduct(product){
               
                let item = {
                    admin_service_area_id: null,
                    product_id: null,
                    name: null,
                    package: null,
                    package_id: null,
                    category_id: null,
                    sell_price: 1,
                    mrp: 1,
                    selected:true,
                };

                item.product_id = product.id;
                item.admin_service_area_id = this.vendorId;
                item.name = product.name;
                item.package = product.package_name;
                item.package_id = product.package_id;
                item.category_id = product.category_id;
                item.sell_price = product.sell_price;
                item.mrp = product.mrp;
                this.bulkForm.items.push(item);
                 console.log(this.bulkForm.items);
            },
            checkIfProductExist(){
                for(let x=0; x < this.bulkForm.items.length; x++){
                    if(this.bulkForm.items[x].product_id == this.selectedProduct)
                        return true;
                }
                return false;
            },
            deleteProductFromList(index){
                this.bulkForm.items.splice(index,1);
            },
            getProductFromId(id){
                for(let x=0; x < this.products.length; x++){
                    if(this.products[x].id == id){
                        return this.products[x];
                    }
                }
            },
            filterProducts(event){
                //this.filteredProducts =[];
                const subCategoryId = event.target.value;

                
                if(subCategoryId == null || subCategoryId == ''){
                    this.filterProductsofAllSubCategories();
                    return;
                }
                //console.log('SC', this.products);
                this.bulkForm.items = [];
                for(let x=0; x < this.products.length; x++){
                    if(this.products[x].category_id == subCategoryId)
                        this.bulkForm.items.push(this.products[x]);
                }

               // alert(this.filteredProducts.length);
            },
            filterProductsofAllSubCategories(){
                this.filteredProducts =[];
                for(let y=0; y < this.filteredSubCategories.length; y++){
                    for(let x=0; x < this.products.length; x++){
                        if(this.products[x].sub_category_id == this.filteredSubCategories[y].id)
                            this.filteredProducts.push(this.products[x]);
                    }
                }
            },
            filterSubCategories(){
                this.filteredSubCategories =[];
                for(let x=0; x < this.subCategories.length; x++){
                    //if(this.subCategories[x].category_id == this.vendor.category_id || this.vendor.category_id == null)
                        this.filteredSubCategories.push(this.subCategories[x]);
                }
            },
            addAllFilteredProducts(){
                this.bulkForm.items = [];
               // alert(this.filteredProducts.length);
                for(let x=0; x < this.filteredProducts.length; x++){
                    //this.selectedProduct = .id;
                    this.addBulkProduct(this.filteredProducts[x]);
                }
            },
            loadSubCategoryList() {
                axios.get("/api/admin_pro_category").then( ({ data }) => {this.subCategories = data.pro_category; this.filterSubCategories();} );
                
            },
            itemCheckBox(index){
                this.bulkForm.items[index].selected = !this.bulkForm.items[index].selected;
            },
            mainCheckBox(){
                for(let x=0; x < this.bulkForm.items.length; x++){
                    this.bulkForm.items[x].selected = !this.all;
                }
            },
            vendorProductLink(){
                return '/service_area_products/'+this.vendorId;
            },
            removeNonSelected(){
                const newItems=[];
                for(let x=0; x < this.bulkForm.items.length; x++){
                   if( this.bulkForm.items[x].selected == true){
                       newItems.push(this.bulkForm.items[x]);
                   }
                }
                this.bulkForm.items = newItems;
            },
            loadVendor() {
                axios.get("/api/admin_service_area/"+this.vendorId).then( ({ data }) => {this.vendor = data; this.filterSubCategories();} );
            },
        },
        mounted() {
            console.log('Component mounted.');
            this.vendorId = this.$route.params.vendor;
            this.loadVendor();
            this.loadVendorProductList();

        },
        created(){
            this.loadProductList();
            this.loadSubCategoryList();
            Fire.$on('LoadVendorProduct', () => this.loadVendorProductList() );
            Fire.$on('LoadProduct', () => this.loadProductList() );
        },
        updated(){
            // $(this.$refs.select).selectpicker('refresh')
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
