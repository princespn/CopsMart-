<template>
    <div class="container p-0">
        <div class="row mt-3">
            <div class="col-12 col-xs-12 mt-2">
                <div class="card" ref="report">
                    <div class="card-header">
                        <form @submit.prevent="getReportData" method="post">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-md-2 pt-2"  style="font-size:14px!important;text-align:right!important">From Date</label>
                            <div class="col-sm-3 col-md-3 p-0">
                                <input required type="date" class="form-control" v-model="form.from"
                                    placeholder="Report Name"
                                    :class="{ 'is-invalid': form.errors.has('from') }">
                                <has-error :form="form" field="from"></has-error>
                            </div>

                            <label for="staticEmail" class="col-sm-2 col-md-2 pt-2" style="font-size:14px!important;text-align:right!important">To Date</label>
                            <div class="col-sm-3 col-md-3 p-0">
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
                <div class="card p-1  card-primary card-outline" ref="report">
                    <div class="card-header">
                        <button class="btn btn-primary" @click="Getcsv()" style="float: right;">Get Excel</button>
                    </div>
                    <div class="card-header">
                        <h3 class="card-title w-100 p-1 mb-0 bg-dark text-center">Sales Report</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 " v-if="reportData">
                        <table  v-if="!isLoading" class="table table-hover  table-bordered" >
                           <tr class="bg-primary">
                                <td colspan="3" class="text-right">Total</td>
                                <td>{{ calc(totalDatass.tax)  }}</td>
                                <td>{{ calc(totalDatass.sgst)  }}</td>
                                <td>{{ calc(totalDatass.cgst)  }}</td>
                                <td>{{ calc(totalDatass.total)  }}</td>
                                <td>{{ calc(totalDatass.other)  }}</td>
                                <td>{{ calc(totalDatass.trgst)  }}</td>
                                <td>{{ calc(totalDatass.final-totalDatass.trgst)  }}</td>
                                <td>{{ calc(totalDatass.amount)  }}</td>
                                <td>NA</td>
                                <td>{{ calc(totalDatass.pg_deduct)  }}</td>
                                <td>{{ calc(totalDatass.pg_delivery_charge)  }}</td>
                                <td>{{ calc(totalDatass.to_settle)  }}</td>
                            </tr>

                            <tr>
                                <th>SR.No</th>
                                <th>Inv. No</th>
                                <th>Inv Date</th>
                                <th>Taxable Value</th>
                                <th>SGST</th>
                                <th>CGST</th>
                                <th>Total</th>
                                <th>Other</th>
                                <th>50% GST</th>
                                <th>Final</th>
                                <th>Recived</th>
                                <th>Pay Mode</th>
                                <th>PG Charge</th>
                                <th>Other Charge</th>
                                <th>To Settle</th>
                            </tr>
                            <tr v-for="(order,index) in reportData" :key="order.id"  i = index>
                                <td>{{index+1}}</td>
                                <td> 
                                    <router-link :to="orderview(order.order_id)" class="nav-link" >
                                            {{order.invoice_no}}
                                    </router-link>
                                </td>
                                <td>{{order.date | myDateFormate}}</td>
                                <td>{{order.taxable_value}}</td>
                                <td>{{ order.sales_sgst}}</td>
                                <td>{{ order.sales_cgst}}</td>
                                <td>{{ order.total_amount}}</td>
                                <td>{{ order.other_charges}}</td>
                                <td>{{ calc(order.total_rtgst)}}</td>
                                <td>{{ calc(order.final_amount-order.total_rtgst)}}</td>
                                <td>{{ order.amount}}</td>
                                <td>{{ order.pg_mode}}</td>
                                <td>{{ order.pg_deduct}}</td>
                                <td>{{ order.pg_delivery_charge}}</td>
                                <td>{{ order.to_settle}}</td>
                            </tr>
                            
                            <tr class="bg-primary">
                                <td colspan="3" class="text-right">Total</td>
                                <td>{{ calc(totalDatass.tax)  }}</td>
                                <td>{{ calc(totalDatass.sgst)  }}</td>
                                <td>{{ calc(totalDatass.cgst)  }}</td>
                                <td>{{ calc(totalDatass.total)  }}</td>
                                <td>{{ calc(totalDatass.other)  }}</td>
                                <td>{{ calc(totalDatass.trgst)  }}</td>
                                <td>{{ calc(totalDatass.final-totalDatass.trgst)  }}</td>
                                <td>{{ calc(totalDatass.amount)  }}</td>
                                <td>NA</td>
                                <td>{{ calc(totalDatass.pg_deduct)  }}</td>
                                <td>{{ calc(totalDatass.pg_delivery_charge)  }}</td>
                                <td>{{ calc(totalDatass.to_settle)  }}</td>
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
                    gst:0,
                    tax:0,
                    sgst:0,
                    cgst:0,
                    total:0,
                    other:0,
                    trgst:0,
                    final:0,
                    amount:0,
                    pg_deduct:0,
                    pg_delivery_charge:0,
                    to_settle:0,
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
        loadOrderStatus() {
            axios.get('/api/order_status').then( ({ data }) => {
                this.orderStatuses = data;
            });
        },
        getReportData(){
            this.$Progress.start();
             var a =$('#authid').val();
             this.form.vendor_id=a;
            this.isLoading = true;
            this.form.post('/api/report/salereport').then( ({data})=>{
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
        Getcsv()
        {
            $("table").table2excel({
                filename: "sales.xls"
            });
        },
        getTotalData()
            {    
              
                this.totalDatass.tax = 0;
                this.totalDatass.gst = 0;
                this.totalDatass.total = 0;
                this.totalDatass.sgst=0;
                this.totalDatass.cgst=0;
                this.totalDatass.other=0;
                this.totalDatass.trgst=0;
                this.totalDatass.final=0;
                this.totalDatass.amount=0;
                this.totalDatass.pg_deduct=0;
                this.totalDatass.pg_delivery_charge=0;
                this.totalDatass.to_settle=0;
                
                console.log(this.reportData);
                for(let x in this.reportData)
                 {

                        this.totalDatass.tax += parseFloat(this.reportData[x].taxable_value);
                        this.totalDatass.sgst += parseFloat(this.reportData[x].sales_sgst);
                        this.totalDatass.cgst += parseFloat(this.reportData[x].sales_cgst);
                        this.totalDatass.total += parseFloat(this.reportData[x].total_amount);
                        this.totalDatass.other += parseFloat(this.reportData[x].other_charges);
                        this.totalDatass.trgst += parseFloat(this.reportData[x].total_rtgst);
                        this.totalDatass.final += parseFloat(this.reportData[x].final_amount);
                        this.totalDatass.amount += parseFloat(this.reportData[x].amount);
                        this.totalDatass.pg_deduct += parseFloat(this.reportData[x].pg_deduct);
                        this.totalDatass.pg_delivery_charge += parseFloat(this.reportData[x].pg_delivery_charge);
                        this.totalDatass.to_settle += parseFloat(this.reportData[x].to_settle);
                    
                }
                

            },
             calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },
            viewCustomer(order)
            {   
                return `/ViewCustomer/${order}` ;
            },
            orderview(order)
            {   
                return `/OrderDetailsManagement/${order}` ;
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
.table th, .table td {padding: 0.2rem!important;}
</style>