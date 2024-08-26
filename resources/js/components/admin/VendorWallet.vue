<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2> {{vendor !=null ? vendor.name :''}} - Vendor Wallet History</h2>
        </div>
        <div class="row">

            <!-- ./col -->

            <!-- ./col -->
            <div class=" col-12">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ wallet != null && wallet.total_payable != null ? wallet.total_payable : 0}} <sup style="font-size: 20px" class="fa fa-rupee-sign"></sup></h3>

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
                            <button type="button" @click="newForm" class="btn btn-warning" data-toggle="modal"
                                data-target="#vendorNew"> <i class="fa fa-vendor-plus"></i> Add
                                Entry</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">

                        <table class="table table-hover" v-if="walletTxns.length > 0">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Order Amount</th>
                                <th>Description</th>
                                <!--<th>Actions</th>-->
                            </tr>

                            <tr v-for="txn in walletTxns" :key="txn.id">
                                <td>  {{ txn.created_at | myDate }}</td>
                                <td>{{ txn.amount  }}</td>
                                <td>{{ txn.is_collectable == true ? 'Yes' : 'No' }}</td>
                                <td>{{txn.description}}</td>
                                <td>
                                    <!-- <button @click="editForm(vendor)" data-toggle="modal"
                                        data-target="#vendorNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteVendor(vendor.id)"
                                        class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button> -->
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- Card body / -->
                    <div v-if="isLoading" class="overlay dark">
                        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                </div><!-- /.row -->


                <!-- Modal -->
                <div class="modal fade" id="vendorNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-vendor"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Vendor Payment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form
                                @submit.prevent="editMode ? updateVendorWalletTransaction() : createVendorWalletTransaction()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Amount</label>
                                        <div class="col-sm-10">
                                            <input required type="number" min="0" class="form-control" v-model="form.amount"
                                                placeholder="Amount"
                                                :class="{ 'is-invalid': form.errors.has('amount') }">
                                            <has-error :form="form" field="amount"></has-error>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Description</label>
                                        <div class="col-sm-10">
                                            <textarea required class="form-control" v-model="form.description"
                                                :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                            <has-error :form="form" field="description"></has-error>
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
            </div>
        </div>
    </div>
</template>

<script>
import $ from "jquery";
    export default {
        data() {
            return {
                form : new Form({
                    id:null,
                    description: null,
                    amount:null,
                    is_collectable: false,
                    vendor_id: null,
                    is_adjustment: false,
                    description: null,
                }),
                walletTxns: {},
                vendorId : null,
                vendor : null,
                editMode: false,
                wallet: null,
                isLoading: false
            }
        },
        methods :{
          
            createVendorWalletTransaction(){
                this.$Progress.start();
                this.form.amount = this.form.amount * -1;

                this.form.post('/api/vendors/'+this.vendorId+'/wallet').then( ()=>{
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
            updateVendorWalletTransaction(){
                this.$Progress.start();
                this.form.put('/api/vendors/'+this.vendorId+'/wallet' + this.form.id).then( ()=>{
                    Fire.$emit('LoadVendorWallet');
                    $('#vendorNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.id +' Wallet Transaction Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadVendorWalletList() {
                this.isLoading = true;
                axios.get("/api/vendors/"+this.vendorId+'/wallet').then( ({ data }) => {
                    this.walletTxns = data;
                    this.isLoading = false;
                });
            },
            deleteVendor(id){
                // sweet alert modal
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        // send delete request
                        console.log(result)
                    if(result.value){
                        this.form.delete('/api/vendors/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Vendor has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadVendor');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Vendor can't  be deleted.`,
                            'danger'
                            )
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.form.vendor_id = this.vendorId;
                this.editMode = false;
            },
            editForm(data){
                this.form.reset();
                this.form.vendor_id = this.vendorId;
                this.form.fill(data);
                this.editMode = true;
            },
            loadVendorDetails() {
                axios.get("/api/vendors/"+this.vendorId).then( ({ data }) => {
                    this.vendor = data;
                });
            },
            loadWalletBalance() {
                axios.get('/api/vendors/'+this.vendorId+'/wallet/balance').then( ({ data }) => {
                    this.wallet = data;
                });
            }
        },
        mounted() {
            // console.log('Component mounted.');
            this.vendorId = this.$route.params.vendorId;
            this.loadVendorDetails();
            this.loadVendorWalletList();
            this.loadWalletBalance();
        },
        created(){
            Fire.$on('LoadVendorWallet', () => { this.loadVendorWalletList(); this.loadWalletBalance(); });
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
