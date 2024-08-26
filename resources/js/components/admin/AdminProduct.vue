<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Admin Product</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Admin Product List</h3>

                        <div class="card-tools">
                           
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#productNew"> <i class="fa fa-product-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        

                        <data-table
                            :classes = "tableClasses"
                            url="/api/admin_product_datatable"
                            :columns="columns"

                            :per-page="perPage">
                        </data-table>
                        <!-- <DataTable v-if="products.length>0"  :data="products" val="id" text="name" v-once /> -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div><!-- /.row -->


        <!-- Modal -->
        <div class="modal fade" id="productNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-product"></i> {{ editMode ? 'Edit' : 'Add'}} Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateProduct() : createProduct()">
                        <div class="modal-body" >
                            
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text"  class="form-control"  v-model="iform.name" placeholder="Product Name" :class="{ 'is-invalid': errors.name }">
                                    <span class="label label-danger" v-if="errors.name">{{errors.name}} </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Image</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" @change="fileSelected" multiple id="staticEmail" :class="{ 'is-invalid': errors.image }">
                                    <span class="label label-danger" v-if="errors.image">{{errors.image}} </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Category</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="iform.category_id" placeholder="Select Category" :class="{ 'is-invalid': errors.category_id }" v-on:change="getSuperSubCategory(this)">
                                        <option v-for="cat of Categories" :key="cat.id" :value="cat.id" :selected="checkSelected(cat)" >{{cat.name}}</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.category_id">{{errors.category_id}} </span>
                                </div>
                            </div>
                            

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Sub Category</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="iform.sub_category_id" placeholder="Select Category" :class="{ 'is-invalid': errors.sub_category_id }">
                                        <option v-for="subCategory of supSubCategories" :key="subCategory.id" :value="subCategory.id" :selected="checkSelected(subCategory)" >{{subCategory.name}}</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.sub_category_id">{{errors.sub_category_id}} </span>
                                </div>
                            </div>

                            

                            <div class="form-group row" v-if="!editMode">
                                <label class="col-sm-4 col-form-label">Packages</label>
                                <div class="col-sm-8">
                                    <v-select multiple :closeOnSelect="true" v-model="selected" :options="options" label="name" />
                                   
                                    <span class="label label-danger" v-if="errors.package">{{errors.package}} </span>
                                </div>
                            </div>
                            <div class="form-group row" v-if="editMode">
                                <label class="col-sm-4 col-form-label">Packages</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="iform.package" placeholder="Select Category" :class="{ 'is-invalid': errors.package }"  id="package_id">
                                        <option v-for="pack of product_packages" :key="pack.id" :value="pack.id" :selected="checkSelected(pack)" >{{pack.name}}</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.package">{{errors.package}} </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Description</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control"  v-model="iform.description" :class="{ 'is-invalid': errors.description }"> </textarea>
                                    <span class="label label-danger" v-if="errors.description">{{errors.description}} </span>
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
    import vSelect from 'vue-select';
    import "vue-select/dist/vue-select.css";
    import $ from "jquery";
