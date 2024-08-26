<template>
    <div class="container">
        <div class="row">
            <br>
            <h1>Order Report</h1>
        </div>
        <div class="row">
            <div class="col-12 col-xs-12">
                <form @submit.prevent="getReportData" method="post">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">From Date</label>
                    <div class="col-sm-4 col-md-4 ">
                        <input required type="date" class="form-control" v-model="form.from"
                            placeholder="Report Name"
                            :class="{ 'is-invalid': form.errors.has('from') }">
                        <has-error :form="form" field="from"></has-error>
                    </div>

                    <label for="staticEmail" class="col-sm-2 col-form-label">To Date</label>
                    <div class="col-sm-4 col-md-4 ">
                        <input required type="date" class="form-control" v-model="form.to"
                            placeholder="Report Name"
                            :class="{ 'is-invalid': form.errors.has('to') }">
                        <has-error :form="form" field="to"></has-error>
                    </div>

                </div>
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-4 col-md-4 ">
                        <select class="form-control" v-model="form.type"
                            placeholder="Report Name"
                            :class="{ 'is-invalid': form.errors.has('type') }">
                            <option :value="null">All</option>
                            <option v-for="status in orderStatuses" :key="status.id" :value="status.id">{{ status.name }}</option>
                        </select>
                        <has-error :form="form" field="type"></has-error>
                    </div>


                    <div class="col-sm-4 col-md-2 ">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="col-sm-4 col-md-2 " v-if="reportData.length > 0">
                        <button type="button" class="btn btn-success" @click="downloadWithCSS">Download PDF</button>
                    </div>

                </div>
                </form>
            </div>
        </div>
        <div class="row mt-3" >
            <div class="col-12">
                <div class="card" ref="report">
                    <div class="card-header">
                        <h3 class="card-title">Report</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" v-if="reportData">
                        <table  v-if="!isLoading" class="table table-hover table-responsive table-bordered" >
                            <tr>
                                <th>Order Number</th>
                                <th>Customer Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Current Status</th>
                                <th>Vendor Status</th>
                                <th>Delivery Status</th>
                                <th>Last Status Update</th>
                                <th v-if="showAction">Actions</th>
                            </tr>

                            <tr v-for="order in reportData" :key="order.id">
                                <td>{{order.id}}</td>
                                <td>{{order.user != null ? order.user.name: 'NA'}}</td>
                                <td>{{ order.user !=null ? order.user.mobile: 'NA'}}</td>
                                <td>{{ order.user != null  ? order.user.email: 'NA'}}</td>
                                <td>{{ order.created_at | myDate}}</td>
                                <td>{{ order.amount}}</td>
                                <td>{{ order.status  != null ? order.status.name: 'NA'}}</td>
                                <td>{{ order.vendor_status  != null  ? order.vendor_status.name: 'NA'}}</td>
                                <td>{{ order.delivery_status  != null ? order.delivery_status.name: 'NA'}}</td>
                                <td>{{ order.status_updated}}</td>
                                <td v-if="showAction">
                                    <button @click="viewOrder(order)"  data-toggle="modal" data-target="#orderNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </td>
                            </tr>


                        </table>
                    </div>
                    <!-- Card body / -->
                    <div v-if="isLoading" class="overlay dark">
                        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                </div><!-- /.row -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <!-- Modal -->
                            <div class="modal fade" id="orderNew" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-order"></i>
                                                Order Details
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                            <div class="modal-body">
                                                <section class="invoice" v-if="orderDetails">
                                                    <!-- title row -->
                                                    <!-- <div class="row">
                                                        <div class="col-xs-12">
                                                            <h2 class="page-header">
                                                                <i class="fa fa-globe"></i> Trust point Co.
                                                                <small class="pull-right">Date: 2017/01/09</small>
                                                            </h2>
                                                        </div>
                                                    </div> -->
                                                    <!-- info row -->
                                                    <div class="row invoice-info">

                                                        <div class="col-sm-4 invoice-col">
                                                            To
                                                            <address>
                                                                <strong>
                                                                    {{ orderDetails.user != null ? orderDetails.user.name : ''}}
                                                                </strong>
                                                                <br>
                                                                Address:
                                                                {{ orderDetails.address != null ? orderDetails.address.address : ''}} <br>
                                                                LandMark:
                                                                {{ orderDetails.address != null ? orderDetails.address.landmark : ''}} <br>
                                                                Phone:
                                                                {{ orderDetails.user != null ? orderDetails.user.mobile : ''}}          <br>
                                                                Email:{{ orderDetails.user != null ? orderDetails.user.email : ''}}
                                                            </address>
                                                        </div><!-- /.col -->
                                                        <div class="col-sm-4 invoice-col">
                                                            <b>Order Number: #{{orderDetails.id}}</b><br>
                                                            <br>
                                                            <b>Amount:</b> {{orderDetails.amount}}<br>
                                                            <b>Payment:</b> <label class="btn btn-sm" :class="{ 'btn-success': orderDetails.payment_status == 1, 'btn-danger':orderDetails.payment_status == 0, 'btn-warning':orderDetails.payment_status == null }"> {{ orderDetails.payment_status == 1 ? 'Complete' : 'Incomplete'}}</label><br>
                                                            <b>Order Status:</b> {{ orderDetails.status != null ? orderDetails.status.name : ''}}
                                                        </div><!-- /.col -->
                                                        <div class="col-sm-4 invoice-col">
                                                            <strong>Vendor Details</strong>

                                                            <p v-if="orderDetails.vendor">
                                                                Name: {{ orderDetails.vendor.name }} <br>
                                                                Mobile: {{ orderDetails.vendor.contact_no }} <br>
                                                                Address: {{ orderDetails.vendor.address }} <br>

                                                                Vendor Order Status: {{ orderDetails.vendor_status != null ? orderDetails.vendor_status.name :'' }} <br>
                                                            </p>
                                                            <p v-if="!orderDetails.vendor">
                                                                [VENDOR DELETED]
                                                            </p>
                                                        </div><!-- /.col -->
                                                    </div><!-- /.row -->

                                                    <!-- Table row -->
                                                    <div class="row">
                                                        <div class="col-xs-12 table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Product</th>
                                                                        <th>Qty</th>
                                                                        <th>Price</th>
                                                                        <th>Sub Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>


                                                                    <tr v-for="product in orderDetails.products" :key="product.id">
                                                                        <td>{{ product.name}}</td>
                                                                        <td>{{ product.qty}}</td>
                                                                        <td>Rs {{ product.price}}</td>
                                                                        <td>Rs {{ product.price * product.qty}}</td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div><!-- /.col -->
                                                    </div><!-- /.row -->

                                                    <div class="row">
                                                        <!-- accepted payments column -->
                                                        <div class="col-md-12">
                                                            <p class="lead">Scheduled Delivery Time : {{orderDetails.scheduled_delivery_date}}</p>
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <tbody>


                                                                        <tr>
                                                                            <th>Total:</th>
                                                                            <td>Rs {{orderDetails.amount}}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div><!-- /.col -->
                                                    </div><!-- /.row -->

                                                    <!-- this row will not appear when printing -->
                                                    <!-- <div class="row no-print">
                                                        <div class="col-xs-12">
                                                            <a href="" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                                                            <button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment</button>
                                                            <button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>
                                                        </div>
                                                    </div> -->
                                                </section>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <!-- <button type="submit" class="btn btn-primary">Save </button> -->
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



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
                type: null,
                admin_id:'',
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
             this.form.admin_id=a;
            this.isLoading = true;
            this.form.post('/api/report/orders').then( ({data})=>{
                // Fire.$emit('LoadReport');
                this.reportData = data;
                toast.fire({
                    type: 'success',
                    title: 'Report Fetched successfully'
                });
                this.isLoading = false;
                this.$Progress.finish();
            }).catch(()=>{
                this.isLoading = false;
                this.$Progress.fail();
            });
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

<style>

</style>
