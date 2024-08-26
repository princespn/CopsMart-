<template>
    <div class="container p-0">
        <!-- /.row -->
       
        <div class="row mt-3">
            <div class="col-12">
                 <!-- edbd -->
                 <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2"> <i class="fa fa-cog mr-2"></i>Canteen Timings</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                        <form @submit.prevent="createVendor()">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">From </label>
                                    <input type="time"  class="form-control"  v-model="iform.open_time" placeholder="Open time" :class="{ 'is-invalid': errors.open_time }">
                                    <span class="label label-danger" v-if="errors.open_time">{{errors.open_time}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">To </label>
                                    <input type="time"  class="form-control"  v-model="iform.close_time" placeholder="Close time" :class="{ 'is-invalid': errors.close_time }">
                                    <span class="label label-danger" v-if="errors.close_time">{{errors.close_time}} </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="staticEmail" class="col-form-label">Closed on a Day </label>
                                    <select class="form-control" v-model="iform.closed" placeholder="Select Closed Day" >
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>

                                </select>
                                    <span class="label label-danger" v-if="errors.closed">{{errors.closed}} </span>
                                </div>
                               
                            </div>
                            <div class="form-group row ">
                                  <span class="label text-danger ml-3" id="err"></span>
                            </div>
                            <div class="row text-center"> 
                                <div class="col-md-4"></div>
                                <div class="col-md-4"><button type="submit" class="btn btn-primary text-center pl-5 pr-5">Submit</button></div>
                                <div class="col-md-4"></div>
                            </div>
                        </form>
                    
                     </div>
                <!-- /.card-body -->
                <!-- /.card -->
               </div>
               <!-- enddd -->
  
               <!-- edbd -->
               <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2"> <i class="fa fa-cog mr-2"></i>Stock Ratio Setting</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                        <form @submit.prevent="createVendor()">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Online Stock (in %)</label>
                                    <input type="text"  class="form-control"  v-model="iform.online" placeholder="Online Product Show" :class="{ 'is-invalid': errors.online }">
                                    <span class="label label-danger" v-if="errors.online">{{errors.delivery_pickup}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Offline Stock (in %)</label>
                                    <input type="text"  class="form-control"  v-model="iform.offline" placeholder="Offline Product Show" :class="{ 'is-invalid': errors.offline }">
                                    <span class="label label-danger" v-if="errors.offline">{{errors.offline}} </span>
                                </div>
                            </div>
                            <div class="form-group row ">
                                  <span class="label text-danger ml-3" id="err"></span>
                            </div>
                            <div class="row text-center"> 
                                <div class="col-md-4"></div>
                                <div class="col-md-4"><button type="submit" class="btn btn-primary text-center pl-5 pr-5">Submit</button></div>
                                <div class="col-md-4"></div>
                            </div>
                        </form>
                    
                     </div>
                <!-- /.card-body -->
                <!-- /.card -->
               </div>
               <!-- enddd -->
                 <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2"> <i class="fa fa-cog mr-2"></i>Sales Rate Percentage</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                         <form @submit.prevent="createVendor()">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Sales Rate Percentage</label>
                                    <input type="text"  class="form-control"  v-model="iform.sales_percent" placeholder="Sales Rate Percent" :class="{ 'is-invalid': errors.sales_rate }">
                                    <span class="label label-danger" v-if="errors.sales_percent">{{errors.sales_percent}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">PG Charges</label>
                                    <input type="text"  class="form-control" readonly v-model="pgcharges" placeholder="PG Charges" :class="{ 'is-invalid': errors.sales_rate }">
                                    <span class="label label-danger" v-if="errors.pgcharges">{{errors.pgcharges}} </span>
                                </div>
                               
                             </div>
                             <div class="form-group row ">
                                  <span class="label text-danger ml-3" id="err"></span>
                             </div>

                            <!------------------------------------->

                        <div class="row text-center"> 
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary text-center pl-5 pr-5">Submit</button></div>
                            <div class="col-md-4"></div>
                            
                        </div>
                    </form>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
               </div>
               <!-- sssss -->
                  <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2"> <i class="fa fa-cog mr-2"></i>Sales Rate Percentage</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                         <form @submit.prevent="createVendor()">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Pickup Charges</label>
                                    <input type="text"  class="form-control"  v-model="iform.pickup_charges" placeholder="Sales Rate Percent" :class="{ 'is-invalid': errors.pickup_charges }">
                                    <span class="label label-danger" v-if="errors.pickup_charges">{{errors.pickup_charges}} </span>
                                </div>
                              
                               
                             </div>
                             <div class="form-group row ">
                                  <span class="label text-danger ml-3" id="err"></span>
                             </div>

                            <!------------------------------------->

                        <div class="row text-center"> 
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary text-center pl-5 pr-5">Submit</button></div>
                            <div class="col-md-4"></div>
                            
                        </div>
                    </form>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
               </div>


         <br><br>
        <!-- Modal -->
         <orders />
       </div>
        </div>
         </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import CBtn from '../common/CBtn.vue';
    import GetOrder  from './GetOrder';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
import { log } from 'util';
    export default {
 components:{          
            'orders': GetOrder,
        },
        data() {
            return {           
                form : new Form({
                    delivery_home:'',
                    delivery_police:'',
                    delivery_pickup:'',
                    vendor_id:'',
                }),
                iform : new Form({
                    online:'',
                    offline:'',
                    sales_percent:'',
                    pickup_charges:'',
                    close_time:'',
                    open_time:'',
                    closed:'',
                    vendor_id:'',
                }),
                sform : new Form({
                    sales_rate:'',
                    vendor_id:'',
                }),
                blank:{
                    delivery_home:'',
                    delivery_police:'',
                    delivery_pickup:'',
                    vendor_id:'',
                },
                errors:{},
                pgcharges:0,
                Categories:{},
            }
        },
        methods :{
            //  newForm(){
               
            //     this.editMode = false;
            // },
            createProduct(){
                // this.form.reset();
                var a =$('#authid').val();
                this.form.vendor_id = a;          
                this.form.post('api/storeDeliveryCharges').then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Charges Stored Successfully'
                    });
                    this.loadVendorList();
                    this.$Progress.finish();
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });

                // this.loadProductList();
            },
            
            createVendor(){
                // this.form.reset();
                var chk=parseFloat(this.iform.online)+parseFloat(this.iform.offline);
                if(chk==100)
                {
                    $('#err').html('');
                var a =$('#authid').val();
                this.iform.vendor_id = a;          
                this.iform.post('api/storeOnline').then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Charges Stored Successfully'
                    });
                     this.loadVendorList();
                    this.$Progress.finish();
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
               }
               else
               {
                  $('#err').html('Please Enter Proper Ratio');
               }
                // this.loadProductList();
            },

            createsales(){
                // this.form.reset();
                var chk=parseFloat(this.sform.online)+parseFloat(this.sform.offline);
                if(chk==100)
                {
                    $('#err').html('');
                var a =$('#authid').val();
                this.sform.vendor_id = a;          
                this.sform.post('api/storeSalesPercent').then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Charges Stored Successfully'
                    });
                  this.loadVendorList();
                    this.$Progress.finish();
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
               }
               else
               {
                  $('#err').html('Please Enter Proper Ratio');
               }
            },
          


            loadVendorList()
            {
                var a =$('#authid').val();
                axios.get("api/vendorgetonlineoffline/"+a).then(data=>{
                this.iform.online=data.data.online;
                this.iform.offline=data.data.offline;
                this.iform.sales_percent=data.data.sales;
                this.pgcharges=data.data.pg_charges;
                this.iform.pickup_charges=data.data.pickup;
                this.iform.close_time=data.data.close_time;
                this.iform.closed=data.data.closed;
                this.iform.open_time=data.data.open_time;
                });
            },




   

           

        },
        mounted() 
        {
            this.isLoading = true;  
        },
        created(){
            // this.loadCategoryList();
            this.loadVendorList();
            Fire.$on('LoadProduct', () => this.loadCategoryList() );
        }
    }
   
</script>

<style scoped>
img
{
    max-width : 3vh;
    max-height : 3vh
}
.disnone{display: none!important;}
table input, table select{width: 85%!important;}
table .btn-sm, .btn-group-sm > .btn {
    padding: 0.25rem 0.25rem!important;}
</style>

<style scoped lang="scss">
.imagePreviewWrapper {
    width: 100px;
    height: 100px;
    display: block;
    cursor: pointer;
    margin: 0 auto 10px;
    background-size: contain;
    background-position: center center;
    background-repeat: no-repeat ;
    border:1px solid black;
}
</style>