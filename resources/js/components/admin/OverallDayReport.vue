<template>
    <div class="container p-0">
        <div class="row mt-3">
            <div class="col-12 col-xs-12 mt-2">
                <div class="card formdata" ref="report">
                    <div class="card-header">
                        <form @submit.prevent="getReportData" method="post">
                        <div class="form-group row">
                            
                            <label for="staticEmail" class="col-sm-1 col-md-1 pt-2" style="font-size:14px!important;text-align:right!important">From Date</label>
                            <div class="col-sm-3 col-md-3 ">
                                <input required type="date" class="form-control" v-model="form.from"
                                    placeholder="Report Name"
                                    :class="{ 'is-invalid': form.errors.has('from') }">
                                <has-error :form="form" field="from"></has-error>
                            </div>

                            <label for="staticEmail" class="col-sm-1 col-md-1 pt-2" style="font-size:14px!important;text-align:right!important">To Date</label>
                            <div class="col-sm-3 col-md-3 ">
                                <input required type="date" class="form-control" v-model="form.to"
                                    placeholder="Report Name"
                                    :class="{ 'is-invalid': form.errors.has('to') }">
                                <has-error :form="form" field="to"></has-error>
                            </div>
                            <div class="col-sm-2 col-md-2 ">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        
                            <div class="col-sm-1 col-md-1 ">
                                <button type="button" class="btn btn-success" @click="PrintData()">Print</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3" >
            <div class="col-12">
                <div class="card p-2" ref="report">
                    <!-- infor -->
                    <div class="card-header">
                        <h3 class="card-title mb-0 text-center bg-dark w-100 pt-2 pb-2">Day Book Report - Overall Report</h3>
                        <div class="row w-100 ">
                            <h2 class="text-center mt-3 text-danger w-100">{{vendordata.name}} , {{vendordata.district}}</h2>
                        </div>
                        <div class="row ">
                            <div class="col-md-6"><h6>GSTIN : {{vendordata.gstin}}</h6></div>
                            <div class="col-md-6 text-right"><h6>Contact No : {{vendordata.contact_no}}</h6></div>
                        </div>
                        <div class="row ">
                            <h6 class="text-center mb-0 w-100">Address : {{vendordata.address}} | Dist. :  {{vendordata.district}} | State :  {{vendordata.state}} </h6>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 " >
                        <h3 class="card-title pt-2 pb-2 mb-0 w-100 text-center p-1 text-black bg-secondary ">From Date: {{form.from  | myDateFormate}}  &nbsp; To Date: {{form.to  | myDateFormate}}</h3>
                        <h3 class="card-title pt-2 pb-2 mb-0 text-center bg-dark w-100 ssds">Sales Data</h3>
                        <table   class="table table-hover  table-bordered" >
                            <tr v-for="order in salesData" :key="order.id">
                                <th colspan="2">Total Invoice  : {{order.count}}</th>
                                <th colspan="3">Counter Payment: {{order.countercount}}</th>
                                <th colspan="2">Online Payment : {{order.oncount}}</th>
                            </tr>
                            <tr v-for="order in salesData" :key="order.id">
                                 <th colspan="2">Total Amount  : {{calc(order.final)}}</th>
                                <th colspan="2">Total Received: {{calc(order.amount)}}</th>
                                <th colspan="2">Total Online : {{calc(order.online)}}</th>
                            </tr>
                            <tr>
                                <th>Taxable Value</th>
                                <th>GST</th>
                                <th>Sub Total</th>
                                <th>Other Charges</th>
                                <th>50% GST</th>
                                <th>Final Total</th>
                                <th>Received</th>
                            </tr>
                            <tr v-for="order in salesData" :key="order.id">
                                <td>{{calc(order.tax)}}</td>
                                <td>{{calc(order.gst)}}</td>
                                <td>{{calc(order.subtotal)}}</td>
                                <td>{{calc(order.other)}}</td>
                                <td>{{calc(order.rtgst)}}</td>
                                <td>{{calc(order.final-order.rtgst)}}</td>
                                <td>{{calc(order.amount)}}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- Card body / -->
                      <br>
                     <!-- /.card-header -->
                    <div class="card-body p-0 " >
                        <h3 class="card-title mb-0 pt-2 pb-2 text-center bg-dark w-100">Sales Bank Settlement Data</h3>
                        <table  v-if="!isLoading" class="table table-hover  table-bordered" >
                            <tr>
                                <th>Payment Type</th>
                                <th>Total Payments</th>
                                <th>Total Amount</th>
                                <th>PG Charges</th>
                                <th>PG Delivery Charges</th>
                                <th>To Settle</th>
                            </tr>
                            <tr v-for="orders in salesbankData" :key="orders.id">
                                <td>{{orders.payment_type}}</td>
                                <td>{{orders.total_payments}}</td>
                                <td>{{calc(orders.total_amount)}}</td>
                                <td>{{calc(orders.pg_charge)}}</td>
                                <td>{{calc(orders.pg_del_charge)}}</td>
                                <td>{{calc(orders.settle)}}</td>
                            </tr>
                        </table>
                        <br>
                        <table  v-if="!isLoading" class="table table-hover bg-secondary text-black table-bordered" >
                            <tr>
                                <th>Total Invoices</th>
                                <th>Final Received</th>
                                <th>PG Charges</th>
                                <th>Other Charges</th>
                                <th>To Settle in Bank</th>
                            </tr>
                            <tr>
                                <td>{{calc(totalDatass.total_payments)}}</td>
                                <td>{{calc(totalDatass.fianl_recived)}}</td>
                                <td>{{calc(totalDatass.pgdeduct)}}</td>
                                <td>{{calc(totalDatass.pgdelcharges)}}</td>
                                <td>{{calc(totalDatass.settle)}}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- Card body / -->
                   <br>
                     <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 " >
                        <h3 class="card-title mb-0 pt-2 pb-2 text-center bg-dark w-100">Total Purchase Data</h3>
                        <table  v-if="!isLoading" class="table table-hover  table-bordered" >
                            <tr>
                                <th>Total Invoices</th>
                                <th>Taxable Value</th>
                                <th>GST</th>
                                <th>Sub Total</th>
                                <th>Other</th>
                                <th>Final Total</th>
                            </tr>
                            <tr v-for="orderp in purchaseData" :key="orderp.id">
                                <td>{{orderp.total_invoice}}</td>
                                <td>{{calc(orderp.taxable_rate)}}</td>
                                <td>{{calc(orderp.gst)}}</td>
                                <td>{{calc(orderp.subtotal)}}</td>
                                <td>{{calc(orderp.other)}}</td>
                                <td>{{calc(orderp.final)}}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- Card body / -->
                      <br>
                     <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 " >
                        <h3 class="card-title mb-0 pt-2 pb-2 text-center bg-dark w-100">Total Sales Profit Calculation</h3>
                        <table  v-if="!isLoading" class="table table-hover bg-secondary text-black table-bordered" >
                            <tr>
                                <th>Total Settled</th>
                                <th>Total Purchase</th>
                                <th>Total Profit</th>
                            </tr>
                            <tr >
                                <td>{{calc(totalDatass.settle)}}</td>
                                <td>{{calc(totalDatass.purchase)}}</td>
                                <td>{{calc(parseFloat(totalDatass.settle)-parseFloat(totalDatass.purchase))}}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- Card body / -->
                    <div v-if="isLoading" class="overlay dark">
                       <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                </div><!-- /.row -->
                   


               
            </div>
        </div>
    </div>
