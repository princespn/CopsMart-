<template>
    <div class="container p-0">
        <!-- /.row -->
        <div class="row mt-2 ml-2">
            <h2>Add Customer</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2"> Add Customer </h3>
                        <div class="card-tools">
                            <a type="button" class="btn btn-sm btn-primary mr-3"  href="/CustomerManagement">Customer List</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                         <form @submit.prevent="createProduct()">
                          <div class="form-group row">
                              <div class="col-sm-4">
                                    <label class="col-form-label">Select Vendor</label>
                                    <v-select   v-model="vendor_id" label="name" :options="options"  style="text-transform: capitalize!important;"    :class="{ 'is-invalid': errors.vendor_id }"/>
                                </div>
                               <div class="col-sm-4">
                                    <label class="col-form-label">Name</label>
                                    <input type="text" style="text-transform: capitalize!important;"  class="form-control"  v-model="iform.name" placeholder="Enter Name here" :class="{ 'is-invalid': errors.name }">
                                    <span class="label label-danger" v-if="errors.name">{{errors.name}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label class="col-form-label">Mobile Number</label>
                                    <input type="number"  class="form-control"  v-model="iform.mobile" placeholder="Enter Mobile Number here" :class="{ 'is-invalid': errors.mobile }">
                                    
                                    <span class="label label-danger" v-if="errors.mobile">{{errors.mobile}} </span>
                                </div>
                               
                                
                          </div>
                           <div class="form-group row">
                                 <div class="col-sm-4">
                                    <label class="col-form-label">Email</label>
                                    <input type="email"  class="form-control"  v-model="iform.email" placeholder="Enter Email here" :class="{ 'is-invalid': errors.email }">
                                    
                                    <span class="label label-danger" v-if="errors.email">{{errors.email}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-form-label">Canteen Card No.</label>
                                    <input type="number"  class="form-control"  v-model="iform.card_no" placeholder="Enter Canteen Card No. here" :class="{ 'is-invalid': errors.card_no }">
                                    
                                    <span class="label label-danger" v-if="errors.card_no">{{errors.card_no}} </span>
                                </div>
                               <div class="col-sm-4">
                                    <label class="col-form-label">Bukkle No.</label>
                                    <input type="number"  class="form-control"  v-model="iform.bukkle_no" placeholder="Enter Bukkle Number here" :class="{ 'is-invalid': errors.bukkle_no }">
                                    <span class="label label-danger" v-if="errors.bukkle_no">{{errors.bukkle_no}} </span>
                                </div>

                           </div>
                          <div class="form-group row">
                              <div class="col-sm-4">
                                    <label class="col-form-label">Employee Post</label>
                                    <input type="text" style="text-transform: capitalize!important;" class="form-control"  v-model="iform.employee_post" placeholder="Enter Employee here" :class="{ 'is-invalid': errors.employee_post }">
                                    
                                    <span class="label label-danger" v-if="errors.employee_post">{{errors.employee_post}} </span>
                                </div>
                               <div class="col-sm-4">
                                    <label class="col-form-label">PIN Code</label>
                                    <input type="number"  class="form-control" id="pincode"  autocomplete="off" v-model="iform.pincode" placeholder="Enter PIN Code here" :class="{ 'is-invalid': errors.pincode }"  v-on:keyup="getData()">
                                    
                                    <span class="label label-danger" v-if="errors.pincode">{{errors.pincode}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-form-label">District</label>
                                    <input type="text" style="text-transform: capitalize!important;" class="form-control"  v-model="iform.district"  placeholder="Enter District Name here" :class="{ 'is-invalid': errors.district }">
                                    <span class="label label-danger" v-if="errors.district">{{errors.district}} </span>
                                </div>
                                
                                
                          </div>
                          <div class="form-group row">
                               <div class="col-sm-4">
                                    <label class="col-form-label">State</label>
                                    <input type="text" style="text-transform: capitalize!important;"  class="form-control"  v-model="iform.state"  placeholder="Enter State Name here" :class="{ 'is-invalid': errors.state }">
                                    
                                    <span class="label label-danger" v-if="errors.state">{{errors.state}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Date of Birth</label>
                                    <input type="date"  class="form-control"  v-model="iform.dob" placeholder="Enter DOB here" :class="{ 'is-invalid': errors.dob }">
                                    <span class="label label-danger" v-if="errors.dob">{{errors.dob}} </span>
                                </div>
                                   <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Date of Joining</label>
                                    <input type="date"  class="form-control"  v-model="iform.date_of_joining" placeholder="Enter DOJ here" :class="{ 'is-invalid': errors.date_of_joining }">
                                    <span class="label label-danger" v-if="errors.date_of_joining">{{errors.date_of_joining}} </span>
                                </div>
                                  
                          </div>
                          <div class="form-group row">
                               <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Date of Retirement</label>
                                    <input type="date"  class="form-control"  v-model="iform.date_of_retirement" placeholder="Enter DOR here" :class="{ 'is-invalid': errors.date_of_retirement }">
                                    <span class="label label-danger" v-if="errors.date_of_retirement">{{errors.date_of_retirement}} </span>
                                </div>
                               <div class="col-sm-4">
                                    <label class="col-form-label">Blood Group </label>
                                    <!-- <input type="text"  class="form-control"  v-model="iform.blood_group" placeholder="Enter Blood Group here" :class="{ 'is-invalid': errors.blood_group }"> -->
                                     <select class="form-control" v-model="iform.blood_group" placeholder="Select Type" :class="{ 'is-invalid': errors.type }" v-on:change="getTypeselected(this)">
                                        <option value="A +">A +</option>
                                        <option value="A -">A -</option>
                                        <option value="B +">B +</option>
                                        <option value="B -">B -</option>
                                        <option value="0 -">0 -</option>
                                        <option value="0 +">0 +</option>
                                        <option value="AB +">AB +</option>
                                        <option value="AB -">AB -</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.blood_group">{{errors.blood_group}} </span>
                                </div>
                              
                                 <div class="col-sm-4" >
                                    <label class="col-form-label text-center">Customer Photo</label>
                                    <input ref="fileInput1" class="form-control" @change="fileSelected" type="file" @input="pickFile1" >
                                    <span class="label label-danger" v-if="errors.image">{{errors.image}} </span>
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
            CImage,
            CBtn,
            'orders': GetOrder,
            CToggle,
            'v-select': vSelect,
        },
        data() {
            return {
                form : new Form({
                    vendor_id:'',
                    name:'',
                    email:'',
                    mobile:'',
                    card_no:'',
                    bukkle_no:'',
                    employee_post:'',
                    pincode:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    date_of_joining:'',
                    date_of_retirement:'',
                    dob:'',
                    identification_mark:'',
                    image:'',
                    is_active:true,
                }),
                iform:{
                    vendor_id:'',
                    name:'',
                    email:'',
                    mobile:'',
                    card_no:'',
                    bukkle_no:'',
                    dob:'', 
                    employee_post:'',
                    pincode:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    date_of_joining:'',
                    date_of_retirement:'',
                    identification_mark:'',
                    image:'',
                    is_active:true,
                },
                blank:{
                    vendor_id:'',
                    name:'',
                    email:'',
                    mobile:'',
                    card_no:'',
                    bukkle_no:'',
                    gender:'',
                    dob:'',
                    employee_post:'',
                    pincode:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    date_of_joining:'',
                    date_of_retirement:'',
                    identification_mark:'',
                    image:'',
                    is_active:true,
                },
                errors:{},
                options:[],
                multipartForm: new FormData,
                vendor_id:'',
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
                axios.get("api/getdistrict/"+ta).then( ({ data }) => ($('#district').val(data.pro_category.District),this.iform.district=data.pro_category.District, $('#state').val(data.pro_category.Circle),this.iform.state=data.pro_category.Circle) ); 
                }
            },

            createProduct(){
                this.$Progress.start();
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.iform.vendor_id = this.vendor_id.vendor_ad_id;   
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }
                axios.post('/api/addVcustomer', this.multipartForm, config).then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Customer Created successfully'
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
          
            newForm(){
                this.errors = {};
                this.iform =this.blank;
                this.editMode = false;
                this.multipartForm = new FormData;
                this.supSubCategories = {};
                this.subCategories = {};
            },
            
             fileSelected(e){
                console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        // console.log('RESULT converted base 64');
                        this.iform.image=  reader.result;
                        console.log(this.iform.image);
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
   

        },
        mounted() 
        {
            this.isLoading = true;  
        },
        created(){
            this.loadOrderStatus();
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