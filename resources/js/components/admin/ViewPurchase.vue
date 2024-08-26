<template>
    <div class="container p-0">
        <!-- /.row -->

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2">View Purchase</h3>
                        <div class="card-tools">
                            <router-link to="/purchasemanagement" class="nav-link">
                            <button  class="btn btn-primary  btn-sm pl-1 mt-1 mr-3" > <i class="fa fa-plus"></i> Add Purchase</button>   
                             </router-link>
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
    import GetOrder  from './GetOrder';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
    export default {
        components:{
            CImage,
            CBtn,
            'orders': GetOrder,
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
                        label: 'Invoice No',
                        name: 'invoice_no',
                        filterable: false,
                    },
                    {
                        label: 'Purchase Vendor Name',
                        name: 'p_v_name',
                        filterable: true,
                    },
                    {
                        label: 'Total',
                        name: 'invoice_total',
                        filterable: true,
                    },
                    {
                        label: 'Other Charges',
                        name: 'other',
                        filterable: true,
                    },
                    {
                        label: 'Order Date And Time',
                        name: 'order_date',
                        filterable: true,
                    },
                    {
                        label: 'Payment Status',
                        name: 'payment_status',
                        filterable: true,
                    },
                    {
                        label: 'Balance',
                        name: 'pendingmt',
                        filterable: true,
                    },
                    {
                        label: 'Actions',
                        component: CBtn,
                        filterable: false,
                        meta:{
                            multiple: true,
                            btnList :[
                                {
                                    label: 'View',
                                    classes : 'btn-sm btn-primary btn-sm',
                                     action: this.getviewurl,
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
                        // "thead": {
                        "text-center": true,
                        
                        //  },
                    },
                    
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
            getviewurl(order)
            {   
                var a=order.invoice_no;
                var ab=order.p_vendor_id;
                 location.href ='/ViewPurchaseDetails/'+a+'/vendor/'+ab;
            },
            getUrl()
            {       var a=$('#authid').val();
                    this.url="/api/getpurchaseData/"+a;
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
.text-left{text-align: center!important;}
</style>
