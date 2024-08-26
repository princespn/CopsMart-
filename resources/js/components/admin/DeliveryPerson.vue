<template>
    <div class="container p-0">
        <!-- /.row -->

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2">Delivery Person List</h3>

                        <div class="card-tools">
                            <a type="button" class="btn btn-sm btn-primary mr-3"  href="/vAddDeliveryBoy"> <i class="fa fa-plus"></i> Add New</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive table-bordered p-3">
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
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import GetOrder  from './GetOrder';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
    export default {
        components:{
            CImage,
            'orders': GetOrder,
            CBtn,
            CToggle,
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
                        label: 'Pan No',
                        name: 'pan_no',
                        filterable: true,
                    },
                    {
                        label: 'Delivery City',
                        name: 'district',
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
                                },
                                {
                                    classes : 'btn-sm btn-danger fa fa-trash',
                                    action: this.deleteDeliveryPerson,
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
                form : new Form({
                    id:'',
                    name:'',
                    password:'',
                    vendor_id:'',
                    admin_id:'',
                    mobile:'',
                    email:'',
                    last_activity:'',
                    driving_license_no:'',
                    aadhar_no:'',
                    service_area_id:'',
                    lat:'', 
                    longi:'', 
                    commodity_type_id:'', 
                    available:'', 
                    working_type:'',
                    slab:'',
                    acount_no:'',
                    bank:'',
                    ifsc:'',
                    cash_limit:'',
                    address:'',
                    pincode:'',
                    dob:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    accverify:'',
                    ifscverify:'',
                    date_of_joining:'',
                    identification_mark:'',
                    delivery_boy_img:'',
                    adhar_front_img:'',
                    adhar_back_img:'',
                    pan_img:'',
                    pan_no:'',
                    client_id:'',
                    is_active: true,
                }),
                iform:{
                    id:'',
                    name:'',
                    password:'',
                    vendor_id:'',
                    admin_id:'',
                    mobile:'',
                    email:'',
                    last_activity:'',
                    driving_license_no:'',
                    aadhar_no:'',
                    service_area_id:'',
                    lat:'', 
                    longi:'', 
                    commodity_type_id:'', 
                    available:'', 
                    working_type:'',
                    slab:'',
                    acount_no:'',
                    bank:'',
                    ifsc:'',
                    cash_limit:'',
                    address:'',
                    pincode:'',
                    dob:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    accverify:'',
                    ifscverify:'',
                    date_of_joining:'',
                    identification_mark:'',
                    delivery_boy_img:'',
                    adhar_front_img:'',
                    adhar_back_img:'',
                    pan_img:'',
                    pan_no:'',
                    client_id:'',
                    is_active: true,
                },
                blank:{
                   id:'',
                    name:'',
                    password:'',
                    vendor_id:'',
                    admin_id:'',
                    mobile:'',
                    email:'',
                    last_activity:'',
                    driving_license_no:'',
                    aadhar_no:'',
                    service_area_id:'',
                    lat:'', 
                    longi:'', 
                    commodity_type_id:'', 
                    available:'', 
                    working_type:'',
                    slab:'',
                    acount_no:'',
                    bank:'',
                    ifsc:'',
                    cash_limit:'',
                    address:'',
                    pincode:'',
                    dob:'',
                    district:'',
                    state:'',
                    blood_group:'',
                    accverify:'',
                    ifscverify:'',
                    date_of_joining:'',
                    identification_mark:'',
                    delivery_boy_img:'',
                    adhar_front_img:'',
                    adhar_back_img:'',
                    pan_img:'',
                    pan_no:'',
                    client_id:'',
                    is_active: true,
                    
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
            getEdit(order)
            {   
                var a=order.id;
                location.href ='/editDeliveryBoy/'+a;
            },
            deleteDeliveryPerson(product){
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
                        this.form.delete('/api/delivery_person/'+product.id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'DeliveryPerson has been deleted.',
                            'success'
                            );
                            setTimeout(()=>{
                            window.location.reload();
                            }, 1000);
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
            updateDeliveryPerson(){
                this.$Progress.start();
                this.form.put('api/delivery_person/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadDeliveryPerson');
                    $('#deliveryPersonNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });
                    setTimeout(()=>{
                            window.location.reload();
                            }, 1000);

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
            changeActiveStatus(deliveryPerson){
                this.editForm(deliveryPerson);
                this.form.is_active = !this.form.is_active ;
                this.updateDeliveryPerson();
            },
            getUrl()
            {       var a=$('#authid').val();
                    this.url="api/delivery_person?ad="+a;;
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
