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
                        <h3 class="card-title">Service Area Product List</h3>

                        <div class="card-tools">
                            <button type="button" @click="sendToBulkProductPage" class="btn btn-primary"> <i class="fa fa-cubes"></i> Assign products</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                             <data-table
                                :classes = "tableClasses"
                                :url="getDataTableUrl()"
                                :columns="columns"

                                :per-page="perPage">
                            </data-table>
                    </div>
                    <!-- /.card-body -->
                    <div v-if="isLoading" class="overlay dark">
                        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div><!-- /.row -->


        <!-- Modal -->
        <div class="modal fade" id="vendorProductNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-vendorProduct"></i> {{ editMode ? 'Edit' : 'Add'}} Service Area Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateVendorProduct() : createVendorProduct()">
                        <div class="modal-body">
                            <div class="form-group row">
                               
                                <label for="staticEmail" class="col-sm-4 col-form-label">Select Product</label>
                                <div class="col-sm-8 col-xs-8">
                                    <span v-if="editMode==false">
                                        <BootstrapSelect v-if="filteredProducts.length>0" @optionSelected="setFormProductId"  :data="products" val="id" text="name" :selected="form.product_id" :class="{ 'is-invalid': form.errors.has('product_id') }" />
                                    </span>

                                        <label class="col-form-label" v-if="editMode==true">
                                            {{ form.name}}
                                        </label>

                                    <has-error :form="form" field="product_id"></has-error>
                                </div>

                            </div>

                            <div class="form-group row">
                               
                                <label for="staticEmail" class="col-sm-4 col-form-label">Package</label>
                                <div class="col-sm-8 col-xs-8">
                                    <span v-if="editMode==false">
                                        <BootstrapSelect v-if="filteredProducts.length>0" @optionSelected="setFormProductId"  :data="products" val="id" text="name" :selected="form.product_id" :class="{ 'is-invalid': form.errors.has('product_id') }" />
                                    </span>

                                        <label class="col-form-label" v-if="editMode==true">
                                            {{ form.package_name}}
                                        </label>

                                    <has-error :form="form" field="product_id"></has-error>
                                </div>

                            </div>

                            

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Selling Price</label>
                                <div class="col-sm-8">
                                    <input type="number"  step=".01" class="form-control"  min="0" v-model="form.sell_price" placeholder="Selling Price" :class="{ 'is-invalid': form.errors.has('sell_price') }">
                                    <has-error :form="form" field="sell_price"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">MRP</label>
                                <div class="col-sm-8">
                                    <input type="number"  step=".01" class="form-control"  min="0" v-model="form.mrp" placeholder="Selling Price" :class="{ 'is-invalid': form.errors.has('mrp') }">
                                    <has-error :form="form" field="mrp"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Delivery Charges</label>
                                <div class="col-sm-8">
                                    <input type="number"  step="1" class="form-control" min="0"  v-model="form.delivery_charges" placeholder="Delivery in days" :class="{ 'is-invalid': form.errors.has('delivery_charges') }">
                                    <has-error :form="form" field="delivery_charges"></has-error>
                                </div>
                            </div>
                           
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Delivery in days</label>
                                <div class="col-sm-8">
                                    <input type="number"  step="1" class="form-control" min="0"  v-model="form.delivery_in_days" placeholder="Delivery in days" :class="{ 'is-invalid': form.errors.has('delivery_in_days') }">
                                    <has-error :form="form" field="delivery_in_days"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Replacement in days</label>
                                <div class="col-sm-8">
                                    <input type="number"  step="1" class="form-control" min="0"  v-model="form.replacement_in_days" placeholder="Replacement in days" :class="{ 'is-invalid': form.errors.has('replacement_in_days') }">
                                    <has-error :form="form" field="replacement_in_days"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Available Finance</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="form.available_finance"  :class="{ 'is-invalid': form.errors.available_finance }" >
                                        <option v-for="option in f_options" :valve=option>{{option}}</option>
                                    </select>
                                    <has-error :form="form" field="available_finance"></has-error>
                                </div>
                            </div>
                            

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

         <!-- Modal -->
        <div class="modal fade" id="vendorProductBulk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-vendorProduct"></i> {{ editMode ? 'Edit' : 'Add'}} VendorProduct</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createVendorProductBulk">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-6 col-xs-4 col-form-label">Select Sub Category</label>
                                <div class="col-sm-6 col-xs-8">
                                    <BootstrapSelect v-if="filteredSubCategories.length>0" @optionSelected="filterProducts"  :data="filteredSubCategories" val="id" text="name" v-once />
                                </div>
                                <label for="staticEmail" class="col-sm-6 col-xs-4 col-form-label">Select Product</label>
                                <div class="col-sm-6 col-xs-8">
                                    <BootstrapSelect v-if="filteredProducts.length>0 && showProductsList" @optionSelected="setSelectedProduct"  :data="filteredProducts" val="id" text="name" v-once />
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-primary" @click="addBulkProduct"> <span> <i class="fa fa-plus"></i></span> Add Selected</button>

                                </div>
                                <div class="col-sm-5">
                                    <button type="button" class="btn  btn-warning" @click="addAllFilteredProducts"> <span> <i class="fa fa-plus"></i></span> Add All List Products </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-responsive table-bordered">
                                        <thead>
                                            <th>Product</th>
                                            <th>MRP</th>
                                            <th>Selling Price</th>
                                            <th>Cost Price</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) of bulkForm.items" :key="index">
                                                <td>
                                                    <input class="form-control" readonly :class="{ 'is-invalid': bulkForm.errors.has('items.'+index+'.product_id')   }" :value="getProductFromId(item.product_id)">
                                                    <has-error :form="bulkForm" :field="'items.'+index+'.product_id'"></has-error>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" min="0" v-model="bulkForm.items[index].mrp" :class="{ 'is-invalid': bulkForm.errors.has('items.'+index+'.mrp') }">
                                                    <has-error :form="bulkForm" :field="'items.'+index+'.mrp'" ></has-error>
                                                    </td>
                                                <td>
                                                    <input type="number" class="form-control" min="0" v-model="bulkForm.items[index].price" :class="{ 'is-invalid': bulkForm.errors.has('items.'+index+'.price') }" >
                                                    <has-error :form="bulkForm" :field="'items.'+index+'.price'"></has-error>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" min="0" v-model="bulkForm.items[index].cost_price" :class="{ 'is-invalid': bulkForm.errors.has('items.'+index+'.cost_price') }">
                                                    <has-error :form="bulkForm" :field="'items.'+index+'.cost_price'" ></has-error>
                                                </td>                                   <td>
                                                    <button class="btn btn-danger" @click="deleteProductFromList(index)" type="button">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import { log } from 'util';
    import $ from "jquery";
    export default {
        components:{
            CImage,
            CBtn,
            CToggle
        },
        data() {
            return {
                perPage: ['10', '25', '50', '100', '250', '500'],
                f_options: ['Bajaj', 'IDFC', 'Other'],
                columns: [
                    
                    {
                        label: 'Name',
                        name: 'name',
                        filterable: true,
                    },
                    {
                        label: 'Package',
                        name: 'package_name',
                        filterable: true,
                    },
                    {
                        label: 'MRP',
                        name: 'mrp',
                        filterable: true,
                    },
                    {
                        label: 'Sale Price',
                        name: 'sell_price',
                        filterable: true,
                    },
                    {
                        label: 'Delivery charge',
                        name: 'delivery_charges',
                        filterable: true,
                    },
                    {
                        label: 'Delivery days',
                        name: 'delivery_in_days',
                        filterable: true,
                    },
                    {
                        label: 'Replacement days',
                        name: 'replacement_in_days',
                        filterable: true,
                    },
                    {
                        label: 'Available finance',
                        name: 'available_finance',
                        filterable: true,
                    },
                    {
                        label: 'Image',
                        component: CImage,
                        filterable: false,
                        meta:{
                            url : '/public/uploads/images/admin_product/',
                        }
                    },
                    {
                        label: 'On/Off',
                        component: CToggle,
                        filterable: false,
                        meta:{
                            field: 'is_active',
                            action: this.changeActiveStatus

                        }
                    },
                    {
                        label: 'Actions',
                        component: CBtn,
                        filterable: false,
                        meta:{
                            multiple: true,
                            btnList :[
                                {
                                    label: 'Edit',
                                    classes : 'btn-primary',
                                    action: this.editForm,
                                    additionalAttributes : {'data-toggle':"modal", 'data-target':"#vendorProductNew" },
                                },
                                {
                                    label: 'Delete',
                                    classes : 'btn-danger',
                                    action: this.deleteVendorProduct,
                                },
                            ]

                        }
                    },
                ],
                tableClasses:{
                    "table-container": {
                        "table-responsive": true,
                    },
                    "table": {
                        "table": true,
                        "table-striped": true,
                        "table-dark": false,
                    },
                },
                form : new Form( {
                    id: null,
                    product_id: null,
                    package_id: null,
                    service_area_id: null,
                    sell_price: null,
                    delivery_charges: '',
                    delivery_in_days: '',
                    replacement_in_days: '',
                    available_finance:null,
                    mrp: null,
                    is_active:true,
                    name:'',
                    package_name:'',
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
                showProductsList: false,
                available_finance:null,
                replacement_in_days: '',
                delivery_in_days: '',
                delivery_charges: '',

            }
        },
        methods :{
            createVendorProduct(){
                this.$Progress.start();
                this.form.post('/api/service_area_products').then( ()=>{
                    // Fire.$emit('LoadVendorProduct');
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
                this.form.post('/api/service_area_products_update/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadVendorProduct');
                    $('#vendorProductNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: ' Updated successfully'
                    });
                    setTimeout(()=>{

                    window.location.reload();
                    }, 1000);
                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadVendorProductList() {
                this.isLoading = true;
                axios.get("/api/service_area_products/"+this.vendorId).then( ({data}) => {
                    // console.log(data);
                   
                    this.vendorProducts = data;
                    this.isLoading = false;
                }).catch(err =>{
                    console.log(err);
                    this.isLoading = false;
                });
            },
            checkSelected(subCategory){
                return subCategory == this.form.available_finance;
                
            },
            deleteVendorProduct(vp){
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
                        axios.delete('/api/service_area_products/'+vp.id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'VendorProduct has been deleted.',
                            'success'
                            );
                            this.isLoading = false;
                            Fire.$emit('LoadVendorProduct');
                            setTimeout(()=>{

                    window.location.reload();
                    }, 1000);
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
                console.log(data);
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
                this.$Progress.start();
                this.bulkForm.post('/api/service_area_products_bulk/'+this.vendorId).then( ()=>{
                    $('#vendorProductBulk').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Multiple Vendor Product Created successfully'
                    });
                    setTimeout( ()=>{
                        Fire.$emit('LoadVendorProduct');
                    },500);
                    this.bulkForm.items = [];
                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });

                this.loadVendorProductList();
            },
            loadProductList() {
                axios.get("/api/product_all").then( ({ data }) => (this.products = data) );
            },
            setSelectedProduct(id){
                this.selectedProduct = id;
            },
            setFormProductId(id){
                this.form.product_id = id;
            },
            addBulkProduct(){
                let item = {
                    product_id: null,
                    service_area_id: null,
                    price: 1,
                    mrp: 1,
                    cost_price: 1,
                    is_featured: false,
                };
                if(this.checkIfProductExist() || !this.selectedProduct){
                    return;
                }

                item.product_id = this.selectedProduct;
                item.service_area_id = this.vendorId;
                this.bulkForm.items.push(item);
                // console.log(this.bulkForm.items);
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
                        return this.products[x].name;
                    }
                }
            },
            getPackageFromId(id){
                for(let x=0; x < this.products.length; x++){
                    if(this.products[x].id == id){
                        return this.products[x].name;
                    }
                }
            },
            filterProducts(subCategoryId){
                this.filteredProducts =[];
                this.showProductsList = false;
                console.log('SC', subCategoryId);
                for(let x=0; x < this.products.length; x++){
                    if(this.products[x].sub_category_id == subCategoryId)
                        this.filteredProducts.push(this.products[x]);
                }
                setTimeout(()=>{
                    this.showProductsList = true;
                },500);
            },
            filterSubCategories(){
                this.filteredSubCategories =[];
                for(let x=0; x < this.subCategories.length; x++){
                    if(this.subCategories[x].category_id == this.vendor.category_id || this.vendor.category_id == null)
                        this.filteredSubCategories.push(this.subCategories[x]);
                }
            },
            addAllFilteredProducts(){
                for(let x=0; x < this.filteredProducts.length; x++){
                    this.selectedProduct = this.filteredProducts[x].id;
                    this.addBulkProduct();
                }
            },
            loadSubCategoryList() {
                axios.get("/api/subCategory").then( ({ data }) => {this.subCategories = data; this.filterSubCategories();} );
            },
            sendToBulkProductPage(){
                return this.$router.push('/service_area_products/'+this.vendorId +'/bulk_assign');
            },
            getDataTableUrl(){
                return '/api/service_area_products/datatable/'+this.vendorId;
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.vendorId = this.$route.params.vendor;
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
