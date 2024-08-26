<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Offer Product List</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Offer ProductList</h3>
                        <div class="card-tools">
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#offerProductNew"> <i class="fa fa-slab-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>Sub-Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tr v-for="(offerproduct, index) in offerproduct" :key="'sc'+index">
                                <td>{{(1+index)}}</td>
                                <td>{{ offerproduct.offer_title }}</td>
                                <td> <img :src="getImageUrl(offerproduct)" alt="" class="img img-responsive" ></td>
                                <td>{{ offerproduct.cname }}</td>
                                <td>{{ offerproduct.scname }}</td>
                                <td>
                                    <button @click="editForm(offerproduct)"  data-toggle="modal" data-target="#offerProductNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteOfferProduct(offerproduct.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div><!-- /.row -->
        <!-- Modal -->
        <div class="modal fade" id="offerProductNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-slab"></i> {{ editMode ? 'Edit' : 'Add'}} Offer Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateOfferProduct() : createOfferProduct()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"> Name</label>
                                <div class="col-sm-8">
                                    <input type="charges" min="0" class="form-control"  v-model="form.offer_title" placeholder="Name" :class="{ 'is-invalid': form.errors.has('offer_title') }">
                                    <has-error :form="form" field="offer_title"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label"> Image</label>
                                <div class="col-sm-8">
                                    <input type="file" @change="fileSelected" id="offer_image" :class="{ 'is-invalid': form.errors.has('offer_image') }">
                                    <has-error :form="form" field="offer_image"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Category</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="form.category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('category_id') }" v-on:change="getSuperSubCategory(this)">
                                        <option v-for="cat of Categories" :key="cat.id" :value="cat.id" :selected="checkSelected(cat)" >{{cat.name}}</option>
                                    </select>
                                    <has-error :form="form" field="category_id"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Super Sub Category</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="form.sup_sub_category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('sup_sub_category_id') }">
                                        <option v-for="subCategory of supSubCategories" :key="subCategory.id" :value="subCategory.id" :selected="checkSelected(subCategory)" >{{subCategory.name}}</option>
                                    </select>
                                   <has-error :form="form" field="sup_sub_category_id"></has-error>
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
    import { moment } from 'moment';
    import $ from "jquery";
    export default {
        data() {
            return {
                form : new Form({
                    id:'',
                    offer_title :null,
                    offer_image :null,
                    vendor_id : '',
                    category_id: '',
                    sup_sub_category_id: '',
                    
                }),
                vendors: {},
                offerproduct: {},
                editMode: false,
                Categories: {},
                supSubCategories: {},
            }
        },
        methods :{
            createOfferProduct(){
                this.$Progress.start();
                this.form.post('/api/offer_product').then( ()=>{
                    this.loadOfferProductList();
                    $('#offerProductNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Offer Product Created successfully'
                    });
                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            updateOfferProduct(){
                this.$Progress.start();
                this.form.put('/api/offer_product/' + this.form.id).then( ()=>{
                    this.loadOfferProductList();
                    $('#offerProductNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Offer Product Updated Successfully'
                    });
                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
           checkSelected(subCategory){
                return subCategory.id == this.form.sub_Category_id
            },
            checkSelectedCategory(Category){
                return Category.id == this.form.category_id
            },
            loadOfferProductList() {
                axios.get('/api/offer_product').then( ({ data }) => (this.offerproduct = data) );
            },
            getImageUrl(offerproduct){
                return offerproduct.offer_image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/public/uploads/images/offerproduct/' + offerproduct.offer_image;
            },
            getSuperSubCategory(){
                var cat_id = this.form.category_id;
                axios.get("api/super_sub_category_by_cat/"+cat_id).then(data=>{
                   this.supSubCategories = data.data.sup_sub_category;
                }); 
                
            },
            loadCategoryList() {
                axios.get("api/pro_category").then(data=>{
                   this.Categories = data.data.pro_category
                });
            },
            fileSelected(e){
                console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.form.offer_image=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }

            },
            deleteOfferProduct(id){
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
                        this.form.delete('/api/offer_product/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Marketing Box has been deleted.',
                            'success'
                            );
                            this.loadOfferProductList();
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Package can't  be deleted.`,
                            'danger'
                            )
                            this.loadOfferProductList();
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.editMode = false;
            },
            editForm(data){
                this.form.fill(data);
                this.editMode = true;
                this.form.offer_image= '';
                this.getSuperSubCategory();
            },
            loadOfferProduct() {
                axios.get("/api/offer_product").then( ({ data }) => (this.offerproduct = data) );
            },
        },
        mounted() {
            console.log('Component mounted.');
            this.loadCategoryList();
            this.loadOfferProductList();
            this.loadOfferProduct();
        }
    }
</script>

<style scoped>
img{
    max-width : 3vh;
    max-height : 3vh
}
</style>
