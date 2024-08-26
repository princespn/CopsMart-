<template>
<div class="container p-0">
<!-- /.row -->

<div class="row mt-3">
<div class="col-12">
<div class="card p-2">
    <div class="card-header"> 
        <h3 class="card-title ml-3">Show Stock</h3>
    </div> 
<!-- /.card-header -->

<!--  -->
<div class="card-header">
                        <div class="row pl-3">
                        <div class="col-md-2 ">
                            <div class="row brdr bg-primary" style="font-size:0.9rem!important;">
                                <div class="col-md-5">Total<br>Items</div>
                                <div class="col-md-7">1000000000</div>
                            </div>
                        </div>
                          <div class="col-md-2 ">
                              <div class="row brdr bg-secondary" style="font-size:0.9rem!important;">
                                <div class="col-md-5">Total<br>Quantity</div>
                                <div class="col-md-7">1000000000</div>
                              </div>
                          </div>
                          <div class="col-md-2 ">
                            <div class="row brdr  bg-info" style="font-size:0.9rem!important;">
                                <div class="col-md-5">Total<br>MRP</div>
                                <div class="col-md-7">1000000000</div>
                              </div>
                          </div>
                          <div class="col-md-2  ">
                            <div class="row brdr bg-success" style="font-size:0.9rem!important;">
                                <div class="col-md-5">Total<br>P.Rate</div>
                                <div class="col-md-7">1000000000</div>
                              </div>
                          </div>
                          <div class="col-md-2 ">
                            <div class="row brdr  bg-secondary" style="font-size:0.9rem!important;">
                                <div class="col-md-5">Total<br>S.Rate</div>
                                <div class="col-md-7">1000000000</div>
                              </div>
                          </div>
                          <div class="col-md-2">
                            <div class="row brdr  bg-danger" style="font-size:0.9rem!important;">
                                <div class="col-md-5">Total<br>GST</div>
                                <div class="col-md-7">1000000000</div>
                              </div>
                          </div>
                        </div>
                    </div>
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
                        label: 'Product Name',
                        name: 'p_v_name',
                        filterable: true,
                    },
                    {
                        label: 'HSN',
                        name: 'hsncode',
                        filterable: true,
                    },
                     {
                        label: 'Size / Color',
                        name: 's_c_name',
                        filterable: true,
                    },
                    {
                        label: 'Stock Added',
                        name: 'stockaddedcount',
                        filterable: true,
                    },
                    {
                        label: 'Purchase Added',
                        name: 'purchaseaddedcount',
                        filterable: true,
                    },
                  
                      {
                        label: 'Stock Sold',
                        name: 'orderpoint',
                        filterable: true,
                    },
                      {
                        label: 'Available Stock',
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
                buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
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
                    qty: 0,
                    reason: 0,
                    is_active: true,
                },
                filter:'',
                // isLoading : true,
            }
        },
        methods :{
            getUrl()
            {   var a=$('#authid').val();
                this.url="/api/getstockshowdata/"+a+'/filter/'+this.filter;
                return this.url;
            },
        },
        mounted() 
        {

        },
        created()
        {   
            this.filter='No Filter';
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