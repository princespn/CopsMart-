<template>
    <div class="container p-0">
        <div class="row mt-3">
            <div class="col-12 col-xs-12 mt-2">
                <form @submit.prevent="getReportData" method="post">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 pt-2 col-form-label" style="font-size:14px!important;text-align:right!important">From Date</label>
                    <div class="col-sm-2 col-md-2 p-0">
                        <input required type="date" class="form-control" v-model="form.from"
                            placeholder="Report Name"
                            :class="{ 'is-invalid': form.errors.has('from') }">
                        <has-error :form="form" field="from"></has-error>
                    </div>

                    <label for="staticEmail" class="col-sm-1 pt-2 col-form-label" style="font-size:14px!important;text-align:right!important">To Date</label>
                    <div class="col-sm-2 col-md-2 p-0">
                        <input required type="date" class="form-control" v-model="form.to"
                            placeholder="Report Name"
                            :class="{ 'is-invalid': form.errors.has('to') }">
                        <has-error :form="form" field="to"></has-error>
                    </div>
                  <div class="col-sm-4 col-md-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
             
                </form>
            </div>
        </div>
        <div class="row mt-3" >
            <div class="col-12">
                <div class="card" ref="report">
                    <div class="card-header">
                        <h3 class="card-title  w-100 bg-dark text-center p-1 ">Total Profit Report</h3>
                        <h3 class="card-title mb-0 w-100 text-center p-1 text-black bg-secondary">From Date: {{form.from  | myDateFormate}}  &nbsp; To Date: {{form.to  | myDateFormate}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-1" v-if="reportData">
                        <table  v-if="!isLoading" class="table table-hover  table-bordered" >
                          
                           <tr>
                                <th colspan="3">Total Recived</th>
                                <th colspan="2">Total Charged </th>
                                <th colspan="2">Other Charges</th>
                                <th colspan="3">Total Settled</th>
                                <th colspan="3">Total Purchase</th>
                                <th colspan="2">Total Profit</th>
                            </tr>
                            <tr class="bg-primary">
                                <td colspan="3"><h5 class="mb-0">{{ calc(totalDatass.total_recived)  }}</h5></td>
                                <td colspan="2"><h5 class="mb-0">{{ calc(totalDatass.charged)  }}</h5></td>
                                <td colspan="2"><h5 class="mb-0">{{ calc(totalDatass.other)  }}</h5></td>
                                <td colspan="3"><h5 class="mb-0">{{ calc(totalDatass.settle)  }}</h5></td>
                                <td colspan="3"><h5 class="mb-0">{{ calc(totalDatass.purchase)  }}</h5></td>
                                <td colspan="2"><h5 class="mb-0">{{ calc(totalDatass.profit)  }}</h5></td>
                            </tr>
                            <br>
                            <tr>
                                <th>SR.No</th>
                                <th>Inv. No</th>
                                <th>Inv Date</th>
                                <th>Taxable Value</th>
                                <th>GST</th>
                                <th>Total</th>
                                <th>Other</th>
                                <th>50% GST</th>
                                <th>Final</th>
                                <th>Recived</th>
                                <th>PG Charge</th>
                                <th>Other Charge</th>
                                <th>Settled</th>
                                <th>Purchase</th>
                                <th>Profit</th>
                            </tr>

                            <tr class="text-black" v-for="(order,index) in reportData" :key="order.id"  i = index>
                                <td>{{index+1}}</td>
                                <td>{{order.invoice_no}}</td>
                                <td>{{order.date | myDateFormate}}</td>
                                <td>{{ order.taxable_value}}</td>
                                <td>{{ order.sales_gst}}</td>
                                <td>{{ order.total_amount}}</td>
                                <td>{{ order.other_charges}}</td>
                                <td>{{ calc(order.total_rtgst) }}</td>
                                <td>{{ calc(order.final_amount-order.total_rtgst)}}</td>
                                <td>{{ order.amount}}</td>
                                <td>{{ order.pg_deduct}}</td>
                                <td>{{ order.pg_delivery_charge}}</td>
                                <td>{{ order.to_settle}}</td>
                                <td>{{ order.purchase_value}}</td>
                                <td>{{ calc(order.total_amount-order.purchase_value)}}</td>
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
                profit:0,
                purchase:0,
                settle:0,
                other:0,
                charged:0,
                total_recived:0,
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
            this.form.post('/api/report/profitreport').then( ({data})=>{
                // Fire.$emit('LoadReport');
                this.reportData = data;
                toast.fire({
                    type: 'success',
                    title: 'Report Fetched successfully'
                });
                this.getTotalData();
                this.isLoading = false;
                
                this.$Progress.finish();
            }).catch(()=>{
                this.isLoading = false;
                this.$Progress.fail();
            });
        },
           getTotalData()
            {    
              
                this.totalDatass.profit = 0;
                this.totalDatass.purchase = 0;
                this.totalDatass.settle = 0;
                this.totalDatass.other = 0;
                this.totalDatass.charged = 0;
                this.totalDatass.total_recived = 0;
                
                for(let x in this.reportData)
                 {
                    this.totalDatass.profit += parseFloat(this.reportData[x].total_amount-this.reportData[x].purchase_value);
                    this.totalDatass.settle += parseFloat(this.reportData[x].to_settle);
                    this.totalDatass.total_recived += parseFloat(this.reportData[x].amount);
                    this.totalDatass.other += parseFloat(this.reportData[x].pg_delivery_charge);

                    this.totalDatass.purchase += parseFloat(this.reportData[x].purchase_value);
                    this.totalDatass.charged += parseFloat(this.reportData[x].pg_deduct);
                    
                }
                

            },
             calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },
        loadOrderDetails(orderId){
            this.$Progress.start();
            axios.get('/api/orders/' + orderId).then(({data}) =>{
                this.orderDetails = data;
                this.$Progress.finish();
            }).catch(err =>{
                this.$$Progress.fail();
            });
        },
        viewOrder(order){
            this.loadOrderDetails(order.id);
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
                // doc.addImage(img,'JPEG',20,20);
                // doc.save("sample-css.pdf");

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
.table th, .table td {padding: 0.2rem!important;font: size 14px!important;}
</style>