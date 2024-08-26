<template>
<div class="container p-0">
<!-- /.row -->

<div class="row mt-3">
<div class="col-12">
<div class="card p-2">
    <div class="card-header"> 
        <h3 class="card-title ml-3">Stock Management</h3>
    </div> 
<!-- /.card-header -->
<form @submit.prevent="createProduct()">
<div class="card-header ">
    <div class="row text-right">
        <div class="col-sm-2">
        <a  href="/addproduct"  class="btn btn-primary btn-sm" >Add Product / Item</a>
        </div> 
        <div class="col-sm-3">
        <a  href="/RemoveStock"  class="btn btn-warning btn-sm">View Removed Stock</a>
        </div> 
        <div class="col-sm-2">
        <a   @click="removedForm"  data-toggle="modal" data-target="#slabNew" class="btn btn-danger btn-sm" >Removed Stock</a>
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
<label for="staticEmail" class="col-form-label">PG Charges</label>
<input type="text" readonly  class="form-control"  v-model="iform.pg_charges" placeholder="PG Charges" :class="{ 'is-invalid': errors.pg }">
<span class="label label-danger" v-if="errors.pg">{{errors.pg}} </span>
</div>
<div class="col-sm-1">
<label for="staticEmail" class="col-form-label">MRP</label>
<input type="text"   class="form-control" readonly  v-model="iform.mrp" placeholder="P. Rate" :class="{ 'is-invalid': errors.mrp }" v-on:keyup="gettotalPrice()">
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

<div class="col-sm-1">
<label for="staticEmail" class="col-form-label">GST %</label>
<input type="text" readonly  class="form-control"  v-model="iform.gst" placeholder="GST %" :class="{ 'is-invalid': errors.gst }">
<span class="label label-danger" v-if="errors.gst">{{errors.gst}} </span>
</div>
<div class="col-sm-2">
<label for="staticEmail" class="col-form-label">Taxable Rate</label>
<input type="text" readonly  class="form-control"  v-model="iform.taxable_rate" placeholder="Taxable Rate" :class="{ 'is-invalid': errors.trate }">
<span class="label label-danger" v-if="errors.trate">{{errors.trate}} </span>
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
<!-- /////////////// -->
 <div class="modal fade" id="slabNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="exampleModalLongTitle"><i class="fa fa-slab"></i> Remove Stock</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="CreateStock()">
                        <div class="modal-body">
                            <div class="form-group row">
                                   <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Search Barcode</label>
                                    <!-- <input type="text"  class="form-control"  v-model="iform.barcode" placeholder="Search Barcode" :class="{ 'is-invalid': errors.barcode }" v-on:keyup="GetProductDetails()"> -->
                                    <input type="text"  class="form-control" id="btable_search"  v-model="itform.barcode" placeholder="Barcode" :class="{ 'is-invalid': errors.barcode }" v-on:keyup="bgetbarcodeDetails()" >
                                    <span class="label label-danger" v-if="errors.barcode">{{errors.barcode}} </span>
                                    </div>
                                    <div class="col-sm-8">
                                    <label for="staticEmail" class="col-form-label">Product Name</label>
                                    <v-select id="prorpor"  v-model="itform.product_idx"  :options="soptions" label="name" @search="newselected"   @input="newproductdetails"/>
                                    <span class="label label-danger" v-if="errors.product_name">{{errors.product_name}} </span>
                                    </div>
                            </div>
                            <div class="form-group row">
                                    <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Size</label>
                                    <select class="form-control" v-model="itform.size" placeholder="Select Size"  v-on:change="rGetStockData()">
                                        <option v-for="catx of rsizesx" :key="catx.id" :value="catx.id"  >{{catx.name}}</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.size">{{errors.size}} </span>
                                    </div>
                                    <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Color</label>
                                    <select class="form-control" v-model="itform.color" placeholder="Select Color" v-on:change="rGetStockData()" >
                                        <option v-for="catxd of rcolorsx" :key="catxd.id" :value="catxd.id" >{{catxd.name}}</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.color">{{errors.color}} </span>
                                    </div>
                                    <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">In Stock</label>
                                    <input type="text"  readonly class="form-control stock"  v-model="itform.stock" placeholder="In Stock" :class="{ 'is-invalid': errors.stock }">
                                    <span class="label label-danger" v-if="errors.stock">{{errors.stock}} </span>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                <label for="staticEmail" class="col-form-label">Select Product</label>
                                <select class="form-control" v-model="itform.stock_select" placeholder="Select Size"  v-on:change="getstockdatanew()">
                                    <option v-for="catxs of getproducts" :key="catxs.id" :value="catxs.id"  >{{catxs.name}}</option>
                                </select>
                                <span class="label label-danger" v-if="errors.stock_select">{{errors.stock_select}} </span>
                                </div>
                            </div>
                             <div class="form-group row">
                                   <div class="col-sm-5">
                                    <label for="staticEmail" class="col-form-label">Remove Quantity</label>
                                    <input type="text"  class="form-control" id="rqty" v-model="itform.qty" placeholder="Remove Stock Qty" :class="{ 'is-invalid': errors.barcode }" v-on:keyup="checkstockqty()" >
                                    <span class="label label-danger" v-if="errors.qty">{{errors.qty}} </span>
                                    <span class="label text-danger" id="changed"></span>
                                    </div>
                                    <div class="col-sm-7">
                                    <label for="staticEmail" class="col-form-label">Select Reason</label>
                                    <select class="form-control" v-model="itform.reason" placeholder="Select Size" >
                                       <option value="Damaged">Damaged</option>
                                     </select>
                                    <span class="label text-danger" v-if="errors.reason">{{errors.reason}} </span>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" id="submitt"  class="btn btn-primary">Save </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<!--  -->
