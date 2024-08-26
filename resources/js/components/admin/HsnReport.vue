<template>
    <div class="container p-0">
        <div class="row mt-3">
            <div class="col-12 col-xs-12 mt-2">
            <div class="card" ref="report">
                <div class="card-header">
                <form @submit.prevent="getReportData" method="post">
                <div class="form-group row">
                    <label for="staticEmail" class="pt-2 col-sm-2 col-md-2" style="font-size:14px!important;text-align:right!important">From Date</label>
                    <div class="col-sm-3 col-md-3 ">
                        <input required type="date" class="form-control" v-model="form.from"
                            placeholder="Report Name"
                            :class="{ 'is-invalid': form.errors.has('from') }">
                        <has-error :form="form" field="from"></has-error>
                    </div>

                    <label for="staticEmail" class="col-sm-2 col-md-2 pt-2 " style="font-size:14px!important;text-align:right!important">To Date</label>
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
                    <div class="card-header">
                        <button class="btn btn-primary" @click="Getcsv()" style="float: right;">Get Excel</button>
                    </div>
                    <div class="card-header">
                        <h3 class="card-title text-center w-100 bg-dark p-1">HSN Wise Sale Summary</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 " v-if="reportData">
                        <table  v-if="!isLoading" class="table table-hover  table-bordered" >
                              <tr class="bg-primary">
                                <td>Total</td>
                                <td>NA</td>
                                <td>NA</td>
                                <td>{{ totalDatass.qty  }}</td>
                                <td>{{ calc(totalDatass.total)  }}</td>
                                <td>{{ calc(totalDatass.tax)  }}</td>
                                <td>{{ calc(totalDatass.igst)  }}</td>
                                <td>{{ calc(totalDatass.cgst)  }}</td>
                                <td>{{ calc(totalDatass.sgst)  }}</td>
                                <td>{{ calc(totalDatass.chess)  }}</td>
                            </tr>
                            <tr>
                                
                                <th>HSN</th>
                                <th>Description</th>
                                <th>UQC</th>
                                <th>Total Quantity</th>
                                <th>Total Value </th>
                                <th>Taxable Value</th>
                                <th>Igst</th>
                                <th>Cgst</th>
                                <th>Sgst</th>
                                <th>Cess Amount</th>

                            </tr>
                           
                            <tr v-for="order in reportData" :key="order.id">
                                <td>{{order.hsn}}</td>
                                <td>{{order.description}}</td>
                                <td>{{order.uqc}}</td>
                                <td>{{order.qty}}</td>
                                <td>{{order.total}}</td>
                                <td>{{order.tax}}</td>
                                <td>{{order.igst}}</td>
                                <td>{{order.cgst}}</td>
                                <td>{{order.sgst}}</td>
                                <td>{{order.chess}}</td>
                                
                            </tr>
                            
                            <tr class="bg-primary">
                                <td>Total</td>
                                <td>NA</td>
                                <td>NA</td>
                                <td>{{ totalDatass.qty  }}</td>
                                <td>{{ calc(totalDatass.total)  }}</td>
                                <td>{{ calc(totalDatass.tax)  }}</td>
                                <td>{{ calc(totalDatass.igst)  }}</td>
                                <td>{{ calc(totalDatass.cgst)  }}</td>
                                <td>{{ calc(totalDatass.sgst)  }}</td>
                                <td>{{ calc(totalDatass.chess)  }}</td>
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
                    total:0,
                    sgst:0,
                    cgst:0,
                    igst:0,
                    tax:0,
                    qty:0,
                    chess:0,
                }),
            reportData: {},
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
            this.form.post('/api/report/hsnreport').then( ({data})=>{
                // Fire.$emit('LoadReport');
                this.reportData = data;
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
              
  
                this.totalDatass.total=0;
                this.totalDatass.sgst=0;
                this.totalDatass.cgst=0;
                this.totalDatass.igst=0;
                this.totalDatass.tax=0;
                this.totalDatass.qty=0;
                this.totalDatass.chess=0;
                //console.log(this.Purchases);
                for(let x in this.reportData)
                 {
                        this.totalDatass.qty += parseFloat(this.reportData[x].qty);
                        this.totalDatass.tax += parseFloat(this.reportData[x].tax);
                        this.totalDatass.total += parseFloat(this.reportData[x].total);
                        this.totalDatass.sgst += parseFloat(this.reportData[x].sgst);
                        this.totalDatass.cgst += parseFloat(this.reportData[x].cgst);
                        this.totalDatass.igst += parseFloat(this.reportData[x].igst);
                        this.totalDatass.chess += parseFloat(this.reportData[x].chess);
                    
                }
                
                 this.getFinalPending();
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
                var doc = new jsPDF('p', 'mm');
                var position = 0;
                doc.save( 'Order Report '+ this.form.from +' - '+ this.form.to+'.pdf');
                this.showAction =true;
            });
        },
         calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },
        Getcsv()
        {
            $("table").table2excel({
                filename: "hsn report.xls"
            });
        },


    },
    mounted() {
        // console.log('Component mounted.');
        this.loadOrderStatus();
    },
    created(){
        // Fire.$on('loadDashboardCounts', () => { this.loadDashboardCounts(); });
    }
}
</script>

<style scoped lang="scss">
.table th, .table td {padding: 0.2rem!important;}
</style>
