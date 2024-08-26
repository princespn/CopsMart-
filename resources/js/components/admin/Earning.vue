<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Incentive</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Incentive List</h3>

                        <div class="card-tools">
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal"
                                data-target="#couponNew"> <i class="fa fa-coupon-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tr>
                                <th>Sr. No</th>
                                <!-- <th>On / Off</th> -->
                                <th>Order Number</th>                               
                                <th>Incentive</th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="(earning, index) in earnings" :key="earning.id">
                                <td>{{(1+index)}}</td>
                                 <!-- <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+earning.id"  :checked="earning.is_active" @change="changeActiveStatus(earning)">
                                        <label class="custom-control-label" :for="'onOff'+earning.id" ></label>
                                    </div>
                                </td> -->
                                 <td>{{ earning.order_id }}</td>
                                <td>{{ earning.earning }}</td>
                                <td>
                                    <button @click="editForm(earning)"  data-toggle="modal" data-target="#couponNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteCoupon(earning.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
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


                <!-- Modal -->
                <div class="modal fade" id="couponNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-coupon"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Earning</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="editMode ? updateEarning() : createEarning()">
                                <div class="modal-body">                                  
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Order Number</label>
                                        <div class="col-sm-8">
                                            <input required type="text"  class="form-control" v-model="form.order_id"
                                                :class="{ 'is-invalid': form.errors.has('order_id') }">
                                            <has-error :form="form" field="order_id"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Incentive Amount</label>
                                        <div class="col-sm-8">
                                            <input required type="text"  class="form-control" v-model="form.earning"
                                                :class="{ 'is-invalid': form.errors.has('earning') }">
                                            <has-error :form="form" field="earning"></has-error>
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
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
    export default {
        components:{
            'v-select': vSelect,
        },
        data() {
            return {
                form : new Form({
                    id: null,
                    order_id: null,
                    earning:null,
                    is_active: true,
                    admin_id:'',
                }),
                earnings: {},
                isLoading : false,
                editMode: false
            }
        },
        methods :{
            createEarning(){
                this.$Progress.start(); 
                var a =$('#authid').val();
                this.form.admin_id=a;
                this.form.post('api/earning').then( ()=>{
                    Fire.$emit('LoadEarning');
                    $('#couponNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Coupon Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadCouponList();
            },
            updateEarning(){
                this.$Progress.start();
               
                this.form.put('api/earning/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadEarning');
                    $('#couponNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            
            loadCouponList() {
                this.isLoading = true;
                var a =$('#authid').val();
                axios.get("api/earning").then( ({ data }) => {
                    this.earnings = data;
                    this.isLoading = false;
                }).catch( err => this.isLoading=false);
            },
            
            deleteCoupon(id){
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
                        this.form.delete('/api/earning/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Coupon has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadEarning');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Coupon can not  be deleted.',
                            'danger'
                            )
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

            
            changeActiveStatus(earning){
                this.editForm(earning);
                this.form.is_active = !this.form.is_active ;
                this.updateEarning();
            },
        },
        mounted() {
            console.log('Component mounted.');
        },
        created(){
            this.loadCouponList();
            Fire.$on('LoadEarning', () => this.loadCouponList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
