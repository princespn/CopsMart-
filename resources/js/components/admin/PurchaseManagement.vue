<template>
<div class="container p-0">
<!-- /.row -->

<div class="row mt-3">
<div class="col-12">
<div class="card p-2">
    <div class="card-header"> 
        <h3 class="card-title ml-3">Purchase Management</h3>
    </div> 
<!-- /.card-header -->
<form @submit.prevent="createProduct()">
<div class="card-header">
<h6 class="bb-1 mb-0">Vendor Details</h6>
<div class="form-group row">
<div class="col-sm-3">
<label for="staticEmail" class="col-form-label">Vendor Name</label>
<select class="form-control" v-model="mform.vendor_id" placeholder="Select Vendor"  v-on:change="getbankdetails(this)">
    <option v-for="cat of Vendors" :key="cat.id" :value="cat.id"  >{{cat.name}}</option>
</select>
 
<span class="label label-danger" v-if="errors.vendor_id">{{errors.vendor_id}} </span>
</div>
<div class="col-sm-3">
<label for="staticEmail" class="col-form-label">Invoice Number</label>
<input type="text"  class="form-control"  id="getinv" v-model="mform.invoice_no" placeholder="Invoice Number" :class="{ 'is-invalid': errors.invoice_no }" v-on:keyup="gettmplist()">
<span class="label label-danger" v-if="errors.invoice_no">{{errors.invoice_no}} </span>
</div>
<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">Invoice Date</label>
<input type="date"  class="form-control"  v-model="mform.invoice_date" placeholder="Invoice Date" :class="{ 'is-invalid': errors.invoice_date }" v-on:keyup="gettmplist()">
<span class="label label-danger" v-if="errors.invoice_date">{{errors.invoice_date}} </span>
</div>
<div class="col-sm-2">
<a  href="/AddPurchaseVendor" target="_blank" class="btn btn-success btn-sm mt-23" > <i class="fa fa-plus"></i> Add Vendor</a>
</div>
<div class="col-sm-2">
<a  href="/addproduct" target="_blank" class="btn btn-primary btn-sm mt-23" ><i class="fa fa-plus"></i> Add Product / Item</a>
</div>
</div>
</div>
<!--  -->
<div class="card-header p-1">
<h6 class="bb-1 mb-0">Item Details</h6>
<div class="form-group row">
<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">Search Barcode</label>
<!-- <input type="text"  class="form-control"  v-model="iform.barcode" placeholder="Search Barcode" :class="{ 'is-invalid': errors.barcode }" v-on:keyup="GetProductDetails()"> -->
<input type="text"  class="form-control" id="table_search"  v-model="iform.barcode" placeholder="Barcode" :class="{ 'is-invalid': errors.barcode }" v-on:keyup="getbarcodeDetails()" >
<span class="label label-danger" v-if="errors.barcode">{{errors.barcode}} </span>
</div>
<div class="col-sm-4">
<label for="staticEmail" class="col-form-label">Product Name</label>
<v-select   v-model="iform.product_idx"  :options="options" label="name" @search="setSelectedx"   @input="getProductx"/>
<span class="label label-danger" v-if="errors.product_name">{{errors.product_name}} </span>
</div>
<div class="col-sm-1">
<label for="staticEmail" class="col-form-label">Size</label>
<select class="form-control" v-model="iform.size" placeholder="Select Size"  v-on:change="GetStockData()">
    <option v-for="catx of sizesx" :key="catx.id" :value="catx.id"  >{{catx.name}}</option>
</select>
<span class="label label-danger" v-if="errors.size">{{errors.size}} </span>
</div>
<div class="col-sm-1">
<label for="staticEmail" class="col-form-label">Color</label>
<select class="form-control" v-model="iform.color" placeholder="Select Size" v-on:change="GetStockData()" >
    <option v-for="catxd of colorsx" :key="catxd.id" :value="catxd.id" >{{catxd.name}}</option>
</select>
<span class="label label-danger" v-if="errors.color">{{errors.color}} </span>
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

