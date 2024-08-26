<template>
    <div class="container p-0">
        <!-- /.row -->
      
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-3">Vendor Management </h3>

                        <div class="card-tools">
                           <a  href="/AddPurchaseVendor" class="btn btn-primary  btn-sm pl-1 mt-1 mr-3" > <i class="fa fa-plus"></i> Add Vendor</a>
                            <input type="text" id="table_search" class="form-control mr-3"
                                    placeholder="Search" v-on:keyup="loadVendorList()" style="width: 150px;display:inline;float:right">
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body  p-0 ">
                        <table class="table table-bordered table-hover" id="example" border="1">
                           <thead>
                                <tr>
                                <th>Sr. No</th>
                                <th>Vendor Name</th>
                                <th>Activate / Deactivate</th>
                                <th>Block</th>
                                <th>Contact Person</th>
                                <th>Contact</th>
                                <th>District</th>
                                <th>GSTIN</th>
                                <th>Status</th>
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
                                        <input type="checkbox" class="custom-control-input" :id="'bonOff'+vendor.id"  :checked="vendor.is_block" @change="changeForceStatus(vendor)">
                                        <label class="custom-control-label" :for="'bonOff'+vendor.id" ></label>
                                    </div>
                                </td>
                              
                               <td>{{ vendor.contact_person | upText }}</td>
                               <td>{{ vendor.mobile_no  }}</td>
                               <td>{{ vendor.district}} </td>
                                 <td>{{ vendor.gst}} </td>
                               <td>ss</td>
                                <td style="white-space:nowrap;">
                                    <router-link :to="getPurchaseVendorEditLink(vendor)" class="btn btn-primary btn-sm" >
                                        <i class="fa fa-edit"></i>
                                    </router-link>
                                    <button @click="deleteVendor(vendor.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <router-link :to="getPurchaseVendorViewLink(vendor)" class="btn btn-success btn-sm" >
                                        <i class="fa fa-eye"></i>
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
  <orders />
    </div>
</template>

<script>
    import { moment } from 'moment';
    import GetOrder  from './GetOrder';
    import $ from "jquery";
    export default {
         components:{          
            'orders': GetOrder,
        },
        data() {
            return {
                 form : new Form({
                    id:'',
                    vendor_id:'',
                    name: '',
                    mobile_no: '',
                    contact_person: '',
                    emp_post: '',
                    address: '',
                    pincode: '',
                    district: '',
                    state:'',
                    gst:'',
                    bankname:'',
                    account_name: '',
                    account_no:'',
                    ifsc: '',
                    is_active: true,
                    is_block:false,
                }),
                iform:{
                    id:'',
                    vendor_id:'',
                    name: '',
                    mobile_no: '',
                    contact_person: '',
                    emp_post: '',
                    address: '',
                    pincode: '',
                    district: '',
                    state:'',
                    gst:'',
                    bankname:'',
                    account_name: '',
                    account_no:'',
                    ifsc: '',
                    is_active: true,
                    is_block:false,
                },
                blank:{
                    id:'',
                    vendor_id:'',
                    name: '',
                    mobile_no: '',
                    contact_person: '',
                    emp_post: '',
                    address: '',
                    pincode: '',
                    district: '',
                    state:'',
                    gst:'',
                    bankname:'',
                    account_name: '',
                    account_no:'',
                    ifsc: '',
                    is_active: true,
                    is_block:false,
                },
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
                this.form.put('api/purchasevendor/' + this.form.id).then( ()=>{
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
                axios.get("api/purchasevendordata?ad="+a+"&table="+ta).then( ({ data }) => (this.vendors = data) );
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
                        this.form.delete('/api/purchasevendor/'+id).then(() => {
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
                this.form.is_block = !this.form.is_block ;
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
            getPurchaseVendorEditLink(vendor){
                return `/editpurchasevendor/${vendor.id}` ;
            },
            
            getPurchaseVendorViewLink(vendor)
            {
                 return `/viewpurchasevendor/${vendor.id}` ;
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
