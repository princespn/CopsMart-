<template>
<div class="container p-0">
<!-- /.row -->

<div class="row mt-1">
<div class="col-12">
<div class="card p-2">
    <div class="card-header"> 
        <div class="row">
        <div class="col-md-4">
           <h3 class="card-title ml-3">Edit Order</h3>
        </div>
        <div class="col-md-4">
             <h4 class="card-title text-primary">Order Id :- {{editId}} </h4>
        </div>

        <div class="col-md-4">
             <h4 class="card-title text-primary">Order Date :- {{mform.created_at | myDate}} </h4>
        </div>
        </div>
    </div> 
<!-- /.card-header -->
<div class="card-header ">
    <h6 class="bb-1 mb-1 text-primary">Customer Details</h6>
    <div class="row ">
          <div class="col-sm-3">
            <h5>Canteen Number:- <br>{{fetch.canteen}}</h5>
        </div> 
        <div class="col-sm-3">
            <h5 >Bukkle Number:- <br>{{fetch.bukkle}}</h5>
        </div> 
        <div class="col-sm-3">
            <h5 >Customer Name:- <br>{{fetch.customer_name}}</h5>
        </div> 
        <div class="col-sm-3">
            <h5 >Mobile Number:- <br>{{fetch.mobile}}</h5>
        </div> 
        
         <!-- <div class="col-sm-3">
              <p class="mb-0 text-danger row" v-if="fetch.customer_name">Name : {{fetch.customer_name}}</p> 
              <p class="mb-0 text-danger row" v-if="fetch.canteen">C.Number : {{fetch.canteen}}</p> 
              <p class="mb-0 text-danger row" v-if="fetch.bukkle"> B.Number : {{fetch.bukkle}}</p> 
              <p class="mb-0 text-danger row" v-if="fetch.mobile">Mo.Number : {{fetch.mobile}}</p> 
        </div>  -->
    </div>
</div>
<!--  -->
<form @submit.prevent="createProduct()">
<div class="card-header p-1">
<h6 class="bb-1 mb-0 text-primary">Item Details</h6>
<div class="form-group row">
<div class="col-sm-3">
<label for="staticEmail" class="col-form-label">Search Barcode</label>
<!-- <input type="text"  class="form-control"  v-model="iform.barcode" placeholder="Search Barcode" :class="{ 'is-invalid': errors.barcode }" v-on:keyup="GetProductDetails()"> -->
<input type="text"  class="form-control" id="table_search"  v-model="iform.barcode" placeholder="Barcode" :class="{ 'is-invalid': errors.barcode }" v-on:keyup="getbarcodeDetails()" >
<span class="label label-danger" v-if="errors.barcode">{{errors.barcode}} </span>
</div>
<div class="col-sm-5">
<label for="staticEmail" class="col-form-label">Product Name</label>
<v-select   v-model="iform.product_idx"  :options="options" label="name" @search="setSelectedx"   @input="getProductx"/>
<span class="label label-danger" v-if="errors.product_name">{{errors.product_name}} </span>
</div>
<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">Size</label>
<select class="form-control" v-model="iform.size" placeholder="Select Size"  v-on:change="GetStockData()">
    <option v-for="catx of sizesx" :key="catx.id" :value="catx.id"  >{{catx.name}}</option>
</select>
<span class="label label-danger" v-if="errors.size">{{errors.size}} </span>
</div>
<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">Color</label>
<select class="form-control" v-model="iform.color" placeholder="Select Size" v-on:change="GetStockData()" >
    <option v-for="catxd of colorsx" :key="catxd.id" :value="catxd.id" >{{catxd.name}}</option>
</select>
<span class="label label-danger" v-if="errors.color">{{errors.color}} </span>
</div>
<div class="col-sm-5">
     <label for="staticEmail" class="col-form-label">Select Product</label>
    <select class="form-control" v-model="iform.stock_select" placeholder="Select Size"  v-on:change="getstockdatanew()">
        <option v-for="catxs of getproducts" :key="catxs.id" :value="catxs.id"  >{{catxs.name}}</option>
    </select>
    <span class="label label-danger" v-if="errors.stock_select">{{errors.stock_select}} </span>
