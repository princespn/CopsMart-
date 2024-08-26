<template>
    <div class="container p-0">
        <!-- /.row -->

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2">Customer List</h3>

                        <div class="card-tools">
                            <router-link to="/vAddCustomer" class="nav-link"><a type="button" class="btn btn-sm btn-primary mr-3"><i class="fa fa-plus"></i> Add Customer </a></router-link>
                          <a type="button" class="btn btn-sm btn-success mr-3"  > <i class="fa fa-plus"></i> Transfer</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive table-bordered p-3">
                        <!-- <table class="table table-hover">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>On / Off</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="(customer, index) in customers" :key="customer.id">
                                <td>{{(1+index)}}</td>
                                <td>{{ customer.name | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+customer.id"  :checked="customer.is_active" @change="changeActiveStatus(customer)">
                                        <label class="custom-control-label" :for="'onOff'+customer.id" ></label>
                                    </div>
                                </td>
                                <td> <img :src="getImageUrl(customer)" alt="" class="img img-responsive" ></td>
                                <td>
                                    <button @click="editForm(customer)"  data-toggle="modal" data-target="#customerNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteCustomer(customer.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    <router-link :to="getSlabLink(customer)" class="btn btn-warning btn-sm" @click="deleteRuleFromList(index)">

                                        <i class="fa fa-motorcycle"></i>
                                    </router-link>
                                </td>
                            </tr>

                        </table> -->
                        <data-table
                            :classes = "tableClasses"
                            :url="this.url"
                            :columns="columns"

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
        },
        data() {
            return {
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
                editMode: false,
                multipartForm: new FormData,
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
                 location.href ='/editCustomer/'+a;
            },
            ViewCustomer(order)
            {   
                var a=order.id;
                location.href ='/ViewCustomer/'+a;
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
            {       var a=$('#authid').val();
                    this.url="/api/customerdata/"+a;
                    return this.url;
            },
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            // this.loadCustomerList();
            this.getUrl();
            Fire.$on('LoadCustomer', () => this.getUrl());
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