<div class="col-sm-1">
<label for="staticEmail" class="col-form-label">MRP</label>
<input type="text"   class="form-control" readonly  v-model="iform.mrp" placeholder="MRP" :class="{ 'is-invalid': errors.mrp }" v-on:keyup="gettotalPrice()">
<span class="label label-danger" v-if="errors.mrp">{{errors.mrp}} </span>
</div>
<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">P.Taxable Rate</label>
<input type="text"   class="form-control"  v-model="ptaxable_rate" placeholder="Taxable Rate" :class="{ 'is-invalid': errors.ptrate }" v-on:keyup="gettotalSalePrice()">
<span class="label label-danger" v-if="errors.ptrate">{{errors.ptrate}} </span>
</div>


<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">P.Rate</label>
<input type="text"   class="form-control"  v-model="iform.purchase_rate" placeholder="P. Rate" :class="{ 'is-invalid': errors.prate }" v-on:keyup="gettotalPrice()">
<span class="label label-danger" v-if="errors.prate">{{errors.prate}} </span>
</div>
<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">Sales Rate</label>
<input type="text"   class="form-control"  v-model="iform.sales_rate" placeholder="Sales Rate" :class="{ 'is-invalid': errors.srate }" v-on:keyup="gettotalnewss()">
<span class="label label-danger" v-if="errors.srate">{{errors.srate}} </span>
</div>
<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">Taxable Rate</label>
<input type="text" readonly  class="form-control"  v-model="iform.taxable_rate" placeholder="Taxable Rate" :class="{ 'is-invalid': errors.trate }" >
<span class="label label-danger" v-if="errors.trate">{{errors.trate}} </span>
</div>
<div class="col-sm-1">
<label for="staticEmail" class="col-form-label">GST %</label>
<input type="text" readonly  class="form-control"  v-model="iform.gst" placeholder="GST %" :class="{ 'is-invalid': errors.gst }">
<span class="label label-danger" v-if="errors.gst">{{errors.gst}} </span>
</div>

<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">Total Price</label>
<input type="text" readonly  class="form-control"  v-model="iform.total_price" placeholder="Total Price" :class="{ 'is-invalid': errors.total_price }">
<span class="label label-danger" v-if="errors.total_price">{{errors.total_price}} </span>
</div>
<div class="col-sm-1">
<button type="submit" class="btn btn-primary text-center btn-sm mt-23">Add</button>
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
            <th>P.Rate</th>
            <th>Taxable Value</th>
            <th>Qty</th>
            <th>Total Taxable</th>
            <th>GST %</th>
            <th>Sgst</th>
            <th>Cgst</th>
            <th>Igst</th>
            <th>Total Gst</th>
            <th>Total Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(purchase, index) in Purchases" :key="'sc'+index" :class="{ 'bg-secondary': purchase.is_active == 0  }">
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
            <td>{{ purchase.gst  }}</td>
            <td>{{ calc(purchase.p_sgst*purchase.qty)  }}</td>
            <td>{{ calc(purchase.p_cgst*purchase.qty)  }}</td>
            <td>{{ calc(purchase.p_igst*purchase.qty)  }}</td>
            <td>{{ calc(purchase.p_tgst*purchase.qty)  }}</td>
            <td>{{ calc(purchase.p_total_amount) }}</td>
            <td>
                <button @click="deleteVendor(purchase.id)" class="btn btn-danger btn-sm" :class="{'d-none': purchase.is_active==0}">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        <tr class="bg-primary">
            <td colspan="5">Total</td>
            <td>{{ totalDatas.mrp  }}</td>
            <td>NA</td>
             <td>NA</td>
            <td>{{ totalDatas.qty  }}</td>
             <td>{{ calc(totalDatas.total_taxable)  }}</td>
             <td></td>
            <td>{{ calc(totalDatas.total_sgst)  }}</td>
            <td>{{ calc(totalDatas.total_cgst)  }}</td>
            <td>{{ calc(totalDatas.total_igst)  }}</td>
            <td>{{ calc(totalDatas.total_tgst)  }}</td>
            <td>{{ calc(totalDatas.total_amount)  }}</td>
             <td>NA</td>
        </tr>
    </tbody>

 </table>