</div>
<div class="col-sm-1">
    <label for="staticEmail" class="col-form-label">In Stock</label>
    <input type="text"  readonly class="form-control stock"  v-model="iform.stock" placeholder="In Stock" :class="{ 'is-invalid': errors.stock }">
    <span class="label label-danger" v-if="errors.stock">{{errors.stock}} </span>
    </div>
<div class="col-sm-1">
<label for="staticEmail" class="col-form-label">Add Qty</label>
<input type="text"   class="form-control"  v-model="iform.qty" placeholder="Add Qty" :class="{ 'is-invalid': errors.qty }" v-on:keyup="gettotalPrice()">
<span class="label label-danger" v-if="errors.qty">{{errors.qty}} </span>
</div>
<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">Total Price</label>
<input type="text" readonly  class="form-control"  v-model="iform.total_price" placeholder="Total Price" :class="{ 'is-invalid': errors.total_price }">
<span class="label label-danger" v-if="errors.total_price">{{errors.total_price}} </span>
</div>
<div class="col-sm-1">
<button type="submit" class="btn btn-primary text-center  mt-23">Add</button>
</div>

</div>
      
</div>
</form>

<!--  -->
<div class="card-body p-3">
 <table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Product name</th>
            <th>Size</th>
            <th>Color</th>
            <th>HSN</th>
            <th>MRP</th>
            <th>Taxable Value</th>
            <th>Qty</th>
            <th>Total Taxable</th>
            <th>Sgst</th>
            <th>Cgst</th>
            <th>Igst</th>
            <th>Total Gst</th>
            <th>Total Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(purchase, index) in Purchases" :key="'sc'+index" :class="{ 'bg-secondary': purchase.order_id}">
            <td>{{(1+index)}}</td>
            <td>{{ purchase.p_v_name | upText }}</td>
            <td>{{ purchase.color_name  }}</td>
            <td>{{ purchase.size_name  }}</td>
            <td>{{ purchase.prohsn  }}</td>
            <td>{{ purchase.mrp  }}</td>
            <td>{{calc(purchase.taxable_rate)}}</td>
            <td>{{ purchase.qty }}</td>
            <td>{{ calc(purchase.taxable_rate*purchase.qty)  }}</td>
            <td>{{ calc(purchase.sgst*purchase.qty)  }}</td>
            <td>{{ calc(purchase.cgst*purchase.qty)  }}</td>
            <td>{{ calc(purchase.igst*purchase.qty)  }}</td>
            <td>{{ calc(purchase.tgst*purchase.qty)  }}</td>
            <td>{{ calc(gettotprice(purchase.taxable_rate*purchase.qty,purchase.tgst*purchase.qty))  }}</td>
            <td v-if="purchase.order_id"><button @click="deleteVendor(purchase.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
            <td v-if="!purchase.order_id"><button @click="deletemakeVendor(purchase.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
        </tr>
        <tr class="bg-primary">
            <td colspan="5">Total</td>
            <td>{{ totalDatas.mrp  }}</td>
            <td>NA</td>
            <td>{{ totalDatas.qty  }}</td>
             <td>{{ calc(totalDatas.total_taxable)  }}</td>
            <td>{{ calc(totalDatas.total_sgst)  }}</td>
            <td>{{ calc(totalDatas.total_cgst)  }}</td>
            <td>{{ calc(totalDatas.total_igst)  }}</td>
            <td>{{ calc(totalDatas.total_tgst)  }}</td>
            <td>{{ calc(totalDatas.total_amount)  }}</td>
            <td>NA</td>
        </tr>
    </tbody>

 </table>
