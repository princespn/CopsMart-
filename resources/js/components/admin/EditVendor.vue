<template>
    <div class="container  p-0">

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2">Add Vendor</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                         <form @submit.prevent="updateCategory()">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label"> Vendor Name</label>
                                    <input type="text"  class="form-control"  v-model="iform.name" placeholder="Enter Vendor Name here" :class="{ 'is-invalid': errors.name }">
                                    <span class="label label-danger" v-if="errors.name">{{errors.name}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label"> Business Name </label>
                                    <input type="text"  class="form-control"  v-model="iform.business_name" placeholder="Enter Business Name here" :class="{ 'is-invalid': errors.business_name }">
                                    <span class="label label-danger" v-if="errors.business_name">{{errors.business_name}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">GSTIN</label>
                                    <input type="text"  class="form-control"  v-model="iform.gstin" placeholder="Enter GSTIN here " :class="{ 'is-invalid': errors.gstin }">
                                    <span class="label label-danger" v-if="errors.gstin">{{errors.gstin}} </span>
                                </div>
                            </div>
                             <div class="form-group row" >
                                <div class="col-sm-4">
                                    <label class="col-form-label">Businee Address </label>
                                    <textarea type="text"  class="form-control"  v-model="iform.address" placeholder="Enter Business Address " :class="{ 'is-invalid': errors.address }"></textarea>
                                    <span class="label label-danger"  v-if="errors.address">{{errors.address}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">PIN Code</label>
                                    <input type="text"  class="form-control" v-on:keyup="getData()"  v-model="iform.pincode" placeholder="Enter PIN Code" id="pincode"   :class="{ 'is-invalid': errors.pincode }">
                                    <span class="label label-danger" v-if="errors.pincode">{{errors.pincode}} </span>
                                </div>
                                
                                <div class="col-sm-4">
                                    <label class="col-form-label">District</label>
                                    <input type="text"  class="form-control"  v-model="iform.district" placeholder="Enter District" id="district" :class="{ 'is-invalid': errors.district }" readonly>
                                    <span class="label label-danger" v-if="errors.district">{{errors.district}} </span>
                                </div>
                            </div>
                            <div class="form-group row" >
                                <div class="col-sm-4">
                                    <label class="col-form-label">State</label>
                                    <input type="text"  class="form-control"  v-model="iform.state" placeholder="Enter State" id="state" :class="{ 'is-invalid': errors.state }" readonly>
                                    <span class="label label-danger" v-if="errors.state">{{errors.state}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Latitude</label>
                                    <input type="text"  class="form-control"  v-model="iform.latitude" placeholder="Enter Latitude" :class="{ 'is-invalid': errors.latitude }">
                                    <span class="label label-danger" v-if="errors.latitude">{{errors.latitude}} </span>
                                </div>
                                
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Longitude</label>
                                    <input type="text"  class="form-control"  v-model="iform.longitude" placeholder="Enter Longitude" :class="{ 'is-invalid': errors.longitude }">
                                    <span class="label label-danger" v-if="errors.longitude">{{errors.longitude}} </span>
                                </div>
                            </div>
                            <div class="card-header">
                               <h4 class="card-title"> Business Details </h4>
                            </div>
                    
                            <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Contact Person Name</label>
                                    <input type="text"  class="form-control"  v-model="iform.contact_person_name" placeholder="Enter Contact Person Name" :class="{ 'is-invalid': errors.contact_person_name }">
                                    <span class="label label-danger" v-if="errors.contact_person_name">{{errors.contact_person_name}} </span>
                                </div>
                               
                             <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Mobile Number</label>
                                    <input type="text"  class="form-control"  v-model="iform.contact_person_mobile" placeholder="Enter Mobile Number" maxlength="10" :class="{ 'is-invalid': errors.contact_person_mobile }">
                                    <span class="label label-danger" v-if="errors.contact_person_mobile">{{errors.contact_person_mobile}} </span>
                                </div>

                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Email ID</label>
                                    <input type="email"  class="form-control"  v-model="iform.email" placeholder="Enter Email ID here" :class="{ 'is-invalid': errors.email }">
                                    <span class="label label-danger" v-if="errors.email">{{errors.email}} </span>
                                </div>
                            </div>

                            <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Business Contact Number</label>
                                    <input type="text"  class="form-control"  v-model="iform.contact_no" placeholder="Enter Businees Contact Number" :class="{ 'is-invalid': errors.contact_no }">
                                    <span class="label label-danger" v-if="errors.contact_no">{{errors.contact_no}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Canteen App Name</label>
                                    <input type="text"  class="form-control"  v-model="iform.app_name" placeholder="Enter Canteen App Name" :class="{ 'is-invalid': errors.app_name }">
                                    <span class="label label-danger" v-if="errors.app_name">{{errors.app_name}} </span>
                                </div>
                            </div>
                            
                            <div class="card-header">
                                    <h4 class="card-title"> Business Documents Uploads </h4>
                            </div>
                            <div class="form-group row">
                                 <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Shop Photo</label>
                                    <input ref="fileInput1" class="form-control p-1" @change="fileSelectedshopimg" type="file" @input="pickFile1" >
                                    <span class="label label-danger" v-if="errors.shop_image">{{errors.shop_image}} </span>
                                    <br>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage1})` }"
                                      @click="selectImage1">
                                    </div>
                                </div>
                            
                                <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Work Order</label>
                                    <input ref="fileInput2" class="form-control p-1" @change="fileSelectedworkorder" type="file" @input="pickFile2" >
                                    <span class="label label-danger" v-if="errors.work_order">{{errors.work_order}}</span>
                                    <br>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage2})` }"
                                      @click="selectImage2">
                                    </div>
                                </div>
                        
                                <div class="col-sm-4" >
                                    <label class="col-form-label text-center">GSTIN Certificate</label>
                                    <input ref="fileInput3" class="form-control p-1" @change="fileSelectedgstcrt" type="file" @input="pickFile3" >
                                    <span class="label label-danger" v-if="errors.gstin_certificate">{{errors.gstin_certificate}} </span>
                                    <br>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage3})` }"
                                      @click="selectImage3">
                                    </div>
                                </div>
                            
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Shop Act or Other Documents</label>
                                    <input ref="fileInput4" class="form-control p-1" @change="fileSelectedshopact" type="file" @input="pickFile4" >
                                    <span class="label label-danger" v-if="errors.shop_act">{{errors.shop_act}} </span>
                                    <br>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage4})` }"
                                      @click="selectImage4">
                                    </div>
                                </div>
                            
                                <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Canteen App Icon</label>
                                    <input ref="fileInput5" class="form-control p-1" @change="fileSelectedicon" type="file" @input="pickFile5" >
                                    <span class="label label-danger" v-if="errors.app_icon">{{errors.app_icon}} </span>
                                    <br>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage5})` }"
                                      @click="selectImage5">
                                    </div>
                                </div>
                        
                                <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Bank Account Cheque / Passbook / Other Doc.</label>
                                    <input ref="fileInput6" class="form-control p-1" @change="fileSelectedbank" type="file" @input="pickFile6" >
                                    <span class="label label-danger" v-if="errors.bank_document">{{errors.bank_document}} </span>
                                    <br>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage6})` }"
                                      @click="selectImage6">
                                    </div>
                                </div>
                            
                            </div>
                            <div class="card-header">
                                <h4 class="card-title"> Bank Details </h4>
                            </div>
                             <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Bank Account Name</label>
                                    <input type="text"  class="form-control"  v-model="iform.bank_account_name" placeholder="Enter Name On Account" :class="{ 'is-invalid': errors.bank_account_name }">
                                    <span class="label label-danger" v-if="errors.bank_account_name">{{errors.bank_account_name}} </span>
                                </div>
                               
                             <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Bank IFSC</label>
                                    <input type="text"  class="form-control" id="ifsc"  v-model="iform.bank_ifsc" v-on:keyup="getifscData()" placeholder="Enter IFSC Number" :class="{ 'is-invalid': errors.bank_ifsc }">
                                    <span class="label label-danger" v-if="errors.bank_ifsc">{{errors.bank_ifsc}} </span>
                                </div>

                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">IFSC Details Verify</label>
                                    <input type="text"  class="form-control"  v-model="iform.ifscverify"  disabled :class="{ 'is-invalid': errors.ifscverify }">
                                    <span class="label label-danger" v-if="errors.ifscverify">{{errors.ifscverify}} </span>
                                </div>
                            </div>
                             <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Bank Account Number</label>
                                    <div class="row">
                                        <div class="col-sm-8">
                                    <input type="text"  class="form-control" id="accnum"  v-model="iform.bank_account_number" placeholder="Enter Account Number" :class="{ 'is-invalid': errors.bank_account_number }">
                                        </div>
                                    <div class="col-sm-4 accv" >
                                            <a @click="accountverify()" class="btn p-1 btn-primary">Verify</a>
                                    </div>
                                    
                                    </div>
                                    <span class="label label-danger" v-if="errors.bank_account_number">{{errors.bank_account_number}} </span>
                                
                                  </div>
                               
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Account Number Verification</label>
                                    <input type="text"  class="form-control"  v-model="iform.account_verification" disabled :class="{ 'is-invalid': errors.account_verification }">
                                    <span class="label label-danger" v-if="errors.account_verification">{{errors.account_verification}} </span>
                                </div>
                            </div>
                            <div class="card-header">
                               <h4 class="card-title">Delivery Details </h4>
                            </div>
                            <div class="form-group row">
                        <div class="col-sm-4">
                        <p for="staticEmail" class="col-form-label">Do you have Delivery Boys for Base City?</p>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" v-model="iform.delivery_for_base_city" name="city" value="Yes">
                        <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" v-model="iform.delivery_for_base_city" name="city" value="No">
                        <label class="form-check-label" for="inlineCheckbox2">No</label>
                        </div>
                      
                      </div>

                    <div class="col-sm-4">
                        <p for="staticEmail" class="col-form-label">Do you have Delivery Service for District?</p>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" v-model="iform.delivery_service_for_district" name="district" type="radio" id="inlineCheckbox1" value="Yes">
                        <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" v-model="iform.delivery_service_for_district" name="district"  id="inlineCheckbox2" value="No">
                        <label class="form-check-label" for="inlineCheckbox2">No</label>
                        </div>
                    </div>
                      <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Pickup Charges</label>
                                    <input type="text"  class="form-control"  v-model="iform.pickup_charges" placeholder="Enter Pickup Charges" :class="{ 'is-invalid': errors.pickup_charges }">
                                    <span class="label label-danger" v-if="errors.pickup_charges">{{errors.pickup_charges}} </span>
                        </div>
                            </div>
                           
                            
                            <div class="card-header">
                               <h4 class="card-title">Set Charges</h4>
                            </div>
                            
                            <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">PG-Charges</label>
                                    <input type="text"  class="form-control"  v-model="iform.pg_charges" placeholder="Enter PG-Charges" :class="{ 'is-invalid': errors.pg_charges }">
                                    <span class="label label-danger" v-if="errors.other">{{errors.pg_charges}} </span>
                                </div>
                               
                             <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Sales Percent</label>
                                    <input type="text"  class="form-control"  v-model="iform.sales_percent" placeholder="Enter Sales Percent" :class="{ 'is-invalid': errors.sales_percent }">
                                    <span class="label label-danger" v-if="errors.sales_percent">{{errors.sales_percent}} </span>
                                </div>

                                 <div class="col-sm-2">
                                    <label for="staticEmail" class="col-form-label">Online Product show</label>
                                    <input type="text"  class="form-control"  v-model="iform.online"  :class="{ 'is-invalid': errors.online }">
                                    <span class="label label-danger" v-if="errors.other">{{errors.online}} </span>
                                </div>
                                 <div class="col-sm-2">
                                    <label for="staticEmail" class="col-form-label">Offline Product show</label>
                                    <input type="text"  class="form-control"  v-model="iform.offline" :class="{ 'is-invalid': errors.offline }">
                                    <span class="label label-danger" v-if="errors.offline">{{errors.offline}} </span>
                                </div>
                            </div>
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <!-- <div class="text-center"> -->
                            <button class="btn btn-danger text-center mt-3 " @click="location.reload();">Reset</button>
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
    import $ from "jquery";
    export default {     
        components:{  
            CImage,
            CBtn,
            CToggle,
            'v-select': vSelect,
        },
        data() {

            
            return {
                gender: "",
                city:"",
                rowId: 10,
                rowData:[{val:"1"}],                
               
                selected: [],
                options: [],
                form : new Form({
                    id:'',
                    admin_id:'',
                    name:'',
                    business_name: '',
                    gstin: '',
                    address: '',
                    pincode: '',
                    district: '',
                    state: '',
                    latitude: '',
                    longitude:'',
                    contact_person_name:'',
                    contact_person_mobile:'',
                    email: '',
                    contact_no:'',
                    app_name: '',
                    //images
                    shop_image: '',
                    work_order: '',
                    gstin_certificate: '',
                    shop_act: '',
                    app_icon: '',
                    bank_document:'',
                        //imagess
                        shop_images: '',
                        work_orders: '',
                        gstin_certificates: '',
                        shop_acts: '',
                        app_icons: '',
                        bank_documents:'',
                        //imagess
                    //images
                    bank_account_name:'',
                    bank_ifsc:'',
                    ifscverify: '',
                    ifscverifybank:'',
                    bank_account_number:'',
                    account_v_name:'' , 
                    account_verification: '',              
                    delivery_for_base_city:'',
                    delivery_service_for_district:'',
                    pickup_charges: '',
                    password:'',
                    pg_charges: '',
                    sales_percent:'',
                    online:'',
                    offline: '',
                    is_active:true,
                }),
                iform:{
                    id:'',
                    admin_id:'',
                    name:'',
                    business_name: '',
                    gstin: '',
                    address: '',
                    pincode: '',
                    district: '',
                    state: '',
                    latitude: '',
                    longitude:'',
                    contact_person_name:'',
                    contact_person_mobile:'',
                    email: '',
                    contact_no:'',
                    app_name: '',
                    //images
                    shop_image: '',
                    work_order: '',
                    gstin_certificate: '',
                    shop_act: '',
                    app_icon: '',
                    bank_document:'',
                       //imagess
                        shop_images: '',
                        work_orders: '',
                        gstin_certificates: '',
                        shop_acts: '',
                        app_icons: '',
                        bank_documents:'',
                        //imagess
                    //images
                    bank_account_name:'',
                    bank_ifsc:'',
                    ifscverify: '',
                    ifscverifybank:'',
                    bank_account_number:'',
                    account_v_name:'' , 
                    account_verification: '',              
                    delivery_for_base_city:'',
                    delivery_service_for_district:'',
                    pickup_charges: '',
                    password:'',
                    pg_charges: '',
                    sales_percent:'',
                    online:'',
                    offline: '',
                    is_active:true,
                },
                blank:{
                    id:'',
                    admin_id:'',
                    name:'',
                    business_name: '',
                    gstin: '',
                    address: '',
                    pincode: '',
                    district: '',
                    state: '',
                    latitude: '',
                    longitude:'',
                    contact_person_name:'',
                    contact_person_mobile:'',
                    email: '',
                    contact_no:'',
                    app_name: '',
                    //images
                    shop_image: '',
                    work_order: '',
                    gstin_certificate: '',
                    shop_act: '',
                    app_icon: '',
                    bank_document:'',
                      //imagess
                        shop_images: '',
                        work_orders: '',
                        gstin_certificates: '',
                        shop_acts: '',
                        app_icons: '',
                        bank_documents:'',
                        //imagess
                    //images
                    bank_account_name:'',
                    bank_ifsc:'',
                    ifscverify: '',
                    ifscverifybank:'',
                    bank_account_number:'',
                    account_verification: '',  
                    account_v_name:'' ,           
                    delivery_for_base_city:'',
                    delivery_service_for_district:'',
                    pickup_charges: '',
                    password:'',
                    pg_charges: '',
                    sales_percent:'',
                    online:'',
                    offline: '',
                    is_active:true,
                },
                 fform: new Form({
                    account :'',
                    ifsc :'',
                }), 
                errors:{},
                multipartForm: new FormData,
                editMode: false,
                isLoading: false,
                previewImage1: null,
                previewImage2: null,
                previewImage3: null,
                previewImage4: null,
                previewImage5: null,
                previewImage6: null,
                editId:'',
            }
        },
        methods :{
           
            updateCategory(){
                this.$Progress.start();
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }
                axios.post('/api/updatevendor/' + this.iform.id, this.multipartForm, config).then( ()=>{
                toast.fire({
                    type: 'success',
                    title: this.iform.name +' Updated successfully'
                });
                this.$Progress.finish();
                location.reload();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadEditProduct()
            {
               axios.get("/api/getvendor/"+this.editId).then( ({ data }) => (console.log(data),this.editForm(data)) ); 
            },

            getData()
            {
                var ta =$('#pincode').val();
                if(ta.length==6)
                { 
                    this.isLoading = true;
                axios.get("/api/getdistrict/"+ta).then( ({ data }) => ($('#district').val(data.pro_category.District),this.iform.district=data.pro_category.District, $('#state').val(data.pro_category.Circle),this.iform.state=data.pro_category.Circle),this.isLoading = false ); 
                }
            },
            accountverify()
            {
                this.fform.account=this.iform.bank_account_number;
                this.fform.ifsc=this.iform.bank_ifsc;
                 this.$Progress.start();
                this.fform.post('/api/getaccounntverified').then( (data)=>{
                        // console.log(data);
                        if(data.data=='NOT')
                        { 
                           toast.fire({
                                type: 'warning',
                                title: 'Account Not Verified !!'
                            });
                              this.$Progress.fail();
                        }
                        else
                        {    
                           this.iform.account_verification=data.data.pro_category.data.client_id;
                           this.iform.account_v_name=data.data.pro_category.data.full_name;
                           $('.accv').css('display','none');  
                           $('.accvc').css('display','block');   
                             this.$Progress.finish();               
                        }
                       }).catch(()=>{
                            toast.fire({
                                            type: 'warning',
                                            title: 'Account Not Verified !!'
                                        });
                            this.$Progress.fail();
                });
            },

            getifscData()
            {
                var ta =$('#ifsc').val();
                // alert(ta.length);
                if(ta.length>7)
                {
                axios.get("/api/getifscdata/"+ta).then( ({ data }) => (this.setData(data))); 
                }
                else
                {
                    this.iform.ifscverify='';
                    this.iform.ifscverifybank='';
                }
            },
            setData(data)
            {
                if(data=='Not Found')
                {
                    this.iform.ifscverify='';
                    this.iform.ifscverifybank='';
                }
                else
                {
                    this.iform.ifscverify=data.pro_category.ADDRESS;
                    this.iform.ifscverifybank=data.pro_category.BANK;
                }
            },
            newForm(){
                this.errors = {};
                this.iform =this.blank;
                this.editMode = false;
                this.multipartForm = new FormData;
            },
             editForm(data){
                this.iform=this.blank;
                this.iform=data;
                this.editMode = true;
                if(data.account_verification!='')
                {
                   $('#accnum').attr('disabled',true);
                   $('.accv').css('display','none');
                }
                if(data.ifscverify!='')
                {
                   $('#ifsc').attr('disabled',true);
                }
                 if(data.shop_image!='')
                {
                    this.previewImage1='/public/uploads/images/vendor/' + data.shop_image;
                }
                if(data.work_order!='')
                {
                    this.previewImage2='/public/uploads/images/vendor/' + data.work_order;
                }
                if(data.gstin_certificate!='')
                {
                    this.previewImage3='/public/uploads/images/vendor/' + data.gstin_certificate;
                }
                if(data.shop_act!='')
                {
                    this.previewImage4='/public/uploads/images/vendor/' + data.shop_act;
                }
                if(data.app_icon!='')
                {
                    this.previewImage5='/public/uploads/images/vendor/' + data.app_icon;
                }
                if(data.bank_document!='')
                {
                    this.previewImage5='/public/uploads/images/vendor/' + data.bank_document;
                }
            },
       
            fileSelectedshopimg(e){
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.iform.shop_images=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }
            },

            fileSelectedworkorder(e){
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.iform.work_orders=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }
            },

            fileSelectedgstcrt(e){
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.iform.gstin_certificates=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }
            },

            fileSelectedshopact(e){
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.iform.shop_acts=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }
            },

            fileSelectedicon(e){
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.iform.app_icons=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }
            },

            fileSelectedbank(e){
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.iform.bank_documents=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }
            },

        selectImage1 () {
        this.$refs.fileInput1.click();
        },
        pickFile1 () {
            let input = this.$refs.fileInput1
            let file = input.files
            if (file && file[0]) {
            let reader = new FileReader
            reader.onload = e => {
            this.previewImage1 = e.target.result
            }
            reader.readAsDataURL(file[0])
            this.$emit('input', file[0])
            }
        },

        selectImage2 () {
        this.$refs.fileInput2.click();
        },
        pickFile2 () {
            let input = this.$refs.fileInput2
            let file = input.files
            if (file && file[0]) {
            let reader = new FileReader
            reader.onload = e => {
            this.previewImage2 = e.target.result
            }
            reader.readAsDataURL(file[0])
            this.$emit('input', file[0])
            }
        },

        selectImage3 () {
        this.$refs.fileInput3.click();
        },
        pickFile3 () {
            let input = this.$refs.fileInput3
            let file = input.files
            if (file && file[0]) {
            let reader = new FileReader
            reader.onload = e => {
            this.previewImage3 = e.target.result
            }
            reader.readAsDataURL(file[0])
            this.$emit('input', file[0])
            }
        },

        selectImage4 () {
        this.$refs.fileInput4.click();
        },
        pickFile4 () {
            let input = this.$refs.fileInput4
            let file = input.files
            if (file && file[0]) {
            let reader = new FileReader
            reader.onload = e => {
            this.previewImage4 = e.target.result
            }
            reader.readAsDataURL(file[0])
            this.$emit('input', file[0])
            }
        },

        selectImage5 () {
          this.$refs.fileInput5.click();
        },
        pickFile5 () {
            let input = this.$refs.fileInput5
            let file = input.files
            if (file && file[0]) {
            let reader = new FileReader
            reader.onload = e => {
            this.previewImage5 = e.target.result
            }
            reader.readAsDataURL(file[0])
            this.$emit('input', file[0])
            }
        },

        selectImage6 () 
        {
          this.$refs.fileInput6.click();
        },

        pickFile6 ()
        {
            let input = this.$refs.fileInput6
            let file = input.files
            if (file && file[0]) {
                let reader = new FileReader
                reader.onload = e => {
                this.previewImage6 = e.target.result
            }
            reader.readAsDataURL(file[0])
            this.$emit('input', file[0])
            }
        },
      },
        mounted() 
        {
        //    this.isLoading = true;  
        },
        created(){
            this.editId = this.$route.params.OrderId;
            this.loadEditProduct();
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