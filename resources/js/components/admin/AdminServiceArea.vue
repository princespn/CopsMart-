<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Admin Service Area</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Service Area List</h3>

                        <div class="card-tools">
                            
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#vendorNew"> <i class="fa fa-vendor-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tr>
                                <th>Sr. No</th>
                                <th>Name</th>
                                <th>Service Area</th>
                                <th>Category</th>
                                <th>On / Off</th>
                                <th>Contact No</th>
                                <th>Alternate Number</th>
                                <th>Actions</th>
                            </tr>
                            

                            <tr v-for="(vendor, index) in vendors" :key="vendor.id">
                                <td>{{(1+index)}}</td>
                                <td>{{ vendor.name | upText }}</td>
                                <td>{{ vendor.service_area_name | upText }}</td>
                                <td>{{ vendor.c_name | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+vendor.id"  :checked="vendor.is_active" @change="changeActiveStatus(vendor)">
                                        <label class="custom-control-label" :for="'onOff'+vendor.id" ></label>
                                    </div>
                                </td>
                                <td>{{ vendor.contact_no}} </td>
                                <td>{{ vendor.alternate_number}} </td>
                                <td>
                                    <button @click="editForm(vendor)"  data-toggle="modal" data-target="#vendorNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteVendor(vendor.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <router-link :to="getVendorProductLink(vendor)" class="btn btn-success btn-sm" >
                                        <i class="fa fa-cubes"></i>
                                    </router-link>
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
        <div class="modal fade" id="vendorNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-vendor"></i> {{ editMode ? 'Edit' : 'Add'}} Service Area</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateVendor() : createVendor()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-1 col-form-label">Name</label>
                                <div class="col-sm-5">
                                    <input type="text"  class="form-control"  v-model="form.name" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                                <label for="staticEmail" class="col-sm-2 col-form-label">Service Area</label>
                                <div class="col-sm-4">
                                    <select class="form-control" v-model="form.service_area_id" placeholder="Select Service Area" :class="{ 'is-invalid': form.errors.has('service_area_id') }">
                                        <option v-for="service_area of service_areas" :key="service_area.id" :value="service_area.id" >{{service_area.name}}</option>
                                    </select>
                                     <has-error :form="form" field="service_area_id"></has-error>
                                </div>
                                
                            </div>
                           
                            <div class="form-group row">
                                <label class="col-sm-1 col-form-label">Category</label>
                                <div class="col-sm-5">
                                    <select class="form-control" v-model="form.category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('category_id') }" v-on:change="getSubCategory(this)">
                                        <option v-for="category of categories" :key="category.id" :value="category.id" >{{category.name}}</option>
                                    </select>
                                     <has-error :form="form" field="category_id"></has-error>
                                </div>
                                
                                <label class="col-sm-2 col-form-label">Sub Category</label>
                                <div class="col-sm-4">
                                    <select class="form-control" v-model="form.sub_category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('sub_category_id') }">
                                        <option v-for="subCategory of sub_categories" :key="subCategory.id" :value="subCategory.id" :selected="checkSelected(subCategory)" >{{subCategory.name}}</option>
                                    </select>
                                   <has-error :form="form" field="sub_category_id"></has-error>
                                </div>

                                
                                
                            </div>

                            <div class="form-group row">
                                
                                <label for="staticEmail" class="col-sm-1 col-form-label">Mobile</label>
                                <div class="col-sm-5">
                                    <input type="number"  class="form-control"  v-model="form.contact_no" placeholder="Contact no." :class="{ 'is-invalid': form.errors.has('contact_no') }">
                                    <has-error :form="form" field="contact_no"></has-error>
                                </div>
                                <label for="staticEmail" class="col-sm-2 col-form-label">Alternate No.</label>
                                <div class="col-sm-4">
                                    <input type="number"  class="form-control"  v-model="form.alternate_number" placeholder="Alternate Contact no." :class="{ 'is-invalid': form.errors.has('alternate_number') }">
                                    <has-error :form="form" field="alternate_number"></has-error>
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
                    name: null,
                    service_area_id: null,
                    category_id: null,
                    sub_category_id: null,
                    contact_no: null,
                    alternate_number: null,
                    is_active: true,
                }),
                vendors: {},
                editMode: false,
                service_areas: [],
                categories: [],
                sub_categories: []
            }
        },
        methods :{
            createVendor(){
                this.$Progress.start();

                this.form.post('api/admin_service_area').then( ()=>{
                    Fire.$emit('LoadVendor');
                    $('#vendorNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Vendor Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadVendorList();
            },
            updateVendor(){
                this.$Progress.start();
                this.form.put('api/admin_service_area/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadVendor');
                    $('#vendorNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadVendorList() {
                axios.get("api/admin_service_area").then( ({ data }) => (this.vendors = data) );
            },
            getImageUrl(vendor){
                return vendor.shop_image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/public/uploads/images/vendor/' + vendor.shop_image;
            },
            deleteVendor(id){
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
                        this.form.delete('/api/admin_service_area/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Vendor has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadVendor');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Vendor can not  be deleted.',
                            'danger'
                            )
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.editMode = false;
            },
            editForm(data){
                this.form.reset();

                this.form.fill(data);
                this.getSubCategory();
                this.editMode = true;

            },
            changeActiveStatus(vendor){
                this.editForm(vendor);
                this.form.is_active = !this.form.is_active ;
                this.updateVendor();
            },
            fileSelected(e){
                console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        // console.log('RESULT converted base 64');
                        this.form.image=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }

            },
            checkSelected(subCategory){
                return subCategory.id == this.form.sub_Category_id
            },
            checkSelectedCategory(Category){
                return Category.id == this.form.category_id
            },
            getMapUrl(vendor){
                return vendor.latitude != null ? `http://maps.google.com/maps?q=${vendor.latitude},${vendor.longitude}` : '#';
            },
            getVendorProductLink(vendor){
                return `/service_area_products/${vendor.id}` ;
            },
            getVendorWalletLink(vendor){
                return `/service_area_wallet/${vendor.id}` ;
            },
            loadServiceAreaList() {
                axios.get("api/get_service_area").then(data=>{

                   this.service_areas = data.data.service_area
                });
            },
            loadCategoryList() {
                axios.get("api/admin_pro_category").then(data=>{

                   this.categories = data.data.pro_category
                });
            },
            getSubCategory(){
                var cat_id = this.form.category_id;
                //get subcategory
                axios.get("api/admin_sub_category_by_cat/"+cat_id).then(data=>{
                   this.sub_categories = data.data.sup_sub_category;
                }); 
                
            },
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadVendorList();
            this.loadServiceAreaList();
            this.loadCategoryList();
            Fire.$on('LoadVendor', () => this.loadVendorList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
