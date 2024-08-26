<template>
    <div class="container p-0">
        <div class="row mt-3">
            <div class="col-12 col-xs-12 mt-2">
                <div class="card" ref="report">
                    <div class="card-header">
                        <form @submit.prevent="getReportData" method="post">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-md-2 pt-2" style="font-size:14px!important;text-align:right!important"> From Date</label>
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
                    <div class="card-header">
                        <h3 class="card-title p-1 bg-dark mb-0 text-center w-100">GST-Sale Summary</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 " v-if="reportData">
                        <table  v-if="!isLoading" class="table table-hover  table-bordered" >
                            <tr>
                                <th>GST %</th>
                                <th>Taxable.Amt</th>
                                <th>GST.Amt</th>
                                <th>Total</th>
                            </tr>

                            <tr v-for="order in reportData" :key="order.id">
                                <td>{{order.gstp}}</td>
                                <td>{{order.tax}}</td>
                                <td>{{order.gst}}</td>
                                <td>{{ order.tot}}</td>
                            </tr>
                            
                            <tr class="bg-primary">
                                <td>Total</td>
                                <td>{{ totalDatass.tax  }}</td>
                                <td>{{ totalDatass.gst  }}</td>
                                <td>{{ totalDatass.total  }}</td>
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
            this.form.post('/api/report/gstreport').then( ({data})=>{
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
              
                this.totalDatass.tax = 0;
                this.totalDatass.gst = 0;
                this.totalDatass.total = 0;
                
                //console.log(this.Purchases);
                for(let x in this.reportData)
                 {

                        this.totalDatass.tax += parseFloat(this.reportData[x].tax);
                        this.totalDatass.gst += parseFloat(this.reportData[x].gst);
                        this.totalDatass.total += parseFloat(this.reportData[x].tot);
                    
                }
                

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