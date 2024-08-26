<template>
    <div class="container p-0">
        <!-- /.row -->
       
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"> 
                        <h3 class="card-title ml-3">Add Purchase Vendor</h3>
                    </div> 
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                         <form @submit.prevent="createProduct()">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Vendor Name</label>
                                    <input type="text" style="text-transform: capitalize!important;"  class="form-control"  v-model="iform.name" placeholder="Vendor Name" :class="{ 'is-invalid': errors.name }">
                                    <span class="label label-danger" v-if="errors.name">{{errors.name}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Mobile Number</label>
                                    <input type="number"   class="form-control"  v-model="iform.mobile_no" placeholder="Mobile Number" :class="{ 'is-invalid': errors.mobile_no }">
                                    <span class="label label-danger" v-if="errors.barcode">{{errors.mobile_no}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Contact Person Name</label>
                                    <input type="text" style="text-transform: capitalize!important;"  class="form-control"  v-model="iform.contact_person" placeholder="Contact Person Name" :class="{ 'is-invalid': errors.contact_person }">
                                    <span class="label label-danger" v-if="errors.hsn">{{errors.contact_person}} </span>
                                </div>
                            </div>
                             <div class="form-group row" >
                                <div class="col-sm-4">
                                    <label class="col-form-label">Employee post</label>
                                    <input type="text" style="text-transform: capitalize!important;" class="form-control"  v-model="iform.emp_post" placeholder="Employee Post" :class="{ 'is-invalid': errors.emp_post }">
                                    <span class="label label-danger" v-if="errors.hsn">{{errors.emp_post}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">GSTIN</label>
                                    <input type="text"  class="form-control" style="text-transform: uppercase!important;"  v-model="iform.gst" placeholder="GSTIN" :class="{ 'is-invalid': errors.gst }">
                                    <span class="label label-danger" v-if="errors.maxqty">{{errors.gst}} </span>
                                </div>
                                
                                <div class="col-sm-4">
                                    <label class="col-form-label">Address</label>
                                    <textarea class="form-control" style="text-transform: capitalize!important;"  v-model="iform.address" :class="{ 'is-invalid': errors.address }"> </textarea>
                                    <span class="label label-danger" v-if="errors.address">{{errors.address}} </span>
                                </div>
                            </div>

                            <div class="form-group row" >
                                <div class="col-sm-4">
                                    <label class="col-form-label">PIN Code</label>
                                    <input type="number" id="pincode"  class="form-control"  v-model="iform.pincode" placeholder="Pincode" :class="{ 'is-invalid': errors.pincode }" v-on:keyup="getData()">
                                    <span class="label label-danger" v-if="errors.weight">{{errors.pincode}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">District</label>
                                    <input type="text" style="text-transform: capitalize!important;" id="district" readonly  class="form-control"  v-model="iform.district" placeholder="District" :class="{ 'is-invalid': errors.district }">
                                    <span class="label label-danger" v-if="errors.other">{{errors.district}} </span>
                                </div>
                                
                                <div class="col-sm-4">
                                    <label class="col-form-label">State</label>
                                    <input type="text" style="text-transform: capitalize!important;" id="state" readonly class="form-control"  v-model="iform.state" placeholder="State" :class="{ 'is-invalid': errors.state }">
                                    <span class="label label-danger" v-if="errors.other">{{errors.state}} </span>
                                </div>
                            </div>
                            <div class="form-group row">  
                                <div class="col-sm-4">
                                    <label class="col-form-label">Bank Name</label>
                                    <input type="text" style="text-transform: capitalize!important;" class="form-control"  v-model="iform.bankname" placeholder="Bank Name" :class="{ 'is-invalid': errors.bankname }">
                                    <span class="label label-danger" v-if="errors.other">{{errors.bankname}} </span>
                                </div>
                               
                                <div class="col-sm-4">
                                    <label class="col-form-label">Bank Account Name</label>
                                    <input type="text" style="text-transform: capitalize!important;" class="form-control"  v-model="iform.account_name" placeholder="Bank Account Name" :class="{ 'is-invalid': errors.account_name }">
                                    <span class="label label-danger" v-if="errors.other">{{errors.account_name}} </span>
                                </div>

                                 <div class="col-sm-4">
                                    <label class="col-form-label">Bank Account Number</label>
                                    <input type="text"  class="form-control"  v-model="iform.account_no" placeholder="Bank Account Number" :class="{ 'is-invalid': errors.account_no }">
                                    <span class="label label-danger" v-if="errors.other">{{errors.account_no}} </span>
                                </div>
                            </div>

                            <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label class="col-form-label">IFSC Code</label>
                                    <input type="text" id="ifsc"  class="form-control" v-on:keyup="getifscData()" v-model="iform.ifsc" placeholder="IFSC Code" :class="{ 'is-invalid': errors.ifsc }">
                                    <span class="label label-danger" v-if="errors.other">{{errors.ifsc}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">IFSC Details Verify</label>
                                    <textarea type="text" style="text-transform: capitalize!important;" class="form-control"  v-model="iform.ifscverify" placeholder="IFSC Details here" disabled :class="{ 'is-invalid': errors.ifscverify,'is-invalid': errors.ifscverifybank }"></textarea>
                                    <span class="label label-danger" v-if="errors.ifscverify">{{errors.ifscverify}} </span> <span class="label label-danger" v-if="errors.ifscverifybank">{{errors.ifscverifybank}} </span>
                                </div>
                            </div>
                          
                           

                        <div class="row text-center"> 
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary text-center pl-5 pr-5">Submit</button></div>
                            <div class="col-md-4"></div>
                            
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
            'v-select': vSelect,
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
                    ifscverify:'',
                    ifscverifybank:''
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
                },
                isLoading:false,  
                errors:{},
                multipartForm: new FormData,
                SetData:{},
            }
        },
        methods :{

           
            createProduct(){
                var a =$('#authid').val();
                this.$Progress.start();
                // this.multipartForm = new FormData;
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.iform.vendor_id = a;
              
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }
                
                axios.post('api/purchasevendor', this.multipartForm, config).then( ()=>{
                    //Fire.$emit('LoadProduct');
                    toast.fire({
                        type: 'success',
                        title: 'Purchase Vendor Created successfully'
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
            getifscData()
            {
                var ta =$('#ifsc').val();
                // alert(ta.length);
                if(ta.length>7)
                {
                axios.get("api/getifscdata/"+ta).then( ({ data }) => (this.setData(data))); 
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
            //  getCategory(){
            //     var a =$('#authid').val();
            //     axios.get("api/getdistrict/"+a).then( ({ data }) => ($('#district').val(data.pro_category.District)) ); 
            // },

            getData()
            {
                var ta =$('#pincode').val();
                if(ta.length==6)
                { 
                    this.isLoading = true;
                axios.get("api/getdistrict/"+ta).then( ({ data }) => ($('#district').val(data.pro_category.District),this.iform.district=data.pro_category.District, $('#state').val(data.pro_category.Circle),this.iform.state=data.pro_category.Circle),this.isLoading = false ); 
                }
            },
            newForm(){
                this.errors = {};
                this.iform =this.blank;
                this.editMode = false;
                this.multipartForm = new FormData;
            },
        },
        mounted() 
        {
            this.isLoading = true;  
        },
        created(){
            this.getCategory();
            Fire.$on('LoadProduct', () => this.getCategory() );
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
</style>