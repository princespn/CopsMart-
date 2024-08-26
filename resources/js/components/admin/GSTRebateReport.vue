<template>
    <div class="container p-0">
        <div class="row mt-3">
            <div class="col-12 col-xs-12 mt-2">
                <div class="card" ref="report">
                    <div class="card-header">
                        <form @submit.prevent="getReportData" method="post">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-md-2 pt-2" style="font-size:14px!important;text-align:right!important">From Date</label>
                            <div class="col-sm-3 col-md-3 ">
                                <input required type="date" class="form-control" v-model="form.from"
                                    placeholder="Report Name"
                                    :class="{ 'is-invalid': form.errors.has('from') }">
                                <has-error :form="form" field="from"></has-error>
                            </div>

                            <label for="staticEmail" class="col-sm-2 col-md-2 pt-2" style="font-size:14px!important;text-align:right!important">To Date</label>
                            <div class="col-sm-3 col-md-3 ">
                                <input required type="date" class="form-control" v-model="form.to"
                                    placeholder="Report Name"
                                    :class="{ 'is-invalid': form.errors.has('to') }">
                                <has-error :form="form" field="to"></has-error>
                            </div>
                        <div class="col-sm-2 col-md-2 ">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3" >
            <div class="col-12">
                <div class="card p-1" ref="report">
                    <!---<div class="card-header btn-primary">
                        <h3 class="card-title mb-0 text-center btn-primary w-100">Details of Monthly Sales Report @ %0% Concession (From: {{ form.from }} to {{ form.to }})</h3>
                    </div>--->
                    
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" v-if="reportData">
                        <table  v-if="!isLoading" class="table table-hover  table-bordered">
                            <tr class="btn-primary">
                                <th colspan="10"><strong>Details of Monthly Sales Report @ %0% Concession (From: {{ form.from }} to {{ form.to }})</strong></th>
                            </tr>
                            <tr class="btn-primary"> 
                                <th colspan="5">{{vendorData.name}}</th>
                             
                                <th>Total</th>
                                <th>{{ calc(totalDatass.final_amount-totalDatass.total_rtgst)  }}</th>
                                <th>{{ calc(totalDatass.sales_gst)  }}</th>                             
                                <th>{{ calc(totalDatass.total_rtgst)  }}</th>
                                <th>{{ calc(totalDatass.amount)  }}</th>
                            </tr>
                            <tr class="bg-dark ">
                                <th>Sr. No.</th>
                                <th>Buckle No</th>
                                <th>Rank</th>
                                <th>Customer Name </th>
                                <th>Inv No</th>
                                <th>Inv Date</th>
                                <th>Amount</th>
                                <th>GST</th>                             
                                <th>GST Rebate 50%</th>
                                <th>Final Received</th>
                            </tr>
                            <tr v-for="(order, index) in reportData" :key="'sc'+index">
                          
                           
                                <td>{{(1+index)}}</td>
                                <td>{{order.bukkle_no}}</td>
                                <td>{{order.employee_post}}</td>
                                <td>{{order.name}}</td>
                                <td>{{ order.invoice_no}}</td>
                                <td>{{order.date | myDateFormate}}</td>
                               
                                <td>{{ order.final_amount-order.total_rtgst}}</td>
                             
                              
                                <td>{{order.sales_gst}}</td>
                                <td>{{ order.total_rtgst}}</td>
                                <td>{{order.amount}}</td>
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
            totalDatass:{
                    final_amount:0,
                    sales_gst:0,
                    total_rtgst:0,
                    amount:0,
                },

                vendorData:({
                    name:0,                 
                }),
            reportData: {},
            orderStatuses: [],
            vendorData: {},
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
            this.form.post('/api/report/gstrebatereport').then( ({data})=>{
                // Fire.$emit('LoadReport');
                this.reportData = data;
                toast.fire({
                    type: 'success',
                    title: 'Report Fetched successfully'
                });
                this.getTotalData();
                this.getVendorData();
                this.isLoading = false;
                
                this.$Progress.finish();
            }).catch(()=>{
                this.isLoading = false;
                this.$Progress.fail();
            });
        },
        getVendorData(){

           
             var a =$('#authid').val();
             //this.form.vendor_id=a;
           
            this.form.get('/api/profile/'+a).then( ({data})=>{
        
                this.vendorData = data;
                toast.fire({
                    type: 'success',
                   // title: 'Vendor data Fetched successfully'
                });
              
               
            }).catch(()=>{
                //this.isLoading = false;
               // this.$Progress.fail();
            });
        },
       
        getTotalData()
            {    
                this.totalDatass.final_amount = 0;
                this.totalDatass.sales_gst = 0;
                this.totalDatass.total_rtgst = 0;
                this.totalDatass.amount = 0;
                
                //console.log(this.Purchases);
                for(let x in this.reportData)
                 {
                        
                        this.totalDatass.sales_gst += parseFloat(this.reportData[x].sales_gst);
                        this.totalDatass.total_rtgst += parseFloat(this.reportData[x].total_rtgst);
                        this.totalDatass.amount += parseFloat(this.reportData[x].amount);
                        this.totalDatass.final_amount += parseFloat(this.reportData[x].final_amount);
                    
                }
                

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
        calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
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


    },
    mounted(){

    },
    created(){
        this.getVendorData();
        // Fire.$on('loadDashboardCounts', () => { this.loadDashboardCounts(); });
    }
}
</script>

<style scoped lang="scss">
.table th, .table td {padding: 0.2rem!important;}
</style>