<template>
    <div class="container p-0">
        <!-- /.row -->
        <div class="row mt-2 ml-3">
            <h3>Add Delivery Boy</h3>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2"> Add Delivery Boy </h3>
                        <div class="card-tools">
                            <a type="button" class="btn btn-sm btn-primary mr-3"  href="/DeliveryBoyManagement">Delivery Boy List</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                         <form @submit.prevent="createDeliveryPerson()">
                          
                          <div class="form-group row">
                              <div class="col-sm-4">
                                    <label class="col-form-label">Select Vendor</label>
                                    <v-select   v-model="vendor_id" label="name" :options="options"  style="text-transform: capitalize!important;"    :class="{ 'is-invalid': errors.vendor_id }"/>
                                </div>
                               <div class="col-sm-4">
                                    <label class="col-form-label">Name</label>
                                    <input type="text"  class="form-control"  v-model="form.name" placeholder="Enter Name here" :class="{ 'is-invalid': errors.name }">
                                    <span class="label label-danger" v-if="errors.name">{{errors.name}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label class="col-form-label">Mobile Number</label>
                                    <input type="text"  class="form-control"  v-model="form.mobile" placeholder="Enter Mobile Number here" :class="{ 'is-invalid': errors.mobile }">
                                    
                                    <span class="label label-danger" v-if="errors.mobile">{{errors.mobile}} </span>
                                </div>
                                
                          </div>
                           <div class="form-group row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">Email ID</label>
                                    <input type="text"  class="form-control"  v-model="form.email" placeholder="Enter Email ID here" :class="{ 'is-invalid': errors.email }">
                                    
                                    <span class="label label-danger" v-if="errors.email">{{errors.email}} </span>
                                </div>
                               <div class="col-sm-4">
                                    <label class="col-form-label">Address</label>
                                    <input type="text"  class="form-control"  v-model="form.address" placeholder="Enter Address here" :class="{ 'is-invalid': errors.address }">
                                    <span class="label label-danger" v-if="errors.address">{{errors.address}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label class="col-form-label">PIN Code</label>
                                    <input type="text"  class="form-control" id="pincode" v-model="form.pincode" placeholder="Enter PIN Code here" :class="{ 'is-invalid': errors.pincode }" v-on:keyup="getData()">
                                    <span class="label label-danger" v-if="errors.pincode">{{errors.pincode}} </span>
                                </div>
                                
                          </div>
                           <div class="form-group row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">District</label>
                                    <input type="text"  class="form-control"  v-model="form.district" readonly placeholder="Enter District here" :class="{ 'is-invalid': errors.district }">
                                    
                                    <span class="label label-danger" v-if="errors.district">{{errors.district}} </span>
                                </div>
                               <div class="col-sm-4">
                                    <label class="col-form-label">State</label>
                                    <input type="text"  class="form-control"  v-model="form.state" readonly placeholder="Enter State Name here" :class="{ 'is-invalid': errors.state }">
                                    <span class="label label-danger" v-if="errors.state">{{errors.state}} </span>
                                </div>
                      
                                 <div class="col-sm-4">
                                    <label class="col-form-label">Blood Group </label>
                                    <!-- <input type="text"  class="form-control"  v-model="form.blood_group" placeholder="Enter Blood Group here" :class="{ 'is-invalid': errors.blood_group }"> -->
                                     <select class="form-control" v-model="form.blood_group" placeholder="Select Type" :class="{ 'is-invalid': errors.type }" v-on:change="getTypeselected(this)">
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="0-">0-</option>
                                        <option value="0+">0+</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.blood_group">{{errors.blood_group}} </span>
                                </div>
                                
                          </div>
                          <div class="form-group row">
                                  <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Date of Birth</label>
                                    <input type="date"  class="form-control"  v-model="form.dob" placeholder="Enter DOB here" :class="{ 'is-invalid': errors.dob }">
                                    <span class="label label-danger" v-if="errors.dob">{{errors.dob}} </span>
                                </div>
                                   <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Date of Joining</label>
                                    <input type="date"  class="form-control"  v-model="form.date_of_joining" placeholder="Enter DOJ here" :class="{ 'is-invalid': errors.date_of_joining }">
                                    <span class="label label-danger" v-if="errors.date_of_joining">{{errors.date_of_joining}} </span>
                                </div>
                                  <div class="col-sm-4">
                                    <label class="col-form-label">Identification Mark </label>
                                    <input type="text"  class="form-control"  v-model="form.identification_mark" placeholder="Enter Identification Mark" :class="{ 'is-invalid': errors.identification_mark }">
                                    
                                    <span class="label label-danger" v-if="errors.identification_mark">{{errors.identification_mark}} </span>
                                </div>
                                   
                          </div>
                            <div class="card-header">
                        <h3 class="card-title"> Business Details </h3>
                    </div>
                          <div class="form-group row">
                               <div class="col-sm-4">
                                    <label class="col-form-label">Adhar Card No. </label>
                                    <input type="text"  class="form-control"  v-model="form.aadhar_no" placeholder="Enter Adhar Card No. here" :class="{ 'is-invalid': errors.aadhar_no }">
                                    
                                    <span class="label label-danger" v-if="errors.aadhar_no">{{errors.aadhar_no}} </span>
                                </div>
                                  <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Adhar Card Front Side</label>
                                    <input ref="fileInput1" class="form-control" @change="AdharFrontSelected" type="file">
                                    <span class="label label-danger" v-if="errors.adhar_front_img">{{errors.adhar_front_img}} </span>
                                </div>
                                   <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Adhar Card Back Side</label>
                                    <input ref="fileInput1" class="form-control" @change="AdharBackSelected" type="file" >
                                    <span class="label label-danger" v-if="errors.adhar_back_img">{{errors.adhar_back_img}} </span>
                                </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-sm-4">
                                  <label class="col-form-label">Pan Card No. </label>
                                    <input type="text"  class="form-control"  v-model="form.pan_no" placeholder="Enter Pan Card No. here" :class="{ 'is-invalid': errors.pan_no }">
                                    
                                    <span class="label label-danger" v-if="errors.pan_no">{{errors.pan_no}} </span>
                              </div>
                                 <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Pan Card Side</label>
                                    <input ref="fileInput1" class="form-control" @change="PanSelected" type="file"  >
                                    <span class="label label-danger" v-if="errors.pan_img">{{errors.pan_img}} </span>
                                </div>
                                 <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Delivery Boy Photo</label>
                                    <input ref="fileInput1" class="form-control" @change="BoySelected" type="file"  >
                                    <span class="label label-danger" v-if="errors.delivery_boy_img">{{errors.delivery_boy_img}} </span>
                                    </div> 
                          </div>
                          <div class="card-header">
                                <h3 class="card-title"> Bank Details </h3>
                            </div>
                             <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Bank Account Name</label>
                                    <input type="text"  class="form-control"  v-model="form.account_name" placeholder="Enter Bank Account Name here" :class="{ 'is-invalid': errors.account_name }">
                                    <span class="label label-danger" v-if="errors.account_name">{{errors.account_name}} </span>
                                </div>
                               
                             <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Bank IFSC</label>
                                    <input type="text"  class="form-control"  id="ifsc" v-model="form.ifsc" placeholder="Enter Bank IFSC here" :class="{ 'is-invalid': errors.ifsc }" v-on:keyup="getifscData()">
                                    <span class="label label-danger" v-if="errors.ifsc">{{errors.ifsc}} </span>
                                </div>

                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">IFSC Details Verify</label>
                                    <textarea type="text"  class="form-control"  v-model="form.ifscverify" placeholder="IFSC Details here" disabled :class="{ 'is-invalid': errors.ifscverify,'is-invalid': errors.bank }" x></textarea>
                                    <span class="label label-danger" v-if="errors.ifscverify">{{errors.ifscverify}} </span> <span class="label label-danger" v-if="errors.bank">{{errors.bank}} </span>
                                </div>
                            </div>
                             <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Bank Account Number</label>
                                    <div class="row">
                                        <div class="col-sm-8">
                                    <input type="text"  class="form-control"  v-model="form.acount_no" placeholder="Enter Account No" :class="{ 'is-invalid': errors.acount_no }">
                                        </div>
                                    <div class="col-sm-4">
                                            <a  class="btn btn-primary" @click="getaccData">Verify</a>
                                    </div>
                                    </div>
                                    <span class="label label-danger" v-if="errors.acount_no">{{errors.acount_no}} </span>
                                
                                  </div>
                               
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Account Number Verification</label>
                                    <input type="text"  class="form-control"  v-model="form.accverify" placeholder="Account Number Verification" disabled :class="{ 'is-invalid': errors.accverify }">
                                    <span class="label label-danger" v-if="errors.accverify">{{errors.accverify}} </span>
                                </div>
                            </div>
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <!-- <div class="text-center"> -->
                            <button class="btn btn-danger text-center mt-3 mr-3">Reset</button>
                        <!-- </div> -->
                        <!-- <div class="col-sm-6"> -->
                            <button class="btn btn-primary text-center mt-3">Submit</button>
                            <!-- </div> -->
                        </div>
                    </div>
                 
                    </form>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>


         <br><br>
        <!-- Modal -->
       <orders />
       </div>
        </div>
         </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import GetOrder  from './GetOrder';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
import { log } from 'util';
    export default {
        
        components:{  
            deleteRow(i) {
               this.rowData.splice(i,1);
            },
            CImage,
            CBtn,
            CToggle,
            'orders': GetOrder,
            'v-select': vSelect,
        },
        data() {
            return {                       
                form : new Form({
                    id:'',
                    name:'',
                    password:'',
                    vendor_id:'',
                    admin_id:'',
                    mobile:'',
                    email:'',
                    last_activity:'',
                    driving_license_no:'',
                    aadhar_no:'',
                    service_area_id:'',
                    lat:'', 
                    longi:'', 
                    commodity_type_id:'', 
                    available:'', 
                    working_type:'',
                    slab:'',
                    acount_no:'',
                    bank:'',
                    ifsc:'',
                    cash_limit:'',
                    address:'',
                    pincode:'',
                    dob:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    accverify:'',
                    ifscverify:'',
                    date_of_joining:'',
                    identification_mark:'',
                    delivery_boy_img:'',
                    adhar_front_img:'',
                    adhar_back_img:'',
                    pan_img:'',
                    pan_no:'',
                    client_id:'',
                    is_active: true,
                }),
                iform:{
                    id:'',
                    name:'',
                    password:'',
                    vendor_id:'',
                    admin_id:'',
                    mobile:'',
                    email:'',
                    last_activity:'',
                    driving_license_no:'',
                    aadhar_no:'',
                    service_area_id:'',
                    lat:'', 
                    longi:'', 
                    commodity_type_id:'', 
                    available:'', 
                    working_type:'',
                    slab:'',
                    acount_no:'',
                    bank:'',
                    ifsc:'',
                    cash_limit:'',
                    address:'',
                    pincode:'',
                    dob:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    accverify:'',
                    ifscverify:'',
                    date_of_joining:'',
                    identification_mark:'',
                    delivery_boy_img:'',
                    adhar_front_img:'',
                    adhar_back_img:'',
                    pan_img:'',
                    pan_no:'',
                    client_id:'',
                    is_active: true,
                },
                blank:{
                   id:'',
                    name:'',
                    password:'',
                    vendor_id:'',
                    admin_id:'',
                    mobile:'',
                    email:'',
                    last_activity:'',
                    driving_license_no:'',
                    aadhar_no:'',
                    service_area_id:'',
                    lat:'', 
                    longi:'', 
                    commodity_type_id:'', 
                    available:'', 
                    working_type:'',
                    slab:'',
                    acount_no:'',
                    bank:'',
                    ifsc:'',
                    cash_limit:'',
                    address:'',
                    pincode:'',
                    dob:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    accverify:'',
                    ifscverify:'',
                    date_of_joining:'',
                    identification_mark:'',
                    delivery_boy_img:'',
                    adhar_front_img:'',
                    adhar_back_img:'',
                    pan_img:'',
                    pan_no:'',
                    client_id:'',
                    is_active: true,
                    
                },
                errors:{},
                multipartForm: new FormData,
                tags: '',
                formd:'',
                 vendor_id:'',
                  options:[],
                isLoading: false,
            }
        },
        methods :{
            loadOrderStatus() {
                var a =$('#authid').val();
                axios.get('/api/vendorlist/'+a).then( ({ data }) => {
                    this.options = data;
                });
            },      
            getData()
            {
                var ta =$('#pincode').val();
                if(ta.length==6)
                {
                axios.get("api/getdistrict/"+ta).then( ({ data }) => ($('#district').val(data.pro_category.District),this.form.district=data.pro_category.District, $('#state').val(data.pro_category.Circle),this.form.state=data.pro_category.Circle) ); 
                }
            },
            getifscData()
            {
                var ta =$('#ifsc').val();
                // alert(ta.length);
                if(ta.length>7)
                {
                axios.get("api/getifscdata/"+ta).then( ({ data }) => (this.setData(data))); 
                }
            },
            getaccData()
            {
                var ta =this.form.ifsc;
                var num =this.form.acount_no;
                // alert(num+'/'+ta);
                if(ta.length>7)
                {
                axios.get("api/getaccdetails/"+ta+"/data/"+num).then( ({ data }) => (this.SetAccData(data))); 
                }
            },
            
            PanSelected(e){
               // console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        //console.log('RESULT converted base 64');
                        this.form.pan_img=  reader.result;
                       // console.log(this.iform.image);
                    }
                    reader.readAsDataURL(file);
                }

            },
            AdharFrontSelected(e){
                // console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        // console.log('RESULT converted base 64');
                        this.form.adhar_front_img=  reader.result;
                        // console.log(this.iform.image);
                    }
                    reader.readAsDataURL(file);
                }

            },
            AdharBackSelected(e){
                // console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        // console.log('RESULT converted base 64');
                        this.form.adhar_back_img=  reader.result;
                        // console.log(this.iform.image);
                    }
                    reader.readAsDataURL(file);
                }

            },
            BoySelected(e){
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.form.delivery_boy_img=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }
            },
            setData(data)
            {
                if(data=='Not Found')
                {
                    this.form.ifscverify='';
                    this.form.bank='';
                }
                else
                {
                    this.form.ifscverify=data.pro_category.ADDRESS;
                    this.form.bank=data.pro_category.BANK;
                }
            },
            SetAccData(data)
            { 
              if(data.status_code==200)
              {
              this.form.client_id=data.data.client_id;
              this.form.accverify=data.data.full_name;
              }
              else
              {
                this.form.client_id='';
                this.form.accverify='';
              }
            },
            createDeliveryPerson(){
                this.$Progress.start();
              //  var a =$('#authid').val();
                this.form.vendor_id=this.vendor_id.vendor_ad_id;
                this.form.post('api/delivery_person').then( ()=>{
                    Fire.$emit('LoadDeliveryPerson');
                    toast.fire({
                        type: 'success',
                        title: 'DeliveryPerson Created successfully'
                    });
                    setTimeout(()=>{
                    window.location.reload();
                    }, 1000);
                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadDeliveryPersonList();
            },
            newForm(){
                this.errors = {};
                this.iform =this.blank;
                this.editMode = false;
                this.multipartForm = new FormData;
                this.supSubCategories = {};
                this.subCategories = {};
            },
           

        },
        mounted() 
        {
            this.isLoading = true;  
        },
        created(){
          this.loadOrderStatus();
            Fire.$on('LoadProduct', () => this.loadProductList() );
        }
    }
   
</script>

<style scoped>
img
{
    max-width : 3vh;
    max-height : 3vh
}
.disnone{display: none!important;}
table input, table select{width: 85%!important;}
table .btn-sm, .btn-group-sm > .btn {
    padding: 0.25rem 0.25rem!important;}
</style>

<style scoped lang="scss">
.imagePreviewWrapper {
    width: 100px;
    height: 100px;
    display: block;
    cursor: pointer;
    margin: 0 auto 10px;
    background-size: contain;
    background-position: center center;
    background-repeat: no-repeat ;
    border:1px solid black;
}


.card-title {
    margin-top: 0.75rem!important;
    margin-bottom: 0px!important;
}
</style>