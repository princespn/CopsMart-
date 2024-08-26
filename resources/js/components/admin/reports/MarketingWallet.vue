<template>
    <div class="container">
        <div class="row">
            <br>
            <h1>Marketing Person wallet Report</h1>
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
                    <label for="staticEmail" class="col-sm-2 col-form-label">Marketing Person</label>
                    <div class="col-sm-4 col-md-4 ">
                        <select class="form-control" v-model="marketingPersonId"
                            placeholder=""
                            :class="{ 'is-invalid': form.errors.has('type') }">
                            <option v-for="dp in marketingPersons" :key="dp.id" :value="dp.id">{{ dp.name }}</option>
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
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ wallet.balance != null ? wallet.balance : 0}} <sup style="font-size: 20px" class="fa fa-rupee-sign"></sup></h3>

                        <p>Balance Amount</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ wallet.collectable != null ? wallet.collectable : 0}}<sup style="font-size: 20px" class="fa fa-rupee-sign"></sup></h3>

                        <p>Collectable Amount</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ wallet.total_payable != null ? wallet.total_payable : 0}} <sup style="font-size: 20px" class="fa fa-rupee-sign"></sup></h3>

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
                        <h3 class="card-title">Wallet Transaction List</h3>

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
                    <div class="card-body table-responsive p-0">

                        <table class="table table-hover" v-if="reportData.length > 0">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Order Amount</th>
                                <th>Description</th>
                                <!-- <th>Actions</th> -->
                            </tr>

                            <tr v-for="txn in reportData" :key="txn.id">
                                <td>{{txn.created_at | myDate}}</td>
                                <td>{{ txn.amount  }}</td>
                                <td>{{ txn.is_collectable == true ? 'Yes' : 'No' }}</td>
                                <td>{{txn.description}}</td>
                                <!-- <td>
                                    <button @click="editForm(marketingPerson)" data-toggle="modal"
                                        data-target="#marketingPersonNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteMarketingPerson(marketingPerson.id)"
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
            reportData: {},
            marketingPersonId:null,
            orderStatuses: [],
            wallet:null,
            counts: {},
            isLoading: false,
            orderDetails: null,
            marketingPersons: [],

        }
    },
    methods :{
        getReportData(){
            this.$Progress.start();
            this.isLoading = true;
            this.form.post('/api/report/marketing_wallet/'+this.marketingPersonId).then( ({data})=>{
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

             axios.get('/api/marketing_person/'+this.marketingPersonId+'/wallet/balance').then( ({ data }) => {
                this.wallet = data;
            }).catch( err => console.log(err));
        },
        loadMarketingPersonList() {
            axios.get("/api/marketing_person").then( ({ data }) => {
                this.marketingPersons = data;
            }).catch( err => console.log(err));
        },


    },
    mounted() {
        // console.log('Component mounted.');
        this.loadMarketingPersonList();
    },
    created(){
        // Fire.$on('loadDashboardCounts', () => { this.loadDashboardCounts(); });
    }
}
</script>

<style>

</style>
