<template>
    <div class="container p-0">
        <!-- /.row -->
        <div class="row mt-2 pl-4">
            <h3><i class="fas fa-box"></i> View Purchase</h3>
        </div>
        <div class="row mt-1 ">
            <div class="col-12">
                <div class="card p-0">
                    <div class="card-header">
                        <span class="text-primary">Vendor Details</span>
                        <hr class="mb-2 mt-1">
                         <div class="row pl-3 ml-2">
                           <div class="col-md-4 row p-2 br">
                                <div class="row w-100">
                                    <p class="col-md-5 mb-0  text-left">Vendor Name </p>
                                    <p class="col-md-1  mb-0  text-center">:</p>
                                    <p class="col-md-5  mb-0  text-left">{{tmpvendors.name}}</p>
                                </div>
                                <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0  text-left">Contact Number</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                    <p class="col-md-5  mb-0  text-left">{{tmpvendors.mobile_no}}</p>
                                </div>
                                <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0  text-left">Contact Person</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                     <p class="col-md-5  mb-0  text-left">{{tmpvendors.contact_person}}</p>
                                </div>
                                 <div class="row  w-100 ">
                                    <p class="col-md-5  mb-0  text-left">Employee Post</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                     <p class="col-md-5  mb-0  text-left">{{tmpvendors.emp_post}}</p>
                                </div>
                           </div>
                           <div class="col-md-4 p-2  br row ml-2">
                               <div class="row pl-2 w-100 ">
                                    <p class="col-md-5  mb-0  text-left text-primary">Address</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                    <p class="col-md-5  mb-0  text-left">{{tmpvendors.address}}</p>
                                </div>
                                <div class="row pl-2 w-100 ">
                                    <p class="col-md-5  mb-0  text-left ">Pincode</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                    <p class="col-md-5  mb-0  text-left">{{tmpvendors.pincode}}</p>
                                </div>
                                <div class="row pl-2 w-100 ">
                                    <p class="col-md-5  mb-0  text-left ">District</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                   <p class="col-md-5  mb-0  text-left">{{tmpvendors.district}}</p>
                                </div>
                           </div>
                           <div class="col-md-4 p-2  row ml-2">
                                <div class="row pl-2 w-100 ">
                                    <p class="col-md-5  mb-0  text-left ">State</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                    <p class="col-md-5  mb-0  text-left">{{tmpvendors.state}}</p>
                                </div>
                                 <div class="row pl-2 w-100 ">
                                    <p class="col-md-5  mb-0  text-left ">GSTIN</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                     <p class="col-md-5  mb-0  text-left">{{tmpvendors.	gst}}</p>
                                </div>
                                <div class="row pl-2 w-100 ">
                                    <p class="col-md-5  mb-0  text-left ">Invoice No</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                    <p class="col-md-5  mb-0  text-left">{{OrderId}}</p>
                                </div>
                                <div class="row pl-2 w-100 ">
                                    <p class="col-md-5  mb-0  text-left ">Invoice Date</p>
                                     <p class="col-md-1  mb-0  text-center">:</p>
                                     <p class="col-md-5  mb-0  text-left">{{totalDatas.invoice_date | myDateFormate}}</p>
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
                                <th>Product name</th>
                                <th>Size</th>
                                <th>Color</th>
                                <th>HSN</th>
                                <th>MRP</th>
                                 <th>P.rate</th>
                                <th>Taxable Value</th>
                                <th>Qty</th>
                                <th>Total Taxable</th>
                                <th>Sgst</th>
                                <th>Cgst</th>
                                <th>Igst</th>
                                <th>Total Gst</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(purchase, index) in Purchases" :key="'sc'+index" >
                                <td>{{(1+index)}}</td>
                                <td>{{ purchase.name | upText }}</td>
                                <td>{{ purchase.color_name  }}</td>
                                <td>{{ purchase.size_name  }}</td>
                                <td>{{ purchase.hsn  }}</td>
                                <td>{{ purchase.mrp  }}</td>
                                <td>{{ purchase.purchase_rate  }}</td>
                                <td>{{calc(purchase.p_taxable_rate)}}</td>
                                <td>{{ purchase.qty }}</td>
                                <td>{{ calc(purchase.p_taxable_rate*purchase.qty)  }}</td>
                                <td>{{ calc(purchase.p_sgst*purchase.qty)  }}</td>
                                <td>{{ calc(purchase.p_cgst*purchase.qty)  }}</td>
                                <td>{{ calc(purchase.p_igst*purchase.qty)  }}</td>
                                <td>{{ calc(purchase.p_tgst*purchase.qty)  }}</td>
                                <td>{{ calc(purchase.p_total_amount) }}</td>
                            </tr>
                            <tr class="bg-primary">
                                <td colspan="5">Total</td>
                                <td>{{ totalDatas.mrp  }}</td>
                                <td>NA</td>
                                <td>NA</td>
                                <td>{{ totalDatas.qty  }}</td>
                                <td>{{ calc(totalDatas.total_taxable)  }}</td>
                                <td>{{ calc(totalDatas.total_sgst)  }}</td>
                                <td>{{ calc(totalDatas.total_cgst)  }}</td>
                                <td>{{ calc(totalDatas.total_igst)  }}</td>
                                <td>{{ calc(totalDatas.total_tgst)  }}</td>
                                <td>{{ calc(totalDatas.total_amount)  }}</td>
                            </tr>
                        </tbody>
                       </table>
                    </div>

                     <div class="row ml-2 mb-2">
                        <div class="col-md-5">
                            <div class="row bg-secondary ml-2">
                                <div class="col-md-4 br">
                                   <h6>Bank Account Details</h6>
                                </div>
                                <div class="col-md-8">
                                        <p class="mb-1 ">Bank Name : {{this.tmpvendors.account_name}}</p>
                                        <p class="mb-1">Account No: {{this.tmpvendors.account_no}}</p>
                                        <p class="mb-1">Bank IFSC : {{this.tmpvendors.ifsc}}</p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row ml-2">
                        <div class="col-md-9">
                          <table class="table table-bordered"  >
                            <thead>
                                <tr class="bg-success"><th style="padding: 0.15rem;!important" colspan="7  text-center">Payment Details</th></tr>
                                <tr> 
                                    <th style="padding: 0.15rem;!important">Payment Date</th>
                                    <th style="padding: 0.15rem;!important;width: 100px!important;">Status</th>
                                    <th style="padding: 0.15rem;!important;width: 100px!important;">Paid Amount</th>
                                    <th style="padding: 0.15rem;!important;width: 100px!important;">Pending</th>
                                    <th style="padding: 0.15rem;!important;width: 100px!important;" class="text-success">Trans. ID</th>
                                    <th style="padding: 0.15rem;!important;width: 100px!important;" class="text-danger">Pay. Method</th>
                                    <th style="padding: 0.15rem;!important;width: 100px!important;" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              <tr v-for="paytm  in Payments"  :key="paytm.id" > 
                                <td >{{paytm.payment_date | myDateFormate}}</td>
                                <td >{{paytm.status}}</td>
                                <td >{{paytm.paid_amount}}</td>
                                <td >{{paytm.remaining_amount}}</td>
                                <td >{{paytm.tansaction_id}}</td>
                                <td style="width: 100px!important;">{{paytm.payment_mode}}</td>
                                <td>NA</td>
                              </tr>
                              <tr > 
                                    <td >
                                    <input type="date" v-model="mform.payment_date" class="form-control fmcntrl1 "  :class="{ 'is-invalid': errors.payment_date }">
                                    </td>
                                    <td >
                                    <input type="text" v-model="mform.payment_status" class="form-control fmcntrl1 "  placeholder="Status"   :class="{ 'is-invalid': errors.payment_status }">
                                    </td>
                                    <td >
                                    <input type="text" id="paidamt"  v-model="mform.payment_paid" class="form-control fmcntrl1 "  placeholder="Paid Amount"   :class="{ 'is-invalid': errors.payment_paid }" v-on:keyup="getFinalPending()">
                                    </td>
                                    <td >
                                    <input type="text" v-model="mform.payment_pending"  readonly class="form-control  fmcntrl1 " placeholder="Pending Amount"    :class="{ 'is-invalid': errors.payment_pending }">
                                    </td>
                                    <td >
                                    <input type="text"  v-model="mform.payment_trans" class="form-control fmcntrl1 "  placeholder="Transaction Id"  :class="{ 'is-invalid': errors.payment_trans }">
                                    </td>
                                    <td >
                                    <select v-model="mform.payment_mode" class="form-control fmcntrl1"  :class="{ 'is-invalid': errors.payment_mode }">
                                    <option value="Bank" selected>Bank</option>
                                    </select>
                                    </td>
                                    <td >
                                    <button type="button" @click="SubmitForm" class="btn btn-success btnsub btn-sm"> <i class="fa fa-check"></i></button>
                                    </td>
                              </tr>
                            </tbody>
                         </table>
                        </div>
                      
                        <div class="col-md-3">
                            <div class="row mt-3">
                            <div class="col-md-5 text-right p-0"><h6 class="text-primary">Taxable Value  </h6></div>
                            <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
                            <div class="col-md-6 text-left p-0"><h6 class="text-primary">{{calc(this.totalDatas.total_taxable)}}</h6></div>
                            </div>
                            <div class="row">
                            <div class="col-md-5 text-right p-0"><h6 class="text-primary">Total GST  </h6></div>
                            <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
                            <div class="col-md-6 text-left p-0"><h6 class="text-primary">{{calc(this.totalDatas.total_tgst)}}</h6></div>
                            </div>

                            <div class="row">
                            <div class="col-md-5 text-right p-0"><h6 class="text-primary">Other Charges</h6></div>
                            <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
                            <!-- <div class="col-md-6 text-left p-0">{{calc(this.totalDatas.other_amount)}}</div> -->
                            <div class="col-md-6 text-left p-0"><h6 class="text-primary">{{calc(this.totalDatas.other_amount)}}</h6></div>
                            </div>
                            <hr class="bg-primary">
                            <div class="row">
                            <div class="col-md-5 text-right p-0"><h6 class="text-primary"><strong>Final Total</strong></h6></div>
                            <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
                            <div class="col-md-6 text-left p-0"><h6 class="text-primary">{{calc(parseFloat(this.totalDatas.final_total)+parseFloat(this.totalDatas.other_amount))}}</h6></div>
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
                OrderId:null,
                VendorId:null,
                orderDetails:{},
                SetData:{},
                errors:{},
                Vendors:{},
                sizesx:{},
                colorsx:{},
                options: [],
                Purchases:{},
                Payments:{},
                tmpvendors:{},
                mform: new Form({
                    invoice_no:'',
                    p_vendor_id:'',
                    invoice_date:'',
                    vendor_id:'',
                    total_taxable:0,
                    total_tgst:0,
                    final_total:0,
                    tot_final_total:0,
                    payment_status:'',
                    payment_date:'',
                    payment_paid:0,
                    payment_pending:0,
                    remaining_amount:0,
                    payment_trans:'',
                    payment_mode:'',
                    purchase_id:'',
                    paid_amount:'',
                    other_charges:0,
                    final_amount:0,
                    pending:0,
                    tansaction_id:'',
                }),
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
                    invoice_date:0,
                    other_amount:0,
                }),
                totalDatass:({
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
                }),
            }
        },
        methods :{
         
            calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },
           
            getMapUrl(vendor){
                return vendor.lati != null ? `http://maps.google.com/maps?q=${vendor.lati},${vendor.longi}` : '#';
            },
            loadTmpList() {
                var a =$('#authid').val();
                this.mform.p_vendor_id=this.VendorId;
                axios.get("/api/loadtmppurchase/"+this.VendorId+"?ad="+a+"&inv="+this.OrderId).then( ({ data }) => (this.Purchases = data,this.getTotalData())  );
                axios.get("/api/getpendingamount/"+this.VendorId+"?ad="+a+"&inv="+this.OrderId).then( ({ data }) => (this.totalDatas.pending_amount=data,this.mform.payment_pending=data,this.mform.total_amount=data));
                 axios.get("/api/getotheramount/"+this.VendorId+"?ad="+a+"&inv="+this.OrderId).then( ({ data }) => (this.totalDatas.other_amount=data));
                axios.get("/api/getpurchasepayment/"+this.VendorId+"?ad="+a+"&inv="+this.OrderId).then( ({ data }) => (this.Payments = data) );
                // axios.get("/api/getpurchaseData/"+this.VendorId+"?ad="+a+"&inv="+this.OrderId).then( ({ data }) => (this.Payments = data) );
                // this.settime();
                // this.url="/api/getpurchaseData/"+a;
                this.getbankdetails();
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
                this.totalDatass.mrp = 0;
                this.totalDatass.qty = 0;
                this.totalDatass.total_taxable = 0;
                this.totalDatass.total_sgst = 0;
                this.totalDatass.total_cgst = 0;
                this.totalDatass.total_igst = 0;
                this.totalDatass.total_tgst = 0;
                this.totalDatass.total_amount = 0;
                //console.log(this.Purchases);
                for(let x in this.Purchases)
                 {
                    
                   this.totalDatas.mrp += parseFloat(this.Purchases[x].mrp);
                   this.totalDatas.qty += parseFloat(this.Purchases[x].qty);
                   this.totalDatas.total_taxable += parseFloat(this.Purchases[x].p_taxable_rate)*this.Purchases[x].qty;
                   this.totalDatas.total_sgst += parseFloat(this.Purchases[x].p_sgst)*this.Purchases[x].qty;
                   this.totalDatas.total_cgst += parseFloat(this.Purchases[x].p_cgst)*this.Purchases[x].qty;
                   this.totalDatas.total_igst += parseFloat(this.Purchases[x].p_igst)*this.Purchases[x].qty;
                   this.totalDatas.total_tgst += parseFloat(this.Purchases[x].p_tgst)*this.Purchases[x].qty;
                   this.totalDatas.final_total=this.totalDatas.total_amount += parseFloat(this.Purchases[x].p_total_amount);
                   this.mform.invoice_date=this.totalDatas.invoice_date=this.Purchases[x].invoice_date;
                   this.mform.invoice_no=this.totalDatas.invoice_no=this.Purchases[x].invoice_no; 
                }
                
                //  this.getFinalPending();
            },
            getFinalPending()
            {  
               var pending=this.totalDatas.pending_amount;
               var paid=$('#paidamt').val();
               var pn=this.mform.payment_pending=(parseFloat(pending)-parseFloat(paid)).toFixed(2);
               if(pn<0)
               {
                  $('.btnsub').attr('disabled',true);
               }
               else
               {
                   $('.btnsub').attr('disabled',false);
               }
            },
            getbankdetails()
            {
                var id=this.VendorId;
                axios.get("/api/purchasevendor/"+id).then( ({ data }) => (this.tmpvendors = data) );
            },
            SubmitForm()
            {   
                var a =$('#authid').val();
                this.mform.vendor_id=a;
                this.mform.purchase_id=this.Payments[0].purchase_id;
                this.mform.post('/api/AddPurchasePayment').then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Purchase  Added Successfully'
                    });
                    setTimeout(()=>{
                    window.location.reload();
                    }, 1000);
                    this.$Progress.finish();
                  }).catch(()=>{
                    this.errors =data.response.data.errors;
                    //console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
            },
          
        },
        mounted() {
            this.OrderId = this.$route.params.OrderId;
            this.VendorId = this.$route.params.VendorId;
          
            // document.getElementById("pending").classList.add('MyClass');
        },
        created(){
            // this.loadSubCategoryList();
            this.OrderId = this.$route.params.OrderId;
            this.VendorId = this.$route.params.VendorId;
            // console.log(this.OrderId);
            //     console.log(this.VendorId);
             this.loadTmpList();
            Fire.$on('LoadSubCategory', () => this.loadTmpList() );
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
.table th, .table td {padding: 0.2rem!important;}
.table th{font-size:12px!important;}
.fmcntrl{ width: 82%!important;height: calc(1.6em + 0.05rem + 2px)!important;}
.fmcntrl1{ width: 100%!important;height: calc(1.6em + 0.05rem + 2px)!important;padding: 0.05rem 0.75rem!important;}

</style>
