<template>
    <div class="container p-0">
        <!-- /.row -->

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2">View Delivery Boy</h3>

                        <!-- <div class="card-tools">
                            <a type="button" class="btn btn-sm btn-primary mr-3"  href="/vAddDeliveryBoy"> <i class="fa fa-plus"></i> Add New</a>
                        </div> -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive table-bordered p-3">
                        <span class="text-primary ml-3 fs-14"><b>Delivery Person Details</b></span>
                        <hr class="mb-1 mt-0">
                         <div class="row pl-3 ml-2">
                           <div class="col-md-3 mr-2 pr-1 br">
                            
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Name </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.name}}</b></p>
                                </div>
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Mobile </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.mobile}}</b></p>
                                </div>
                              
                                 <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0 p-0 text-left">Email</p>
                                     <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-5   mb-0 p-0 text-left">{{delivery_boy.email}}</p>
                                </div>
                              
                           </div>
                           <div class="col-md-3 mr-2 pr-1 br">
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Name </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.name}}</b></p>
                                </div>
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Mobile </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.mobile}}</b></p>
                                </div>
                              
                                 <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0 p-0 text-left">Email</p>
                                     <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-5   mb-0 p-0 text-left">{{delivery_boy.email}}</p>
                                </div>
                           </div>
                           <div class="col-md-3  mr-2 pr-1 br">
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Name </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.name}}</b></p>
                                </div>
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Mobile </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.mobile}}</b></p>
                                </div>
                              
                                 <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0 p-0 text-left">Email</p>
                                     <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-5   mb-0 p-0 text-left">{{delivery_boy.email}}</p>
                                </div>
                           </div>
                        </div>
                           <span class="text-primary ml-3 fs-14"><b>Identity Documents Details</b></span>
                        <hr class="mb-1 mt-0">
                         <div class="row pl-3 ml-2">
                           <div class="col-md-3 mr-2 pr-1 br">
                            
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Aadhar Card No.</p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.aadhar_no}}</b></p>
                                </div>
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Pan Card No. </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.pan_no}}</b></p>
                                </div>
                              
                              
                           </div>
                           <div class="col-md-3 mr-2 pr-1 br">
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Name </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.name}}</b></p>
                                </div>
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Mobile </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.mobile}}</b></p>
                                </div>
                              
                                 <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0 p-0 text-left">Email</p>
                                     <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-5   mb-0 p-0 text-left">{{delivery_boy.email}}</p>
                                </div>
                           </div>
                           <div class="col-md-3  mr-2 pr-1 br">
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Name </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.name}}</b></p>
                                </div>
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Mobile </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{delivery_boy.mobile}}</b></p>
                                </div>
                              
                                 <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0 p-0 text-left">Email</p>
                                     <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-5   mb-0 p-0 text-left">{{delivery_boy.email}}</p>
                                </div>
                           </div>
                           
                           
                       </div>
                    <br/>
                      <h2 class="text-left">DeliveryManagement</h2> <!-- /.row -->
       
        <!-- /.row -->
        <div class="row ">

<div class="col-md-12 ">
    <nav>
        <div class="nav nav-tabs nav-fill border" id="nav-tab" role="tablist">
            <a class="nav-item nav-link" id="nav-profile-tab"  :class="{active : currentOrdersTab == 'active'}" :aria-selected="currentOrdersTab == 'active'" @click="changeTab('active')">Order Details</a>
            <a class="nav-item nav-link" id="nav-contact-tab"  :class="{active : currentOrdersTab == 'cancelled'}" :aria-selected="currentOrdersTab == 'cancelled'" @click="changeTab('cancelled')">Reviews and Ratings</a>
        </div>
    </nav>
    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
           
                        <data-table
                            :classes = "tableClasses"
                            :url="this.url"
                            :columns="columns"

                            :per-page="perPage">
                        </data-table>
        </div>
        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <data-table
                            :classes = "tableClasses"
                            :url="this.newurl"
                            :columns="mcolumns"
                            :per-page="perPage">
                        </data-table>
        </div>
    </div>
