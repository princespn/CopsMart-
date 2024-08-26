<template>
    <div class="container p-0">
        <!-- /.row -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"> 
                        <div class="row">
                            <div class="col-md-8"><h3 class="card-title ml-3">View Customer</h3></div>
                        </div>
                    </div> 
                    <div class="card-header" style="border:none!important">
                        <span class="ml-2 text-primary">Customer Details </span>
                          <hr class="mb-1 mt-0">
                           <div class="row pl-3 ml-2">
                               <div class="col-md-2 w-100">
                                   <img :src="imgurl(Customers.user)" alt="" >
                                </div>
                                <div class="col-md-4 pl-1 pr-1 br">
                                    <div class="row w-100">
                                        <p class="col-md-5 mb-0  text-left">Customer Name </p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5  mb-0 p-0 text-left">{{Customers.user.name}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">Mobile No.</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.user.mobile}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">Canteen Card No :</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.user.card_no}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0 text-left">Bukkle No.</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.user.bukkle_no}}</p>
                                    </div>
                                    <div class="row w-100">
                                        <p class="col-md-5 mb-0 text-left">Identification Mark</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5  mb-0 p-0 text-left">{{Customers.user.identification_mark}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 pl-1 pl-1 br">
                                    <div class="row w-100">
                                        <p class="col-md-5 mb-0  text-left">Employee Post </p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5  mb-0 p-0 text-left">{{Customers.user.employee_post}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">Blood Group</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.user.blood_group}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">Date of Birth</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.user.dob}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">Date of Joining</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.user.date_of_joining}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0 text-left">Date of Retirement</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.user.date_of_retirement}}</p>
                                    </div>
                                </div>
                                <div class="col-md-1 w-100">
                                    <div class="text-right">
                                        <router-link :to="editUrl(this.editId)" class="nav-link" >
                                            <button  class="btn btn-primary  btn-sm " > Edit</button>   
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        <span class="ml-2 text-primary">Address Details </span>
                        <hr class="mb-1 mt-0">
                        <div class="row pl-3 ml-2">
                            <div class="col-md-1"></div>
                            <div class="col-md-3 br"  v-for="(purchase, index) in Customers.useraddress" :key="'sc'+index"  >
                                    <div class="row  w-100 text-left">
                                        <h6 class="w-100  mb-0 text-primary p-1">{{purchase.title | upText}}</h6>
                                        <p class="w-100 ">{{purchase.address }}, {{purchase.district}} ,  <br> {{purchase.state}} , {{purchase.pincode}}</p>
                                        <p class="w-100 "><a target="_blank" :href="getMapUrl(purchase)" class="btn btn-sm btn-primary" style="padding: 0.2rem!important;font-size:12px!important"> <i class="fa fa-map-marker"></i> Google Map Link to Address.</a></p>
                                    </div>
                                   
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <hr>
                    <div class="card-body p-3">
                       <table class="table table-bordered table-hover" >
                           <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Invoice No</th>
                                    <th>Products</th>
                                    <th>Total</th>
                                    <th>Delivery Charge</th>
                                    <th>Order Date & Time</th>
                                    <th>Order Status</th>
                                    <th>Action</th>
                                </tr>
                           </thead>
                          
                            <tbody>
                            <tr v-for="(vendor, index) in Customers.orders" :key="vendor.id"  >
                                <td style="padding: 0.2rem;">{{(1+index)}}</td>
                                <td>{{ vendor.invoice_no | upText }}</td>
                                <td>{{ vendor.proname}} </td>
                                <td>{{ vendor.amount}} </td>
                                <td>{{ vendor.delivery_charges}} </td>
                                <td>{{ vendor.created_at | myDate}} </td>
                                <td>{{ vendor.orderstatus | upText}} </td>
                                <td>
                                     <router-link :to="vieworder(vendor.id)" class="nav-link" >
                                            <button  class="btn btn-primary  btn-sm " > view</button>   
                                        </router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                       <!-- /.card-body -->
                    </div>
                <!-- /.card -->
            </div>


         <br><br>
           <orders />
        <!-- Modal -->
       
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
            CToggle,
            'v-select': vSelect,
            'orders': GetOrder,
        },
        data() {
            return {
                errors:{},
                multipartForm: new FormData,
                isLoading: true,
                editId:'',
                Customers:{},
            }
        },
        methods :{
            loadEditProduct()
            {    
                axios.get("/api/customer/viewprofile/"+this.editId).then( ({ data }) => (this.Customers=data) );
            },
            editUrl(vendor){
                return `/editCustomer/${vendor}` ;
            },
            vieworder(vendor)
            {
              return `/OrderDetailsManagement/${vendor}` ;
            },
            getMapUrl(vendor){
                return vendor.latitude != null ? `http://maps.google.com/maps?q=${vendor.latitude},${vendor.longitude}` : '#';
            },
            imgurl(user)
            {
                return user.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/uploads/images/customer/' + user.image;
            }
           
           
           
          
            

        },
        mounted() 
        {
            this.isLoading = true;  
        },
        created(){
           this.isLoading = true;  
            this.editId = this.$route.params.OrderId;
            this.loadEditProduct();
            Fire.$on('LoadProduct', () => this.loadEditProduct() );
        }
    }
   
</script>


<style scoped>
.table th, .table td {padding: 0.2rem!important;}
.table td {padding: 0.2rem!important;font-size: 12px!important;}
img
{
    max-width : 20vh;
    max-height : 20vh
}
.disnone{display: none!important;}
table input, table select{width: 85%!important;}
table .btn-sm, .btn-group-sm > .btn {
    padding: 0.25rem 0.25rem!important;}
</style>

<style scoped lang="scss">

.pce{
    padding:6px 13px!important;
}
.br{
    border-right:1px solid black!important;
}

.card-title {
    margin-top: 0.75rem!important;
    margin-bottom: 0px!important;
}
</style>