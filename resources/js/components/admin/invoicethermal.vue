<template>
    <div class="container p-1 " >
        <!-- /.row -->
        <div class="row mt-1 text-black text-center">
            <div class="col-md-12 p-0">
                <div class="card p-0 mb-0" style="max-width:104.2mm;">
                    <div class="card-header text-center" style="background-color:black!important;color:white;">
                     <h4 class="mb-0" style="font-weight: 700!important;text-transform: uppercase;">{{orderDetails.vendors.app_name}}</h4>
                    </div>
                    <div class="card-header text-center">
                        <h4 class="text-center w-100 fw-900">{{orderDetails.vendors.name |upText}} , {{orderDetails.vendors.district|upText}}</h4>
                        <h6 class="text-left w-100">Contact No.: {{orderDetails.vendors.contact_no}}</h6>
                        <h6 class="text-left w-100">GSTIN.: {{orderDetails.vendors.gstin}}</h6>
                        <h6 class="text-left w-100">Address: {{orderDetails.vendors.address}}</h6>
                    </div>
                    <div class="card-header text-center" style="padding:0px!important;background-color:black!important;color:white;">
                         <h3 class="text-center fw-900 mb-0">TAX INVOICE</h3>
                    </div>
                    <div class="card-header p-0 text-left">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="w-100 fw-900 p-0" style="float:left;width:50%!important;">Inv No.: INV/{{orderDetails.invoice_no}} </h6><h6 class="w-100 fw-900 p-0" style="float:right;width:50%!important;"> Inv Date: {{orderDetails.created_at |myDateFormate}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-header p-0 text-left">
                         <h6 class="w-100 fw-900 text-center">Customer Details</h6>
                         <div class="row fsc">
                             <div class="col-md-12"><p class="w-100  mb-0  p-0 text-left" style="font-size:17px!important;">Customer Name : {{orderDetails.user.name}}</p></div>
                         </div>
                         <div class="row fsc">
                             <div class="col-md-12">
                                <p class="mb-0  p-0 text-left" style="float:left;width:50%!;font-size:13px!important;"><span>Bukkle No. : {{orderDetails.user.bukkle_no}}</span></p>
                                <p class="mb-0  p-0 text-left" style="float:right;width:50%!important;font-size:13px!important;"><span>Mobile No. : {{orderDetails.user.mobile}}</span></p>
                            </div>
                         <!-- </div>
                         <div class="row fsc"> -->
                             <!-- <div class="col-md-8"><p class="  mb-0   p-0 text-left"></p></div> -->
                         </div>
                    </div>
                    <div class="card-header p-0 text-left">
                        <p class="w-100 fw-900 mb-0 text-center" style="font-size:13px!important;">Delivery Address :{{orderDetails.delivery_type}}</p>
                        <div class="row w-100 fsc">
                            <p class="col-md-12 mb-0 text-left fs-14" >{{orderDetails.useraddress.address}}</p>
                        </div>
                        <div class="row w-100 fsc">
                            <h5 class="col-md-12  fw-900 mb-0 text-left" >Recepient No.{{orderDetails.useraddress.mobile}}</h5>
                        </div>
                    </div>
                    <div class="card-header p-0 mb-0" style="padding:0px!important;border-bottom:none!important;"> 
                       <p class="w-100 fw-900 mb-0 text-cenetr">Item Details</p>
                    </div>
                       <div class="card-body p-0">
                        <table class="table table-bordered">
                            <tr>
                                    <th>No</th>
                                    <th>Items name </th>
                                    <th>Size /<br/> Color</th>
                                    <th>Qty</th>
                                    <th>Total</th> 
                            </tr>
                            <tr v-for="(category,index) in orderDetails.products" :key="category.id">
                                <td>{{1+index}}</td>
                                <td style="font-weight:900!important;text-align:left!important;font-size: 15px!important;color:black!important;">{{ truncateWords(category.name,5, '...') | upText }}</td>
                                <td>{{ category.size_name | upText }} / {{Substt(category.color_name) | upText  }}</td>
                                <td style="font-size:15px!important;font-weight:900!important;">{{ category.qty }}</td>
                                <td style="font-size:14px!important">{{ calc(parseFloat(category.price*category.qty) )}}</td> 
                            </tr>
                        </table>
                    </div>
                    <br />
                    <div class="card-header text-center mb-0 p-0">
                        <div class="row w-100 pr-0">
                            <div class="col-md-12 pr-0">
                                <div class="row w-100 pr-0">
                                    <div class="col-md-6 w-60"><p class="mb-0 p-0 text-left w-100" style="font-size:14px;font-weight: 800;">Taxable Value : {{calc(this.totalDatas.total_taxable)}}</p></div>
                                    <div class="col-md-6 w-40"><p class="mb-0 p-0 text-right w-100" style="font-size:14px;font-weight: 800;">Total GST: {{calc(parseFloat(this.totalDatas.total_tgst))}}</p></div>
                                </div>
                                <div class="row w-100 pr-0">
                                    <div class="col-md-12 w-100"><p class="mb-0 p-0 text-left w-100" style="font-size:14px;font-weight: 800;">Rebate 50% GST : {{calc(this.totalDatas.total_rtgst)}}</p></div>
                                </div>
                                <div class="row w-100 pr-0">
                                    <div class="col-md-6 w-40"><p class="mb-0 p-0 text-left w-100" style="font-size:14px;font-weight: 800;">Other Charges : {{calc(this.orderDetails.delivery_charges)}}</p></div>
                                    <div class="col-md-6 w-60"><p class="mb-0 p-0 text-right w-100" style="font-size:14px;font-weight: 800;">Subtotal: {{calc((parseFloat(this.totalDatas.total_tgst)+parseFloat(this.totalDatas.total_taxable)+parseFloat(this.orderDetails.delivery_charges))-parseFloat(this.totalDatas.total_rtgst))}}</p></div>
                                </div>
                                
                                
                            </div>
                            <!-- <hr class="mb-1 mt-0" style="border-top:1px solid black!important">       -->
                        </div>
                    </div>
                    <div class="card-header text-center mb-0 p-0"  style="padding:0px!important;background-color:black!important;color:white;font-size:18px;font-weight: 800;">
                        <p class="mb-0 p-0 text-right w-50" style="width:50%!important;float:left!important; font-size:larger;"><strong> Final Total : </strong>  &nbsp;</p>
                        <p class="mb-0 p-0 text-left w-50" style="width:50%!important;float:right!important;font-size:larger;">{{Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(this.orderDetails.delivery_charges))-parseFloat(this.totalDatas.total_rtgst))}}</p>
                    </div> 

                    <div class="card-header text-center mb-0 p-0">
                        <div class="row w-100">
                            <div class="col-md-12">
                                <p class="mb-1 p-0 text-left w-100" style="float:left!important;font-size:15px!important;font-weight: 800!important;">Payment Mode : {{orderDetails.order_payment.payment_mode  | upText}}</p>
                            </div>
                        </div>

                        <div class="row w-100">
                            <div class="col-md-6 w-50">
                                <p class="mb-0 p-0 text-left" style="float:left!important;font-size:15px!important;font-weight: 800!important;">Total Qty : {{this.totalDatas.qty}}</p>
                            </div>
                            <div class="col-md-6  w-50">
                                <p class="mb-0 p-0 text-left" style="float:right!important;font-size:15px!important;font-weight: 800!important;">Total MRP : {{this.totalDatas.mrp}}</p>
                            </div>
                        </div>
                        <div class="row w-100 mt-1">
                            <!-- <div class="col-md-2"></div>
                            <div class="col-md-6 text-center"> -->
                                <p class="mb-0 mt-2 p-0 text-center w-50 bg-dark" style="background-color:black!important;margin: auto !important;font-size:18px;font-weight: 800;"><strong>You Saved</strong> : {{ (this.totalDatas.mrp - Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(this.orderDetails.delivery_charges))-parseFloat(this.totalDatas.total_rtgst))) }}</p>
                            <!-- </div>
                            <div class="col-md-3"></div> -->
                        </div>
                           <!-- <div class="row fsc">
                                <div class="col-md-12"><p class="w-100  mb-0 p-0 text-left">Payment Status : {{orderDetails.order_payment.response_msg}}</p></div> 
                                </div>
                                <div class="row fsc">
                                    <div class="col-md-12"><p class="w-100  mb-0 p-0 text-left">Transaction ID : {{orderDetails.order_payment.transaction_uid }}</p></div>
                                </div> 
                            -->
                    </div>
                    <!-- <div class="card-header mb-0">
                            <p class="w-100 mb-0 text-left fsc">Note:</p>
                            <p class="mb-0 w-100 text-left">*. This is computer generate Invoice and do not need signature.</p>
                            <p class="mb-0 w-100 text-left">*. User can check Delivery status in orders section if ordered online.</p>
                            <p class="mb-0 w-100 text-left">*. Online Payment for all purchased goods is mandatory.</p>
                    </div> -->
                    <div class="card-header mb-0">
                        <h5 class=" text-center">Thanks for Shopping with Us !</h5>
                       <h6 class=" text-center">We hope to see you soon ......</h6>
                    </div>
                    <div class="card-header mb-0 ml-1">
                        <h6 class="text-left" style="font-weight:800;">Canteen timing : {{ orderDetails.vendors.opentime }} to {{ orderDetails.vendors.closetime }}</h6>
                       <h6 class="text-left" style="font-weight:800;">Canteen will be closed on : {{ orderDetails.vendors.closed }}</h6>
                       <h6 class="text-center mt-1 mb-0" style="font-weight:800;"> ***NO RETURN NO EXCHANGE***</h6>
                    </div>
                    <div class="card-header mb-0"  style="border:none!important;border-bottom:2px dashed black!important">
                        <h6 class=" text-center">Designed By</h6>
                        <h6 class=" text-center">Mechatron Techgear Pvt Ltd</h6>
                    </div>
                  
                    <div class="card-header text-center mt-3" style="padding:0px!important;background-color:black!important;color:white;">
                         <h3 class="text-center fw-900 mb-0">TAX INVOICE</h3>
                    </div>
                    <div class="card-header p-0 text-left">
                         <div class="row">
                            <div class="col-md-12">
                                <h6 class="w-100 fw-900 p-0" style="float:left;width:50%!important;">Inv No.: INV/{{orderDetails.invoice_no}} </h6><h6 class="w-100 fw-900 p-0" style="float:right;width:50%!important;"> Inv Date: {{orderDetails.created_at |myDateFormate}}</h6>
                            </div>
                         </div>
                        
                    </div>
                    <div class="card-header p-0 text-left">
                         <h6 class="w-100 fw-900 text-center">Customer Details</h6>
                         <div class="row fsc">
                             <div class="col-md-12"><p class="w-100  mb-0  p-0 text-left" style="font-size:15px!important;">Customer Name : {{orderDetails.user.name}}</p></div>
                         </div>
                         <div class="row fsc w-100">
                            <div class="col-md-12">
                                <p class="mb-0  p-0 text-left" style="float:left;width:50%!important;font-size:14px!important;"><span>Bukkle No. : {{orderDetails.user.bukkle_no}}</span></p>
                                <p class="mb-0  p-0 text-left" style="float:right;width:50%!important;font-size:14px!important;"><span>Mobile No. : {{orderDetails.user.mobile}}</span></p>
                            </div>
                         </div>
                         <!-- <div class="row fsc">
                             <div class="col-md-12"><p class="w-100  mb-0   p-0 text-left"></p></div>
                         </div> -->
                    </div>
                    <div class="card-header text-center mb-0 p-0">
                        <div class="row w-100 pr-0">
                            <div class="col-md-12 pr-0">
                                <div class="row w-100 pr-0">
                                    <div class="col-md-6 w-60"><p class="mb-0 p-0 text-left w-100" style="font-size:14px;font-weight: 800;">Taxable Value : {{calc(this.totalDatas.total_taxable)}}</p></div>
                                    <div class="col-md-6 w-40"><p class="mb-0 p-0 text-right w-100" style="font-size:14px;font-weight: 800;">Total GST: {{calc(parseFloat(this.totalDatas.total_tgst))}}</p></div>
                                </div>
                                <div class="row w-100 pr-0">
                                    <div class="col-md-12 w-100"><p class="mb-0 p-0 text-left w-100" style="font-size:14px;font-weight: 800;">Rebate 50% GST : {{calc(this.totalDatas.total_rtgst)}}</p></div>
                                </div>
                                <div class="row w-100 pr-0">
                                    <div class="col-md-6 w-40"><p class="mb-0 p-0 text-left w-100" style="font-size:14px;font-weight: 800;">Other Charges : {{calc(this.orderDetails.delivery_charges)}}</p></div>
                                    <div class="col-md-6 w-60"><p class="mb-0 p-0 text-right w-100" style="font-size:14px;font-weight: 800;">Subtotal: {{calc((parseFloat(this.totalDatas.total_tgst)+parseFloat(this.totalDatas.total_taxable)+parseFloat(this.orderDetails.delivery_charges))-parseFloat(this.totalDatas.total_rtgst))}}</p></div>
                                </div>
                                
                                
                            </div>
                            <!-- <hr class="mb-1 mt-0" style="border-top:1px solid black!important">       -->
                        </div>
                    </div>
                    
                    <div class="card-header text-center mb-0 p-0"  style="padding:0px!important;background-color:black!important;color:white;font-size:18px;font-weight: 800;">
                        <p class="mb-0 p-0 text-right w-50" style="width:50%!important;float:left!important;">Final Total :   &nbsp;</p>
                        <p class="mb-0 p-0 text-left w-50" style="width:50%!important;float:right!important;"> &nbsp;{{Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(this.orderDetails.delivery_charges))-parseFloat(this.totalDatas.total_rtgst))}}</p>
                    </div>
                          
                </div>
            </div>
        </div><!-- /.row --> 
    </div>
</template>

<script>
   
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
                    //console.log(data);
                    this.orderDetails = data[0];  this.getTotalData();
                    this.settime();
                });
            },
            truncateWords(sentence, amount, tail) {
                const words = sentence.split(' ');
                if (amount >= words.length) {
                    return sentence;
                }
                const truncated = words.slice(0, amount);
                return `${truncated.join(' ')}${tail}`;
            },



            Substt(sentence) {
                var temp = sentence.substr(0, 3);
                return temp;
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
                   this.totalDatas.mrp += parseFloat(this.orderDetails.products[x].mrp)*this.orderDetails.products[x].qty;
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
                    //    self.close();
                    }, 1000);
            } , 
        },
        mounted() {
            this.OrderId = this.$route.params.OrderId;
            this.loadorderDetails();
        },
        created(){
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
.table th, .table td 
{
    padding: 0.2rem;
    font-size : 11px!important;
    border-top: 1px solid #000000!important;

}
.card-header {
    border-bottom: 1px solid rgba(0, 0, 0)!important;
    /* padding: 0px!important; */
}
body{color: black!important;}
</style>
