<template>
    <div class="container p-0">
        <!-- /.row -->
        <div class="row">
            <!-- <h2>Delivery Charges List</h2> -->
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2">Delivery Charges List</h3>
                        <div class="card-tools">
                            <button type="button" @click="newForm" class="btn btn-sm btn-primary mr-3" data-toggle="modal" data-target="#DeliveryCharge"> <i class="fa fa-slab-plus"></i> Add New</button>
                        </div>
                    </div>
                    
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Pincode</th>
                                    <th>Type</th>
                                    <th>Delivery Charge</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tr v-for="(deliveryCharges, index) in deliveryCharges" :key="'sc'+index">
                                <td>{{(1+index)}}</td>
                                <td>{{ deliveryCharges.pincode }}</td>
                                <td>{{ deliveryCharges.type }}</td>
                                <td>{{ deliveryCharges.delivery_charge }}</td>
                                <td>
                                    <button @click="editForm(deliveryCharges)"  data-toggle="modal" data-target="#DeliveryCharge" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteDeliveryCharge(deliveryCharges.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div><!-- /.row -->
          
        <!-- Modal -->
        <div class="modal fade" id="DeliveryCharge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-slab"></i> {{ editMode ? 'Edit' : 'Add'}}Delivery Charge</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateDeliveryCharge() : createDeliveryCharge()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label">Pincode</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control"  v-model="form.pincode" placeholder="Pincode" :class="{ 'is-invalid': form.errors.has('start_limit') }">
                                  <span class="label label-danger" v-if="errors.pincode">{{errors.pincode}} </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label">Type</label>
                                <div class="col-sm-9">
                                    <select class="form-control" v-model="form.type"  :class="{ 'is-invalid': errors.type }" >
                                        <option value="Home">Home</option>
                                        <option value="Police Station">Police Station</option>
                                        <!-- <option value="Pickup">Pickup</option> -->
                                    </select>
                                    <span class="label label-danger" v-if="errors.type">{{errors.type}} </span>
                                </div>
                            </div>
                            
                             <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label"> Delivery Charge</label>
                                <div class="col-sm-9">
                                    <input type="charges" min="0" class="form-control"  v-model="form.delivery_charge" placeholder="Delivery Charge" :class="{ 'is-invalid': form.errors.has('delivery_charge') }">
                                   <span class="label label-danger" v-if="errors.delivery_charge">{{errors.delivery_charge}} </span>
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
          <orders />
    </div>
</template>

<script>
    import { moment } from 'moment';
    import GetOrder  from './GetOrder';
    import vSelect from 'vue-select';
    import "vue-select/dist/vue-select.css";
    import $ from "jquery";
    export default {
        components:{
            'v-select': vSelect,
            'orders': GetOrder,
        },
        data() {
            return {
                form : new Form({
                    id:'',
                    pincode :null,
                    type :null,
                    delivery_charge :null,
                    vendor_id:'',
                }),
                errors:{},
                deliveryCharges: {},
                editMode: false,
            }
        },
        methods :{
            createDeliveryCharge(){
                this.$Progress.start();
                var a =$('#authid').val();
                this.form.vendor_id=a;
                this.form.post('/api/delivery_charges').then((data)=>{
                    if(data.data.resid==201)
                    {
                        swal.fire(
                        'Warning!',
                        data.data.message,
                        'warning'
                        );
                    }
                    else
                    {
                        toast.fire({
                            type: 'success',
                            title: 'Delivery Charge added successfully'
                        });
                    }
                    this.loadDeliveryChargeList();
                    $('#DeliveryCharge').modal('hide');
                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });
            },
            updateDeliveryCharge(){
                this.$Progress.start();
                
                this.form.put('/api/delivery_charges/' + this.form.id).then( ()=>{
                    this.loadDeliveryChargeList();
                    $('#DeliveryCharge').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Delivery Charge Updated successfully'
                    });
                    this.$Progress.finish();
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    this.$Progress.fail();
                });
            },
            loadDeliveryChargeList() {
                
                axios.get('/api/delivery_charges').then( ({ data }) => (this.marketingbox = data) );
            },
            deleteDeliveryCharge(id){
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
                        this.form.delete('/api/delivery_charges/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Delivery Charge has been deleted.',
                            'success'
                            );
                            this.loadDeliveryChargeList();
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Delivery Charge can not  be deleted.',
                            'danger'
                            )
                            this.loadDeliveryChargeList();
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.editMode = false;
            },
            editForm(data){
                this.selected = [];
                this.form.reset();
                this.form.fill(data);
                this.editMode = true;
            },
            loadDeliveryChargeList() {
                    var a =$('#authid').val();
                axios.get("/api/delivery_charges/"+a).then( ({ data }) => (this.deliveryCharges = data) );
            },
        },
        mounted() {
            console.log('Component mounted.');
            this.loadDeliveryChargeList();
        }
    }
</script>

<style scoped>
img{
    max-width : 3vh;
    max-height : 3vh
}
</style>