</template>

<script>

const moment = require('moment')();

import jsPDF from "jspdf";
import html2canvas from "html2canvas"
export default {
    data() {
        return {
            form : new Form({
                from: moment.format('YYYY-MM-DD'),
                to: moment.format('YYYY-MM-DD'),
                vendor_id:'',
            }),
            totalDatass:({
                    total_payments:0,
                    fianl_recived:0,
                    pgdelcharges:0,
                    pgdeduct:0,
                    settle:0,
                    purchase:0,
                }),
            salesData: {},
            salesbankData: {},
            purchaseData: {},

            vendordata:[],
            orderStatuses: [],
            counts: {},
            isLoading: false,
            orderDetails: null,
            showAction : true,

        }
    },
    methods :{
        getReportData(){
            this.$Progress.start();
             var a =$('#authid').val();
             this.form.vendor_id=a;
            this.isLoading = true;
            this.form.post('/api/report/overallreport').then( ({data})=>{
                // Fire.$emit('LoadReport');
                this.salesData = data.sales_data;
                this.salesbankData=data.sales_bank;
                this.purchaseData=data.purchase;
               // console.log(this.salesData);
                toast.fire({
                    type: 'success',
                    title: 'Report Fetched successfully'
                });
                this. getTotalData();
                this.isLoading = false;
                this.$Progress.finish();
            }).catch(()=>{
                this.isLoading = false;
                this.$Progress.fail();
            });
        },
        getTotalData()
            {    
              
                this.totalDatass.total_payments = 0;
                this.totalDatass.fianl_recived = 0;
                this.totalDatass.pgdelcharges = 0;
                this.totalDatass.pgdeduct = 0;
                this.totalDatass.settle = 0;
                this.totalDatass.purchase =0;
                for(let x in this.salesbankData)
                {
                    this.totalDatass.total_payments += parseFloat(this.salesbankData[x].total_payments);
                    this.totalDatass.fianl_recived += parseFloat(this.salesbankData[x].total_amount);
                    this.totalDatass.pgdeduct += parseFloat(this.salesbankData[x].pg_charge);
                    this.totalDatass.pgdelcharges += parseFloat(this.salesbankData[x].pg_del_charge);
                    this.totalDatass.settle += parseFloat(this.salesbankData[x].settle);
                }

                for(let xx in this.salesData)
                {
                  this.totalDatass.purchase += parseFloat(this.salesData[xx].purchase);
                }
        },
        
        calc(theform) 
        {
            var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
            return with2Decimals;
        },


         loadVendorList()  
            {
                var a =$('#authid').val();
                axios.get("/api/vendordetails/"+a).then(data=>{
                 this.vendordata=data.data;
                });
            },
       
        download() {
            const doc = new jsPDF();
            /** WITHOUT CSS */
            const contentHtml = this.$refs.report.innerHTML;
            doc.fromHTML(contentHtml, 15, 15, {
                width: 170
                });
            doc.save("sample.pdf");
        },
        downloadWithCSS() {
            const doc = new jsPDF('p', 'mm');
            this.showAction =false;
            /** WITH CSS */
            var canvasElement = document.createElement('canvas');
            html2canvas(this.$refs.report, { canvas: canvasElement }).then( (canvas) => {
                const imgData  = canvas.toDataURL("image/png",);
                var imgWidth = 210;
                var pageHeight = 295;
                var imgHeight = canvas.height * imgWidth / canvas.width;
                var heightLeft = imgHeight;
                var doc = new jsPDF('p', 'mm');
                var position = 0;
                doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    doc.addPage();
                    doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                doc.save( 'Order Report '+ this.form.from +' - '+ this.form.to+'.pdf');
                this.showAction =true;
            });
        },
        PrintData()
        {
            $('.formdata').css('display','none');
            // $('.card-title').addClass('addborder');
            $('.main-footer').css('border-top','none');
            $('.card').css('box-shadow','none');
            $('.card').css('border','none');
            $('.ssds').css('border-bottom','none');
            setTimeout(()=>{
               window.print();
            }, 1000);
        },



    },
    mounted(){

    },
    created(){
        this.loadVendorList() ; 
        // Fire.$on('loadDashboardCounts', () => { this.loadDashboardCounts(); });
    }
}
//  window.onafterprint = function(){
//                     window.location.reload(true);
//                 }
</script>



<style scoped lang="scss">
.table th, .table td {padding: 0.2rem!important;}
</style>