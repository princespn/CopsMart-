<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>VendorProduct</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">VendorProduct List</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <!-- <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#vendorProductNew"> <i class="fa fa-plus"></i> Add New</button> -->
                            <button type="button" @click="sendToBulkProductPage" class="btn btn-primary"> <i class="fa fa-cubes"></i> Assign products</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table table-responsive table-bordered p-3">

                        <!-- <table class="table table-hover">
                            <tr>
                                <th>Sr. No</th>
                                <th>Name</th>
                                <th>On / Off</th>
                                <th>MRP</th>
                                <th>Selling Price</th>
                                <th>Ocean Price</th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="(vendorProduct, index) in vendorProducts" :key="vendorProduct.id">
                                <td>{{ (1+index)}}</td>
                                <td>{{ vendorProduct.product != null ? vendorProduct.product.name  : '[Product Deleted]'}}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+vendorProduct.id" :checked="vendorProduct.is_active" @change="changeActiveStatus(vendorProduct)">
                                        <label class="custom-control-label" :for="'onOff'+vendorProduct.id" ></label>
                                    </div>
                                </td>
                                <td>{{ vendorProduct.mrp == null ? 'NA' : vendorProduct.mrp  }}</td>
                                <td>{{ vendorProduct.price  }}</td>
                                <td>{{ vendorProduct.cost_price  }}</td>
                                <td>
                                    <button @click="editForm(vendorProduct)"  data-toggle="modal" data-target="#vendorProductNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteVendorProduct(vendorProduct.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </table> -->

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
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-vendorProduct"></i> {{ editMode ? 'Edit' : 'Add'}} VendorProduct</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateVendorProduct() : createVendorProduct()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <!-- <label for="staticEmail" class="col-sm-6 col-xs-4 col-form-label">Select Sub Category</label>
                                <div class="col-sm-6 col-xs-8">
                                    <BootstrapSelect v-if="filteredSubCategories.length>0" @optionSelected="filterProducts"  :data="filteredSubCategories" val="id" text="name" v-once />
                                </div> -->
                                <label for="staticEmail" class="col-sm-4 col-form-label">Select Product</label>
                                <div class="col-sm-8 col-xs-8">
                                    <span v-if="editMode==false">
                                        <BootstrapSelect v-if="filteredProducts.length>0" @optionSelected="setFormProductId"  :data="products" val="id" text="name" :selected="form.product_id" :class="{ 'is-invalid': form.errors.has('product_id') }" />
                                    </span>

                                        <label class="col-form-label" v-if="editMode==true">
                                            {{ getProductFromId(form.product_id)}}
                                        </label>

                                    <has-error :form="form" field="product_id"></has-error>
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Selling Price</label>
                                <div class="col-sm-8">
                                    <input type="number"  step=".01" class="form-control"  min="0" v-model="form.price" placeholder="Selling Price" :class="{ 'is-invalid': form.errors.has('price') }">
                                    <has-error :form="form" field="price"></has-error>
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
                                <label for="staticEmail" class="col-sm-4 col-form-label">Cost Price</label>
                                <div class="col-sm-8">
                                    <input type="number"  step=".01" class="form-control" min="0"  v-model="form.cost_price" placeholder="Cost Price" :class="{ 'is-invalid': form.errors.has('cost_price') }">
                                    <has-error :form="form" field="cost_price"></has-error>
                                </div>
                            </div>

                            
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Offer Price</label>
                                <div class="col-sm-8">
                                    <input type="text"  class="form-control"  v-model="form.offer_price" placeholder="Offer Price" :class="{ 'is-invalid': form.errors.hasoffer_price }">
                                    <has-error :form="form" field="offer_price"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Offer Status</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="form.offer_status" placeholder="Select Status" :class="{ 'is-invalid': form.errors.hasoffer_status }" >
                                        <option :value="1" :selected="checkSelected(1)" >Active</option>
                                        <option :value="0" :selected="checkSelected(1)" >Inactive</option>
                                    </select>
                                    <has-error :form="form" field="offer_status"></has-error>
                                </div>
                            </div>

                             <div class="form-group row">
                                <label for="staticSize" class="col-sm-4 col-form-label">Available Size</label>
                                <div class="col-sm-8">
                                    <input type="text"   class="form-control"   v-model="form.size" placeholder="Available Size" >
                                    <!-- <has-error :form="form" field="size"></has-error> -->
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Daily Needs</label>
                                <div class="col-sm-8">
                                    <input type="checkbox" id="daily_needs" v-model="form.daily_needs" value="1">
                                    <has-error :form="form" field="daily_needs"></has-error>
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
                                                    <!-- <has-error :form="bulkForm" :field="'items.'+index+'.vendor_id'"></has-error> -->
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
        <orders />
    </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import GetOrder  from './GetOrder';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import { log } from 'util';
    import $ from "jquery";
    export default {
        components:{
            CImage,
            CBtn,
            CToggle,
            'orders': GetOrder,

        },
        data() {
            return {
                perPage: ['10', '25', '50', '100', '250', '500'],
                columns: [
                    // {
                    //     label: 'ID',
                    //     name: 'id',
                    //     filterable: true,
                    // },
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
                        label: 'Price',
                        name: 'price',
                        filterable: true,
                    },
                    {
                        label: 'Cost Price',
                        name: 'cost_price',
                        filterable: true,
                    },
                    // {
                    //     label: 'Offer Price',
                    //     name: 'offer_price',
                    //     filterable: true,
                    // },
                    // {
                    //     label: 'Daily Needs',
                    //     name: 'daily_needs',
                    //     filterable: true,
                    // },
                     {
                        label: 'Size',
                        name: 'size',
                        filterable: true,
                    },
                    {
                        label: 'Image',
                        component: CImage,
                        filterable: false,
                        meta:{
                            url : '/public/uploads/images/product/',
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
                                    classes : 'btn-primary btn-sm',
                                    action: this.editForm,
                                    additionalAttributes : {'data-toggle':"modal", 'data-target':"#vendorProductNew" },
                                },
                                {
                                    label: 'Delete',
                                    classes : 'btn-danger btn-sm',
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
                    vendor_id: null,
                    price: null,
                    offer_price: '',
                    offer_status: '',
                    daily_needs: '',
                    size: null,
                    mrp: null,
                    cost_price: null,
                    is_featured: false,
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
                showProductsList: false,
                offer_price: '',
                offer_status: '',
                daily_needs: '',

            }
        },
        methods :{
            createVendorProduct(){
                this.$Progress.start();
                this.form.post('/api/vendor_product').then( ()=>{
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
                this.form.put('/api/vendor_product/' + this.form.id).then( ()=>{
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
                axios.get("/api/vendor_products/"+this.vendorId).then( ({data}) => {
                    this.vendorProducts = data;
                    this.isLoading = false;
                }).catch(err =>{
                    console.log(err);
                    this.isLoading = false;
                });
            },
            checkSelected(subCategory){
                
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
                        axios.delete('/api/vendor_product/'+vp.id).then(() => {
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
                            `VendorProduct can't  be deleted.`,
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
                this.$Progress.start();
                this.bulkForm.post('/api/vendor_product_bulk/'+this.vendorId).then( ()=>{
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
                    vendor_id: null,
                    price: 1,
                    mrp: 1,
                    cost_price: 1,
                    is_featured: false,
                };
                if(this.checkIfProductExist() || !this.selectedProduct){
                    return;
                }

                item.product_id = this.selectedProduct;
                item.vendor_id = this.vendorId;
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
                return this.$router.push('/vendor_products/'+this.vendorId +'/bulk_assign');
            },
            getDataTableUrl(){
                return '/api/vendor_products/datatable/'+this.vendorId;
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.vendorId = this.$route.params.vendor;
            // this.loadVendorProductList();

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
