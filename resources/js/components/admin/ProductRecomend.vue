<template>
    <div class="container">
        <!-- /.row -->
      
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2">Recomended Product</h3>
                        <div class="card-tools">
                            <button type="button" @click="newForm" class="btn btn-success mr-2" data-toggle="modal"
                                data-target="#deliveryPersonNew"> <i class="fa fa-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-3">

                        <table class="table table-bordered table-hover" >
                           <tr>
                                <th>Sr. No</th>
                                <th>On / Off</th>
                                <th>Product Name</th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="(coupon, index) in coupons" :key="coupon.id">
                                <td>{{(1+index)}}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+coupon.id"  :checked="coupon.is_active" @change="changeActiveStatus(coupon)">
                                        <label class="custom-control-label" :for="'onOff'+coupon.id" ></label>
                                    </div>
                                </td>
                                <td> {{ coupon.product_name != null ? coupon.product_name : 'NA' }} </td>
                                <td>
                                    <button @click="editForm(coupon)"  data-toggle="modal" data-target="#deliveryPersonNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                            </tr>


                        </table>
                    </div>
                    <!-- Card body / -->
                </div><!-- /.row -->


                <!-- Modal -->
                <div class="modal fade" id="deliveryPersonNew" data-backdrop="static" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-deliveryPerson"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Recomended Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="editMode ? updateDeliveryPerson() : createDeliveryPerson()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Product</label>
                                        <div class="col-sm-10">
                                            <v-select multiple :closeOnSelect="true" v-model="selected" :options="options" label="name" @input="setSelected"/>
                                            <has-error :form="form" field="vendor_id"></has-error>
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
              <orders />
        </div>
    </div>
</template>

<script>
    import { moment } from 'moment';
    import GetOrder  from './GetOrder';
    import $ from "jquery";
    import vSelect from 'vue-select';
    import "vue-select/dist/vue-select.css";

    export default {
          components:{
            'v-select': vSelect,
            'orders': GetOrder,
        },
        data() {
            return {
                form : new Form({
                    id:null,
                    product_id: [],
                    vendor_id:null,
                    is_active:true,
                }),
                deliveryPersons: [],
                coupons:{},
                editMode: false,
                vendors: {},
                serviceAreas: [],
                selected: [],
                options: [],
                isLoading: false
            }
        },
        methods :{
            createDeliveryPerson(){
                this.$Progress.start();
                var a =$('#authid').val();
                this.form.vendor_id=a;
                this.form.product_id = [];
                for (let x in this.selected){
                    this.form.product_id.push(this.selected[x].id);
                   
                }
                this.form.post('api/recomendproduct').then( ()=>{
                   Fire.$emit('LoadDeliveryPerson');
                    $('#deliveryPersonNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Recomended Product Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadDeliveryPersonList();
            },
            updateDeliveryPerson(){
               this.$Progress.start();
                this.form.product_id = [];
                
                for (let x in this.selected){
                    this.form.product_id.push(this.selected[x].id);
                }
                this.form.post('api/updaterecomendproduct/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadDeliveryPerson');
                    $('#deliveryPersonNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Recomended Product Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadCouponList() {
                var a =$('#authid').val();
                axios.get("api/productrecomended/"+a).then( ({ data }) => {
                    if(data.data!=[])
                    {
                     this.coupons = data;
                    }
                   
                });
            },
            deleteDeliveryPerson(id){
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
                        this.form.delete('/api/delivery_person/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'DeliveryPerson has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadDeliveryPerson');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `DeliveryPerson can't  be deleted.`,
                            'danger'
                            )
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.selected=[];
                this.editMode = false;
            },
            editForm(data){
                this.selected = [];
                this.form.reset();
                this.form.fill(data);
                this.editMode = true;
                this.loadVendorList();
                
                if(data.product_id!=''){
                    var product_id = data.product_id.split(',');
                    var name = data.product_name.split(',');
                    for (let x in product_id){
                        this.selected.push({'id':product_id[x],'name':name[x]});
                       
                    }
                }  
            },

            setSelected(){
                console.log(this.selected);
            },
            checkSelected(vendor){
                return vendor.id == this.form.vendor_id
            },
            
            changeActiveStatus(coupon)
            {
                this.editForm(coupon);
                this.form.is_active = !this.form.is_active ;
                this.updateDeliveryPerson();
            },
           
            loadVendorList() 
            {   
                var a =$('#authid').val();
                axios.get("/api/productlist/"+a).then(data=>{
                   this.vendor_list = data.data;
                   this.options = this.vendor_list;
               });
            },
        },
        mounted() {
            console.log('Component mounted.');
        },
        created(){
             this.loadCouponList();
            this.loadVendorList();
            // this.loadCommodityList();
             Fire.$on('LoadDeliveryPerson', () => this.loadCouponList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
