<template >
    <div class="container p-0">
        <div class="row mt-1">
            <div class="col-md-9">
           
           
                <div class="card-header p-1">
                    <h6 class="bb-1 mb-0 text-primary">Item Details</h6>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label for="staticEmail" class="col-form-label">Product Name</label>
                            <v-select ref="productname" v-model="iform.product_idx" inputId="selectProductName" :options="options" label="name" @input="getProductx" />
                            <span class="label label-danger" v-if="errors.product_name">{{ errors.product_name }}</span>
                        </div>
                    </div>
                </div>
             <!-- list -->
             <div class="row p-3">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Product name</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>HSN</th>
                            <th>MRP</th>
                            <!-- <th>Taxable Value</th> -->
                            <th>Qty</th>
                            <th>Total Taxable</th>
                            <!-- <th>Sgst</th>
                            <th>Cgst</th>
                            <th>Igst</th> -->
                            <th>Total Gst</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(purchase, index) in Purchases" :key="'sc'+index" :class="{ 'bg-secondary': purchase.is_active == 0  }">
                            <td>{{(1+index)}}</td>
                            <td>{{ purchase.p_v_name | upText }}</td>
                            <td>{{ purchase.color_name  }}</td>
                            <td>{{ purchase.size_name  }}</td>
                            <td>{{ purchase.hsn  }}</td>
                            <td>{{ purchase.mrp  }}</td>
                            <!-- <td>{{calc(purchase.taxable_rate)}}</td> -->
                            <td>{{ purchase.qty }}</td>
                            <td>{{ calc(purchase.taxable_rate*purchase.qty)  }}</td>
                            <!-- <td>{{ calc(purchase.sgst*purchase.qty)  }}</td>
                            <td>{{ calc(purchase.cgst*purchase.qty)  }}</td>
                            <td>{{ calc(purchase.igst*purchase.qty)  }}</td> -->
                            <td>{{ calc(purchase.tgst*purchase.qty)  }}</td>
                            <td>{{ calc(purchase.total_amount) }}</td>
                            
                            <td><button @click="deleteVendor(purchase.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                        </tr>
                        <tr class="bg-primary">
                            <td colspan="5">Total</td>
                            <td>{{ totalDatas.mrp  }}</td>
                            <!-- <td>NA</td> -->
                            <td>{{ totalDatas.qty  }}</td>
                            <td>{{ calc(totalDatas.total_taxable)  }}</td>
                            <!-- <td>{{ calc(totalDatas.total_sgst)  }}</td>
                            <td>{{ calc(totalDatas.total_cgst)  }}</td>
                            <td>{{ calc(totalDatas.total_igst)  }}</td> -->
                            <td>{{ calc(totalDatas.total_tgst)  }}</td>
                            <td>{{ calc(totalDatas.total_amount)  }}</td>
                            <td>NA</td>
                        </tr>
                    </tbody>
                
                </table>
            </div>
            </div>
            <!--<div class="col-md-1"></div>-->
            <div class="col-md-2 border setright p-0">
                 <h4 class="text-white text-center p-1 mb-0">Total : {{Math.round(calc(parseFloat(this.totalDatas.final_total)))}}</h4>
                 <h6 class="bg-dark text-center">Select customer details</h6>
                 <div class="row p-1">
                    <div class="col-md-2">
                        <h6 class="text-white">B.No.</h6>
                    </div>
                    <div class="col-md-10">
                     <input type="text"  class="form-control"  id="bucklnum" v-model="cform.bukkle" placeholder="Bukkle Number" :class="{ 'is-invalid': errors.user_id }"  >
                     <span class="label label-danger" v-if="errors.user_id">{{errors.user_id}} </span>
                    </div>
                 </div>
                 <div class="row p-1">
                    <div class="col-md-2">
                        <h6 class="text-white">M.No.</h6>
                    </div>
                    <div class="col-md-10">
                     <input type="text"  class="form-control"   v-model="cform.mobile" placeholder="Mobile Number" :class="{ 'is-invalid': errors.user_id }"  >
                     <span class="label label-danger" v-if="errors.user_id">{{errors.user_id}}</span>
                    </div>
                 </div>
                 <div class="row p-1">
                    <div class="col-md-2">
                        <h6 class="text-white">Name</h6>
                    </div>
                    <div class="col-md-10">
                     <v-select class="bg-white" v-model="cform.customer_name" :options="userss" label="name" :class="{ 'is-invalid': form.errors.has('user_id') }" />
                     <span class="label label-danger" v-if="errors.user_id">{{errors.user_id}} </span>
                    </div>
                 </div>
                 <!-- <div class="row p-1"> -->
                  <button class="btn btn-primary form-control fdnew" @click="submitcust">Submit</button>
                    <div class="row m-2 mb-1 text-white  ">
                     <div class="col-sm-12">
                        <p class="mb-0 row fs-14" v-if="fetch.customer_name">Name : {{fetch.customer_name}}</p> 
                        <p class="mb-0 row fs-14" v-if="fetch.bukkle"> B.Number : {{fetch.bukkle}}</p> 
                        <p class="mb-0 row fs-14" v-if="fetch.mobile">Mo.Number : {{fetch.mobile}}</p>
                     </div> 
                    </div>
                    <button class="btn btn-primary form-control fdnew" @click="CartEmpty">Empty Cart</button>
                    <h6 class="bg-dark text-center">Invoice Details :</h6>
                    <div class="row p-1">
                        <div class="col-md-6">
                           <h6  class="text-white mb-0">Taxable Value : </h6>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-white mb-0">{{calc(this.totalDatas.total_taxable)}}</h6>
                        </div>
                     </div>
                     <div class="row p-1">
                        <div class="col-md-6">
                           <h6  class="text-white  mb-0">Total GST : </h6>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-white  mb-0">{{calc(this.totalDatas.total_tgst)}}</h6>
                        </div>
                     </div>
                     <div class="row p-1">
                        <div class="col-md-6">
                           <h6  class="text-white  mb-0">Total 50% GST : </h6>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-white  mb-0">{{calc(this.totalDatas.total_rtgst)}}</h6>
                        </div>
                     </div>
                     <div class="row p-1">
                        <div class="col-md-6">
                           <h6  class="text-white  mb-0">Others : </h6>
                        </div>
                        <div class="col-md-6">
                            <input type="text"   class="form-control fmcntrl  mb-0" id="other"  v-model="mform.other_charges" placeholder="other" :class="{ 'is-invalid': errors.other_charges }" v-on:keyup="gettotaloffinal()" >
                        </div>
                     </div>
                     <hr />
                     <h4 class="bg-dark text-center  mb-0">Total : {{Math.round(parseFloat(this.totalDatas.final_total))}}</h4>
                     <br/>
                     <div class="row p-1">
                        <div class="col-md-5">
                           <h6  class="text-white text-right  mb-0">Paid Amt:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text" id="paidamt" readonly v-model="mform.payment_paid" class="form-control fmcntrl1 "  placeholder="Paid Amount"   :class="{ 'is-invalid': errors.payment_paid }" v-on:keyup="getFinalPending()">
                             <span class="label label-danger" v-if="errors.payment_paid">{{errors.payment_paid}} </span>
                        </div>
                     </div>
                     <div class="row p-1">
                        <div class="col-md-5">
                           <h6  class="text-white text-right">Pending Amt:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text" v-model="mform.payment_pending" readonly class="form-control fmcntrl1 " placeholder="Pending Amount"    :class="{ 'is-invalid': errors.payment_pending }">
                            <span class="label label-danger" v-if="errors.payment_pending">{{errors.payment_pending}} </span>
                        </div>
                     </div>
                     <div class="row p-1">
                        <div class="col-md-5">
                           <h6  class="text-white text-right">Transaction Id:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text"  v-model="mform.transaction_id" class="form-control fmcntrl1 "  placeholder="Transaction Id"  :class="{ 'is-invalid': errors.transaction_id }">
                            <span class="label label-danger" v-if="errors.transaction_id">{{errors.transaction_id}} </span>
                        </div>
                     </div>
                     <div class="row p-1">
                        <div class="col-md-5">
                           <h6  class="text-white text-right">Payment Mode:</h6>
                        </div>
                        <div class="col-md-7">
                            <select v-model="mform.payment_mode" class="form-control fmcntrl1 fdnew"  :class="{ 'is-invalid': errors.payment_mode }">
                                <option value="Bank">Bank</option>
                                <option value="Card">Card</option>
                                <option value="Upi">Upi</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>
                     </div>   
                    <div class="row p-1">
                        <div class="col-md-12">
                            <button type="button" :disabled="isSubmitDisabled" @click="SubmitForm" id="subbmit" class="btn btn-primary form-control fdnew"> Submit</button>
                        </div>
                    </div>
                 <!-- </div> -->
            </div>
           
        </div>
        <!-- list -->
      
        <!-- modal -->
         <!-- Modal -->
         <div class="modal fade" id="subcategoryNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-dark">
                                <h5 class="modal-title text-center" id="exampleModalLongTitle"><i class="fa fa-category"></i>
                                    Add Product to Cart</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body"> 
                                <form @submit.prevent="createProduct()">
                                <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Product Name</label>
                                <div class="col-sm-10">
                                    <input type="text" style="text-transform: capitalize;" class="form-control" v-model="pname"
                                                placeholder="Product Name" readonly>
                                </div>
                                </div>
                                <div class="form-group row">
                                   
                                    <label for="staticEmail" class="col-sm-2 col-form-label">In Stock</label>
                                    <div class="col-sm-2">
                                    <input type="text"  readonly class="form-control stock pl-1"  v-model="iform.stock" placeholder="In Stock" :class="{ 'is-invalid': errors.stock }">
                                    <span class="label label-danger" v-if="errors.stock">{{errors.stock}} </span>
                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">Add Qty</label>
                                    <div class="col-sm-2">
                                        <input type="text"   class="form-control"  v-model="iform.qty" placeholder="Add Qty" :class="{ 'is-invalid': errors.qty }" v-on:keyup="gettotalPrice()" id="addQuantity">
                                        <span class="label label-danger" v-if="errors.qty">{{errors.qty}} </span>
                                    </div>

                                    <label for="staticEmail" class="col-sm-1 col-form-label">Price</label>
                                    <div class="col-sm-3">
                                        <input type="text"   class="form-control"  v-model="iform.total_price"  :class="{ 'is-invalid': errors.total_price }" >
                                        <span class="label label-danger" v-if="errors.total_price">{{errors.total_price}} </span>
                                    </div>
                                </div>
                              
                                <hr/>
                                <div class="form-group row">
                                    
                                    <label for="staticEmail" class="col-sm-2 col-form-label">Size</label>
                                    <div class="col-sm-4">
                                    <select class="form-control fdnew" v-model="iform.size" placeholder="Select Size"  v-on:change="GetStockData()">
                                        <option v-for="catx of sizesx" :key="catx.id" :value="catx.id"  >{{catx.name}}</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.size">{{errors.size}} </span>
                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">Color</label>
                                    <div class="col-sm-4">
                                        <select class="form-control fdnew" v-model="iform.color" placeholder="Select Size" v-on:change="GetStockData()" >
                                            <option v-for="catxd of colorsx" :key="catxd.id" :value="catxd.id" >{{catxd.name}}</option>
                                        </select>
                                        <span class="label label-danger" v-if="errors.color">{{errors.color}} </span>
                                    </div>



                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class=" col-sm-4 col-form-label">Select Product</label>
                                    <div class="col-sm-8">
                                        <select class="form-control fdnew" v-model="iform.stock_select" placeholder="Select Size"  v-on:change="getstockdatanew()">
                                            <option v-for="catxs of getproducts" :key="catxs.id" :value="catxs.id"  >{{catxs.name}}</option>
                                        </select>
                                        <span class="label label-danger" v-if="errors.stock_select">{{errors.stock_select}} </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary text-center  mt-23">Add</button>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
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
                                <router-link :to="this.invoicethirlink" >
                                    <button class="btn btn-sm btn-primary"><i class="fas fa-file"></i> Get Thermal Print</button>
                                </router-link>
                                <router-link :to="this.invoicealink" >
                                    <button class="btn btn-sm btn-primary"><i class="fas fa-file"></i> A5 Print</button>
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
            perPage: ['10', '25', '50', '100', '250', '500'],
            columns: [
                {
                    label: 'Sr. No',
                    name: 'srno',
                    filterable: true,
                },
                {
                    label: 'Image',
                    component: CImage,
                    filterable: false,
                    meta:{
                        url : '/public/uploads/images/product/',
                    }
                },
                {
                    label: 'Product Name',
                    name: 'p_v_name',
                    filterable: true,
                },
                {
                    label: 'Category',
                    name: 'cata_name',
                    filterable: true,
                },
                {
                   label: 'Sub Category',
                    name: 'sub_cata_name',
                    filterable: true,
                },
                {
                     label: 'Sub Sub Category',
                    name: 'sub_sub_cata_name',
                    filterable: true,
                },
                {
                    label: 'Stock',
                    name: 'stock',
                    filterable: true,
                },
                {
                    label: 'Purchase Rate',
                    name: 'purchase_rate',
                    filterable: true,
                },
                 {
                    label: 'Sales Rate',
                    name: 'sales_rate',
                    filterable: true,
                },
            ],
           tableClasses:{
                "table-container": {
                    "table-responsive": true,
                },
                "table": {
                    "table": true,
                    "table-striped": true,
                    "table-dark": false,
                    // "thead": {
                    "text-center": true,
                    
                    //  },
                },
                
            },
            url:'',
            lastOpenedProduct: null, // Track the last opened product
            rowId: 10,
            rowData:[{val:"1"}],   
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
                qty: 1,
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
            optionsnew:[],
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
                final_total:0,
                pending_amount:0,
                total_rtgst:0
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
                total_rtgst:0
            }),
            itform:{
                id:'',
                vendor_id:'',
                barcode: '',
                product_idx: '',
                product_id: '',
                stock_select:'',
                size: '',
                color: '',
                stock: 0,
                qty: 0,
                reason: 0,
                is_active: true,
            },
            fetch:({
                canteen :'',
                bukkle:'',
                customer_name:'',
                mobile:'',
            }), 
            userid:'',   
            invoicelink:'',
            invoicethirlink:'',
            invoicealink:'',
            isLoading:true,
            userss:[],
            pname:''
        }
    },
    methods :{

        loadOrderStatus() {
            var a =$('#authid').val();
            axios.get('/api/customerlistdata/'+a).then( ({ data }) => {
                // console.log(data.data);
                this.userss = data;
            });
        },

        createProduct(){
            if((this.iform.stock < this.iform.qty) || this.iform.stock==0 || this.iform.stock<0)
            {
               toast.fire({
                    type: 'warning',
                    position: 'center',
                    title: ' Stock Not Available'
                });
            }
            else
            {
                if(this.iform.qty==0)
                {
                   toast.fire({
                     type: 'warning',
                     position: 'center',
                     title: ' Please enter Quantity'
                   });
                }
            else{
            var a =$('#authid').val();
            this.$Progress.start();
            const config = { headers: { 'Content-Type': 'multipart/form-data' } };
            this.iform.vendor_id = a;
            this.iform.user_id=this.userid;
            this.iform.product_id=this.iform.product_idx.id;
            for (let x in this.iform){
                this.multipartForm.append(x, this.iform[x]);
            }    
            axios.post('/api/AddMakeabill', this.multipartForm, config).then( (data)=>{
              if(data.data.resid==202)
              {
                    toast.fire({
                        type: 'warning',
                        position: 'center',
                        title: 'Quantity is greater than available qyantity'
                    }); 
              }
                if(data.data.resid==200)
                {
                    toast.fire({
                        type: 'success',
                        position: 'center',
                        title: 'Stock Created successfully'
                    }); this.$Progress.finish();
                    this.getUrl(this.iform.user_id);
                    this.iform.qty=1;
                    this.makeblank();
                    this.iform =this.blank;
                    $("#table_search").focus();
                    $('#subcategoryNew').modal('hide');
                    this.lastOpenedProduct = null;
                }
                // setTimeout(()=>{
                // window.location.reload();
                // }, 1000);
               
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

        getImageUrl(product){
            return product.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/uploads/images/product/' + product.proimage;
        },

        getUrl(data)
        {   var a=$('#authid').val();
            axios.get("/api/getmakabilldata/"+a+"/user/"+data).then( ({ data }) => (this.Purchases = data,this.getTotalData() ));
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
            if (this.lastOpenedProduct && this.iform.product_idx.id === this.lastOpenedProduct.id) {
            toast.fire({
                type: 'warning',
                position: 'center',
                title: 'Please select a different product before opening the same one again.'
            });
            this.iform.product_idx = null; // Clear the selection
                } else {

                    this.colorsx=[];
                this.iform.stock_select=this.iform.size=this.iform.color=this.iform.purchase_rate=this.iform.stock_id=this.iform.sales_rate='';
                this.iform.stock=0;   this.getproducts=this.newgets=[]; 
                this.sizesx=[];
                this.iform.barcode=this.iform.product_idx.barcode;
                var a =$('#authid').val();
                this.sform.vendor_id=a;
                this.pname=this.iform.product_idx.name;
                this.iform.gst=this.iform.product_idx.gst;
                this.iform.mrp=this.iform.product_idx.mrp;
                this.sform.size=this.iform.product_idx.size;
                this.sform.color=this.iform.product_idx.color;
                this.sform.product_id=this.iform.product_idx.id;
                this.sizesx=this.iform.product_idx.sizesx;
                if(this.sizesx.length>0)
                {
                    var sizeee=this.sizesx[0].id;
                    this.iform.size=sizeee;
                }
                this.colorsx=this.iform.product_idx.colorsx;
                if(this.colorsx.length>0)
                {  
                    var colorrrr=this.colorsx[0].id;
                    this.iform.color=colorrrr;
                }
                this.newgets=this.iform.product_idx.product; 
                // console.log(this.newgets);
                this.fform.product_id=this.iform.product_idx.id;
                this.fform.color=colorrrr;
                this.fform.size=sizeee;
                this.fform.vendor_id=a;
                setTimeout(()=>{
                    this.GetStockData();
                    jQuery("#addQuantity").select();
                }, 500);
                
            $('#subcategoryNew').modal('show');
            
                    // Perform the actions to open the product
                    this.lastOpenedProduct = this.iform.product_idx; // Update the last opened product
                }


          
        },

        newForm(){
            this.errors = {};
            this.iform =this.blank;
            this.editMode = false;
            this.multipartForm = new FormData;
        },

        removedForm(){
            this.errors = {};
            this.rform =this.rblank;
            this.editMode = false;
            this.multipartForm = new FormData;
        },

        GetStockData()
            {  
                this.getproducts=[];
                var i=0;
                this.iform.stock_select='';
                this.stcknew='';
                this.iform.purchase_rate=0;
                this.iform.stock_id=0;
                this.iform.sales_rate=0;
                this.iform.stock=0; 
                this.iform.total_price='';
                for(let x in this.newgets)
                { 
                    if(this.newgets[x].size==this.iform.size && this.newgets[x].color==this.iform.color)
                    {   
                        this.getproducts[i]=this.newgets[x];
                        i++;
                    }
                } 
                    console.log(this.getproducts[0]);
                        this.iform.stock_select=this.getproducts[0];
                        this.iform.stock_select=this.getproducts[0].id;
                        this.stcknew=this.getproducts[0].stock_details[0];
                        this.iform.purchase_rate=this.stcknew.purchase_rate;
                        this.iform.stock_id=this.stcknew.id;
                        this.iform.total_price=this.iform.sales_rate=this.stcknew.price;
                        this.iform.stock=this.getproducts[0].mstock; 
                        this.gettotalPrice();
            },
    
            getstockdatanew()
                {   
                    // console.log(this.getproducts);
                    $('.loading-overlay').addClass('is-active');
                    var barcode =this.iform.stock_select;
                    for(let x in this.getproducts)
                    {
                      if(this.getproducts[x].id==barcode)
                      {
                        this.iform.stock_select=this.getproducts[x].id;
                        this.stcknew=this.getproducts[x].stock_details[0];
                        this.iform.purchase_rate=this.stcknew.purchase_rate;
                        this.iform.stock_id=this.stcknew.id;
                        this.iform.sales_rate=this.stcknew.price;
                        this.iform.stock=this.getproducts[x].mstock;   
                      }
                    }
                   
                         this.gettotalPrice();
            },
    
        getbarcodeDetails()
        {   
          
            var barcode =$('#table_search').val();
            if(barcode==undefined || barcode==null || barcode==''){barcode='';}
            // setTimeout(()=>{
            if(barcode.length>4)
            {  
                $('.loading-overlay').addClass('is-active');
                var a =$('#authid').val();
                axios.get("api/some/"+a+"/getbarcodeDetails/"+barcode).then(data=>{
                    this.options = data.data;  
                     setTimeout(()=>{
                         this.iform.product_idx=data.data[0];
                         this.getProductx();
                       }, 200);
                });
            }
            // }, 200);
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

        //Customer Fetch
        submitcust()
        {   
            $('.loading-overlay').addClass('is-active');    
            var a =$('#authid').val();
            // this.$Progress.start();
            const config = { headers: { 'Content-Type': 'multipart/form-data' } };
            this.cform.vendor_id = a;  
            this.cform.post('/api/fetchcustomer').then( (data)=>{

                if(data.data==''){
                    this.fetch.canteen='';
                this.fetch.bukkle='';
                this.fetch.customer_name='';
                this.fetch.mobile=''
                this.iform.user_id=''
                this.mform.user_id=''
                this.userid=''
                }
                else{
                this.fetch.canteen=data.data.card_no;
                this.fetch.bukkle=data.data.bukkle_no;
                this.fetch.customer_name=data.data.name;
                this.fetch.mobile=data.data.mobile;
                this.iform.user_id=data.data.id;
                this.mform.user_id=data.data.id;
                this.userid=data.data.id;
                this.getUrl(data.data.id);
                jQuery("#selectProductName").focus();
                }
            }).catch((data)=>{                   
                this.errors =data.response.data.errors;
                console.log('some error',data.response.data.errors);
                this.$Progress.fail();
            });
            $('.loading-overlay').removeClass('is-active');    
        },

        getTotalData()
        {    
            this.totalDatas.final_total=0;
            this.totalDatas.mrp = 0;
            this.totalDatas.qty = 0;
            this.totalDatas.total_taxable = 0;
            this.totalDatas.total_sgst = 0;
            this.totalDatas.total_cgst = 0;
            this.totalDatas.total_igst = 0;
            this.totalDatas.total_tgst = 0;
            this.totalDatas.total_rtgst = 0;
            this.totalDatas.total_amount = 0;
           
            for(let x in this.Purchases)
             {   
               this.totalDatas.mrp += parseFloat(this.Purchases[x].mrp);
               this.totalDatas.qty += parseFloat(this.Purchases[x].qty);
               this.totalDatas.total_taxable += parseFloat(this.Purchases[x].taxable_rate)*this.Purchases[x].qty;
               this.totalDatas.total_sgst += parseFloat(this.Purchases[x].sgst)*this.Purchases[x].qty;
               this.totalDatas.total_cgst += parseFloat(this.Purchases[x].cgst)*this.Purchases[x].qty;
               this.totalDatas.total_igst += parseFloat(this.Purchases[x].igst)*this.Purchases[x].qty;
               this.totalDatas.total_tgst += parseFloat(this.Purchases[x].tgst)*this.Purchases[x].qty;
               this.totalDatas.final_total=this.totalDatas.total_amount += parseFloat(this.Purchases[x].total_amount);
               // =this.totalDatas.final_total;
               var vt=$('#other').val();
               if(vt==undefined || vt==null || vt=='')
              {
                var vt=0;
              }
              this.totalDatas.final_total=Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(vt))-parseFloat(this.totalDatas.total_rtgst));;
               this.mform.payment_paid=this.totalDatas.final_total;
               this.mform.payment_pending=Math.round(parseFloat(this.totalDatas.final_total)-parseFloat(this.mform.payment_paid));
           }
           if(this.totalDatas.total_igst==0){
            this.totalDatas.total_rtgst=this.calc(this.totalDatas.total_tgst/2);
           }else
           {
            this.totalDatas.total_rtgst=this.totalDatas.total_cgst;
           }
           this.totalDatas.final_total=Math.round(parseFloat(this.totalDatas.final_total)-parseFloat(this.totalDatas.total_rtgst))
                // this.mform.payment_pending=this.totalDatas.final_total;
                    this.mform.payment_paid= this.totalDatas.final_total;
        //    console.log(this.totalDatas.total_rtgst);
           
            // console.log('----------------------------------');
            //  setTimeout(()=>{
            //     this.gettotaloffinal;
            //     console.log('--------------bfggfgffh--------------------');
            //     }, 1000);
            
                    
        },

        // cust end
        gettotalPrice()
        {  
        //    $('.loading-overlay').addClass('is-active'); 
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

        calc(theform) 
        {
            var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
            return with2Decimals;
        },

        SubmitForm()
        {   
              $('.loading-overlay').addClass('is-active'); 
            var a =$('#authid').val();
            this.mform.vendor_id=a;
            this.mform.user_id=this.iform.user_id=this.userid;
            var vt=$('#other').val();
            this.mform.other_charges=vt;
            // this.mform.final_total=Math.round(parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(this.mform.other_charges));
            this.mform.total_rtgst=this.totalDatas.total_rtgst;
            this.mform.final_total=Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(this.mform.other_charges))-parseFloat(this.totalDatas.total_rtgst));
            // alert(this.mform.final_total);
            this.mform.total_taxable=this.totalDatas.total_taxable;
            this.mform.total_tgst=this.totalDatas.total_tgst;
            this.mform.post('/api/addOrder').then( (data)=>{
                if(data.data.status=='200')
                {
                    this.invoicelink='/Invoice/'+data.data.order_id.id ;
                    this.invoicethirlink='/InvoiceThermal/'+data.data.order_id.id ;
                    this.invoicealink='/InvoicePrint/'+data.data.order_id.id ;
                    toast.fire({
                        type: 'success',
                        position:'center',
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
                    position:'center',
                    title: data.message
                   });
                }
                
                this.$Progress.finish();
              }).catch((data)=>{
                toast.fire({
                    type: 'error',
                    position:'center',
                    title: data.message
                });
                this.errors =data.reponse.errors;
                console.log(data.errors);
                this.$Progress.fail();
                 $('.loading-overlay').removeClass('is-active'); 
            });
            $('.loading-overlay').removeClass('is-active'); 
        },

        ResetForm()
        {    
            var a =$('#authid').val();
            this.rform.vendor_id=a;
            this.rform.user_id=this.iform.user_id;
            this.rform.post('/api/resetmakeabill').then( ()=>{
                toast.fire({
                    type: 'success',
                    title: 'Bill List Reset Successfully'
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

        gettotaloffinal()
        {  
            var vt=$('#other').val();
            if(vt==undefined || vt==null || vt=='')
            {
                var vt=0;
            }
            this.totalDatas.final_total=Math.round((parseFloat(this.totalDatas.total_taxable)+parseFloat(this.totalDatas.total_tgst)+parseFloat(vt))-parseFloat(this.totalDatas.total_rtgst));
            // this.totalDatas.final_total=Math.round(parseFloat(vt)+parseFloat(this.totalDatas.total_amount));
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
        
        CartEmpty()
        {
           var cartid= this.iform.user_id;
           axios.get("/api/getemptyCartData/"+cartid).then( ({ data }) => {
              if(data!='0')
              {
                swal.fire(
                        'success!',
                        'Cart Empty Successfully.',
                        'success'
                    );
                this.getUrl(cartid);
              }else
              {
                swal.fire(
                        'success!',
                        'Cart is already empty.',
                        'success'
                    );
              }
           });
           
       
        },

        getproductlist()
        {
            var a =$('#authid').val();
            axios.get("api/some/"+a+"/getproDetailsNew").then(data=>{
                this.optionsnew = data.data;  
                this.options = data.data;        
                $('.loading-overlay2').removeClass('is-active');
            });
        },

        handleKeyup(event) {
            if (event.key === 'Escape') {
                this.closeModal();
            }
        },
        closeModal() {
            $('#subcategoryNew').modal('hide');
        },
    },

    mounted() 
    {     
        document.addEventListener('keyup', this.handleKeyup);

        $('.loading-overlay2').addClass('is-active');
        jQuery("#bucklnum").focus();    
        $('#subcategoryNew').on('hidden.bs.modal', function (e) {
            jQuery("#selectProductName").focus();
        })
    },  
    beforeDestroy() {
        document.removeEventListener('keyup', this.handleKeyup);
    },

    created()
    {   
        this. getproductlist();
        $('.clicknew').click();
        this.getUrl();
        this.loadOrderStatus();
        Fire.$on('LoadProduct', () =>  this.getUrl());
        
    }
}
</script>

<style scoped>
.form-control {
   height: calc(1.3em + 0.1rem + 2px)!important;
}
.fdnew
{
    height: calc(1.6em + 0.75rem + 2px)!important;
}
.col-md-9 {
    flex: 0 0 80%!important;
    max-width: 95%!important;
}

</style>