import { log } from 'util';
    export default {
        components:{
            CImage,
            CBtn,
            CToggle,
            'v-select': vSelect,
        },
        data() {
            return {
                selected: [],
                options: [],
                perPage: ['10', '25', '50', '100', '250', '500'],
                columns: [
                    
                    {
                        label: 'Name',
                        name: 'name',
                        filterable: true,
                    },
                    {
                        label: 'Category',
                        name: 'c_name',
                        filterable: true,
                    },
                    
                    {
                        label: 'Sub Category',
                        name: 'sc_name',
                        filterable: true,
                    },
                    {
                        label: 'Package',
                        name: 'package_name',
                        filterable: true,
                    },
                    {
                        label: 'Image',
                        component: CImage,
                        filterable: false,
                        meta:{
                            url : '/uploads/images/admin_product/',
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
                                    additionalAttributes : {'data-toggle':"modal", 'data-target':"#productNew" },
                                },
                                {
                                    label: 'Delete',
                                    classes : 'btn-danger',
                                    action: this.deleteProduct,
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
                form : new Form({
                    id:'',
                    name:'',
                    image: '',
                    sub_category_id: '',
                    category_id: '',
                    package: '',
                    description: '',
                    is_active: true,
                }),
                iform:{
                    id:'',
                    name:'',
                    image: '',
                    sub_category_id: '',
                    category_id: '',
                    package: [],
                    description: '',
                    is_active: 1,
                    package_id: '',
                    pack_row_id: '',
                },
                blank:{
                    id:'',
                    name:'',
                    image: '',
                    sub_category_id: '',
                    category_id: '',
                    commodity_type_id: '',
                    package: '',
                    description: '',
                    is_active: true,
                },
                errors:{},
                multipartForm: new FormData,
                products: [],
                supSubCategories: {},
                Categories: {},
                editMode: false,
                scatFilter: null,
                filtered: [],
                attachments: [],
                commodities: [],
                product_packages: [],
            }
        },
        methods :{
            addRow(){
                $("#package_table").append('<tr><td><input type="hidden" value="0" class="package_id"><input type="text" class="form-control package" ></td><td class="text-center"><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-minus"></i></button></td></tr>');
            },
            removeRow(obj){
                console.log(obj);
                $(obj).closest('tr').remove();
            },
            createProduct(){
                
                this.$Progress.start();
                // this.multipartForm = new FormData;
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.iform.package = [];
                for (let x in this.selected){
                    this.iform.package.push(this.selected[x].id);
                   
                }
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }
                
                console.log(this.multipartForm);
                axios.post('api/admin_product', this.multipartForm, config).then( ()=>{
                    Fire.$emit('LoadProduct');
                    $('#productNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Product Created successfully'
                    });
                    setTimeout(()=>{

                    window.location.reload();
                    }, 1000);

                    this.$Progress.finish();
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });

                // this.loadProductList();
            },
            updateProduct(){
                this.$Progress.start();
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }
                console.log(this.multipartForm);

                
                //this.multipartForm.append('package_id', this.package_id);
                axios.post('api/admin_product_update/' + this.iform.id, this.multipartForm, config).then( ()=>{
                    Fire.$emit('LoadProduct');
                    $('#productNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Product Updated successfully'
                    });
                    setTimeout(()=>{

                    window.location.reload();
                    }, 1000);
                    this.$Progress.finish();
                    
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
            },
            loadProductList() {
             axios.get("api/admin_product_all").then( ({ data }) => {this.products = data; this.filterBySubCat(); });
                axios.get("api/admin_product_all").then(response=>{

              this.products = response; this.filterBySubCat();
            });
            },
            deleteProduct(product){
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
                        this.form.delete('/api/admin_product/'+product.id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Product has been deleted.',
                            'success'
                            );
                            setTimeout(()=>{

                    window.location.reload();
                    }, 1000);
                            Fire.$emit('LoadProduct');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Product can not  be deleted.',
                            'danger'
                            )
                        })
                    }
                })
            },
            newForm(){
                this.errors = {};
                this.iform =this.blank;
                this.editMode = false;
                this.multipartForm = new FormData;
                this.supSubCategories = {};
                this.subCategories = {};
            },
            editForm(data){
                this.multipartForm = new FormData;
                this.errors = {};
                this.iform =this.blank;
                this.iform = data;
                this.iform.package = data.package_id;
                this.iform.pack_row_id = data.pack_row_id;
                this.getSuperSubCategory();
                this.editMode = true;
            },
            fileSelected(e){
                console.log('file slected', e);

                if(e.target.files != 'undefined' && e.target.files.length > 0 ){
                    for(let i=0;i<e.target.files.length;i++){
                        this.multipartForm.append('images[]', e.target.files[i]);
                    }
                }

            },
            changeActiveStatus(product){
                this.multipartForm = new FormData;
                this.editForm(product);
                this.iform.is_active = this.iform.is_active == 1  ? 0 : 1 ;
                this.updateProduct();
            },
            getImageUrl(product){
                return product.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/uploads/images/product/' + product.image;
            },
            getSuperSubCategory(){
                var cat_id = this.iform.category_id;
                //get subcategory
                axios.get("api/admin_sub_category_by_cat/"+cat_id).then(data=>{

                   this.supSubCategories = data.data.sup_sub_category;
                }); 
                
            },
           
            loadCategoryList() {

                axios.get("api/admin_pro_category").then(data=>{

                   this.Categories = data.data.pro_category
                });

            },
            loadPackage(){
                axios.get("api/package").then(data=>{

                   this.product_packages = data.data.package;
                   this.options = this.product_packages;
                });
            },
            checkSelected(subCategory){
                return subCategory.id == this.form.sub_Category_id
            },
            checkSelectedCategory(Category){
                return Category.id == this.form.category_id
            },
            filterBySubCat(){
                //return this.filtered =  this.products.filter(this.checkSubCategory);
            },
            checkSubCategory(value){
                console.log(value);

                return value.sub_category_id == this.scatFilter || this.scatFilter==null;
            },
            logger(data){
                console.log('VTB',data);;

            },
            loadCommodityList() {
                axios.get("api/commodity_type").then( ({ data }) => {
                    this.commodities = data;
                }).catch( err => console.log(err));
            },

        },
        mounted() {
            console.log('Component mounted.');
            
        },
        created(){
            this.loadCategoryList();
            this.loadCommodityList();
            this.loadPackage();
            Fire.$on('LoadProduct', () => this.loadProductList() );
        }


    }
    
    
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