</div>
<!-- /.card -->
 <div class="row mt-1">
    <div class="col-md-9">
      <table class="table table-bordered"  >
        <thead>
            <tr>
                <th>Paid Amount</th>
                <th>Pending Amount</th>
                <th>Transaction Id</th>
                <th>Payment Mode</th>
            </tr>
        </thead>
        <tbody>
        <tr > 
            <td class="pl-2">
                <input type="text" id="paidamt" readonly v-model="mform.payment_paid" class="form-control fmcntrl1 "  placeholder="Paid Amount"   :class="{ 'is-invalid': errors.payment_paid }" v-on:keyup="getFinalPending()">
                <span class="label label-danger" v-if="errors.payment_paid">{{errors.payment_paid}} </span>
            </td>
            <td class="pl-2">
                <input type="text" v-model="mform.payment_pending" readonly class="form-control fmcntrl1 " placeholder="Pending Amount"    :class="{ 'is-invalid': errors.payment_pending }">
                <span class="label label-danger" v-if="errors.payment_pending">{{errors.payment_pending}} </span>
            </td>
            <td class="pl-2">
                <input type="text"  v-model="mform.transaction_id" class="form-control fmcntrl1 "  placeholder="Transaction Id"  :class="{ 'is-invalid': errors.transaction_id }">
                <span class="label label-danger" v-if="errors.transaction_id">{{errors.transaction_id}} </span>
            </td>
            <td class="pl-2">
                <select v-model="mform.payment_mode" class="form-control fmcntrl1"  :class="{ 'is-invalid': errors.payment_mode }">
                   <!-- <option value="" selected>Select Mode</option> -->
                    <option value="Bank" selected>Bank</option>
                    <option value="Card" >Card</option>
                    <option value="Upi" >Upi</option>
                </select>
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
            <div class="col-md-5 text-right p-0"><h6 class="text-primary">Rebate 50% GST</h6></div>
            <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
            <div class="col-md-6 text-left p-0"><h6 class="text-primary">{{calc(this.totalDatas.total_rtgst)}}</h6></div>
        </div>
    
        <div class="row">
        <div class="col-md-5 text-right p-0"><h6 class="text-primary">Other Charges</h6></div>
        <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
        <div class="col-md-6 text-left p-0"><input type="text"   class="form-control fmcntrl" id="other"  v-model="mform.other_charges" placeholder="other" :class="{ 'is-invalid': errors.other_charges }" v-on:keyup="gettotaloffinal()" ></div>
    </div>
    <hr class="bg-primary">
      <div class="row">
        <div class="col-md-5 text-right p-0"><h6 class="text-primary">Final Total</h6></div>
        <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
        <div class="col-md-6 text-left p-0"><h6 class="text-primary">{{Math.round(parseFloat(this.totalDatas.final_total))}}
        </h6></div>
    </div>
    </div>
 </div>

  <!-- Modal -->
   <div class="row ">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
           <!-- <button type="button" @click="ResetForm" class="btn btn-danger" > Reset</button> -->
           <button type="button"  :disabled="isSubmitDisabled" @click="SubmitForm" id="subbmit" class="btn btn-primary"> Submit</button>
    </div>
    <div class="col-md-4"></div>
</div>    
</div>
<br><br>
<!-- Modal -->


 

