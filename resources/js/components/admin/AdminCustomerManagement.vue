<template>
    <div class="container p-0">
        <!-- /.row -->

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row w-100">
                            <div class="col-md-4">
                                <h3 class="card-title ml-2">Customer List</h3>
                            </div>
                            <div class="col-sm-4 col-md-4 p-0">
                                <label for="staticEmail" class="col-form-label" style="font-size:12px!important;">Select Vendor</label>
                                <v-select   v-model="vendor_id"  :options="options" label="name"  />
                            </div>
                            <div class="col-sm-1 col-md-1 mt-3">
                                <button type="button" @click="getUrl"  class="btn btn-sm btn-primary mt-3">Submit</button>
                            </div>
                            <div class="col-md-3 text-right">
                                 <router-link to="/adminaddCustomer" class="nav-link"><a type="button" class="btn btn-sm btn-primary mr-3"><i class="fa fa-plus"></i> Add Customer </a></router-link>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive table-bordered p-3">
                       
                        <data-table
                            :classes = "tableClasses"
                            :url="this.url"
                            :columns="columns"
                            @loading="isLoading = true"
                            @finishedLoading="isLoading = false"
                            :per-page="perPage">
                        </data-table>
                       
                    </div>

                </div><!-- /.row -->

  <orders />
            </div>
        </div>
    </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import GetOrder  from './GetOrder';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
    export default {
        components:{
            CImage,
            CBtn,
            CToggle,
            'orders': GetOrder,
             'v-select': vSelect,
        },
        data() {
            return {
                isLoading: false,
                fullPage: true,
                perPage: ['10', '25', '50', '100', '250', '500'],
                columns: [
                    {
                        label: 'Sr. No',
                        name: 'srno',
                        filterable: true,
                    },
                    {
                        label: 'Name',
                        name: 'name',
                        filterable: true,
                    },
                    {
                        label: 'Mobile',
                        name: 'mobile',
                        filterable: true,
                    },
                    {
                        label: 'Card No',
                        name: 'card_no',
                        filterable: true,
                    },
                    {
                        label: 'Department Post',
                        name: 'employee_post',
                        filterable: true,
                    },
                    {
                        label: 'Activate / Deactivate',
                        component: CToggle,
                        filterable: false,
                        meta:{
                            field: 'is_active',
                            action: this.changeActiveStatus

                        }
                    },
                        {
                        label: 'Actions',
                        component: CBtn,
                        filterable: false,
                        meta:{
                            multiple: true,
                            btnList :[
                                {
                                   
                                    classes : 'btn-sm btn-primary mr-1 fa fa-edit',
                                    action: this.getEdit,
                                    // additionalAttributes : {'data-toggle':"modal", 'data-target':"#productNew" },
                                },
                                {
                                    // label: 'Delete',
                                    classes : 'btn-sm btn-danger mr-1 fa fa-trash',
                                    action: this.deleteProduct,
                                },
                                {
                                    // label: 'Delete',
                                    classes : 'btn-sm btn-success fa fa-eye',
                                    action: this.ViewCustomer,
                                },
                            ]

                        }
                    },
                ],
                tableClasses:{
                    "table-container": {
                        "table-responsive": true,
                    },
                    "table": {
                        "table": true,
                        "table-striped": true,
                        "table-dark": false,
                    },
                },
                 form : new Form ({
                    id:'',
                    vendor_id:'',
                    name:'',
                    email:'',
                    email_verified_at:'',
                    mobile:'',
                    mobile_verified_at:'',
                    card_no:'',
                    bukkle_no:'',
                    gender:'',
                    dob:'',
                    employee_post:'',
                    pincode:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    date_of_joining:'',
                    date_of_retirement:'',
                    identification_mark:'',
                    address:'',
                    image:'',
                    type:'',
                    last_activity:'',
                    is_active:true,
                }),
                iform:{
                    id:'',
                    vendor_id:'',
                    name:'',
                    email:'',
                    email_verified_at:'',
                    mobile:'',
                    mobile_verified_at:'',
                    card_no:'',
                    bukkle_no:'',
                    gender:'',
                    dob:'',
                    employee_post:'',
                    pincode:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    date_of_joining:'',
                    date_of_retirement:'',
                    identification_mark:'',
                    address:'',
                    image:'',
                    type:'',
                    last_activity:'',
                    is_active:true,
                },
                blank:{
                    id:'',
                    vendor_id:'',
                    name:'',
                    email:'',
                    email_verified_at:'',
                    mobile:'',
                    mobile_verified_at:'',
                    card_no:'',
                    bukkle_no:'',
                    gender:'',
                    dob:'',
                    employee_post:'',
                    pincode:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    date_of_joining:'',
                    date_of_retirement:'',
                    identification_mark:'',
                    address:'',
                    image:'',
                    type:'',
                    last_activity:'',
                    is_active:true,
                },
                customers: {},
                commodities: [],
                options: [],
                editMode: false,
                multipartForm: new FormData,
                vendor_id:'',
                currentRule:
                {
                    limit_start:null,
                    limit_end:null,
                    charges:null,
                },
                url:'',
               
            }
            
        },
        methods :{ 
            wait()
            {
                $('.loading-overlay').addClass('is-active');
            }  ,
            loadOrderStatus() {
                var a =$('#authid').val();
                axios.get('/api/vendorlist/'+a).then( ({ data }) => {
                    this.options = data;
                });
            },      
            deleteProduct(product){
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
                        // console.log(result)
                    if(result.value){
                        this.form.post('/api/DeletCustomer/'+product.id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Product has been deleted.',
                            'success'
                            );
                            setTimeout(()=>{

                    window.location.reload();
                    }, 1000);
                            Fire.$emit('LoadProduct');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Product can't  be deleted.`,
                            'danger'
                            )
                        })
                    }
                })
            },
            getEdit(order)
            {   
                var a=order.id;
                 location.href ='/admineditCustomer/'+a;
            },
            ViewCustomer(order)
            {   
                var a=order.id;
                location.href ='/adminViewCustomer/'+a;
            },
             updateCategory(){
                this.$Progress.start();
                this.form.post('api/UpdateCustomer/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadCategory');
                    $('#categoryNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            newForm(){
                this.form.reset();
                this.editMode = false;
            },
            editForm(data){
                this.form.reset();
                this.form.fill(data);
                this.editMode = true;
            },
             changeActiveStatus(category){
                this.editForm(category);
                this.form.is_active = !this.form.is_active ;
                this.updateCategory();
            },
            getUrl()
            {       
                  this.url="/api/customerdata/"+this.vendor_id.vendor_ad_id;
                  return this.url;
            },

        },
        mounted() {
        
        },
        created(){
            this.loadOrderStatus();
            this.getUrl();
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
