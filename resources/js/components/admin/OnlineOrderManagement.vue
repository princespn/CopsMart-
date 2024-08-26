<template>
    <div class="container p-0">
        <!-- /.row -->
        <div class="row mt-1 pl-4">
            <h3><i class="fas fa-box"></i> Orders Management</h3>
        </div>
        <div class="row mt-1 ">
            <div class="col-12">
                <div class="card p-0">
                    <div class="card-header ">
                    
                           <div class="row p-3">
                               <div class="col-md-3 p-1 ">
                                   <div class="border border-primary"> 
                                    <div class="row">
                                        <div class="col-md-5 pt-2">
                                            <h6 class="text-primary pl-2 text-center">Total Orders</h6>
                                        </div>
                                        <div class="col-md-7 pt-2">
                                            <h4>{{ordercount.total}}</h4>
                                        </div>
                                    </div>
                                   </div>
                               </div>
                               <div class="col-md-3 p-1 ">
                                   <div class="border border-primary"> 
                                    <div class="row">
                                        <div class="col-md-5 pt-2">
                                            <h6 class="text-primary pl-1 text-center">Pending Orders</h6>
                                        </div>
                                        <div class="col-md-7 pt-2">
                                             <h4>{{ordercount.neworder}}</h4>
                                        </div>
                                    </div>
                                   </div>
                               </div>
                               <div class="col-md-3 p-1 ">
                                   <div class="border border-primary"> 
                                    <div class="row">
                                        <div class="col-md-5 pt-2">
                                            <h6 class="text-primary pl-1 text-center">Delivered Orders</h6>
                                        </div>
                                        <div class="col-md-7 pt-2">
                                            <h4 class="text-success ">{{ordercount.delivered}}</h4>
                                        </div>
                                    </div>
                                   </div>
                               </div>
                               <div class="col-md-3 p-1 ">
                                   <div class="border border-primary"> 
                                    <div class="row">
                                        <div class="col-md-5 pt-2">
                                            <h6 class="text-primary pl-1 text-center">Total Amount</h6>
                                        </div>
                                        <div class="col-md-7 pt-2">
                                            <h4>{{calc(ordercount.sales)}}</h4>
                                        </div>
                                    </div>
                                   </div>
                               </div>
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
                        <!-- <DataTable v-if="products.length>0"  :data="products" val="id" text="name" v-once /> -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div><!-- /.row -->

  <orders />
        <!-- Modal -->
      
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
import { log } from 'util';
    export default {
        
        components:{  deleteRow(i) {
               this.rowData.splice(i,1);
            },
            CImage,
            CBtn,
            CToggle,
            'orders': GetOrder,
            'v-select': vSelect,
        },
        data() {
            return {
                rowId: 1,
                rowData:[{val:"1"}],                
                types : [
                    {
                        "id": "normal",
                        "value": "Normal Product"
                    },
                    {
                        "id": "f&b",
                        "value": "Fashion and Electronics Product"
                    },
                ],
                selected: [],
                options: [],
                perPage: ['10', '25', '50', '100', '500',],
                columns: [
                    {
                        label: 'SR. No',
                        name: 'srno',
                        filterable: true,
                    },
                     {
                        label: 'Orderid',
                        name: 'id',
                        filterable: true,
                    },
                    {
                        label: 'Invoice No',
                        name: 'invoice_no',
                        filterable: true,
                    },
                    // {
                    //     label: 'View',
                    //     component: CBtn,
                    //       meta:{
                    //         btnList :[
                    //             {   
                                    
                    //                 classes : 'btn-sm btn-primary fa fa-eye',
                    //                 action: this.url,
                    //                 additionalAttributes : {'value':"id" },
                    //             },
                    //         ]
                    //     },
                    //     filterable: false,
                    // },
                    {
                        label: 'Customer',
                        name: 'nameuser',
                        filterable: false,
                    },
                    {
                        label: 'Total',
                        name: 'amount',
                        filterable: false,
                    },
                    {
                        label: 'Delivery Charges',
                        name: 'delivery_charges',
                        filterable: false,
                    },
                    {
                        label: 'Date And Time',
                        name: 'orderdate',
                        filterable: true,
                    },
                    {
                        label: 'Order Status',
                         name: 'orderstatus',
                        filterable: false,
                       
                    },
                    {
                        label: 'Delivery Status',
                        name: 'deliverystatus',
                        filterable: false,
                       
                    },
                    
                    {
                        label: 'Actions',
                        component: CBtn, 
                        filterable: false,
                        meta:{
                            multiple: true,
                            btnList :[
                                 {
                                    classes : 'btn-sm btn-info mr-1 fad fa-edit',
                                    action: this.getediturl,
                                    additionalAttributes : {'title':"Edit Order" },
                                },
                                {
                                    classes : 'btn-sm btn-primary mr-1 fas fa-check',
                                    action: this.acceptOrder,
                                     additionalAttributes : {'data-toggle':"tooltip" ,'data-placement':"top", 'title':"Accept Order" },
                                },
                                {
                                    classes : 'btn-sm btn-warning mr-1 fas fa-cube',
                                    action: this.PackagedOrder,
                                    additionalAttributes : {'title':"Package Order" },
                                },
                                {
                                    // label: 'Packaging',
                                    classes : 'btn-sm btn-info mr-1 fas fa-shipping-fast',
                                    action: this.OutForDelivery,
                                    additionalAttributes : {'title':"Out for delivery" },
                                },
                                {
                                    classes : 'btn-sm btn-success mr-1 fad fa-box-check',
                                    action: this.DeliveredOrder,
                                    additionalAttributes : {'title':"Delivered" },
                                },
                                {
                                    classes : 'btn-sm btn-info mr-1 fad fa-eye',
                                    action: this.getviewurl,
                                    additionalAttributes : {'title':"View Order" },
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
                        "maintable":true,
                        "table-dark": false,
                    },
                    "td":
                    {
                        "nowrap":true,
                    }

                },
                form : new Form({
                    id:'',
                    name:'',
                    hsn: '',
                    barcode: '',
                    gst: '',
                    maxqty: '',
                    description: '',
                    weight: '',
                    brand_id: '',
                    other:'',
                    category_id:'',
                    sub_category_id:'',
                    sub_sub_category_id: '',
                    vendor_id:'',
                    tags: '',
                    images:[],
                    selected:[],
                    selectedcolor:[],
                    selectedsize:[],
                    attributex:'',
                    attributesub:'',
                    is_active: true,
                    is_featured:false,
                }),
                iform:{
                    id:'',
                    name:'',
                    hsn: '',
                    barcode: '',
                    gst: '',
                    maxqty: '',
                    description: '',
                    weight: '',
                    brand_id: '',
                    other:'',
                    category_id:'',
                    sub_category_id:'',
                    sub_sub_category_id: '',
                    vendor_id:'',
                    tags: [],
                    images:[],
                    selected:[],
                    selectedcolor:[],
                    selectedsize:[],
                    attributex:[],
                    attributesub:[],
                    is_active: true,
                    is_featured:false,
                },
                blank:{
                    id:'',
                    name:'',
                    hsn: '',
                    barcode: '',
                    gst: '',
                    maxqty: '',
                    description: '',
                    weight: '',
                    brand_id: '',
                    other:'',
                    vendor_id:'',
                    category_id:'',
                    sub_category_id:'',
                    sub_sub_category_id: '',
                    tags: '',
                    images:[],
                    selected:[],
                    selectedcolor:[],
                    selectedsize:[],
                    attributex:'',
                    attributesub:'',
                    is_active: true,
                    is_featured:false,
                    
                },
                errors:{},
                multipartForm: new FormData,
                products: [],
                ordercount:[],
                supSubCategories: {},
                subCategories: {},
                coSubCategories: {},
                Categories: {},
                Sizeww:{},
                Colorww:{},
                editMode: false,
                scatFilter: null,
                filtered: [],
                attachments: [],
                commodities: [],
                product_packages: [],
                isLoading: false,
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
                        this.form.delete('/api/product/'+product.id).then(() => {
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
            newForm()
            {

            },
            editForm(data){
                this.multipartForm = new FormData;
                this.errors = {};
                this.iform =this.blank;
                this.iform = data;
                this.editMode = true;
            },

            changeActiveStatus(product){
                this.multipartForm = new FormData;
               // console.log(product);
                this.editForm(product);
                this.iform.is_active = this.iform.is_active == 1  ? 0 : 1 ;
                this.updateProduct();
            },
            changeRecommendedStatus(product){
                this.multipartForm = new FormData;
                console.log(product.is_featured);
                this.editForm(product);
                this.iform.is_featured = this.iform.is_featured == 1  ? 0 : 1 ;
                this.updateProduct();
            },
            getImageUrl(product){
                return product.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/uploads/images/product/' + product.image;
            },

            getUrl()
            {     var a=$('#authid').val();
                 this.url='/api/order_online_datatable/'+a;
                //  console.log(this.url);
                 return this.url;
            },

            getviewurl(order)
            {   
                var a=order.id;
                 location.href ='/OrderDetailsManagement/'+a;
            },

             getediturl(order)
            {   
                var a=order.id;
                 location.href ='/OrderEditManagement/'+a;
            },

           acceptOrder(product){
                // sweet alert modal
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, accept order!'
                    }).then((result) => {
                        // send delete request
                        // console.log(result)
                    if(result.value){
                        this.form.get('/api/acceptorder/'+product.id).then((data) => {
                            //  console.log(data);
                            if(data.data==1)
                            {
                                    swal.fire(
                                    'Accepted!',
                                    'Order has been Accepted.',
                                    'success'
                                    );
                                    setTimeout(()=>{
                                    window.location.reload();
                                    }, 1000);
                            }
                            else if(data.data==0)
                            {
                                swal.fire(
                                'Failed!',
                                `Order can't be Accepted.`,
                                'warning'
                                )
                            }
                            else if(data.data==2)
                            {
                                swal.fire(
                                'Warning!',
                                `Order Is Already Accepted.`,
                                'warning'
                                )
                            }
                        })
                    }
                })
            },

            PackagedOrder(product)
            {   
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Packed it!'
                    }).then((result) => {
                        // send delete request
                        // console.log(result)
                    if(result.value){
                        this.form.get('/api/packageorder/'+product.id).then((data) => {
                        if(data.data==1)
                        {
                            swal.fire(
                            'Packed!',
                            'Order has been Packed.',
                            'success'
                            );
                            setTimeout(()=>{
                            window.location.reload();
                            }, 1000);
                        }
                        else if(data.data==0)
                        {
                            swal.fire(
                            'Failed!',
                            `Order can't be Packed.`,
                            'warning'
                            )
                        }
                        else if(data.data==2)
                        {
                            swal.fire(
                            'Warning!',
                            `Order Is Already Packed.`,
                            'warning'
                            )
                        }
                        else if(data.data==3)
                        {
                            swal.fire(
                            'Warning!',
                            `Order is not accepted yet !!.`,
                            'warning'
                            )
                        }
                        })
                    }
                })
            },

            OutForDelivery(product)
            {   
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delivered it!'
                    }).then((result) => {
                        // send delete request
                        //console.log(result)
                    if(result.value){
                         this.form.get('/api/outfordelivery/'+product.id).then((data) => {
                        if(data.data==1)
                        {
                              swal.fire(
                            'Ready To Delivered!',
                            'Order has been ready to delivered.',
                            'success'
                            );
                            setTimeout(()=>{
                            window.location.reload();
                            }, 1000);
                        }
                        else if(data.data==0)
                        {
                            swal.fire(
                            'Failed!',
                            `Order is not ready.`,
                            'warning'
                            )
                        }
                        else if(data.data==2)
                        {
                            swal.fire(
                            'Warning!',
                            `Order is already ready to delivered.`,
                            'warning'
                            )
                        }
                        })
                    }
                })
            },

            DeliveredOrder(product)
            {   
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delivered it!'
                    }).then((result) => {
                        // send delete request
                        // console.log(result)
                    if(result.value){
                         this.form.get('/api/deliveredorder/'+product.id).then((data) => {
                            //  console.log(data);
                           if(data.data==1)
                        {
                            swal.fire(
                            'Delivered!',
                            'Order has been Delivered.',
                            'success'
                            );
                            setTimeout(()=>{
                            window.location.reload();
                            }, 1000);
                        }
                        else if(data.data==0)
                        {
                            swal.fire(
                            'Failed!',
                            `Order can't be Delivered.`,
                            'warning'
                            )
                        }
                        else if(data.data==2)
                        {
                            swal.fire(
                            'Warning!',
                            `Order Is Already Delivered.`,
                            'warning'
                            )
                        }
                        else if(data.data==3)
                        {
                            swal.fire(
                            'Warning!',
                            `Order is not ready for delivered.`,
                            'warning'
                            )
                        }
                        else if(data.data==4)
                        {
                            swal.fire(
                            'Warning!',
                            `Delivery Person Not Assign.`,
                            'warning'
                            )
                        }
                        })
                    }
                })
            },

           
            loadproductlist() {
                var a =$('#authid').val();
                axios.get("api/order_count/"+a).then(data=>{
                   this.ordercount = data.data
                });

            },
            calc(theform) 
            {
                var with2Decimals = theform.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]
                return with2Decimals;
            },

        },
        mounted() {
            this.isLoading = true;
           // console.log('Component mounted.');  
        },
        created(){
            // this.loadCategoryList();
            // this.loadCommodityList();
            // this.loadPackage();
             this.loadproductlist();
            this.getUrl();
            Fire.$on('LoadProduct', () => this.getUrl() );
        }


    }
    
    
</script>

<style scoped>
.table td{
    padding: 0.35rem!important;
}
</style>
