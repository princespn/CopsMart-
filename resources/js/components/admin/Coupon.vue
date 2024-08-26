<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Coupon</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Coupon List</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal"
                                data-target="#couponNew"> <i class="fa fa-coupon-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-3">
                        <table id="datatable" class="table table-bordered table-hover">
                            <tr>
                                <th>Sr. No</th>
                                <th>Name</th>
                                <th>On / Off</th>
                                <th>Vendor Discount %</th>
                                <th>Ocean Discount %</th>
                                <th>Min. Order Amount</th>
                                <th>Max Discount Amount</th>
                                <!-- <th>Per User Limit</th> -->
                                <th>Category</th>
                                <th>Vendor</th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="(coupon, index) in coupons" :key="coupon.id">
                                <td>{{(1+index)}}</td>
                                <td>{{ coupon.name | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+coupon.id"  :checked="coupon.is_active" @change="changeActiveStatus(coupon)">
                                        <label class="custom-control-label" :for="'onOff'+coupon.id" ></label>
                                    </div>
                                </td>
                                <td> {{ coupon.discount_value }} </td>
                                <td> {{ coupon.ocean_discount }} </td>
                                <td> {{ coupon.min_order_amount }} </td>
                                <td> {{ coupon.max_discount_amount }} </td>
                                <!-- <td> {{ coupon.per_user_limit }} </td> -->
                                <td> {{ coupon.category_name != null ? coupon.category_name : 'NA' }} </td>
                                <td> {{ coupon.vendor_name != null ? coupon.vendor_name : 'NA' }} </td>
                                <td>
                                    <button @click="editForm(coupon)"  data-toggle="modal" data-target="#couponNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteCoupon(coupon.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- Card body / -->
                    <div v-if="isLoading" class="overlay dark">
                        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                </div><!-- /.row -->


                <!-- Modal -->
                <div class="modal fade" id="couponNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-coupon"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Coupon</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="editMode ? updateCoupon() : createCoupon()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Name</label>
                                        <div class="col-sm-8">
                                            <input required type="text" class="form-control" v-model="form.name"
                                                placeholder="Coupon Name"
                                                :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>
                                    <!--<div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Marketing Person</label>
                                        <div class="col-sm-10">
                                            <select required type="text" class="form-control" v-model="form.marketing_person_id"
                                                placeholder="Coupon Name"
                                                :class="{ 'is-invalid': form.errors.has('marketing_person_id') }">
                                                <option :value="null" disabled>Select Marketing Person</option>
                                                <option v-for="mp in marketingPersons" :value="mp.id" :key="mp.id">{{ mp.name}}</option>
                                            </select>
                                            <has-error :form="form" field="marketing_person_id"></has-error>
                                        </div>
                                    </div>-->
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Category Name</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" v-model="form.category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('category_id') }" v-on:change="loadVendorList(this)">
                                                <option v-for="cat of Categories" :key="cat.id" :value="cat.id" :selected="checkSelected(cat)" >{{cat.name}}</option>
                                            </select>
                                           <has-error :form="form" field="category_id"></has-error>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Vendor</label>
                                        <div class="col-sm-8">
                                            <v-select multiple :closeOnSelect="true" v-model="selected" :options="options" label="name" @input="setSelected"/>
                                            <has-error :form="form" field="vendor_id"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Vendor Discount (in %)</label>
                                        <div class="col-sm-8">
                                            <input required type="number" max="100" min="0" class="form-control" v-model="form.discount_value"
                                                :class="{ 'is-invalid': form.errors.has('discount_value') }">
                                            <has-error :form="form" field="discount_value"></has-error>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Ocean Discount (in %)</label>
                                        <div class="col-sm-8">
                                            <input required type="number" max="100" min="0" class="form-control" v-model="form.ocean_discount"
                                                :class="{ 'is-invalid': form.errors.has('ocean_discount') }">
                                            <has-error :form="form" field="ocean_discount"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Min. Order Amount</label>
                                        <div class="col-sm-8">
                                            <input required type="number" min="1" class="form-control" v-model="form.min_order_amount"
                                                :class="{ 'is-invalid': form.errors.has('min_order_amount') }">
                                            <has-error :form="form" field="min_order_amount"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Max. Discount Amount</label>
                                        <div class="col-sm-8">
                                            <input required type="number" min="1" class="form-control" v-model="form.max_discount_amount"
                                                :class="{ 'is-invalid': form.errors.has('max_discount_amount') }">
                                            <has-error :form="form" field="max_discount_amount"></has-error>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Per User Limit</label>
                                        <div class="col-sm-10">
                                            <input type="number" min="1" class="form-control" v-model="form.per_user_limit"
                                                :class="{ 'is-invalid': form.errors.has('per_user_limit') }">
                                            <has-error :form="form" field="per_user_limit"></has-error>
                                        </div>
                                    </div> -->

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
        </div>
    </div>
</template>

<script>
    import { moment } from 'moment';
    import $ from "jquery";
    import vSelect from 'vue-select';
    import "vue-select/dist/vue-select.css";
    export default {
        components:{
            'v-select': vSelect,
        },
        data() {
            return {
                form : new Form({
                    id: null,
                    name: null,
                    category_id:'',
                    discount_value: 0,
                    ocean_discount:0,
                    min_order_amount: null,
                    max_discount_amount: null,
                    per_user_limit: 1,
                    marketing_person_id: null,
                    is_active: true,
                    vendor_id : [],
                    admin_id:'',
                }),
                vendors: {},
                coupons: {},
                marketingPersons: [],
                Categories: {},
                selected: [],
                options: [],
                vendor_list: [],
                isLoading : false,
                editMode: false
            }
        },
        methods :{
            createCoupon(){
                this.$Progress.start();
                var a =$('#authid').val();
                this.form.admin_id=a;
                this.form.vendor_id = [];
                for (let x in this.selected){
                    this.form.vendor_id.push(this.selected[x].id);
                }
                this.form.post('api/coupon').then( ()=>{
                    Fire.$emit('LoadCoupon');
                    $('#couponNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Coupon Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });

                // this.loadCouponList();
            },
            updateCoupon(){
                this.$Progress.start();
                this.form.vendor_id = [];
                for (let x in this.selected){
                    this.form.vendor_id.push(this.selected[x].id);
                   
                }
                this.form.put('api/coupon/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadCoupon');
                    $('#couponNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadCategoryList() {
               
                axios.get("api/pro_category").then(data=>{
                   this.Categories = data.data.pro_category
                });

            },
            loadVendorList() {
                var cat_id = this.form.category_id;
                axios.get("/api/loadVendorCat/"+cat_id).then(data=>{
                   this.vendor_list = data.data.vendor;
                   this.options = this.vendor_list;
               });
            },
            checkSelected(vendor){
                return vendor.id == this.form.vendor_id
            },
            loadCouponList() {
                 var a =$('#authid').val();
                this.isLoading = true;
                axios.get("api/coupon?ad="+a).then( ({ data }) => {
                    this.coupons = data;
                    this.isLoading = false;
                }).catch( err => this.isLoading=false);
            },
            setSelected(data){
                console.log(this.selected);
            },
            deleteCoupon(id){
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
                        this.form.delete('/api/coupon/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Coupon has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadCoupon');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Coupon can not  be deleted.',
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
                
                this.selected = [];
                this.form.reset();
                this.form.fill(data);
                this.editMode = true;
                this.loadVendorList();
                
                if(data.vendor_id!=''){
                    var vendor_id = data.vendor_id.split(',');
                    var name = data.vendor_name.split(',');
                    for (let x in vendor_id){
                        this.selected.push({'id':vendor_id[x],'name':name[x]});
                       
                    }
                }
                
                
            },
            fileSelected(e){
                console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        console.log('RESULT converted base 64');
                        this.form.image=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }

            },
            changeActiveStatus(coupon){
                this.editForm(coupon);
                this.form.is_active = !this.form.is_active ;
                this.updateCoupon();
            },
            loadMarketingPersonList() {
                axios.get("api/marketing_person").then( ({ data }) => {
                    this.marketingPersons = data;
                }).catch( err => console.log(err));
            },
        },
        mounted() {
            console.log('Component mounted.');
        },
        created(){
            this.loadCategoryList();
            this.loadCouponList();
            //this.loadVendorList();
            this.loadMarketingPersonList();
            Fire.$on('LoadCoupon', () => this.loadCouponList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
