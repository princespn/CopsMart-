<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Vendor</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Vendor List</h3>

                        <div class="card-tools">
                            
                            <button type="button" @click="newForm" class="btn btn-success pl-1" data-toggle="modal" data-target="#vendorNew"> <i class="fa fa-vendor-plus"></i> Add New</button>
                            <br>
                             <input type="text" id="table_search" class="form-control"
                                    placeholder="Search" v-on:keyup="loadVendorList()" style="width: 150px;display:inline;float:right">
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body  p-0 ">
                        <table class="table table-bordered table-hover table-responsive" id="example" border="1">
                           <thead>
                                <tr>
                                <th>Sr. No</th>
                                <th>Name</th>
                                <th>On / Off</th>
                                <th>Force <br> On / Off</th>
                                <th>Shop name</th>
                                <th>Location</th>
                                <th>Address</th>
                                <th>Shop Image</th>
                                <th>Email</th>
                                <th>Contact No</th>
                                <th>Return Replacement</th>
                                <th>Actions</th>
                            </tr>
                           </thead>
                          
                            <tbody>
                            <tr v-for="(vendor, index) in vendors" :key="vendor.id"  >
                                <td style="padding: 0.2rem;">{{(1+index)}}</td>
                                <td>{{ vendor.name | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+vendor.id"  :checked="vendor.is_active" @change="changeActiveStatus(vendor)">
                                        <label class="custom-control-label" :for="'onOff'+vendor.id" ></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'fonOff'+vendor.id"  :checked="vendor.force_off" @change="changeForceStatus(vendor)">
                                        <label class="custom-control-label" :for="'fonOff'+vendor.id" ></label>
                                    </div>
                                </td>
                               <td>{{ vendor.shop_name | upText }}</td>
                                <td> <a target="_blank" :href="getMapUrl(vendor)" class="btn btn-sm btn-primary" style="padding: 0.2rem!important;font-size:12px!important"> <i class="fa fa-map-marker"></i> </a></td>
                                <td>{{ vendor.address}} </td>
                                <td><img :src="getImageUrl(vendor)" alt="" class="img img-responsive" ></td>
                                <td>{{ vendor.email}} </td>
                                <td>{{ vendor.contact_no}} </td>
                                <td>{{ vendor.return_replacement}} </td>
                                <td style="white-space:nowrap;">
                                    <button @click="editForm(vendor)"  data-toggle="modal" data-target="#vendorNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteVendor(vendor.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <router-link :to="getVendorProductLink(vendor)" class="btn btn-success btn-sm" >
                                        <i class="fa fa-cubes"></i>
                                    </router-link>

                                    <router-link :to="getVendorWalletLink(vendor)" class="btn btn-success btn-sm" >
                                        <i class="fa fa-rupee-sign"></i>
                                    </router-link>
                                </td>
                            </tr>
                            </tbody>
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
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-vendor"></i> {{ editMode ? 'Edit' : 'Add'}} Vendor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateVendor() : createVendor()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  v-model="form.name" placeholder="Vendor Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                                <label for="staticEmail" class="col-sm-2 col-form-label">Image</label>
                                <div class="col-sm-4">
                                    <input type="file" class="form-control" @change="fileSelected" multiple id="staticEmail" >
                               </div>
                            </div>
                           
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-4">
                                    <input type="email"  class="form-control"  v-model="form.email" placeholder="Vendor email" :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                                <label class="col-sm-2 col-form-label">Super Category</label>
                                <div class="col-sm-4">
                                    <select class="form-control" v-model="form.super_category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('super_category_id') }" v-on:change="getCategory(this)">
                                        <option v-for="category of super_categories" :key="category.id" :value="category.id" >{{category.name}}</option>
                                    </select>
                                     <has-error :form="form" field="super_category_id"></has-error>
                                </div>
                            </div>

                            
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Category</label>
                                <div class="col-sm-4">
                                    <select class="form-control" v-model="form.category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('category_id') }">
                                        <option v-for="category of categories" :key="category.id" :value="category.id" >{{category.name}}</option>
                                    </select>
                                     <has-error :form="form" field="category_id"></has-error>
                                </div>

                                <label for="staticEmail" class="col-sm-2 col-form-label">Latitude</label>
                                <div class="col-sm-4">
                                    <input type="number"  step=".000000000001" class="form-control"  v-model="form.latitude" placeholder="Latitude" :class="{ 'is-invalid': form.errors.has('Latitude') }">
                                    <has-error :form="form" field="latitude"></has-error>
                                </div>
                            </div>
                           
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Longitude</label>
                                <div class="col-sm-4">
                                    <input type="number" step=".000000000001"  class="form-control"  v-model="form.longitude" placeholder="Longitude" :class="{ 'is-invalid': form.errors.has('longitude') }">
                                    <has-error :form="form" field="longitude"></has-error>
                                </div>
                                <label for="staticEmail" class="col-sm-2 col-form-label">Service Range</label>
                                <div class="col-sm-4">
                                    <input type="number"  class="form-control"  v-model="form.service_range" placeholder="service range in meters" :class="{ 'is-invalid': form.errors.has('service_range') }">
                                    <has-error :form="form" field="service_range"></has-error>
                                </div>
                            </div>
                           
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Mobile</label>
                                <div class="col-sm-4">
                                    <input type="number"  class="form-control"  v-model="form.contact_no" placeholder="Vendor Contact no." :class="{ 'is-invalid': form.errors.has('contact_no') }">
                                    <has-error :form="form" field="contact_no"></has-error>
                                </div>

                                <label for="staticEmail" class="col-sm-2 col-form-label">Return Replacement</label>
                                <div class="col-sm-4">
                                    <textarea  class="form-control"  v-model="form.return_replacement" placeholder="Return Replacement" :class="{ 'is-invalid': form.errors.has('return_replacement') }"></textarea>
                                    <has-error :form="form" field="return_replacement"></has-error>
                                </div>

                                
                            </div>
                            
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-4">
                                    <textarea  class="form-control"  v-model="form.address" placeholder="Vendor Address" :class="{ 'is-invalid': form.errors.has('address') }"></textarea>
                                    <has-error :form="form" field="address"></has-error>
                                </div>

                                <label for="staticEmail" class="col-sm-2 col-form-label">About Vendor</label>
                                <div class="col-sm-4">
                                    <textarea  class="form-control"  v-model="form.about_vendor" placeholder="About Vendor" :class="{ 'is-invalid': form.errors.has('about_vendor') }"></textarea>
                                    <has-error :form="form" field="about_vendor"></has-error>
                                </div>

                                    
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Slab Time</label>
                                <div class="col-sm-4">
                                    <input type="text" minlength="2" maxlength="3" class="form-control without_ampm"  v-model="form.slab" placeholder= "Enter Time in min(i.e 20 ,30,40) format." :class="{ 'is-invalid': form.errors.has('slab') }">
                                    <has-error :form="form" field="slab"></has-error>
                                </div>

                                <label for="staticEmail" class="col-sm-2 col-form-label">Shop name</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control without_ampm"  v-model="form.shop_name" placeholder= "Enter shop name" :class="{ 'is-invalid': form.errors.has('shop_name') }">
                                    <has-error :form="form" field="shop_name"></has-error>
                                </div>
                            </div>
                            <!--  -->
                              <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Shop Reg. No</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control shopreg"  v-model="form.shopreg" placeholder= "Enter Shop Reg. No" :class="{ 'is-invalid': form.errors.has('slab') }">
                                    <has-error :form="form" field="slab"></has-error>
                                </div>

                                <label for="staticEmail" class="col-sm-2 col-form-label">Food License No</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control without_ampm"  v-model="form.foodlice" placeholder= "Food License No" :class="{ 'is-invalid': form.errors.has('shop_name') }">
                                    <has-error :form="form" field="shop_name"></has-error>
                                </div>
                            </div>
                            <!--  -->
                             <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Shop Open Time</label>
                                <div class="col-sm-4">
                                    <input type="time"  class="form-control without_ampm"  v-model="form.open_time" :class="{ 'is-invalid': form.errors.has('open_time') }">
                                    <has-error :form="form" field="open_time"></has-error>
                                </div>

                                <label for="staticEmail" class="col-sm-2 col-form-label">Shop name</label>
                                <div class="col-sm-4">
                                    <input type="time"  class="form-control without_ampm"  v-model="form.close_time"  :class="{ 'is-invalid': form.errors.has('close_time') }">
                                    <has-error :form="form" field="close_time"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-1 col-form-label">Minimum Order Amount</label>
                                <div class="col-sm-3">
                                    <input type="text" minlength="2" maxlength="3" class="form-control without_ampm"  v-model="form.min_amount" placeholder= "Enter Minimum Amount." :class="{ 'is-invalid': form.errors.has('min_amount') }">
                                    <has-error :form="form" field="miniamt"></has-error>
                                </div>

                                <label for="staticEmail" class="col-sm-1 col-form-label">Maximum Discount Amount</label>
                                <div class="col-sm-3">
                                    <input type="text"  class="form-control without_ampm"  v-model="form.max_discount" placeholder= "Enter Maximum Amount" :class="{ 'is-invalid': form.errors.has('max_discount') }">
                                    <has-error :form="form" field="maxamt"></has-error>
                                </div>

                                <label for="staticEmail" class="col-sm-1 col-form-label">Discount</label>
                                <div class="col-sm-3">
                                    <input type="text"  class="form-control without_ampm"  v-model="form.discount_percentage" placeholder= "Enter Discount" :class="{ 'is-invalid': form.errors.has('min_amount') }">
                                    <has-error :form="form" field="discount"></has-error>
                                </div>
                            </div>


                              <div class="form-group row">
                                <label for="staticEmail" class="col-sm-1 col-form-label">Selling Price</label>
                                <div class="col-sm-3">
                                    <input type="text" minlength="2" maxlength="4" class="form-control without_ampm"  v-model="form.vprice" placeholder= "Enter Selling Price." :class="{ 'is-invalid': form.errors.has('vprice') }">
                                    <has-error :form="form" field="miniamt"></has-error>
                                </div>

                                <label for="staticEmail" class="col-sm-1 col-form-label">Ocean Price</label>
                                <div class="col-sm-3">
                                    <input type="text" minlength="2" maxlength="4"  class="form-control without_ampm"  v-model="form.oprice" placeholder= "Enter Ocean Price" :class="{ 'is-invalid': form.errors.has('oprice') }">
                                    <has-error :form="form" field="maxamt"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                
                                <div class="col-sm-3">
                                    <input type="checkbox" id="checkbox" v-model="form.add_pro">
                                    <label  >Add Product</label>
                                </div>

                                
                                <div class="col-sm-3">
                                    <input type="checkbox" id="checkbox" v-model="form.edit_pro">
                                    <label >Edit Product</label>
                                </div>

                                <div class="col-sm-3">
                                    <input type="checkbox" id="checkbox" v-model="form.fif_nine">
                                    <label >Special Offer</label>
                                </div>

                                <div class="col-sm-3">
                                    <input type="checkbox" id="checkbox" v-model="form.fulldis">
                                    <label >Full Delivery Discount</label>
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
import GetOrder  from './GetOrder';
    import { moment } from 'moment';
    import $ from "jquery";
    export default {
         components:{          
            'orders': GetOrder,
        },
        data() {
            return {
                form : new Form({
                    id:'',
                    name: null,
                    image:'',
                    address: null,
                    about_vendor: null,
                    service_range:12000,
                    super_category_id: null,
                    category_id: null,
                    latitude: null,
                    longitude: null,
                    email: null,
                    contact_no: null,
                    return_replacement: null,
                    slab:null,
                    shop_name:'',
                    close_time:'',
                    foodlice:'',
                    shopreg:'',
                    open_time:'',
                    discount_percentage:'',
                    min_amount:'',
                    max_discount:'',
                    vprice:'',
                    oprice:'',
                    is_active: true,
                    force_off: true,
                    add_pro: true,
                    edit_pro: true,
                    fif_nine: true,
                    fulldis: true,
                    admin_id:'',
                }),
                vendors: {},
                editMode: false,
                super_categories: [],
                categories: []
            }
        },
        methods :{
            createVendor(){
                this.$Progress.start();
                var a =$('#authid').val();
                this.form.admin_id=a;
                this.form.post('api/vendors').then( ()=>{
                    Fire.$emit('LoadVendor');
                    $('#vendorNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Vendor Created Successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadVendorList();
            },
            updateVendor(){
                this.$Progress.start();
                this.form.put('api/vendors/' + this.form.id).then( ()=>{
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
                var a =$('#authid').val();
                var ta =$('#table_search').val();
                // alert(ta);
                if(ta==undefined || ta==null || ta==''){ta='';}
                axios.get("api/vendors?ad="+a+"&table="+ta).then( ({ data }) => (this.vendors = data) );
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
                        this.form.delete('/api/vendors/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Vendor has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadVendor');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Vendor can't  be deleted.`,
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
                this.getCategory();
                this.editMode = true;

            },
            changeActiveStatus(vendor){
                this.editForm(vendor);
                this.form.is_active = !this.form.is_active ;
                this.updateVendor();
            },
            changeForceStatus(vendor){
                this.editForm(vendor);
                this.form.force_off = !this.form.force_off ;
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
            getMapUrl(vendor){
                return vendor.latitude != null ? `http://maps.google.com/maps?q=${vendor.latitude},${vendor.longitude}` : '#';
            },
            getVendorProductLink(vendor){
                return `/vendor_products/${vendor.id}` ;
            },
            getVendorWalletLink(vendor){
                return `/vendor_wallet/${vendor.id}` ;
            },
            loadCategoryList() {
                   var a =$('#authid').val();
                axios.get("api/category_all?ad="+a).then( ({ data }) => (this.super_categories = data) );
            },
            getCategory(){
                var cat_id = this.form.super_category_id;
                axios.get("api/prod_category_by_id/"+cat_id).then( ({ data }) => (this.categories = data) );
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadVendorList();
            this.loadCategoryList();
            Fire.$on('LoadVendor', () => this.loadVendorList() );
             $('#example').DataTable();
        }


    }

$(document).ready(function() {
   
} );
</script>

<style scoped>
img{
    max-width : 7vh;
    max-height : 7vh
}
.table th, .table td {
    text-align: center!important;
    padding: 0.4rem;
    font-size: 11px!important;
}
.table > thead > tr > th
{
    white-space: nowrap!important;
}
</style>