</div>
                </div><!-- /.row -->
            </div>
               
</div>
  <orders />


            </div>
        </div>
    </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import GetOrder  from './GetOrder';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
    export default {
        components:{
            CImage,
            'orders': GetOrder,
            CBtn,
            CToggle,
        },
        data() {
            return {
                perPage: ['10', '25', '50', '100', '250', '500'],
                columns: [
                    {
                        label: 'SR. No',
                        name: 'srno',
                        filterable: true,
                    },
                     {
                        label: 'Orderid',
                        name: 'id',
                        filterable: true,
                    },
                    {
                        label: 'Product Name',
                        name: 'proname',
                        filterable: true,
                    },
                    
                    {
                        label: 'Total',
                        name: 'amount',
                        filterable: false,
                    },
                   
                    {
                        label: 'Delivery Charges',
                        name: 'delivery_charges',
                        filterable: false,
                    },
                    {
                        label: 'Date And Time',
                        name: 'orderdate',
                        filterable: true,
                    },
                    {
                        label: 'Order Status',
                         name: 'orderstatus',
                        filterable: false,
                       
                    },
                    {
                        label: 'Delivery Status',
                        name: 'deliverystatus',
                        filterable: false,
                       
                    },
                    
                    {
                        label: 'Actions',
                        component: CBtn, 
                        filterable: false,
                        meta:{
                            multiple: true,
                            btnList :[
                                {
                                    classes : 'btn-sm btn-info mr-1 fad fa-eye',
                                    action: this.getviewurl,
                                    additionalAttributes : {'title':"View Order" },
                                },
                            ]

                        }
                    },
                ],
                mcolumns: [
                    {
                        label: 'SR. No',
                        name: 'srno',
                        filterable: true,
                    },
                    {
                        label: 'Date And Time',
                        name: 'orderdate',
                        filterable: true,
                    },
                     {
                        label: 'Orderid',
                        name: 'order_id',
                        filterable: true,
                    },
                    {
                        label: 'Customer Name',
                        name: 'nameuser',
                        filterable: true,
                    },
                    {
                        label: 'Reviews',
                        name: 'review',
                        filterable: false,
                       
                    },
                    {
                        label: 'Star Rating',
                        name: 'star',
                        filterable: false,
                       
                    },
                ],
                tableClasses:{
                    "table-container": {
                        "table-responsive": true,
                    },
                    "table": {
                        "table": true,
                        "table-striped": true,
                        "maintable":true,
                        "table-dark": false,
                    },
                    "td":
                    {
                        "nowrap":true,
                    }

                },
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
                customers: {},
                delivery_boy:{},
                commodities: [],
                editMode: false,
                currentOrdersTab:'pending',
                isLoading: false,
                multipartForm: new FormData,
                currentRule:
                {
                    limit_start:null,
                    limit_end:null,
                    charges:null,
                },
                url:'',newurl:'',
            }
            
        },
        methods :{         
            loadEditProduct()
            {    
                axios.get("/api/delivery_person/"+this.OrderId).then( ({ data }) => (this.delivery_boy=data) );
            },
            getUrl()
            {  
                 this.url='/api/order_datatable_delwise/'+this.OrderId;
                //  console.log(this.url);
                 return this.url;
            },

            getnewUrl()
            {  
                 this.newurl='/api/getdeliveryReview/'+this.OrderId;
                 return this.newurl;
            },
            changeTab(tab){
                $('.tab-pane').removeClass('show active');
                switch (tab) {
                    case 'active':
                        this.currentOrdersTab='active';
                        $('#nav-profile').addClass('show active');
                        break;
                    case 'cancelled':
                        this.currentOrdersTab='cancelled';
                        $('#nav-contact').addClass('show active');
                        break;
                }
                
            },
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.OrderId = this.$route.params.OrderId;
            this.loadEditProduct();  this.getUrl();this.getnewUrl();
             // this.loadDeliveryPersonList();
             //  this.getUrl();
            //  Fire.$on('LoadCustomer', () => this.getUrl());
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
.br{
    border-right:1px solid black!important;
}
</style>
