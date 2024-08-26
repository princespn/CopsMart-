<template>
    <div class="container p-0">
        <!-- /.row -->
        <div class="row mt-2 pl-4">
            <h3><i class="fas fa-box"></i> Orders Management :- Order No / Invoice No : #{{orderDetails.id}} / {{orderDetails.invoice_no}}</h3>
        </div>
        <div class="row mt-1 ">
            <div class="col-12">
                <div class="card p-0">
                    <div class="row card-title m-2 mt-3"> 
                      <div class="col-md-2">Order Id  : # {{orderDetails.id}} </div>   
                        <div class="col-md-1">
                          <label class="ml-3 mr-3 btn btn-sm" :class="{ 'btn-success': orderDetails.payment_status == 1, 'btn-danger':orderDetails.payment_status == 0, 'btn-warning':orderDetails.payment_status == null }"> {{ orderDetails.payment_status == 1 ? 'Paid' : 'Unpaid'}}</label>
                        </div>  
                      <div class="col-md-2 pl-0" style="font-size:13px"> {{ orderDetails.created_at | myDate}}</div>  
                      <div class="col-md-4 pt-1">
                        <span class="newcs" :class="{ 'showd': orderDetails.makeabill == 1}"><span class="bg-primary p-2"> <i class="fa fa-user"></i> Order Pickup </span></span>
                        <span class="newcs" :class="{ 'showd': orderDetails.makeabill == 0}">
                         <span class="bg-primary btn-sm p-2" v-if="orderDetails.delivery_person"> <i class="fa fa-user"></i> &nbsp; {{ orderDetails.delivery_person.name }} </span>
                         <span v-if="!orderDetails.delivery_person" class="row">
                            <label style="font-size:12px!important;font-weight:600" class="col-md-4 p-0">Assign Delivery Boy</label>
                            <div  v-if="!orderDetails.delivery_person" class="col-md-5" style="margin-top:-10px">
                              <select class="form-control" v-if="deliveryPersons.length>0"   v-model="deliveryAssignForm.delivery_person_id" :class="{ 'is-invalid': deliveryAssignForm.errors.has('delivery_person_id') }" >
                                <option v-for="dp in deliveryPersons" :key="dp.id" :value="dp.id" >{{dp.name}}</option>
                             </select>   
                        </div>
                        <div class="col-md-1">
                             <button type="button" @click="assignDeliveryPerson(orderDetails.id)" class="btn btn-primary p-1 btn-sm" style="margin-top: -14px;" ><i class="fa fa-check "></i></button>                        
                        </div>
                    </span>
                    </span>
                    </div><div class="col-md-1 text-right"><a target="_blank" :href="invoicedata(orderDetails)" class="btn btn-sm btn-primary" style="padding: 0.2rem!important;font-size:11px!important"> <i class="fa fa-file"></i> Invoice</a>
                        <a target="_blank" :href="invoiceadata(orderDetails)" class="btn btn-sm btn-primary" style="padding: 0.2rem!important;font-size:11px!important"> <i class="fa fa-file"></i> A5</a>
                    </div>
                    <div class="col-md-2 text-right"><a target="_blank" :href="invoicetdata(orderDetails)" class="btn btn-sm btn-primary" style="padding: 0.2rem!important;font-size:11px!important"> <i class="fa fa-file"></i> Thermal</a></div></div>
                    <hr class="mb-0 hrb" >
                    <div class="card-header">
                       <span class="text-primary ml-3 fs-14"><b>Order Status</b></span>
                        <hr class="mt-0">
                       <div class="row mb-3">
                            <div class="col-md-1"></div>
                            <button class="col-md-1  btn btn-sm border"  :class="{ 'btn-danger': orderDetails.order_status_id > 3}">Pending</button>
                            <span class="col-md-1 p-0"><hr style="margin-top:1.2rem!important;border-top:1px solid black!important" ></span>
                            <!-- accept -->
                            
                            <button  class="col-md-1 btn btn-sm border" :class="{ 'btn-info': orderDetails.order_status_id > 4}" @click="acceptOrder(orderDetails)">Accepted</button>
                            <span class="col-md-1 p-0"><hr style="margin-top:1.2rem!important;border-top:1px solid black!important" ></span>
                            <!-- packaging -->
                           
                           <button  class="col-md-1   btn btn-sm border" :class="{ 'btn-secondary': orderDetails.order_status_id > 5}" @click="PackagedOrder(orderDetails)">Packaging</button>
                           <span class="col-md-1 p-0"><hr style="margin-top:1.2rem!important;border-top:1px solid black!important" ></span>
                            <!-- out for delivery -->
                          
                           <button  class="col-md-2 btn btn-sm border" :class="{ 'btn-warning': orderDetails.order_status_id > 6}" @click="OutForDelivery(orderDetails)">Out for Delivery</button>
                           <span class="col-md-1 p-0"><hr style="margin-top:1.2rem!important;border-top:1px solid black!important" ></span>
                            <!-- out for delivery -->
                          
                           <button  class="col-md-1   btn btn-sm border" :class="{ 'btn-success': orderDetails.order_status_id > 8}" @click="DeliveredOrder(orderDetails)">Delivered</button>
                   
                                
                       </div>
                        <span class="text-primary ml-3 fs-14"><b>Customer Status</b></span>
                        <hr class="mb-1 mt-0">
                         <div class="row pl-3 ml-2">
                           <div class="col-md-4 row pr-1 br">
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0 p-0 text-left">Customer Name </p>
                                    <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-6  mb-0 p-0 text-left"><b>{{orderDetails.user.name}}</b></p>
                                </div>
                                <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0 p-0 text-left">Bukkle No.</p>
                                     <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-5   mb-0 p-0 text-left">{{orderDetails.user.bukkle_no}}</p>
                                </div>
                                <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0 p-0 text-left">Canteen Card No :</p>
                                     <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-5   mb-0 p-0 text-left">{{orderDetails.user.card_no}}</p>
                                </div>
                                 <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0 p-0 text-left">Total Orders :</p>
                                     <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                    <p class="col-md-5   mb-0 p-0 text-left">{{orderDetails.ordertotalno}}</p>
                                </div>
                           </div>
                           <div class="col-md-3 pr-1  br row ml-2">
                               <div class="row w-100">
                                    <p class="col-md-12 text-center text-primary mb-0">Contact Info</p>
                                </div><br>
                                 <div class="row w-100 ">
                                    <p class="col-md-12 p-0  mb-0 text-center fs-14">@{{orderDetails.user.mobile}}</p>
                                </div><br>
                                 <div class="row w-100 ">
                                    <p class="col-md-12  p-0  mb-0 text-center fs-14">@{{orderDetails.user.email}}</p>
                                </div><br>
                           </div>
                           <div class="col-md-5 pr-1 row ml-2">
                               <div class="row w-100">
                                    <p class="col-md-12 text-center mb-0 text-danger">Delivery Address :</p>
                                </div>
                                 <div class="row w-100 ">
                                    <p class="col-md-12  p-0 ml-1  mb-1 text-center fs-14">{{orderDetails.useraddress.address}}</p>
                                </div>
                                <hr class="mb-1 w-100 mt-1">
                                 <div class="row w-100 ">
                                    <p class="col-md-12  p-0  mb-0 text-center fs-14">Delivery Type:{{orderDetails.delivery_type}}</p>
                                </div>
                                <hr class="mb-1 w-100 mt-1">
                                 <div class="row w-100 ">
                                    <p class="col-md-12  p-0  mb-0 text-center "><a target="_blank" :href="getMapUrl(orderDetails)" class="btn btn-sm btn-primary" style="padding: 0.2rem!important;font-size:12px!important"> <i class="fa fa-map-marker"></i> Google Map Link to Address</a></p>
                                </div>
                           </div>    
                       </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-2">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Product Items </th>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>HSN Code</th>
                                    <th>MRP</th>
                                    <th>Qty</th>
                                    <th>Taxable Value</th>
                                    <th>Sgst</th>
                                    <th>Cgst</th>
                                    <th>Igst</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tr v-for="(category, index) in orderDetails.products" :key="category.id">
                                <td>{{(1+index)}}</td>
                                <td>{{ category.name | upText }}</td>
                                <td>{{ category.size_name | upText }}</td>
                                <td>{{ category.color_name | upText }}</td>
                                <td>{{ category.prohsn}}</td>
                                <td>{{ calc(category.mrp)}}</td>
                                <td>{{ category.qty }}</td>
                                <td>{{ calc(category.taxable_rate*category.qty) }}</td>
                                <td>{{ calc(category.sgst*category.qty) }}</td>
                                <td>{{ calc(category.cgst*category.qty) }}</td>
                                <td>{{ calc(category.igst*category.qty) }}</td>
                                <td>{{ calc(parseFloat(category.taxable_rate*category.qty)+parseFloat(category.tgst*category.qty) )}}</td>
                                <!-- <td>{{ category.name | upText }}</td> -->
                            </tr>
                            <tr class="bg-primary" style="padding: 0.15rem!important;">
                                <td colspan="5" style="padding: 0.15rem!important;">Total</td>
                                <td style="padding: 0.15rem!important;">{{ totalDatas.mrp  }}</td>
                                <td style="padding: 0.15rem!important;">{{ totalDatas.qty  }}</td>
                                <td style="padding: 0.15rem!important;">{{ calc(totalDatas.total_taxable)  }}</td>
                                <td style="padding: 0.15rem!important;">{{ calc(totalDatas.total_sgst)  }}</td>
                                <td style="padding: 0.15rem!important;">{{ calc(totalDatas.total_cgst)  }}</td>
                                <td style="padding: 0.15rem!important;">{{ calc(totalDatas.total_igst)  }}</td>
                                <!-- <td>{{ calc(totalDatas.total_tgst)  }}</td> -->
                                <td style="padding: 0.15rem!important;">{{ calc(parseFloat(totalDatas.total_taxable)+parseFloat(totalDatas.total_tgst))  }}</td>
                            </tr>
                           

                          
                        </table>
                    </div>
                    <div class="row ml-2">
                       <div class="col-md-6">
                          <table class="table table-bordered"  >
                            <thead>
                                <tr class="bg-success"><th style="padding: 0.15rem!important;" colspan="4  text-center">Payment Details</th></tr>
                                <tr> 
                                    <th style="padding: 0.15rem!important;">Payment Method</th>
                                    <th style="padding: 0.15rem!important;">Status</th>
                                    <th style="padding: 0.15rem!important;" class="text-success">Trans. ID</th>
                                    <th style="padding: 0.15rem!important;" class="text-danger">Pay. Gateway</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr > 
                                <td style="padding: 0.15rem!important;">{{orderDetails.order_payment.payment_mode  | upText}}</td>
                                    <td style="padding: 0.15rem!important;">{{orderDetails.order_payment.response_msg}}</td>
                                    <td style="padding: 0.15rem!important;" class="text-success">{{orderDetails.order_payment.transaction_uid}}</td>
                                    <td  style="padding: 0.15rem!important;" class="text-danger">{{orderDetails.order_payment.pname  | upText}}</td>
                            </tr>
                            </tbody>
                         </table>
                       </div>
                       <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <div class="row mt-3">
                                        <div class="col-md-5 text-right p-0"><h5 class="text-primary">Taxable Value </h5></div>
                                        <div class="col-md-1 text-center p-0"><h5 class="text-primary">:</h5></div>
                                        <div class="col-md-6 text-left p-0"><h5 >{{calc(this.totalDatas.total_taxable)}}</h5></div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 text-right p-0"><h5 class="text-primary">Total GST </h5></div>
                                <div class="col-md-1 text-center p-0"><h5 class="text-primary">:</h5></div>
                                <div class="col-md-6 text-left p-0"><h5 >{{calc(this.totalDatas.total_tgst)}}</h5></div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 text-right p-0"><h5 class="text-primary">Rebate 50% GST </h5></div>
                                <div class="col-md-1 text-center p-0"><h5 class="text-primary">:</h5></div>
                                <div class="col-md-6 text-left p-0"><h5 >{{calc(this.totalDatas.total_rtgst)}}</h5></div>
                            </div>
                             <div class="row">
                                <div class="col-md-5 text-right p-0"><h5 class="text-primary">Delivery Charges  </h5></div>
                                <div class="col-md-1 text-center p-0"><h5 class="text-primary">:</h5></div>
                                <div class="col-md-6 text-left p-0"><h5 >{{calc(this.orderDetails.delivery_charges)}}</h5></div>
                            </div>
                            <hr class="bg-primary">
                            <div class="row">
                                <div class="col-md-5 text-right p-0"><h5 class="text-primary">Final Total</h5></div>
                                <div class="col-md-1 text-center p-0"><h5 class="text-primary">:</h5></div>
                                <div class="col-md-6 text-left p-0"><h5 >{{Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(this.orderDetails.delivery_charges))-parseFloat(this.totalDatas.total_rtgst))}}</h5></div>
                            </div>
                        </div>
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
    import CImage from '../common/CImage.vue';
    import GetOrder  from './GetOrder';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
    export default {
         components:{          
            'orders': GetOrder,
        },
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
                    total_sgst:0,
                    total_cgst:0,
                    total_igst:0,
                    total_amount:0,
                    total_tgst:0,
                    final_total:0,
                    pending_amount:0,
                    total_rtgst:0,
                }),
            }
        },
        methods :{
          loadorderDetails() {
             //  this.OrderId = this.$route.params.OrderId;
                axios.get("/api/orderdetails/"+this.OrderId).then( ({ data }) => {
                    this.orderDetails = data[0];  this.getTotalData();
                    
                });
            },
            invoicedata(vendor){
                return `/Invoice/${vendor.id}` ;
            },
            invoiceadata(vendor){
                return `/InvoicePrint/${vendor.id}` ;
            },
             invoicetdata(vendor){
                return `/InvoiceThermal/${vendor.id}` ;
            },
             calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },
            assignDeliveryPerson(order) {
                this.deliveryAssignForm.post(`/api/orders/${order}/assign_delivery_person`).then((data)=>{
                //console.log(data);
                if(data.data.success==true)
                {
                    swal.fire(
                        'Updated!',
                        'Delivery Person Assigned Successfully.',
                        'success'
                    );
                    setTimeout(()=>{
                                    window.location.reload();
                                    }, 1000);
                }
                else
                {
                    swal.fire(
                        'Failed!',
                        'Delivery Person Not Assigned!!.',
                        'warning'
                    );
                }
                });

            },
            loadDeliveryPersonList() 
            {
                var a=$('#authid').val();
                axios.get("/api/delivery_person_list?a="+a).then( ({ data }) => {
                    this.deliveryPersons = data;
                });
            },

            acceptOrder(product){
                // sweet alert modal
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, accept order!'
                    }).then((result) => {
                        // send delete request
                        console.log(result)
                    if(result.value){
                        this.form.get('/api/acceptorder/'+product.id).then((data) => {
                            //  console.log(data);
                            if(data.data==1)
                            {
                                    swal.fire(
                                    'Accepted!',
                                    'Order has been Accepted.',
                                    'success'
                                    );
                                    setTimeout(()=>{
                                    window.location.reload();
                                    }, 1000);
                            }
                            else if(data.data==0)
                            {
                                swal.fire(
                                'Failed!',
                                `Order can't be Accepted.`,
                                'warning'
                                )
                            }
                            else if(data.data==2)
                            {
                                swal.fire(
                                'Warning!',
                                `Order Is Already Accepted.`,
                                'warning'
                                )
                            }
                        })
                    }
                })
            },

            PackagedOrder(product)
            {   
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Packed it!'
                    }).then((result) => {
                        // send delete request
                        console.log(result)
                    if(result.value){
                        this.form.get('/api/packageorder/'+product.id).then((data) => {
                        if(data.data==1)
                        {
                            swal.fire(
                            'Packed!',
                            'Order has been Packed.',
                            'success'
                            );
                            setTimeout(()=>{
                            window.location.reload();
                            }, 1000);
                        }
                        else if(data.data==0)
                        {
                            swal.fire(
                            'Failed!',
                            `Order can't be Packed.`,
                            'warning'
                            )
                        }
                        else if(data.data==2)
                        {
                            swal.fire(
                            'Warning!',
                            `Order Is Already Packed.`,
                            'warning'
                            )
                        }
                        else if(data.data==3)
                        {
                            swal.fire(
                            'Warning!',
                            `Order is not accepted yet !!.`,
                            'warning'
                            )
                        }
                        })
                    }
                })
            },

            OutForDelivery(product)
            {   
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delivered it!'
                    }).then((result) => {
                        // send delete request
                        //console.log(result)
                    if(result.value){
                         this.form.get('/api/outfordelivery/'+product.id).then((data) => {
                        if(data.data==1)
                        {
                              swal.fire(
                            'Ready To Delivered!',
                            'Order has been ready to delivered.',
                            'success'
                            );
                            setTimeout(()=>{
                            window.location.reload();
                            }, 1000);
                        }
                        else if(data.data==0)
                        {
                            swal.fire(
                            'Failed!',
                            `Order is not ready.`,
                            'warning'
                            )
                        }
                        else if(data.data==2)
                        {
                            swal.fire(
                            'Warning!',
                            `Order is already ready to delivered.`,
                            'warning'
                            )
                        }
                        })
                    }
                })
            },

            DeliveredOrder(product)
            {   
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delivered it!'
                    }).then((result) => {
                        // send delete request
                        console.log(result)
                    if(result.value){
                         this.form.get('/api/deliveredorder/'+product.id).then((data) => {
                            //  console.log(data);
                           if(data.data==1)
                        {
                            swal.fire(
                            'Delivered!',
                            'Order has been Delivered.',
                            'success'
                            );
                            setTimeout(()=>{
                            window.location.reload();
                            }, 1000);
                        }
                        else if(data.data==0)
                        {
                            swal.fire(
                            'Failed!',
                            `Order can't be Delivered.`,
                            'warning'
                            )
                        }
                        else if(data.data==2)
                        {
                            swal.fire(
                            'Warning!',
                            `Order Is Already Delivered.`,
                            'warning'
                            )
                        }
                        else if(data.data==3)
                        {
                            swal.fire(
                            'Warning!',
                            `Order is not ready for delivered.`,
                            'warning'
                            )
                        }
                        else if(data.data==4)
                        {
                            swal.fire(
                            'Warning!',
                            `Delivery Person Not Assign.`,
                            'warning'
                            )
                        }
                        })
                    }
                })
            },
            getMapUrl(vendor){
                return vendor.useraddress.lat != null ? `http://maps.google.com/maps?q=${vendor.useraddress.lat},${vendor.useraddress.long}` : '#';
            },
            getTotalData()
            {    
                this.totalDatas.mrp = 0;
                this.totalDatas.qty = 0;
                this.totalDatas.total_taxable = 0;
                this.totalDatas.total_sgst = 0;
                this.totalDatas.total_cgst = 0;
                this.totalDatas.total_igst = 0;
                this.totalDatas.total_tgst = 0;
                this.totalDatas.total_amount = 0;
                this.totalDatas.total_rtgst = 0;
                for(let x in this.orderDetails.products)
                 {
                   this.totalDatas.mrp += parseFloat(this.orderDetails.products[x].mrp);
                   this.totalDatas.qty += parseFloat(this.orderDetails.products[x].qty);
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

        },
        mounted() {
            this.OrderId = this.$route.params.OrderId;
            this.loadorderDetails();
            console.log('Component mounted.')
             
            // document.getElementById("pending").classList.add('MyClass');
        },
        created(){
            // this.loadSubCategoryList();
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

</style>
