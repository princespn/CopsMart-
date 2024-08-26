<template>
    <div class="container p-0">
        <!-- /.row -->

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                       <div class="row w-100">
                           <div class="col-md-8">
                            <h3 class="card-title ml-2">Vendor List</h3>
                       </div>
                        <div class="col-md-2"> 
                            <router-link to="/AdminAddVendor" class="nav-link"><a type="button" class="btn btn-sm btn-primary "><i class="fa fa-plus"></i> Add Vendor </a></router-link>
                        </div> 
                        
                           
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
                        label: 'District',
                        name: 'district',
                        filterable: true,
                    },
                    {
                        label: 'Contact',
                        name: 'contact_no',
                        filterable: true,
                    },
                    {
                        label: 'Total Orders',
                        name: 'total_order',
                        filterable: true,
                    },
                    {
                        label: 'Pending Orders',
                        name: 'total_pending',
                        filterable: true,
                    },
                     {
                        label: 'Total Amount',
                        name: 'total_amount',
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
                        "nowrap":true,
                    },
                },
                form : new Form({
                    id:'',
                    admin_id:'',
                    name:'',
                    business_name: '',
                    gstin: '',
                    address: '',
                    pincode: '',
                    district: '',
                    state: '',
                    latitude: '',
                    longitude:'',
                    contact_person_name:'',
                    contact_person_mobile:'',
                    email: '',
                    contact_no:'',
                    app_name: '',
                    //images
                    shop_image: '',
                    work_order: '',
                    gstin_certificate: '',
                    shop_act: '',
                    app_icon: '',
                    bank_document:'',
                    //images
                    bank_account_name:'',
                    bank_ifsc:'',
                    ifscverify: '',
                    ifscverifybank:'',
                    bank_account_number:'',
                    account_v_name:'' , 
                    account_verification: '',              
                    delivery_for_base_city:'',
                    delivery_service_for_district:'',
                    pickup_charges: '',
                    password:'',
                    pg_charges: '',
                    sales_percent:'',
                    online:'',
                    offline: '',
                    is_active:true,
                }),
                iform:{
                    id:'',
                    admin_id:'',
                    name:'',
                    business_name: '',
                    gstin: '',
                    address: '',
                    pincode: '',
                    district: '',
                    state: '',
                    latitude: '',
                    longitude:'',
                    contact_person_name:'',
                    contact_person_mobile:'',
                    email: '',
                    contact_no:'',
                    app_name: '',
                    //images
                    shop_image: '',
                    work_order: '',
                    gstin_certificate: '',
                    shop_act: '',
                    app_icon: '',
                    bank_document:'',
                    //images
                    bank_account_name:'',
                    bank_ifsc:'',
                    ifscverify: '',
                    ifscverifybank:'',
                    bank_account_number:'',
                    account_v_name:'' , 
                    account_verification: '',              
                    delivery_for_base_city:'',
                    delivery_service_for_district:'',
                    pickup_charges: '',
                    password:'',
                    pg_charges: '',
                    sales_percent:'',
                    online:'',
                    offline: '',
                    is_active:true,
                },
                blank:{
                    id:'',
                    admin_id:'',
                    name:'',
                    business_name: '',
                    gstin: '',
                    address: '',
                    pincode: '',
                    district: '',
                    state: '',
                    latitude: '',
                    longitude:'',
                    contact_person_name:'',
                    contact_person_mobile:'',
                    email: '',
                    contact_no:'',
                    app_name: '',
                    //images
                    shop_image: '',
                    work_order: '',
                    gstin_certificate: '',
                    shop_act: '',
                    app_icon: '',
                    bank_document:'',
                    //images
                    bank_account_name:'',
                    bank_ifsc:'',
                    ifscverify: '',
                    ifscverifybank:'',
                    bank_account_number:'',
                    account_verification: '',  
                    account_v_name:'' ,           
                    delivery_for_base_city:'',
                    delivery_service_for_district:'',
                    pickup_charges: '',
                    password:'',
                    pg_charges: '',
                    sales_percent:'',
                    online:'',
                    offline: '',
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
                 const router = this.$router
                //  router.go({path: '/EditVendor/'+a})
                 router.push({ path: '/EditVendor/'+a, replace: true })
            },
            ViewCustomer(order)
            {   
                var a=order.id;
                location.href ='/ViewCustomer/'+a;
            },
            updateCategory(){
                this.$Progress.start();
                this.form.put('/api/vendors/' + this.form.id).then( ()=>{
                toast.fire({
                    type: 'success',
                    title: this.form.name +' Updated successfully'
                });
                this.$Progress.finish();
                location.reload();
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
                    this.url="/api/admin/vendorlist/"+a;
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