<!-- /.card-body -->

 <div class="row mt-1 ml-2">
    <div class="col-md-8">
       <div class="row  p-1">
           <div class="col-md-7">
               <div class="row bg-secondary">
                <div class="col-md-4 center">
               <h5 class="mt-2">Bank Account Details</h5>
            </div>
            <div class="col-md-8 mt-2">
               <p class="mb-1 ">Bank Name : {{this.tmpvendors.account_name}}</p>
               <p class="mb-1">Account No: {{this.tmpvendors.account_no}}</p>
               <p class="mb-1">Bank IFSC : {{this.tmpvendors.ifsc}}</p>
            </div>
               </div>
           </div>
           
       </div>
       <div class="row">
        <div class="col-md-5 text-right p-0"><h6 class="text-primary">Pending Amount  </h6></div>
        <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
        <div class="col-md-6 text-left p-0"><h6 class="text-primary" id="pendingamt">{{calc(this.totalDatas.pending_amount)}}</h6></div>
    </div>
    </div>
    <div class="col-md-4">
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
        <div class="col-md-6 text-left p-0"><input type="text"   class="form-control fmcntrl" id="other"  v-model="mform.other_charges" placeholder="other" :class="{ 'is-invalid': errors.other_charges }" v-on:keyup="gettotaloffinal()" ></div>
    </div>
    <hr class="bg-primary">
      <div class="row">
        <div class="col-md-5 text-right p-0"><h6 class="text-primary"><strong>Final Total</strong></h6></div>
        <div class="col-md-1 text-center p-0"><h6 class="text-primary">:</h6></div>
        <div class="col-md-6 text-left p-0"><h6 class="text-primary">{{calc(parseFloat(this.totalDatas.final_total))}}</h6></div>
    </div>
    </div>
 </div>
 <div class="row ">
    <table class="table table-bordered"  >
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Paid Amount</th>
                <th>Pending Amount</th>
                <th>Transaction Id</th>
                <th>Payment Mode</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="paytm  in Payments"  :key="paytm.id" class="bg-success"> 
            <td class="pl-2">{{paytm.payment_date | myDateFormate}}</td>
            <td class="pl-2">{{paytm.status}}</td>
            <td class="pl-2">{{paytm.paid_amount}}</td>
            <td class="pl-2">{{paytm.remaining_amount}}</td>
            <td class="pl-2">{{paytm.tansaction_id}}</td>
            <td class="pl-2">{{paytm.payment_mode}}</td>
        </tr>
        <tr > 
            <td class="pl-2">
                <input type="date" v-model="mform.payment_date" class="form-control fmcntrl1 "  :class="{ 'is-invalid': errors.payment_date }">
            </td>
            <td class="pl-2">
                <input type="text" v-model="mform.payment_status" class="form-control fmcntrl1 "  placeholder="Status"   :class="{ 'is-invalid': errors.payment_status }">
            </td>
            <td class="pl-2">
                <input type="text" id="paidamt"  v-model="mform.payment_paid" class="form-control fmcntrl1 "  placeholder="Paid Amount"   :class="{ 'is-invalid': errors.payment_paid }" v-on:keyup="getFinalPending()">
            </td>
            <td class="pl-2">
                <input type="text" v-model="mform.payment_pending" class="form-control fmcntrl1 " placeholder="Pending Amount"    :class="{ 'is-invalid': errors.payment_pending }">
            </td>
            <td class="pl-2">
                <input type="text"  v-model="mform.payment_trans" class="form-control fmcntrl1 "  placeholder="Transaction Id"  :class="{ 'is-invalid': errors.payment_trans }">
            </td>
            <td class="pl-2">
                <select v-model="mform.payment_mode" class="form-control fmcntrl1"  :class="{ 'is-invalid': errors.payment_mode }">
                    <option value="Bank" selected>Bank</option>
                </select>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="row ">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
           <button type="button" @click="ResetForm" class="btn btn-danger" > Reset</button>
           <button type="button" @click="SubmitForm" id="subbmit" class="btn btn-primary"> Submit</button>
    </div>
    <div class="col-md-4"></div>
