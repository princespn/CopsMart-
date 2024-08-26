<template>
    <div class="container p-1 " style="border: 1px solid black!important;">
        <!-- /.row -->
        <div class="row mt-1 text-black">
            <div class="col-12">
                <div class="card p-1" style="max-width:148 mm;">
                    
                    <div class="row card-title m-2 mt-3 "> 
                      <!-- <div class="col-md-2 text-center p-0" >
                          <img src="/images/logo.jpg" alt="Logo" class="img mt-2 img-responsive" style="max-width:100%;max-height:100%;">
                      </div>    -->
                      <div class="row w-100 mb-0"> 
                        <div style="width:2%"></div>
                        <div style="width:96%"><h3 style="width:100%;" class="text-center  mb-0 mt-1 bg-black">{{orderDetails.vendors.app_name |upText}}</h3></div>
                        <div style="width:2%"></div>
                    </div>
                      <div class="col-md-12" style="font-size:14px">
                          <div class="row">
                              <div class="col-md-12">
                                <h2 class="text-center text-danger fw-900">{{orderDetails.vendors.name |upText}} , {{orderDetails.vendors.district|upText}}</h2>
                              </div>
                          </div>
                          <div class="row w-100">
                              <div class="col-md-6" style="width: 50%!important;">
                                <h5 class="text-left ">Contact No.: {{orderDetails.vendors.contact_no}}</h5>
                               </div>
                               <div class="col-md-6" style="width: 50%!important;;">
                                <h5 class="text-right ">GSTIN.: {{orderDetails.vendors.gstin}}</h5>
                               </div>
                          </div>
                          <div class="row">
                                <h6 class="text-left mt-1 ft17">Address: {{orderDetails.vendors.address}}</h6>
                            </div>
                            <div class="row w-100">
                                <div class="col-md-6" style="width:50%">
                                     <h6 class="text-left mt-1 ft17">Dist.: {{orderDetails.vendors.district}}</h6>
                                </div>
                                <div class="col-md-6" style="width:50%">
                                     <h6 class="text-right mt-1 ft17">State.: {{orderDetails.vendors.state}}</h6>
                                </div>
                            </div>
                     </div>
                     </div>
                    <hr class=" mt-0 mb-0">
                    <div class="row w-100  mb-0"> 
                        <h5 style="width:100%;" class="text-center mb-0 mt-2">Place of Supply.: {{orderDetails.vendors.state}}</h5>
                    </div>
                    <div class="row w-100 mb-0"> 
                        <div style="width:2%"></div>
                        <div style="width:96%"><h3 style="width:100%;" class="text-center  mb-0 mt-1 bg-black">TAX INVOICE</h3></div>
                        <div style="width:2%"></div>
                    </div>

                            <div class="row w-100">
                                <div style="width: 50%;">
                                    <h6 class="text-left mb-0 ft17 mt-1 fw-900 ml-4">Invoice No.: INV/{{orderDetails.invoice_no}}</h6>
                                </div>
                                <div style="width: 50%;">
                                    <h6 class="text-right mb-0 ft17 mt-1 fw-900 mr-4">Invoice Date.: {{orderDetails.created_at |myDateFormate}}</h6>
                                </div>
                            </div>
                        

                    <!-- <hr class=" mt-0 mb-0"> -->
                    <div class="card-header">
                        <!-- <h6 class="text-primary">Customer Details</h6> -->
                        <hr class="mb-1 mt-1">
                         <div class="row pl-3 ml-2">
                                <div class="row w-100 p-2" style="border-bottom: 1px solid black;">
                                    <h6 class="col-md-5 ft15 mb-0 p-0 text-left" style="width:33.3%;">Customer Name : {{orderDetails.user.name}} </h6>  
                                    <h6 class="col-md-5 ft15 mb-0 p-0 text-left" style="width:33.3%;">Bukkle No : {{orderDetails.user.bukkle_no}} </h6>                                    
                                    <h6 class="col-md-5 ft15 mb-0 p-0 text-left" style="width:33.3%;">Canteen Card No : {{orderDetails.user.card_no}} </h6>                                                                      
                                    
                                </div>
                                <div class="row w-100">
                                    <div style="width: 50%;" class="br">
                                        <div class="row w-100">
                                            <h6 class="col-md-12 text-center ft17">Contact Info</h6>
                                        </div>
                                        <div class="row w-100 ">
                                            <h6 class="col-md-12 p-0 ft15 mb-0 text-center fs-14">@{{orderDetails.user.mobile}}</h6>
                                        </div>
                                        <div class="row w-100">
                                            <h6 class="col-md-12 ml-1 ft15 p-0  mb-0 text-center fs-14">@{{orderDetails.user.email}}</h6>
                                        </div>
                                    </div>
                                    <div style="width: 50%;">
                                        <div class="row w-100">
                                            <h6 class="col-md-12 text-center ft17 text-danger">Delivery Address :</h6>
                                        </div>
                                        <div class="row w-100 ">
                                            <h6 class="col-md-12  p-0 ft15 mb-0 text-center fs-14">{{orderDetails.useraddress.address}}</h6>
                                        </div>
                                        <hr class="mb-1 w-100 mt-1">
                                        <div class="row w-100 ">
                                            <h6 class="col-md-12 ft17 p-0  mb-0 text-center fs-14">Delivery Type: {{orderDetails.delivery_type}}</h6>
                                        </div>
                                    </div>
                                </div>
                           
                          
                       </div>
                    </div>
     
                    <!-- /.card-header -->
                    <div class="card-body p-2">
                        <table class="table table-bordered table-hover" style="font-size:16px !important;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Items </th>
                                    <!-- <th>Size</th> -->
                                    <!-- <th>HSN</th> -->
                                    <th>Total=MRP*qty</th>
                                    <!-- <th>GST %</th> -->
                                    <!--<th>Sales Rate</th>-->
                                    <th>Taxable Value </th>
                                    <th>GST %</th>
                                    <th>GST Amount</th>
                                    <th>Qty</th>
                                    <!-- <th>Taxable Value</th> -->
                                    <th>Total Amount</th> 
                                    <!-- <th>Sgst</th>
                                    <th>Cgst</th>
                                    <th>Igst</th>-->
                                  
                                </tr>
                            </thead>
                            <tr v-for="(category, index) in orderDetails.products" :key="category.id">
                                <td>{{(1+index)}}</td>
                                <td style="text-align: left!important;">{{ category.name | upText }}</td>
                                <!-- <td>{{ category.size_name | upText }}</td> -->
                                <!-- <td>{{ category.hsn }}</td> -->
                                <td>{{ calc(category.mrp* category.qty )}}</td>
                                
                                <!--<td>{{calc(category.price)}}</td>-->
                                <td>{{ calc(category.taxable_rate*category.qty) }}</td>
                                <td>{{ category.gst}}</td>
                                <td>{{ calc(category.sgst*category.qty+category.cgst*category.qty+category.igst*category.qty) }}</td>
                               
                                <td>{{ category.qty }}</td>
                                
                                 
                               <!--<td>{{ calc(category.cgst*category.qty) }}</td>
                                <td>{{ calc(category.igst*category.qty) }}</td>-->
                                <td>{{ calc(parseFloat(category.price*category.qty) )}}</td> 
                                <!-- <td>{{ category.name | upText }}</td> -->
                            </tr>
                            <tr style="padding: 0.15rem!important">
                                <td colspan="2" style="padding: 0.15rem!important">Total</td>
                                <td style="padding: 0.15rem!important">{{  totalDatas.mrp  }}</td>
                                <td style="padding: 0.15rem!important">{{ calc(totalDatas.total_taxable)  }}</td>
                                <td style="padding: 0.15rem!important">{{ totalDatas.gst}}</td>

                                <td style="padding: 0.15rem!important">{{ calc(totalDatas.total_cgst+totalDatas.total_sgst+totalDatas.total_igst)  }}</td>
                                <!-- <td></td> -->
                                 
                                <!-- <td style="padding: 0.15rem;!important">{{ calc(totalDatas.total_taxable)  }}</td> -->
                                <!-- <td style="padding: 0.15rem;!important">{{ calc(totalDatas.total_sgst)  }}</td>
                                <td style="padding: 0.15rem;!important">{{ calc(totalDatas.total_cgst)  }}</td>
                                <td style="padding: 0.15rem;!important">{{ calc(totalDatas.total_igst)  }}</td>
                                <td>{{ calc(totalDatas.total_tgst)  }}</td> -->
                                <td style="padding: 0.15rem!important">{{  totalDatas.qty  }}</td>
                                <td style="padding: 0.15rem!important">{{ calc(parseFloat(totalDatas.total_sales))}}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- <p class="pagebreak"></p> -->
                    <div class="row ml-2">
                       <div class="col-md-6" style="width:50%;">
                          <div class="row  p-2">
                               <div class="col-md-4">
                                   <h3 class="">Payment Details</h3>
                               </div>
                               <div class="col-md-8">
                                  <h6 class="">Payment Mode : {{orderDetails.order_payment.payment_mode  | upText}}</h6>
                                  <h6 class="">Payment Status : {{orderDetails.order_payment.response_msg }}</h6>
                                  <h6 class="">Transaction ID : {{orderDetails.order_payment.transaction_uid }}</h6>
                               </div>
                          </div>
                           <br/>
                           <div class="row">
                               <p class="w-100 ft15">Note:</p>
                               <p class="mb-0 w-100 ft15 text-left" style="font-weight:800;">Canteen timing : {{ orderDetails.vendors.opentime }} to {{ orderDetails.vendors.closetime }}</p>
                                <p class=" mb-0 w-100 ft15 text-left" style="font-weight:800;">Canteen will be closed on : {{ orderDetails.vendors.closed }}</p>
                                <p class="mb-0 w-100 ft15 text-left mt-1 mb-0" style="font-weight:800;"> ***NO RETURN NO EXCHANGE***</p>
                                <p class="mb-0 w-100 ft15" style="font-weight:800;">*. You can exchange , if faulty on thesame day of purchase.</p>
                                <p class="mb-0 w-100 ft15" style="font-weight:800;">*. This is computer generate Invoice and do not need signature.</p>
                           </div>
                       </div>
                       <!-- <div class="col-md-2"></div> -->
                        <div class="col-md-6" style="width:50%;">
                            <!-- <div class="row mt-3">
                                        <div class="col-md-5 text-right p-0"><h6 class="text-primary">Taxable Value  </h6></div>
                                        <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
                                        <div class="col-md-6 text-left p-0"><h6 >{{calc(this.totalDatas.total_taxable)}}</h6></div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 text-right p-0"><h6 class="text-primary">Total SGST  </h6></div>
                                <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
                                <div class="col-md-6 text-left p-0"><h6 >{{calc(this.totalDatas.total_sgst)}}</h6></div>
                            </div>
                             <div class="row">
                                <div class="col-md-5 text-right p-0"><h6 class="text-primary">Total CGST  </h6></div>
                                <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
                                <div class="col-md-6 text-left p-0"><h6 >{{calc(this.totalDatas.total_cgst)}}</h6></div>
                            </div>
                             <div class="row">
                                <div class="col-md-5 text-right p-0"><h6 class="text-primary">Total IGST  </h6></div>
                                <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
                                <div class="col-md-6 text-left p-0"><h6 >{{calc(this.totalDatas.total_igst)}}</h6></div>
                            </div> -->
                            <div class="row w-100">
                                <div class="col-md-5 text-center p-0" style="width:45%;"><h5 class="text-primary">Taxable Value</h5></div>
                                <div class="col-md-1 text-center p-0" style="width:5%;"><h5 class="text-primary">:</h5></div>
                                <div class="col-md-6 text-center p-0" style="width:35%;"><h5 >{{calc(parseFloat(this.totalDatas.total_taxable))}}</h5></div>
                            </div>
                             <div class="row w-100">
                                <div class="col-md-5 text-center p-0" style="width:45%;"><h5 class="text-primary">Total GST</h5></div>
                                <div class="col-md-1 text-center p-0" style="width:5%;"><h5 class="text-primary">:</h5></div>
                                <div class="col-md-6 text-center p-0" style="width:35%;"><h5 >{{calc(parseFloat(this.totalDatas.total_tgst))}}</h5></div>
                            </div>
  
                            <div class="row w-100">
                                <div class="col-md-5 text-center p-0" style="width:45%;"><h5 class="text-primary">Rebate 50% GST</h5></div>
                                <div class="col-md-1 text-center p-0" style="width:5%;"><h5 class="text-primary">:</h5></div>
                                <div class="col-md-6 text-center p-0" style="width:35%;"><h5 >{{calc(parseFloat(this.totalDatas.total_rtgst))}}</h5></div>
                            </div>
                            <div class="row w-100">
                                <div class="col-md-5 text-center p-0" style="width:45%;"><h5 class="text-primary">Other Charges</h5></div>
                                <div class="col-md-1 text-center p-0" style="width:5%;"><h5 class="text-primary">:</h5></div>
                                <div class="col-md-6 text-center p-0" style="width:35%;"><h5 >{{calc(parseFloat(this.orderDetails.delivery_charges))}}</h5></div>
                            </div>
                            <div class="row w-100">
                                <div class="col-md-5 text-center p-0" style="width:45%;"><h5 class="text-primary">Sub Total</h5></div>
                                <div class="col-md-1 text-center p-0" style="width:5%;"><h5 class="text-primary">:</h5></div>
                                <div class="col-md-6 text-center p-0" style="width:35%;"><h5 >{{calc((parseFloat(this.totalDatas.total_tgst)+parseFloat(this.totalDatas.total_taxable)+parseFloat(this.orderDetails.delivery_charges))-parseFloat(this.totalDatas.total_rtgst))}}</h5></div>
                            </div>
                            <!-- <hr class="bg-primary">
                            <div class="row bg-dark w-100">-->
                                <div class="row w-100">
                                <div class="col-md-5 text-center ft17 p-0" style="width:45%;"><h4><strong> Final Total</strong></h4></div>
                                <div class="col-md-1 text-center ft17 p-0" style="width:5%;"><h5>:</h5></div>
                                <div class="col-md-6 text-center ft17 p-0" style="width:35%;"><h4>{{Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(this.orderDetails.delivery_charges))-parseFloat(this.totalDatas.total_rtgst))}}</h4></div>
                            </div>

                            <div class="row mt-2 w-100">
                                <div class="col-md-5 text-center p-0 ft17 " style="width:45%;"><h5 ><strong>You saved </strong></h5></div>
                                <div class="col-md-1 text-center p-0 ft17" style="width:5%;"><h5>:</h5></div>
                                <div class="col-md-6 text-center p-0 ft17" style="width:35%;"><h5 >{{ (this.totalDatas.mrp - Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(this.orderDetails.delivery_charges))-parseFloat(this.totalDatas.total_rtgst))) }}</h5></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
            <div class="stickbottom">
                <h3 class=" text-center">*** Thanks for Shopping with Us ! ***</h3>
                <hr>
                <h6 class=" text-center">Designed By</h6>
                <h6 class=" text-center">Mechatron Techgear Pvt Ltd</h6>
            </div>
            
         </div>
          </div>
        </div><!-- /.row -->

      
    </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
    export default {
        data() {
            return {
                form : new Form({
                    id:'',
                    name:'',
                    image:'',
                    super_category_id : '',
                    category_id : '',
                    subcategory_name:[],
                    admin_id:'',
                    is_active: true
                }),
                subCategories: {},
                categories: {},
                supSubCategories:{},
                deliveryPersons:[],
                deliveryAssignForm : new Form({
                    delivery_person_id: null,
                }),
                inputs: [],
                vendors: {},
                OrderId:null,
                orderDetails:null,
                totalDatas:({
                    total:0,
                    mrp:0,
                    qty:0,
                    total_taxable:0,
                    taxable_rate:0,
                    sales_rate:0,
                    total_sales:0,
                    total_sgst:0,
                    total_cgst:0,
                    total_igst:0,
                    total_amount:0,
                    total_tgst:0,
                    total_rtgst:0,
                    final_total:0,
                    pending_amount:0,
                }),
            }
        },
        methods :{
           loadorderDetails() {
             //  this.OrderId = this.$route.params.OrderId;
                axios.get("/api/orderdetails/"+this.OrderId).then( ({ data }) => {
                    //console.log(data);
                    this.orderDetails = data[0];  this.getTotalData();
                    this.settime();
                });
            },
            calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },
            loadDeliveryPersonList() 
            {
                var a=$('#authid').val();
                axios.get("/api/delivery_person_list?a="+a).then( ({ data }) => {
                    this.deliveryPersons = data;
                });
            },
            getTotalData()
            {    
                this.totalDatas.mrp = 0;
                this.totalDatas.qty = 0;
                this.totalDatas.total_taxable = 0;
                this.totalDatas.taxable_rate = 0;
                this.totalDatas.total_sgst = 0;
                this.totalDatas.total_cgst = 0;
                this.totalDatas.total_igst = 0;
                this.totalDatas.total_tgst = 0;
                this.totalDatas.total_amount = 0;
                this.totalDatas.total_rtgst = 0;
                for(let x in this.orderDetails.products)
                 {
                   this.totalDatas.mrp += parseFloat(this.orderDetails.products[x].mrp * this.orderDetails.products[x].qty);
                   this.totalDatas.qty += parseFloat(this.orderDetails.products[x].qty);
                   this.totalDatas.taxable_rate += parseFloat(this.orderDetails.products[x].taxable_rate);
                   this.totalDatas.sales_rate += parseFloat(this.orderDetails.products[x].price);
                   this.totalDatas.total_sales += parseFloat(this.orderDetails.products[x].price)*this.orderDetails.products[x].qty;
                   this.totalDatas.total_taxable += parseFloat(this.orderDetails.products[x].taxable_rate)*this.orderDetails.products[x].qty;
                   this.totalDatas.total_sgst += parseFloat(this.orderDetails.products[x].sgst)*this.orderDetails.products[x].qty;
                   this.totalDatas.total_cgst += parseFloat(this.orderDetails.products[x].cgst)*this.orderDetails.products[x].qty;
                   this.totalDatas.total_igst += parseFloat(this.orderDetails.products[x].igst)*this.orderDetails.products[x].qty;
                   this.totalDatas.total_tgst += parseFloat(this.orderDetails.products[x].tgst)*this.orderDetails.products[x].qty;
                //    this.totalDatas.final_total=this.totalDatas.total_amount += parseFloat(this.orderDetails.products[x].total_amount);
                  
                }
                this.totalDatas.total_rtgst=parseFloat(this.totalDatas.total_tgst/2);
                //  this.getFinalPending();
            },
            settime()
            {    
                  setTimeout(()=>{
                    $('#fott').addClass('newcs');
                   window.print();
                    }, 1000);
            } , 
        },
        mounted() {
            this.OrderId = this.$route.params.OrderId;
            this.loadorderDetails();
            //4qconsole.log('Component mounted.')
        },
        created(){
            // this.loadSubCategoryList();
            //$('.main-footer').css('display','none');
            this.OrderId = this.$route.params.OrderId;
            this.loadDeliveryPersonList();
            Fire.$on('LoadSubCategory', () => this.loadorderDetails() );
        }


    }
</script>

<style scoped>
img{
    max-width : 3vh;
    max-height : 3vh
}
.pce{
    padding:6px 13px!important;
}
/* .col-md-1 
{
    height: 50px!important;
} */
.br{
    border-right:1px solid black!important;
}
.ft15{
    font-size: 15px!important;
}
.ft17{
    font-size: 17px!important;
}
td{
    padding: 0.2rem!important;
    font-size: 16px!important;
}

th{
    font-size: 15px!important;
}
h5{
margin-bottom: 0!important;
}
</style>
