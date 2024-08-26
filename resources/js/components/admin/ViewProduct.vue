<template>
    <div class="container p-0">
        <!-- /.row -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"> 
                        <div class="row">
                            <div class="col-md-8"><h3 class="card-title ml-3">View Product</h3></div>
                        </div>
                    </div> 
                    <div class="card-header" style="border:none!important">
                        <h6 class="ml-2 text-primary mb-0">Product Details </h6>
                          <hr class="mb-1 mt-0">
                           <div class="row pl-3 ml-2">
                               <div class="col-md-2 w-100">
                                   <img :src="imgurl(Customers.product.image1)" alt="" >
                                </div>
                                <div class="col-md-4 pl-1 pr-1 br">
                                    <div class="row w-100">
                                        <p class="col-md-12  mb-0 text-primary text-left">{{Customers.product.name}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">MRP</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">₹ {{Customers.product.mrp}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">Sizes Available</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.product.size_name}}</p>
                                    </div>
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0 text-left">Colors Available</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.product.color_name}}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 pl-1 pl-1 br">
                                    <div class="w-100">
                                        <p class="col-md-12 mb-0 text-center text-primary">Short Description :</p>
                                        <p class="col-md-12  mb-0 p-0 text-center">{{Customers.product.description}}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 w-100">
                                     <div class="row w-100">
                                        <h5 class="col-md-12 mb-0 w-100 text-left text-primary text-center">	&#9733; 	&#9733; 	&#9733; 	&#9733; 	&#9733;</h5>
                                        <h6 class="col-md-12  mb-0 p-0 text-center text-primary">{{Customers.product_count}} Reviews</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- ---------------------------------- -->
                        <hr class="mb-1 mt-0">
                        <div class="row pl-3 ml-2">
                               <div class="col-md-3 pl-1 pl-1 br">
                                    <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">Category</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.product.category}}</p>
                                    </div>
                                     <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">Brand</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.product.brandname}}</p>
                                    </div>
                                     <div class="row  w-100 ">
                                        <p class="col-md-5  mb-0  text-left">Related Tag</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-5   mb-0 p-0 text-left">{{Customers.product.tag_name}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 pl-1 pl-1 br">
                                     <div class="row  w-100 ">
                                        <p class="col-md-6 ml-1 mb-0  text-left">Sub-Category</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-4   mb-0 p-0 text-left">{{Customers.product.subcategory}}</p>
                                    </div>
                                     <div class="row  w-100 ">
                                        <p class="col-md-6 ml-1  mb-0  text-left">Sub-Sub-Category</p>
                                        <p class="col-md-1  mb-0 p-0 text-center">:</p>
                                        <p class="col-md-4  mb-0 p-0 text-left">{{Customers.product.subsubcategory}}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 pl-1 pl-1 br">
                                    <div class="w-100">
                                        <p class="col-md-12 mb-0 text-left text-primary">Item Information :</p>
                                        <p class="col-md-12  mb-0 p-0 text-center">{{Customers.product.other}}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 pl-1 pl-1">
                                    <div class="w-100 text-primary">
                                        <p class="col-md-12 mb-0 text-center ">Stock Information :</p>
                                        <h5 class="col-md-12  mb-0 p-0 text-center">{{Customers.stock}}</h5>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <hr>
                    <div class="card-body p-3">
                       <div class="card card-primary card-outline">
          <div class="card-body">
            
             <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item p-0 col-md-6 text-center">
                <a class="nav-link active" @click="getUrl" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true"><h5>Order Details</h5></a>
              </li>
              <li class="nav-item p-0 col-md-6 text-center">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><h5>Reviews and Ratings</h5></a>
              </li>
            </ul>
            <div class="tab-content mt-2" id="custom-content-below-tabContent">
              <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">

                    <div class="table-bordered  text-center">
                        <data-table
                            :classes = "tableClasses"
                            :class = "text-center"
                            :url="this.orderurl"
                            :columns="columns"
                            :per-page="perPage">
                        </data-table>
                    </div>
              </div>
              <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                 <table  class="table table-hover  table-bordered" >
                        <data-table
                            :classes = "tableClasses"
                            :class = "text-center"
                            :url="this.reviewurl"
                            :columns="columnsr"
                            :per-page="perPager">
                        </data-table>
                </table>
              </div> 
            </div>
          </div>
          <!-- /.card -->
        </div>
                    </div>
                <!-- /.card -->
            </div>


         <br><br>
           <orders />
        <!-- Modal -->
       
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
import { log } from 'util';
    export default {
        components:{  
            CImage,
            CBtn,
            CToggle,
            'v-select': vSelect,
            'orders': GetOrder,
        },
        data() {
            return {
                perPage: ['10', '50', '100', '500', '1000','2500','5000'],
                columns: [
                    {
                        label: 'Inv. No',
                        name: 'inv_no',
                         clases:'text-center',
                        filterable: true,
                    },
                    {
                        label: 'Inv Date',
                        name: 'inv_date',
                        clases:'text-center',
                        filterable: true,
                    },
                    {
                        label: 'Customer',
                        name: 'customer',
                        clases:'text-center',
                        filterable: true,
                    },
                    {
                        label: 'Items',
                        name: 'amount',
                        filterable: true,
                        clases:'text-center',
                    },
                    {
                        label: 'Amount',
                        name: 'amount',
                        filterable: true,
                        clases:'text-center',
                    },
                   

                ],
                 perPager: ['10', '50', '100', '500', '1000','2500','5000'],
                 columnsr: [
                    {
                        label: 'Inv. No',
                        name: 'inv_no',
                         clases:'text-center',
                        filterable: true,
                    },
                    {
                        label: 'Inv Date',
                        name: 'inv_date',
                        clases:'text-center',
                        filterable: true,
                    },
                    {
                        label: 'Customer',
                        name: 'customer',
                        clases:'text-center',
                        filterable: true,
                    },
                    {
                        label: 'Review',
                        name: 'review',
                        filterable: true,
                        clases:'text-center',
                    },
                    {
                        label: 'Star Rating',
                        name: 'stars',
                        filterable: true,
                        clases:'text-center',
                    },
                   

                ],
                tableClasses:{
                    "table-container": {
                        "table-responsive": true,
                        "table-border": true,
                        "text-center": true,
                    },
                    "table": {
                        "table": true,
                        "table-striped": true,
                        "table-dark": false,
                    },
                    
                },
                errors:{},
                multipartForm: new FormData,
                isLoading: true,
                editId:'',
                Customers:{},
                orderurl:'',
                reviewurl:'',
            }
        },
        methods :{
            getUrl()
            {    
                this.orderurl='/api/orderproductview/'+this.editId;
                return this.orderurl;
            },
            getUrl()
            {    
                this.reviewurl='/api/orderproductview/'+this.editId;
                return this.reviewurl;
            },
            loadEditProduct()
            {    
                axios.get("/api/getproduct/"+this.editId).then( ({ data }) => (this.Customers=data) );
            },
            editUrl(vendor){
                return `/editCustomer/${vendor}` ;
            },
            vieworder(vendor)
            {
              return `/OrderDetailsManagement/${vendor}` ;
            },
            getMapUrl(vendor){
                return vendor.latitude != null ? `http://maps.google.com/maps?q=${vendor.latitude},${vendor.longitude}` : '#';
            },
            imgurl(user)
            {
                return user == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/uploads/images/product/' + user;
            }
           
        },
        mounted() 
        {
            this.isLoading = true;  
        },
        created(){
           this.isLoading = true;  
            this.editId = this.$route.params.OrderId;
            this.loadEditProduct();
            this.getUrl();
            Fire.$on('LoadProduct', () => this.loadEditProduct() );
        }
    }
   
</script>


<style scoped>
.table th, .table td {padding: 0.2rem!important;}
.table td {padding: 0.2rem!important;font-size: 12px!important;}
img
{
    max-width : 20vh;
    max-height : 20vh
}
p{font-size: 13px!important;}
.disnone{display: none!important;}
table input, table select{width: 85%!important;}
table .btn-sm, .btn-group-sm > .btn {
    padding: 0.25rem 0.25rem!important;}
</style>

<style scoped lang="scss">

.pce{
    padding:6px 13px!important;
}
.br{
    border-right:1px solid black!important;
}

.card-title {
    margin-top: 0.75rem!important;
    margin-bottom: 0px!important;
}
</style>