</div>
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
                rowId: 10,
                rowData:[{val:"1"}],   
                form : new Form({
                     id:'',
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
                    taxable_rate:0,
                    total_amount:0,
                    is_active: true,
                }),
                iform:{
                    id:'',
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
                },
                blank:{
                    id:'',
                    vendor_id:'',
                    p_vendor_id:'',
                    invoice_no:'',
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
                    taxable_rate:0,
                    total_amount:0,
                    is_active: true,
                },
                sform: new Form({
                    vendor_id :'',
                    size :'',
                }),
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
                    payment_trans:'',
                    payment_mode:'',
                    other_charges:0,
                    final_amount:0,
                    pending:0,
                }),
                fform: new Form({
                    vendor_id :'',
                    p_vendor_id :'',
                    product_id :'',
                    color:'',
                    size:'',
                }),
                rform: new Form({
                    vendor_id :'',
                    p_vendor_id :'',
                    invoice_no:'',
                }),
                ptaxable_rate:'',
                errors:{},
                multipartForm: new FormData,
                SetData:{},
                Vendors:{},
                sizesx:{},
                colorsx:{},
                options: [],
                Purchases:{},
                Payments:{},
                pgcharges:0,
                sales_percent:0,
                total_percent:0,
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
                    final_total:0,
                    pending_amount:0,
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
            createProduct(){
                var a =$('#authid').val();
                this.$Progress.start();
                    // Check if the taxable rate is present before proceeding
                    if (!this.ptaxable_rate) {
                        this.errors.ptrate = "Please generate the sales taxable rate before adding the item.";
                        this.$Progress.fail();
                        return; // Stop the form submission
                    }

                // this.multipartForm = new FormData;
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.iform.vendor_id = a;
               var invvv= this.iform.invoice_no = this.mform.invoice_no;
                this.iform.invoice_date = this.mform.invoice_date;
                var datee=this.iform.invoice_date;
                this.iform.p_vendor_id = this.mform.vendor_id;
                this.iform.product_id=this.iform.product_idx.id;
                //    console.log(this.iform);
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }
                
                axios.post('api/AddPurchase', this.multipartForm, config).then( ()=>{
                    //Fire.$emit('LoadProduct');
                    toast.fire({
                        type: 'success',
                        title: 'Purchase Vendor Created successfully'
                    });
                    this.totalDatas.pending_amount=0;
                    this.iform = this.blank;
                    this.makeblank();
                    $("#table_search").focus();
                    this.loadTmpList(invvv);
                  
                    this.iform.invoice_date=this.mform.invoice_date=datee;
                    this.$Progress.finish();
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
            },
            makeblank()
            {
                    this.iform.id='';
                    this.iform.vendor_id='';
                    this.iform.invoice_no='';
                    this.iform.mrp='';
                    this.iform.p_vendor_id='';
                    this.iform.barcode= '';
                    this.iform.invoice_date= '';
                    this.iform.product_idx= '';
                    this.iform.product_id= '';
                    this.iform.size= '';
                    this.iform.color= '';
                    this.iform.stock= 0;
                    this.iform.qty= 0;
                    this.iform.purchase_rate=0;
                    this.iform.sales_rate=0;
                    this.iform.pg_charges=0;
                    this.iform.total_price=0;
                    this.iform.gst= 0;
                    this.iform.tgst= 0;
                    this.iform.cgst= 0;
                    this.iform.sgst= 0;
                    this.iform.igst= 0;
                    this.iform.taxable_rate=0;
                    this.iform.total_amount=0;
                    this.iform.is_active=true;
            },
            loadPurchaseVendorList() {
                var cat_id = $('#authid').val();
                 axios.get("/api/loadPurchaseVendorList/"+cat_id).then(data=>{
                   this.Vendors = data.data;
                   
                });
            },
            getData()
            {
                var ta =$('#pincode').val();
                if(ta.length==6)
                {
                    //  $('.loading-overlay').removeClass('is-active');
                axios.get("api/getdistrict/"+ta).then( ({ data }) => ($('#district').val(data.pro_category.District),this.iform.district=data.pro_category.District, $('#state').val(data.pro_category.Circle),this.iform.state=data.pro_category.Circle) ); 
                }
            },
            getProduct()
            {
               this.iform.product_idx=this.iform.barcode;
              // console.log(this.iform.product_id);
               var a =$('#authid').val();
               this.sform.vendor_id=a;
               this.sform.size=this.iform.product_idx.size;
                $('.loading-overlay').addClass('is-active');
                this.sform.post('api/GetSizeResult').then( (data)=>{
                    //console.log(data);
                   this.sizesx=data.data;
                    if(this.sizesx.length>0)
                    {
                        this.iform.size=this.sizesx[0].id;
                        this.fform.size=this.iform.size;
                    }
                  this.iform.stock=0;
                   $('.loading-overlay').removeClass('is-active');
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                     $('.loading-overlay').removeClass('is-active');
                });

               var a =$('#authid').val();
               this.sform.vendor_id=a;
               this.sform.size=this.iform.product_idx.color;
                $('.loading-overlay').addClass('is-active');
               this.sform.post('api/GetColorResult').then( (data)=>{
                   // console.log(data);
                    this.colorsx=data.data;
                    if(this.colorsx.length>0)
                    {
                        this.iform.color=this.colorsx[0].id;
                        this.fform.color=this.iform.color;
                    }
                    this.iform.stock=0;
                    $('.loading-overlay').removeClass('is-active');
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                     $('.loading-overlay').removeClass('is-active');
                });
                
                   setTimeout(()=>{
                     this.GetStockData();
                    }, 1000);
            },
            getProductx()
            {
              this.iform.barcode=this.iform.product_idx.barcode;
              var a =$('#authid').val();
               this.sform.vendor_id=a;
                this.iform.gst=this.iform.product_idx.gst;
                this.iform.mrp=this.iform.product_idx.mrp;
               this.sform.size=this.iform.product_idx.size;
                $('.loading-overlay').addClass('is-active');
                this.sform.post('api/GetSizeResult').then( (data)=>{
                   // console.log(data);
                   this.sizesx=data.data;
                    if(this.sizesx.length>0)
                    {
                        this.iform.size=this.sizesx[0].id;
                        this.fform.size=this.iform.size;
                    }
                   this.iform.stock=0;
                    $('.loading-overlay').removeClass('is-active');
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                     $('.loading-overlay').removeClass('is-active');
                });

               var a =$('#authid').val();
               this.sform.vendor_id=a;
               this.sform.size=this.iform.product_idx.color;
                $('.loading-overlay').addClass('is-active');
               this.sform.post('api/GetColorResult').then( (data)=>{
                   // console.log(data);
                   this.colorsx=data.data;
                    if(this.colorsx.length>0)
                    {
                        this.iform.color=this.colorsx[0].id;
                        this.fform.color=this.iform.color;
                    }
                      this.iform.stock=0;
                       $('.loading-overlay').removeClass('is-active');
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                });
                setTimeout(()=>{
                     this.GetStockData();
                    }, 1000);
            },
            newForm(){
                this.errors = {};
                this.iform =this.blank;
                this.editMode = false;
                this.multipartForm = new FormData;
            },
            GetStockData()
            {  
                var a =$('#authid').val();
                this.fform.product_id=this.iform.product_idx.id;
                this.fform.color=this.iform.color;
                this.fform.size=this.iform.size;
                this.fform.vendor_id=a;
                this.fform.p_vendor_id=this.mform.vendor_id;
                 $('.loading-overlay').addClass('is-active');
                this.fform.post('api/GetStockResult').then( (data)=>{
                   this.iform.stock=data.data.qtyy;
                   this.iform.purchase_rate=data.data.purchase_rate;
                   this.iform.purchase_rate=data.data.purchase_rate;
                   this.iform.sales_rate=data.data.price;
                    $('.loading-overlay').removeClass('is-active');
                  // alert(data.data);
                //    $('.stock').val(data.data.qqty);
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                     $('.loading-overlay').removeClass('is-active');
                });
            },
            getbarcodeDetails()
            {   
                var barcode =$('#table_search').val();
                if(barcode==undefined || barcode==null || barcode==''){barcode='';}
                if(barcode.length>0)
                {
                    var a =$('#authid').val();
                     $('.loading-overlay').addClass('is-active');
                    axios.get("api/some/"+a+"/getbarcodeDetails/"+barcode).then(data=>{
                      this.options = data.data;     
                       $('.loading-overlay').removeClass('is-active');   
                    });
                }
            },
            gettmplist()
            { 
            //   this.mform.payment_pending=0;
              this.totalDatas.final_total=0;
              this.totalDatas.pending_amount=0;
              var barcode =$('#getinv').val();
              this.loadTmpList(barcode);
            },
            gettotalSalePrice()
            {
                var gst=this.iform.gst;
                var idc=parseFloat(1)+(parseFloat(gst/100));
                var txb=this.ptaxable_rate;
                var prate=parseFloat(txb)*parseFloat(idc);
                this.iform.purchase_rate=parseFloat(prate).toFixed(2);
                setTimeout(()=>{
                    this.gettotalPrice();
                        }, 500);
            },
            gettotalPrice()
            {   
                 $('.loading-overlay').addClass('is-active');
               this.CalculateSales();
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
            gettotalnewss()
            {   
               var gst=this.iform.gst;
               if(this.iform.sales_rate==''){this.iform.sales_rate=0;}
               if(this.iform.qty==''){this.iform.qty=0;}
               else{this.iform.sales_rate=this.iform.sales_rate;}
               var price=this.iform.total_price=parseFloat(this.iform.sales_rate) +parseFloat(this.iform.pg_charges);
               var idc=parseFloat(1)+(parseFloat(gst/100));
               var tsc=parseFloat(price/idc).toFixed(2);
               this.iform.taxable_rate=tsc;
               this.iform.tgst=(parseFloat(price)-parseFloat(tsc)).toFixed(2);
               this.iform.cgst =this.iform.sgst=parseFloat(this.iform.tgst/2).toFixed(2);
               this.iform.total_amount=(parseFloat(this.iform.qty)*parseFloat(price)).toFixed(2);
            },
            getbankdetails(id)
            {
                id=this.mform.vendor_id;
                axios.get("/api/purchasevendor/"+id).then( ({ data }) => (this.tmpvendors = data) );
                this.GetStockData();
                this.gettmplist();
            },
            setSelectedx(barcode)
            {   
                if(barcode.length>0)
                {  
                    var a =$('#authid').val();
                   axios.get("api/some/"+a+"/getproDetails/"+barcode).then(data=>{
                   this.options = data.data;        
                });
                }
            },
            loadTmpList(inv) { 
                 $('.loading-overlay').addClass('is-active');
                var a =$('#authid').val();
                axios.get("/api/loadtmppurchase/"+this.mform.vendor_id+"?ad="+a+"&inv="+inv).then( ({ data }) => (this.Purchases = data,this.getTotalData()) );
                axios.get("/api/getpendingamount/"+this.mform.vendor_id+"?ad="+a+"&inv="+inv).then( ({ data }) => (this.mform.pending=this.totalDatas.pending_amount=data));
                axios.get("/api/getpurchasepayment/"+this.mform.vendor_id+"?ad="+a+"&inv="+inv).then( ({ data }) => (this.Payments = data) );
                this.settime();
                $('.loading-overlay').removeClass('is-active');    
            },
            getTotalData()
            {    
                this.mform.invoice_date='';
                this.totalDatass.final_total=0;
                this.totalDatas.pending_amount=0;
                this.mform.final_total=0;
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
                this.mform.payment_pending=0;
                this.totalDatas.pending_amount=0;
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
                   this.mform.invoice_date=this.Purchases[x].invoice_date;
               
                    if(this.Purchases[x].is_active==0)
                    {
                        this.totalDatass.mrp += parseFloat(this.Purchases[x].mrp);
                        this.totalDatass.qty += parseFloat(this.Purchases[x].qty);
                        this.totalDatass.total_taxable += parseFloat(this.Purchases[x].p_taxable_rate)*this.Purchases[x].qty;
                        this.totalDatass.total_sgst += parseFloat(this.Purchases[x].p_sgst)*this.Purchases[x].qty;
                        this.totalDatass.total_cgst += parseFloat(this.Purchases[x].p_cgst)*this.Purchases[x].qty;
                        this.totalDatass.total_igst += parseFloat(this.Purchases[x].p_igst)*this.Purchases[x].qty;
                        this.totalDatass.total_tgst += parseFloat(this.Purchases[x].p_tgst)*this.Purchases[x].qty;
                        this.totalDatass.final_total=this.totalDatass.total_amount += parseFloat(this.Purchases[x].p_total_amount);
                    }
                }
                
                 this.getFinalPending();
            },
            gettotaloffinal()
            {
                var vt=$('#other').val();
                if(vt==undefined || vt==null || vt=='')
                {
                    var vt=0;
                }
                
                // 
                
                this.totalDatas.final_total=parseFloat(vt)+parseFloat(this.totalDatas.total_amount);
                
               this.getFinalPending();
            },
            addRow(i){
                let row = {
                    val : this.selected,
                };
                this.rowData.splice(i, 0, row);
                this.selected = '';
            },
            deleteRow(i) {
               this.rowData.splice(i,1);
            },
            removeRow(obj){
                console.log(obj);
                $(obj).closest('tr').remove();
            },
            getFinalPending()
            {  
                $('.loading-overlay').addClass('is-active');
            //    var pending=this.mform.pending=this.totalDatas.pending_amount;
               var pending=this.totalDatas.pending_amount;
               var tot=this.mform.final_total=this.totalDatas.final_total;
               var totx=this.totalDatass.final_total=(parseFloat(this.totalDatass.final_total));
               
            //    alert(totx);
               var paid=$('#paidamt').val();
               if(paid==''){paid=0;}
               var tott=(parseFloat(tot)-parseFloat(paid)).toFixed(2);
               this.mform.payment_pending=(parseFloat(tott)-parseFloat(totx)+parseFloat(pending)).toFixed(2);
               this.mform.tot_final_total=this.mform.payment_pending;
                if(this.mform.payment_pending<0)
                {
                  toast.fire({
                        type: 'warning',
                        title: 'Enter Correct Paid Amount'
                    });
                 $('#subbmit').attr('disabled',true); 
                }
                else
                {
                    $('#subbmit').attr('disabled',false); 
                }
                $('.loading-overlay').removeClass('is-active');    
            },
            SubmitForm()
            {   
                this.mform.p_vendor_id= this.mform.vendor_id;
                var a =$('#authid').val();
                this.mform.vendor_id=a;
                var tot=this.mform.final_total
                var pending=this.totalDatas.pending_amount;
                var totx=this.totalDatass.final_total
                this.mform.final_total=(parseFloat(tot)-parseFloat(totx))+parseFloat(pending);
                // alert(this.mform.final_total);
                this.mform.total_taxable=this.totalDatas.total_taxable;
                this.mform.total_tgst=this.totalDatas.total_tgst;
                // this.totalDatas.final_total
                this.mform.post('/api/addpurchasedata').then( (data)=>{
                    // console.log(data);
                    if(data.data.msg=='Added Successfully')
                    {
                        toast.fire({
                            type: 'success',
                            title:'Purchase '+data.data.msg,
                        });
                        setTimeout(()=>{
                           window.location.reload();
                        }, 1000);
                    }
                    else
                    {   
                         this.mform.vendor_id= this.mform.p_vendor_id;
                         toast.fire({
                            type: 'warning',
                            title: data.data.msg,
                        });
                    }
                    this.$Progress.finish();
                  }).catch(()=>{
                    this.errors =data.response.data.errors;
                    //console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
            },
            ResetForm()
            {    
                var a =$('#authid').val();
                this.rform.vendor_id=a;
                this.rform.p_vendor_id=this.mform.vendor_id;
                this.rform.invoice_no=this.mform.invoice_no;
                this.rform.post('api/resettmppurchase').then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Purchase List Reset Successfully'
                    });
                    setTimeout(()=>{
                    window.location.reload();
                    }, 1000);
                    this.$Progress.finish();
                  }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
            },   
            settime()
            {    
                  setTimeout(()=>{
                    var pending=this.mform.pending=this.totalDatas.pending_amount;
                    //   this.totalDatas.final_total=parseFloat(this.totalDatas.final_total)+parseFloat(pending);
                      this.mform.payment_pending=parseFloat(this.mform.payment_pending)+parseFloat(pending);
                    }, 1000);
                      
            } , 
            calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },
            deleteVendor(id){
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
                        //console.log(result)
                    if(result.value){
                        axios.get("/api/deletetmppurchase/"+id).then(data=>{
                            swal.fire(
                            'Deleted!',
                            'Purchase has been deleted.',
                            'success'
                            );
                            this.gettmplist();
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
            loadVendorList()  
            {
                var a =$('#authid').val();
                axios.get("api/vendorgetonlineoffline/"+a).then(data=>{
                // this..online=data.data.online;
                // this.iform.offline=data.data.offline;
                this.sales_percent=data.data.sales;
                this.pgcharges=data.data.pg_charges;
                this.total_percent=(parseFloat( this.sales_percent)+parseFloat(this.pgcharges));
                });
            },
            CalculateSales()
            {
                var prate=this.iform.purchase_rate;
                var tp=this.total_percent;
               //  alert(tp);
                var idc=parseFloat(tp/100);
                var tsc=parseFloat(prate)*parseFloat(idc);
               // alert(tsc);
                this.iform.sales_rate=parseFloat(prate)+parseFloat(tsc);
                this.iform.sales_rate=this.iform.sales_rate.toFixed(2);
            },
        },
        mounted() 
        {
            this.isLoading = true;  
        },
        created()
        {   
            this.loadPurchaseVendorList();
            this.loadVendorList();
            Fire.$on('LoadProduct', () => this.loadPurchaseVendorList() );
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