</div>
</div>
    <div class="modal fade" id="orderplaced" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="false">
             <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-sucesss" id="exampleModalLongTitle">You Order Placed Successfully....</h5>  
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <p class="text-center">
                            <router-link :to="this.invoicelink" >
                                <button class="btn btn-sm btn-primary"><i class="fas fa-file"></i> Get Invoice </button>
                            </router-link>
                             <router-link :to="this.invoicetherlink" >
                                <button class="btn btn-sm btn-primary"><i class="fas fa-file"></i> Get Thermal </button>
                            </router-link>
                            <router-link :to="this.invoicealink" >
                                <button class="btn btn-sm btn-primary"><i class="fas fa-file"></i> Get A5 </button>
                            </router-link>
                            </p>
                        </div>
                     
          
            </div>
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
        },
        data() {
            return { 
                isSubmitDisabled: false,
                form : new Form({
                    id:'',
                    user_id:'',
                    vendor_id:'',
                    invoice_no:'',
                    p_vendor_id:'',
                    barcode: '',
                    invoice_date: '',
                    product_id: '',
                    product_idx: '',
                    size: '',
                    color: '',
                    stock: 0,
                    qty: 0,
                    purchase_rate:0,
                    sales_rate:0,
                    pg_charges:0,
                    total_price:0,
                    gst: 0,
                    tgst: 0,
                    cgst: 0,
                    sgst: 0,
                    igst: 0,
                    stock_id:'',
                    taxable_rate:0,
                    total_amount:0,
                    is_active: true,
                    stock_select:'',
                }),
                iform:{
                    id:'',
                    user_id:'',
                    vendor_id:'',
                    invoice_no:'',
                    mrp:'',
                    p_vendor_id:'',
                    barcode: '',
                    invoice_date: '',
                    product_idx: '',
                    product_id: '',
                    size: '',
                    color: '',
                    stock: 0,
                    qty: 1,
                    purchase_rate:0,
                    sales_rate:0,
                    pg_charges:0,
                    total_price:0,
                    gst: 0,
                    stock_id:'',
                    tgst: 0,
                    cgst: 0,
                    sgst: 0,
                    igst: 0,
                    taxable_rate:0,
                    total_amount:0,
                    is_active: true,
                    stock_select:'',
                },
                blank:{
                    id:'',
                    user_id:'',
                    vendor_id:'',
                    p_vendor_id:'',
                    invoice_no:'',
                    barcode: '',
                    invoice_date: '',
                    product_id: '',
                    product_idx: '',
                    stock_id:'',
                    size: '',
                    color: '',
                    stock: 0,
                    qty: 0,
                    purchase_rate:0,
                    sales_rate:0,
                    pg_charges:0,
                    total_price:0,
                    gst: 0,
                    tgst: 0,
                    cgst: 0,
                    sgst: 0,
                    igst: 0,
                    taxable_rate:0,
                    total_amount:0,
                    is_active: true,
                    stock_select:'',
                },
                sform: new Form({
                    vendor_id :'',
                    size :'',
                    color:'',
                    product_id:'',
                }),
                fform: new Form({
                    vendor_id :'',
                    p_vendor_id :'',
                    product_id :'',
                    color:'',
                    size:'',
                }), 
                mform: new Form({
                    vendor_id:'',
                    user_id:'',
                    final_total:0,
                    payment_paid:0,
                    payment_pending:0,
                    transaction_id:'',
                    payment_mode:'',
                    other_charges:0,
                    final_amount:0,
                    total_tgst:0,
                    total_rtgst:0,
                    total_taxable:0,
                    total_id:[],
                }),
                cform: new Form({
                    vendor_id :'',
                    canteen :'',
                    bukkle:'',
                    customer_name:'',
                    mobile:'',
                }),
                rform: new Form({
                    vendor_id :'',
                    user_id :'',
                }),
                errors:{},
                multipartForm: new FormData,
                SetData:{},
                Vendors:{},
                sizesx:{},
                colorsx:{},
                rsizesx:{},
                rcolorsx:{},
                options: [],
                getproducts:{},
                soptions:[],
                getproducts:[],
                Purchases:{},
                Payments:{},
                tmpvendors:{},
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
                    total_rtgst:0,
                    final_total:0,
                    pending_amount:0,
                }),
                fetch:({
                    canteen :'',
                    bukkle:'',
                    customer_name:'',
                    mobile:'',
                }), 
                userid:'',   
                user_id:'',
                invoicelink:'',
                invoicetherlink:'',
                invoicealink:'',
                isLoading:true,
                editId:null,
            }
        },
        methods :{
            createProduct(){
                if((this.iform.stock < this.iform.qty) || this.iform.stock==0 || this.iform.stock<0)
                {
                   toast.fire({
                        type: 'warning',
                        title: ' Stock Not Available'
                    });
                }
                else if(this.userid==undefined || this.userid==null || this.userid=='')
                {
                    toast.fire({
                        type: 'warning',
                        title: 'User Not Found'
                    });
                }
                else
                {
                    if(this.iform.qty==0)
                    {
                       toast.fire({
                         type: 'warning',
                         title: ' Please enter Quantity'
                       });
                    }
                   else {
                var a =$('#authid').val();
                this.$Progress.start();
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.iform.vendor_id = a;
                this.iform.user_id=this.userid;
                this.iform.product_id=this.iform.product_idx.id;
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }    
                axios.post('/api/AddMakeabill', this.multipartForm, config).then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Stock Created successfully'
                    }); this.$Progress.finish();
                    this.loadorderDetails();
                    this.makeblank();
                    this.iform =this.blank;
                    $("#table_search").focus();
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
                   }
                }
            },
            makeblank()
            {
                    this.id='';
                    this.user_id='';
                    this.vendor_id='';
                    this.invoice_no='';
                    this.mrp='';
                    this.p_vendor_id='';
                    this.barcode= '';
                    this.invoice_date= '';
                    this.product_idx= '';
                    this.product_id= '';
                    this.size= '';
                    this.color= '';
                    this.stock= 0;
                    this.qty= 0;
                    this.purchase_rate=0;
                    this.sales_rate=0;
                    this.pg_charges=0;
                    this.total_price=0;
                    this.gst= 0;
                    this.stock_id='';
                    this.tgst= 0;
                    this.cgst= 0;
                    this.sgst= 0;
                    this.igst= 0;
                    this.taxable_rate=0;
                    this.total_amount=0;
                    this.is_active= true;
                    this.stock_select='';
            },
             getProduct()
            {    
               this.colorsx=[];
                this.iform.size='';
                this.iform.color='';
                this.sizesx=[];
                this.iform.product_idx=this.iform.barcode;
                var a =$('#authid').val();
                this.sform.vendor_id=a;
                this.sform.size=this.iform.product_idx.size;
                this.sform.color=this.iform.product_idx.color;
                this.sform.product_id=this.iform.product_idx.id;
                 $('.loading-overlay').addClass('is-active');
                  this.sform.post('/api/StockColorFetch').then( (data)=>{
                    this.sizesx=data.data.size;
                    if(this.sizesx.length>0)
                    {
                        this.iform.size=this.sizesx[0].id;
                    }
                    this.colorsx=data.data.color;
                    if(this.colorsx.length>0)
                    {
                        this.iform.color=this.colorsx[0].id;
                    }
                    this.getproducts=data.data.product; 
                    this.iform.stock_select=this.getproducts[0].id;
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                });
               setTimeout(()=>{
                     this.getstockdatanew();
                    }, 1000);
            
            },
            getProductx()
            {  
               
                this.colorsx=[];
                this.iform.size='';
                this.iform.color='';
                this.sizesx=[];
                this.iform.barcode=this.iform.product_idx.barcode;
                var a =$('#authid').val();
                this.sform.vendor_id=a;
                this.iform.gst=this.iform.product_idx.gst;
                this.iform.mrp=this.iform.product_idx.mrp;
                this.sform.size=this.iform.product_idx.size;
                this.sform.color=this.iform.product_idx.color;
                this.sform.product_id=this.iform.product_idx.id;
                $('.loading-overlay').addClass('is-active');
                this.sform.post('/api/StockColorFetch').then( (data)=>{
                    this.sizesx=data.data.size;
                    if(this.sizesx.length>0)
                    {
                        var sizeee=this.sizesx[0].id;
                        this.iform.size=sizeee;
                    }
                    this.colorsx=data.data.color;
                    if(this.colorsx.length>0)
                    {  
                        var colorrrr=this.colorsx[0].id;
                        this.iform.color=colorrrr;
                    }
                    this.getproducts=data.data.product; 
                    this.iform.stock_select=this.getproducts[0].id;
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                });

                // this.fform.product_id=this.iform.product_idx.id;
                // this.fform.color=colorrrr;
                // this.fform.size=sizeee;
                // this.fform.vendor_id=a;


                setTimeout(()=>{
                     this.getstockdatanew();
                }, 2000);
                
            },
            GetStockData()
            {  
                  var a =$('#authid').val();
                this.fform.product_id=this.iform.product_idx.id;
                this.fform.color=this.iform.color;
                this.fform.size=this.iform.size;
                this.fform.vendor_id=a;
                this.fform.post('/api/GetproductData').then( (data)=>{
                        this.getproducts=data.data; 
                        if(this.getproducts.length>0)
                        {
                         this.iform.stock_select=this.getproducts[0].id;
                          setTimeout(()=>{
                            this.getstockdatanew();
                            }, 1000);
                        }
                        else
                        {    
                            this.iform.stock=0;
                             $('.loading-overlay').removeClass('is-active');
                        }
                   // $('.loading-overlay').removeClass('is-active');
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                     $('.loading-overlay').removeClass('is-active');
                });
                //  $('.loading-overlay').removeClass('is-active');
               
            },
            
            getstockdatanew()
            {   
                 $('.loading-overlay').addClass('is-active');
                var barcode =this.iform.stock_select;
                axios.get("/api/getcountstock/"+barcode).then(data=>{
                   this.iform.stock = data.data;     
                });

                var a=$('#authid').val();

                axios.get("/api/newGetSelstockdata/"+barcode+"/"+a).then(data=>{
                  this.iform.purchase_rate=data.data.purchase_rate;
                   this.iform.stock_id=data.data.id;
                   this.iform.sales_rate=data.data.price;
                   this.iform.stock=data.data.stockk;    
                });
                 setTimeout(()=>{
                     this.gettotalPrice();
                    }, 1000);
            
            },
            getbarcodeDetails()
            {   
                var barcode =$('#table_search').val();
                if(barcode==undefined || barcode==null || barcode==''){barcode='';}
                if(barcode.length>0)
                {
                    var a =$('#authid').val();
                    axios.get("/api/some/"+a+"/getbarcodeDetails/"+barcode).then(data=>{
                      this.options = data.data;        
                    });
                }
            },
            setSelectedx(barcode)
            {   
                if(barcode.length>0)
                {  
                    var a =$('#authid').val();
                   axios.get("/api/some/"+a+"/getproDetails/"+barcode).then(data=>{
                   this.options = data.data;        
                });
                }
            },
            gettotalPrice()
            {  
               $('.loading-overlay').addClass('is-active'); 
               var gst=this.iform.gst;
               var price=this.iform.total_price=parseFloat(this.iform.sales_rate) + parseFloat(this.iform.pg_charges);
               var idc=parseFloat(1)+(parseFloat(gst/100));
               var tsc=parseFloat(price/idc).toFixed(2);
               this.iform.taxable_rate=tsc;
               this.iform.tgst=(parseFloat(price)-parseFloat(tsc)).toFixed(2);
               this.iform.cgst =this.iform.sgst=parseFloat(this.iform.tgst/2).toFixed(2);
               this.iform.total_amount=(parseFloat(this.iform.qty)*parseFloat(price)).toFixed(2);
               $('.loading-overlay').removeClass('is-active');
            },  
            newForm(){
                this.errors = {};
                this.iform =this.blank;
                this.editMode = false;
                this.multipartForm = new FormData;
            }, 
            //Customer Fetch
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
               
                for(let x in this.Purchases)
                 {   
                  // this.totalDatas.total_amount=
                   this.totalDatas.mrp += parseFloat(this.Purchases[x].mrp);
                   this.totalDatas.qty += parseFloat(this.Purchases[x].qty);
                   this.totalDatas.total_taxable += parseFloat(this.Purchases[x].taxable_rate)*this.Purchases[x].qty;
                   this.totalDatas.total_sgst += parseFloat(this.Purchases[x].sgst)*this.Purchases[x].qty;
                   this.totalDatas.total_cgst += parseFloat(this.Purchases[x].cgst)*this.Purchases[x].qty;
                   this.totalDatas.total_igst += parseFloat(this.Purchases[x].igst)*this.Purchases[x].qty;
                   this.totalDatas.total_tgst += parseFloat(this.Purchases[x].tgst)*this.Purchases[x].qty;
                    this.totalDatas.final_total=this.totalDatas.total_amount += parseFloat(parseFloat(this.Purchases[x].taxable_rate*this.Purchases[x].qty)+parseFloat(this.Purchases[x].tgst*this.Purchases[x].qty));
                   // =this.totalDatas.final_total;
                   var vt=$('#other').val();
                   if(vt==undefined || vt==null || vt=='')
                  {
                    var vt=0;
                  }
                  this.totalDatas.total_rtgst=parseFloat(this.totalDatas.total_tgst/2);
                  this.totalDatas.final_total=Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(vt))-parseFloat(this.totalDatas.total_rtgst));;
                    this.mform.payment_paid=this.totalDatas.final_total;
                   this.mform.payment_pending=Math.round(parseFloat(this.totalDatas.final_total)-parseFloat(this.mform.payment_paid));
               }
                 
                //  this.getFinalPending();
            },
            // cust end 
            calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },
            SubmitForm()
            {   
                if((this.mform.payment_mode=='' || this.mform.payment_mode==undefined || this.mform.payment_mode==null || this.mform.payment_mode==0) && this.mform.payment_pending==0 && this.mform.transaction_id=='' && this.mform.payment_mode=='')
                {
                   toast.fire({
                        type: 'warning',
                        title: 'Select All Feilds'
                    });
                }
                else
                {
                $('.loading-overlay').addClass('is-active'); 
                var a =$('#authid').val();
                this.mform.vendor_id=a;
                this.mform.user_id=this.iform.user_id=this.userid;
                this.mform.total_rtgst=this.totalDatas.total_rtgst;
                this.mform.final_total=Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(this.mform.other_charges))-parseFloat(this.totalDatas.total_rtgst));
                this.mform.total_taxable=this.totalDatas.total_taxable;
                this.mform.total_tgst=this.totalDatas.total_tgst;
                this.mform.post('/api/editorders/'+this.editId).then( (data)=>{
                    if(data.data.status=='200')
                    {
                        this.invoicelink='/Invoice/'+this.editId ;
                         this.invoicetherlink='/InvoiceThermal/'+this.editId ;
                         this.invoicealink='/InvoicePrint/'+this.editId ;
                        toast.fire({
                            type: 'success',
                            title: 'Order Added Successfully'
                        });
                        this.isSubmitDisabled = true;
                        setTimeout(()=>{
                     $('#orderplaced').modal('show');  
                       $('.loading-overlay').removeClass('is-active'); 
                    }, 2000);
                      
                    }
                    else
                    {
                         $('.loading-overlay').removeClass('is-active'); 
                        toast.fire({
                        type: 'error',
                        title: data.message
                       });
                    }
                    
                    this.$Progress.finish();
                  }).catch((data)=>{
                    toast.fire({
                        type: 'error',
                        title: data.message
                    });
                    this.errors =data.reponse.errors;
                    console.log(data.errors);
                    this.$Progress.fail();
                     $('.loading-overlay').removeClass('is-active'); 
                });
                }
            },

            deletemakeVendor(id){
                // sweet alert modal
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        // send delete request
                        // console.log(result)
                    if(result.value){
                        axios.get("/api/deletemakeabill/"+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Item has been deleted.',
                            'success'
                            );
                        this.getUrl(this.mform.user_id);
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Vendor can't  be deleted.`,
                            'danger'
                            )
                        })
                    }
                })
            },

            deleteVendor(id){
                // sweet alert modal
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this !",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        // send delete request
                        // console.log(result)
                    if(result.value){
                        axios.get("/api/deleteorderproduct/"+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Item has been deleted.',
                            'success'
                            );
                        this.loadorderDetails();
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Vendor can't  be deleted.`,
                            'danger'
                            )
                        })
                    }
                })
            },
            gettotaloffinal()
            {
                var vt=$('#other').val();
                if(vt==undefined || vt==null || vt=='')
                {
                    var vt=0;
                }
                this.totalDatas.final_total=Math.round(parseFloat(vt)+parseFloat(this.totalDatas.total_amount));
                this.mform.payment_pending=this.totalDatas.final_total;
                this.mform.payment_paid=this.mform.payment_pending;
                this.getFinalPending();
            },
              getFinalPending()
            {  
                var tot=this.mform.final_total=Math.round(this.totalDatas.final_total);
                var paid=this.mform.payment_paid;
                if(paid==''){paid=0;}
                var tott=(parseFloat(tot)-parseFloat(paid)).toFixed(2);
                this.mform.payment_pending=(parseFloat(tott)).toFixed(2);
                if(this.mform.payment_pending<0 || parseFloat(tot)!=parseFloat(paid))
                {
                    toast.fire({
                        type: 'warning',
                        title: 'Enter Correct Paid Amount'
                    });
                    $('#subbmit').attr('disabled',true); 
                }
                else
                { 
                    toast.fire({
                        type: 'success',
                        title: 'You Can Submit Now'
                    });
                    $('#subbmit').attr('disabled',false); 
                }
            },
            loadorderDetails() {
             //  this.OrderId = this.$route.params.OrderId;
                axios.get("/api/orderdetails/"+this.editId).then( ({ data }) => {
                    this.Purchases = data[0].products; 
                    //console.log(this.Purchases.user);
                    this.fetch.canteen=data[0].user.card_no;
                    this.fetch.bukkle=data[0].user.bukkle_no;
                    this.fetch.customer_name=data[0].user.name;
                    this.fetch.mobile=data[0].user.mobile;
                    this.user_id=data[0].user_id;
                    this.userid=data[0].user_id;
                    this.mform.payment_mode=data[0].order_payment.payment_mode;
                    this.mform.other_charges=data[0].delivery_charges;
                    this.mform.other_charges=data[0].delivery_charges;
                    this.mform.created_at =data[0].created_at;
                    $('#other').val(data[0].delivery_charges);
                    this.mform.transaction_id=data[0].order_payment.transaction_uid;
                    this.getTotalData(); 
                    this.getUrl();
                });
            },
            getUrl()
            {   var a=$('#authid').val();
                axios.get("/api/getmakabilldata/"+a+"/user/"+this.userid).then( ({ data }) => (this.Purchases =this.Purchases.concat(data), this.getTotalData() ));
            },
            gettotprice(tmp,cmp)
            {
              return parseFloat(tmp)+parseFloat(cmp);
            },
        },
        mounted() 
        {
           // $('#orderplaced').modal('show'); 
        },
        created()
        {   
            // $('#orderplaced').modal('show');  
            this.editId = this.$route.params.OrderId;
           // this.getUrl();
            this.loadorderDetails();
          //  Fire.$on('LoadProduct', () =>  this.getUrl());
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
.table th, .table td {padding: 0.2rem!important;}
.fmcntrl{ width: 82%!important;height: calc(1.6em + 0.05rem + 2px)!important;}
.fmcntrl1{ width: 100%!important;height: calc(1.6em + 0.05rem + 2px)!important;padding: 0.05rem 0.75rem!important;}
.mt{margin-top:0.2rem!important;}.mb{margin-bottom:0.2rem!important;}
</style>