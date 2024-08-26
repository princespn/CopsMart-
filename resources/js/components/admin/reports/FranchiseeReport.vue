<template>
    <div class="container">
        <div class="row">
            <br>
            <h1>Franchisee Report</h1>
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
                    <label for="staticEmail" class="col-sm-2 col-form-label">Select Franchisee </label>
                    <div class="col-sm-4 col-md-4 ">
                        <select class="form-control" v-model="vendorId"
                            placeholder=""
                            :class="{ 'is-invalid': form.errors.has('type') }">
                            <option v-for="dp in vendors" :key="dp.id" :value="dp.id">{{ dp.name }}</option>
                        </select>
                    </div>


                    <div class="col-sm-4 col-md-2 ">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </div>
                </form>
            </div>
        </div>
        <div class="row" v-if="wallet">

            <!-- ./col -->
            <div class="col-12">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ wallet.balance != null ? wallet.balance : 0}} <sup style="font-size: 20px" class="fa fa-rupee-sign"></sup></h3>

                        <p>Payable Balance</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Franchisee Order List</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                             <button type="button" @click="newForm" class="btn btn-warning" data-toggle="modal"
                                data-target="#vendorNew"> <i class="fa fa-vendor-plus"></i> Add
                                Entry</button>
                      
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">

                        <table class="table table-bordered table-hover" v-if="reportData.length > 0">
                            <tr>
                                <th>Date</th>
                                <th>Order ID / Customer</th>
                                <th>Order Amount</th>
                                <th>Vendor Amount</th>
                                <th>Delivery Charge</th>
                                <th>Vendor Delivery Charge</th>
                                <th>Collectable Amount</th>
                                <th>Franchisee Profit</th>
                                <th>Commission</th>
                                <th>Ocean Profit</th>
                                <th>Description</th>
                                <!-- <th>Actions</th> -->
                            </tr>

                            <tr v-for="txn in reportData" :key="txn.id">
                                <td>{{txn.created | myDate}}</td>
                                <td>{{txn.order_id}} / {{txn.user_name}}</td>
                                <td>{{ txn.order_amount  }}</td>
                                <td>{{ txn.vendor_amount  }}</td>
                                <td>{{ txn.delivery_charges  }}</td>
                                <td>{{ txn.vendor_delivery_chargers  }}</td>
                                <td>{{ txn.is_collectable == true ? 'Yes' : 'No' }}</td>
                                <td>{{ txn.franchisee_profit  }}</td>
                                <td>{{ txn.commision  }}</td>
                                <td>{{ txn.profit  }}</td>
                                <td>{{txn.description}}</td>
                                
                                <!-- <td>
                                    <button @click="editForm(vendor)" data-toggle="modal"
                                        data-target="#vendorNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteDeliveryPerson(vendor.id)"
                                        class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td> -->
                            </tr>

                        </table>
                    </div>
                    <!-- Card body / -->
                    <div v-if="isLoading" class="overlay dark">
                        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                     <div class="modal fade" id="vendorNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-vendor"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Franchisee Payment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form
                                @submit.prevent="editMode ? updateVendorWalletTransaction() : createVendorWalletTransaction()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label">Amount</label>
                                        <div class="col-sm-9">
                                            <input required type="number" min="0" class="form-control" v-model="iform.amount"
                                                placeholder="Amount"
                                                :class="{ 'is-invalid': form.errors.has('amount') }">
                                            <has-error :iform="iform" field="amount"></has-error>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label">Description</label>
                                        <div class="col-sm-9">
                                            <textarea required class="form-control" v-model="iform.description"
                                                :class="{ 'is-invalid': iform.errors.has('description') }"></textarea>
                                            <has-error :iform="iform" field="description"></has-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div><!-- /.row -->


            </div>
        </div>
    </div>
</template>

<script>
// import VdtnetTable from 'vue-datatables-net'

import VdtnetTable from '../../common/JDataTable';
import 'datatables.net-bs4'

// below you should only import what you need
// Example: import buttons and plugins

// // import the rest for your specific theme
// import 'datatables.net-buttons-bs4'
// import 'datatables.net-select-bs4'

// import 'datatables.net-select-bs4/css/select.bootstrap4.min.css'
// import 'datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css'
const moment = require('moment')();
export default {
    components:{
        'vdtnet-table' : VdtnetTable
    },
    data() {
        return {
            form : new Form({
                from: moment.format('YYYY-MM-DD'),
                to: moment.format('YYYY-MM-DD'),
                type: null,
            }),
             iform : new Form({
                    id:null,
                    description: null,
                    amount:null,
                    is_collectable: false,
                    vendor_id: null,
                    is_adjustment: false,
                    description: null,
                }),
            reportData: {},
            vendorId:null,
            orderStatuses: [],
            wallet:null,
            counts: {},
            isLoading: false,
            orderDetails: null,
            vendors: [],

        }
    },
    methods :{
        getReportData(){
            this.$Progress.start();
            this.isLoading = true;
            this.form.post('/api/report/franchisee_wallet/'+this.vendorId).then( ({data})=>{
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

            axios.get('/api/franchisee_wallet/'+this.vendorId+'/wallet/balance').then( ({ data }) => {
                this.wallet = data;
            }).catch( err => console.log(err));
        },
        loadDeliveryPersonList() {
             var a =$('#authid').val();
            axios.get("/api/FranchiseeList?ad="+a).then( ({ data }) => {
                this.vendors = data;
            }).catch( err => console.log(err));
        },
         newForm(){
                this.iform.reset();
                this.iform.vendor_id = this.vendorId;
                this.editMode = false;
            },
            createVendorWalletTransaction(){
                this.$Progress.start();;
                this.iform.post('/api/franchisee_wallet/'+this.vendorId+'/wallet').then( ()=>{
                    Fire.$emit('LoadVendorWallet');
                    $('#vendorNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Wallet Transaction Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadVendorList();
            },


    },
    mounted() {
        // console.log('Component mounted.');
        this.loadDeliveryPersonList();
    },
    created(){
       Fire.$on('LoadVendorWallet', () => { this.getReportData(); });
    }
}
</script>

<style>
table th , table td{vertical-align: middle!important;text-align: center!important;font-size:13px!important;;}
</style>