<div class="card-header">
    <div class="row">
        <div class="col-md-3 ml-2">
            <label class="ncol-form-label">Filter</label>
                <select class="form-control-sm" v-model="filter" placeholder="Select Category" >
                    <option value="No Filter">No Filter</option>
                    <option value="Stock High to Low">Stock High to Low</option>
                    <option value="Stock Low to High">Stock Low to High</option>
                    <option value="Product Name Ascending">Product Name Ascending</option>
                </select>
        </div>
        <div class="col-md-2 text-center">
                <button type="button" @click="getUrl" class="btn btn-success btn-sm">Submit</button>
        </div>
    </div>
</div>
<div class="card-body table-responsive table-bordered p-3">    
        <h5 class="text-center mb-2  text-primary">Stock List</h5>              
    <data-table
        :classes = "tableClasses"
        :url="this.url"
        :columns="columns"
        :per-page="perPage"
    >
    </data-table>
    <!-- <div v-if="isLoading" class="overlay dark">
        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
    </div> -->
</div>
   <!-- notification -->
<orders />
           <!-- end -->
<!-- /.card -->
  <!-- Modal -->
       
</div>


<br><br>
<!-- Modal -->

</div>
</div>
</div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import CBtn from '../common/CBtn.vue';
    import GetOrder  from './GetOrder';
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
            'orders': GetOrder,
            CImage,
            CBtn,
            CToggle,
            'v-select': vSelect,
        },
        data() {
            return {
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
                        label: 'Size / Color',
                        name: 's_c_name',
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
                errors:{},
                multipartForm: new FormData,
                SetData:{},
                Vendors:{},
                sizesx:{},
                colorsx:{},
                rsizesx:{},
                rcolorsx:{},
                options: [],
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
                    qty: 1,
                    reason: 0,
                    is_active: true,
                },
                filter:'',
                // isLoading : true,
            }
        },
        methods :{
            /*createProduct(){
                var a =$('#authid').val();
                this.$Progress.start();
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.iform.vendor_id = a;
                this.iform.product_id=this.iform.product_idx.id;
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }    
                axios.post('/api/AddStockData', this.multipartForm, config).then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Stock Created successfully'
                    }); this.$Progress.finish();
                    setTimeout(()=>{
                    window.location.reload();
                    }, 1000);
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
            },*/

            createProduct() {
    var a = $('#authid').val();
    this.$Progress.start();
    
    // Check if the taxable rate is present before proceeding
    if (!this.ptaxable_rate) {
        this.errors.ptrate = "Please generate the sales taxable rate before adding the item.";
        this.$Progress.fail();
        return; // Stop the form submission
    }

    const config = { headers: { 'Content-Type': 'multipart/form-data' } };
    this.iform.vendor_id = a;
    this.iform.product_id = this.iform.product_idx.id;

    for (let x in this.iform) {
        this.multipartForm.append(x, this.iform[x]);
    }    

    axios.post('/api/AddStockData', this.multipartForm, config).then(() => {
        toast.fire({
            type: 'success',
            title: 'Stock Created successfully'
        }); 
        this.$Progress.finish();
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }).catch((data) => {
        this.errors = data.response.data.errors;
        console.log('some error', data.response.data.errors);
        this.$Progress.fail();
    });
},


            getImageUrl(product){
                return product.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/uploads/images/product/' + product.proimage;
            },
            getUrl()
            {   var a=$('#authid').val();
                this.url="/api/getstockmanagedata/"+a+'/filter/'+this.filter;
                return this.url;
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
               var idc=parseFloat(1)+(parseFloat(gst/100));
            //    alert(this.iform.taxable_rate);
               if(this.iform.sales_rate==''){this.iform.sales_rate=0;}
               if(this.iform.qty==''){this.iform.qty=0;}
               else{this.iform.sales_rate=this.iform.sales_rate;}
               var price=this.iform.total_price=parseFloat(this.iform.sales_rate) +parseFloat(this.iform.pg_charges);
               var idc=parseFloat(1)+(parseFloat(gst/100));
               var tsc=parseFloat(price/idc).toFixed(2);
            //    this.iform.taxable_rate=tsc;
               this.iform.tgst=(parseFloat(price)-parseFloat(tsc)).toFixed(2);
               this.iform.cgst =this.iform.sgst=parseFloat(this.iform.tgst/2).toFixed(2);
               this.iform.total_amount=(parseFloat(this.iform.qty)*parseFloat(price)).toFixed(2);
                $('.loading-overlay').removeClass('is-active');
            },
            getProduct()
            {
                this.iform.product_idx=this.iform.barcode;
                var a =$('#authid').val();
                this.sform.vendor_id=a;
                this.sform.size=this.iform.product_idx.size;
                this.sform.post('api/GetSizeResult').then( (data)=>{
                this.sizesx=data.data;
                 if(this.sizesx.length>0)
                    {
                        this.iform.size=this.sizesx[0].id;
                    }
                this.iform.stock=0;
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                });
               var a =$('#authid').val();
               this.sform.vendor_id=a;
               this.sform.size=this.iform.product_idx.color;
               this.sform.post('api/GetColorResult').then( (data)=>{
                   this.colorsx=data.data;
                    if(this.colorsx.length>0)
                    {
                        this.iform.color=this.colorsx[0].id;
                    }
                   this.iform.stock=0;
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
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
                    //   console.log(this.colorsx);
                    if(this.colorsx.length>0)
                    {
                        this.iform.color=this.colorsx[0].id;
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
                var a =$('#authid').val();
                this.fform.product_id=this.iform.product_idx.id;
                this.fform.color=this.iform.color;
                this.fform.size=this.iform.size;
                this.fform.vendor_id=a;
                 $('.loading-overlay').addClass('is-active');
                this.fform.post('/api/GetStockData').then( (data)=>{
                   this.iform.purchase_rate=data.data.purchase_rate;
                   this.iform.purchase_rate=data.data.purchase_rate;
                   this.iform.sales_rate=data.data.price;
                    $('.loading-overlay').removeClass('is-active');
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                     $('.loading-overlay').removeClass('is-active');
                });
                 $('.loading-overlay').addClass('is-active');
                this.fform.post('/api/GetonlyStockData').then( (data)=>{
                       this.iform.stock=data.data;
                        $('.loading-overlay').removeClass('is-active');
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    $('.loading-overlay').removeClass('is-active');
                });
            },
            getbarcodeDetails()
            {   
                var barcode =$('#table_search').val();
                var a =$('#authid').val();
                if(barcode==undefined || barcode==null || barcode==''){barcode='';}
                 $('.loading-overlay').addClass('is-active');
                if(barcode.length>0)
                {
                axios.get("api/some/"+a+"/getbarcodeDetails/"+barcode).then(data=>{
                   this.options = data.data;        
                    $('.loading-overlay').removeClass('is-active');
                });
                }
            },
            setSelectedx(barcode)
            {   
                var a =$('#authid').val();
                if(barcode.length>0)
                {
                    //  $('.loading-overlay').addClass('is-active');
                   axios.get("api/some/"+a+"/getproDetails/"+barcode).then(data=>{
                   this.options = data.data;    
                    // $('.loading-overlay').removeClass('is-active');    
                });
                }
            },
            //Remove
           
            newselected(pro)
            { var a =$('#authid').val();
              if(pro.length>0)
                { 
                   axios.get("api/some/"+a+"/getproDetails/"+pro).then(data=>{
                   this.soptions = data.data;     
                });
                }
            },   
            newproductdetails()
            {
                this.itform.barcode=this.itform.product_idx.barcode;
                var a =$('#authid').val();
                this.sform.vendor_id=a;
                this.sform.size=this.itform.product_idx.size;
                this.sform.color=this.itform.product_idx.color;
                this.sform.product_id=this.itform.product_idx.id;
                this.sform.post('api/StockColorFetch').then( (data)=>{
                   this.rsizesx=data.data.size;
                    if(this.rsizesx.length>0)
                    {
                        var sizeee=this.rsizesx[0].id;
                        this.itform.size=sizeee;
                    }
                    this.rcolorsx=data.data.color;
                    if(this.rcolorsx.length>0)
                    {  
                        var colorrrr=this.rcolorsx[0].id;
                        this.itform.color=colorrrr;
                    }
                    this.getproducts=data.data.product; 
                    this.itform.stock_select=this.getproducts[0].id;
                     setTimeout(()=>{
                            this.getstockdatanew();
                            }, 1000);
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                });

            },

            bgetbarcodeDetails()
            {   
                var a =$('#authid').val();
                var barcode =$('#btable_search').val();
                if(barcode==undefined || barcode==null || barcode==''){barcode='';}
                if(barcode.length>0)
                {
                axios.get("api/some/"+a+"/getbarcodeDetails/"+barcode).then(data=>{
                   this.soptions = data.data;        
                });
                }
            },
            rGetStockData()
            {  
                var a =$('#authid').val();
                this.fform.product_id=this.itform.product_idx.id;
                this.fform.color=this.itform.color;
                this.fform.size=this.itform.size;
                this.fform.vendor_id=a;
                this.fform.post('/api/GetproductData').then( (data)=>{
                        this.getproducts=data.data; 
                        if(this.getproducts.length>0)
                        {
                         this.itform.stock_select=this.getproducts[0].id;
                          setTimeout(()=>{
                            this.getstockdatanew();
                            }, 1000);
                        }
                        else
                        {    
                            this.iform.stock=0;
                             $('.loading-overlay').removeClass('is-active');
                        }
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                });
            },
            getstockdatanew()
            {   
                var barcode =this.itform.stock_select;
                
                axios.get("/api/getcountstock/"+barcode).then(data=>{
                  //this.itform.stock = data.data;     
                });
                         var a=$('#authid').val();

                    axios.get("/api/newGetSelstockdata/"+barcode+"/"+a).then(data=>{
                   this.itform.stock=data.data.stockk;    
                });
            },
            checkstockqty()
            {
               var mqty=this.itform.stock;
               var qty=$('#rqty').val();
               if(qty=='' || qty==undefined || qty==null)
               {
                   qty=0;
               }
               else
               {
                   var pt=parseFloat(parseFloat(mqty)-parseFloat(qty));
                   if(pt<0)
                   {
                      $('#changed').html('Please check remove quantity');
                      $('#submitt').attr('disabled',true);
                   }
                   else
                   {
                       $('#changed').html('');
                       $('#submitt').attr('disabled',false);
                   }
               }
            },
            CreateStock()
            {
                var a =$('#authid').val();
                this.$Progress.start();
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.itform.vendor_id = a;
                this.itform.product_id=this.itform.product_idx.id;
                for (let x in this.itform){
                    this.multipartForm.append(x, this.itform[x]);
                }    
                axios.post('/api/addremovestock', this.multipartForm, config).then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Stock Removed successfully'
                    }); this.$Progress.finish();
                    setTimeout(()=>{
                    window.location.reload();
                    }, 1000);
                   
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
            },
            // remove end
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
               var price=this.iform.total_price=parseFloat(this.iform.sales_rate) + parseFloat(this.iform.pg_charges);
               var idc=parseFloat(1)+(parseFloat(gst/100));
               var tsc=parseFloat(price/idc).toFixed(2);
               this.iform.taxable_rate=tsc;
               this.iform.tgst=(parseFloat(price)-parseFloat(tsc)).toFixed(2);
               this.iform.cgst =this.iform.sgst=parseFloat(this.iform.tgst/2).toFixed(2);
               this.iform.total_amount=(parseFloat(this.iform.qty)*parseFloat(price)).toFixed(2);
            },
            calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },
            loadVendorList() 
            {
                var a =$('#authid').val();
                axios.get("api/vendorgetonlineoffline/"+a).then(data=>{
                this.sales_percent=data.data.sales;
                this.pgcharges=data.data.pg_charges;
                this.total_percent=(parseFloat(this.sales_percent)+parseFloat(this.pgcharges));
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
                // var price=this.iform.total_price
                this.iform.sales_rate=this.iform.sales_rate.toFixed(2);
            },
        },
        mounted() 
        {
            // this.isLoading = true;  
             $("#table_search").focus();
        },
        created()
        {   
            this.filter='No Filter';
            this.loadVendorList();  
            this.getUrl();
            Fire.$on('LoadProduct', () =>  this.getUrl());
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