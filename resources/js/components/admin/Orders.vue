<template>
    <!--<a href="#" class="d-block">{{Auth::user()->name}}</a>-->
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <div class="col-xs-12">
                <h1>Orders</h1>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">

            <div class="col-xs-12 ">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link" id="nav-home-tab" :class="{active : currentOrdersTab == 'pending'}" :aria-selected="currentOrdersTab == 'pending'" @click="changeTab('pending')">New Orders</a>
                        <a class="nav-item nav-link" id="nav-profile-tab"  :class="{active : currentOrdersTab == 'active'}" :aria-selected="currentOrdersTab == 'active'" @click="changeTab('active')">Active Orders</a>
                        <a class="nav-item nav-link" id="nav-profile-tab"  :class="{active : currentOrdersTab == 'delivered'}" :aria-selected="currentOrdersTab == 'delivered'" @click="changeTab('delivered')">Delivered Orders</a>
                        <a class="nav-item nav-link" id="nav-profile-tab"  :class="{active : currentOrdersTab == 'cancelled'}" :aria-selected="currentOrdersTab == 'cancelled'" @click="changeTab('cancelled')">Cancelled Orders</a>
                    </div>
                </nav>
                <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <table  v-if="!isLoading" class="table table-hover table-responsive table-bordered">
                            <tr>
                                <th>Order Number</th>
                                <th>Customer Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Current Status</th>
                                <th>Vendor Status</th>
                                <th>Delivery Status</th>
                                <th>Last Status Update</th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="order in orders" :key="order.id">
                                <td>{{order.id}}</td>
                                <td>{{order.user != null ? order.user.name: 'NA'}}</td>
                                <td>{{ order.user !=null ? order.user.mobile: 'NA'}}</td>
                                <td>{{ order.user != null  ? order.user.email: 'NA'}}</td>
                                <td>{{ order.created_at | myDate}}</td>
                                <td>{{ order.amount}}</td>
                                <td>{{ order.status  != null ? order.status.name: 'NA'}}</td>
                                <td>{{ order.vendor_status  != null  ? order.vendor_status.name: 'NA'}}</td>
                                <td>{{ order.delivery_status  != null ? order.delivery_status.name: 'NA'}}</td>
                                <td>{{ order.status_updated | myDate}}</td>
                                <td>
                                    <button @click="viewOrder(order)"  data-toggle="modal" data-target="#orderNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </td>
                            </tr>


                        </table>
                        <div class="col-sm-12" v-if="isLoading">
                            <span>
                                <h1 style="text-align:center"><i class="fa fa-spinner fa-spin"></i></h1>
                            </span>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui minim occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute sit dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est eiusmod tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi culpa non adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis occaecat ex.
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui minim occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute sit dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est eiusmod tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi culpa non adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis occaecat ex.
                    </div>
                    <div class="tab-pane fade" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                        Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui minim occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute sit dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est eiusmod tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi culpa non adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis occaecat ex.
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row mt-3">
            <div class="col-12">
                <!-- Modal -->
                <div class="modal fade" id="orderNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-order"></i>
                                    Order Details
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <div class="modal-body">
                                    <section class="invoice" v-if="orderDetails">
                                        <!-- title row -->
                                        <!-- <div class="row">
                                            <div class="col-xs-12">
                                                <h2 class="page-header">
                                                    <i class="fa fa-globe"></i> Trust point Co.
                                                    <small class="pull-right">Date: 2017/01/09</small>
                                                </h2>
                                            </div> /.col
                                        </div> -->
                                        <!-- info row -->
                                        <div class="row invoice-info">

                                            <div class="col-sm-3 invoice-col">
                                                To
                                                <address>
                                                    <strong>
                                                        {{ orderDetails.user != null ? orderDetails.user.name : ''}}
                                                    </strong>
                                                    <br>
                                                    Address:
                                                    {{ orderDetails.address != null ? orderDetails.address.address : ''}} <br>
                                                    LandMark:
                                                    {{ orderDetails.address != null ? orderDetails.address.landmark : ''}} <br>
                                                    Phone:
                                                    {{ orderDetails.user != null ? orderDetails.user.mobile : ''}}          <br>
                                                    Email:{{ orderDetails.user != null ? orderDetails.user.email : ''}}
                                                </address>
                                            </div><!-- /.col -->
                                            <div class="col-sm-3 invoice-col">
                                                <b>Order Number: #{{orderDetails.id}}</b><br>
                                                <br>
                                                <b>Amount:</b> {{orderDetails.amount}}<br>
                                                <b>Payment Method:</b> {{orderDetails.payment_method.name}}<br>
                                                <b>Ocean Coupon Discount:</b> {{orderDetails.ocean_discount}} %<br>
                                                <b>Ocean Coupon Amount:</b> {{orderDetails.applied_ocean_discount}} <br>
                                                <b>Vendor Coupon Discount:</b> {{orderDetails.vendor_discount}} %<br>
                                                <b>Vendor Coupon Amount:</b> {{orderDetails.applied_vendor_discount}} <br>
                                                <span v-if="!orderDetails.payment_method.is_postpaid">
                                                    <b>Payment Status:</b> <label class="btn btn-sm" :class="{ 'btn-success': orderDetails.payment_status == 1, 'btn-danger':orderDetails.payment_status == 0, 'btn-warning':orderDetails.payment_status == null }"> {{ orderDetails.payment_status == 1 ? 'Complete' : 'Incomplete'}}</label><br>

                                                </span>
                                                <b>Order Status:</b> {{ orderDetails.status != null ? orderDetails.status.name : ''}}
                                            </div><!-- /.col -->
                                            <div class="col-sm-3 invoice-col">
                                                <strong>Vendor Details</strong>
                                                <p v-if="orderDetails.vendor">
                                                    Name: {{ orderDetails.vendor.name }} <br>
                                                    Mobile: {{ orderDetails.vendor.contact_no }} <br>
                                                    Address: {{ orderDetails.vendor.address }} <br>
                                                    Vendor Order Status: {{ orderDetails.vendor_status != null ? orderDetails.vendor_status.name :'NA' }} <br>
                                                    Vendor Delivery Discount: {{ orderDetails.vend_del_discount }} <br>
                                                    Ocean Delivery Discount: {{ orderDetails.ocean_del_dis }} <br>
                                                    Vendor Slot : {{ orderDetails.vendor_slot != null ? orderDetails.vendor_slot :'NA' }} <br>
                                                    <span v-if="orderDetails.vendeo_accept_time!= null">Vendor Accept DateTime : {{ orderDetails.vendeo_accept_time  | myDate }} </span><br>
                                                </p>
                                                <p v-if="!orderDetails.vendor">
                                                    [VENDOR DELETED]
                                                </p>
                                            </div><!-- /.col -->
                                            <div class="col-sm-3 invoice-col">
                                                <strong>Delivery Person Details</strong>
                                                <p v-if="orderDetails.delivery_person">
                                                    Name: {{ orderDetails.delivery_person.name }} <br>
                                                    Mobile: {{ orderDetails.delivery_person.mobile }} <br>

                                                    Delivery Status: {{ orderDetails.delivery_status != null ? orderDetails.delivery_status.name :'' }} <br>
                                                </p>
                                                  <p v-if="orderDetails.delivery_status_id!=3 && orderDetails.delivery_status_id!=4 && orderDetails.delivery_status_id!=null">
                                                   
                                                    <button type="button" @click="DeliveryRedict(orderDetails)" class="btn btn-primary"> Delivery Remove</button>
                                                    <br>
                                                   
                                                </p>
                                                <p v-if="!orderDetails.delivery_person">
                                                    [Delivery Person Not Assigned]
                                                    <select v-if="deliveryPersons.length>0"   v-model="deliveryAssignForm.delivery_person_id" :class="{ 'is-invalid': deliveryAssignForm.errors.has('delivery_person_id') }" >
                                                        <option v-for="dp in deliveryPersons" :key="dp.id" :value="dp.id" >{{dp.name}}</option>
                                                    </select>
                                                        <button type="button" @click="assignDeliveryPerson(orderDetails.id)" class="btn btn-primary"> Assign</button>
                                                </p>
                                                <p>Delivery Slot : {{ orderDetails.delivery_slot  != null  ? orderDetails.delivery_slot: 'NA'}}</p>
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->

                                        <!-- Table row -->
                                        <div class="row">
                                            <div class="col-xs-12 table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Qty</th>
                                                            <th>Price</th>
                                                            <th>Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>


                                                        <tr v-for="product in orderDetails.products" :key="product.id">
                                                            <td>{{ product.name}}</td>
                                                            <td>{{ product.qty}}</td>
                                                            <td>Rs {{ product.price}}</td>
                                                            <td>Rs {{ product.price * product.qty}}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->

                                        <div class="row">
                                            <!-- accepted payments column -->
                                            <div class="col-md-12">
                                                <p class="lead">Scheduled Delivery Time : {{orderDetails.scheduled_delivery_date | myDate}}</p>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tbody>

                                                            <tr>
                                                                <th>Sub Total</th>
                                                                <td>Rs {{ getSubtotal(orderDetails) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Delivery Charges:</th>
                                                                <td>Rs {{orderDetails.delivery_charges}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Marketing Charges:</th>
                                                                <td>Rs {{orderDetails.marketing_charges}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total:</th>
                                                                <td>Rs {{orderDetails.amount}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->

                                      
                                    </section>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <!-- <button type="submit" class="btn btn-primary">Save </button> -->
                                </div>
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
    export default {
         components:{          
            'orders': GetOrder,
        },
        data() {
            return {
                form : new Form({
                    id:'',
                    name:'',
                    image: '',
                    is_active: true,
                }),
                orderDetails : null,
                orders: {},
                deliveryPersons:[],
                editMode: false,
                currentOrdersTab:'pending',
                isLoading: false,
                deliveryAssignForm : new Form({
                    delivery_person_id: null,
                }),
                DeliveryRemove : new Form({
                    order_id: null,
                    
                }),
            }
        },
        methods :{
            createOrder(){
                this.$Progress.start();

                this.form.post('api/orders').then( ()=>{
                    Fire.$emit('LoadOrder');
                    $('#orderNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Order Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadOrderList();
            },
            DeliveryRedict(order)
            {
               this.DeliveryRemove.order_id =order.id;
               this.DeliveryRemove.post(`/api/orders/${order.id}/DeliveryRemove`).then(()=>{
                    this.loadOrderDetails(order);
                    swal.fire(
                        'Updated!',
                        'Order has been updated.',
                        'success'
                    );
                    location.reload();
                }).catch(err => console.log(err));
            },
            updateOrder(){
                this.$Progress.start();
                this.form.put('api/orders/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadOrder');
                    $('#orderNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadOrderList(type = 'pending') {
                this.isLoading = true;
                var a =$('#authid').val();
                axios.get('api/orders?type='+type+'&ad='+a ).then( ({ data }) => {
                    this.orders = data;
                    this.isLoading = false;
                }).catch(err =>{
                    console.log(err);
                    this.isLoading = false;
                });
            },
            deleteOrder(id){
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
                        this.form.delete('/api/orders/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Order has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadOrder');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Order can not  be deleted.',
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
                this.form.reset();
                this.form.fill(data);
                this.form.image = null;
                this.editMode = true;
            },
            changeActiveStatus(order){
                this.editForm(order);
                this.form.is_active = !this.form.is_active ;
                this.updateOrder();
            },
            changeTab(tab){
                switch (tab) {
                    case 'active':
                        this.currentOrdersTab='active';
                        break;
                    case 'cancelled':
                        this.currentOrdersTab='cancelled';
                        break;
                    case 'delivered':
                        this.currentOrdersTab='delivered';
                        break;

                    default:
                        this.currentOrdersTab = 'pending';
                        break;
                }
                this.loadOrderList(tab);
            },
            loadOrderDetails(orderId){
                this.$Progress.start();
                axios.get('/api/orders/' + orderId).then(({data}) =>{
                    this.orderDetails = data;
                    this.$Progress.finish();
                }).catch(err =>{
                    this.$Progress.fail();
                });
            },
            viewOrder(order){
                this.loadOrderDetails(order.id);
            },
            getTime(date = null) {
                console.log(moment(date).format('Do MMMM YYYY, h:mm:ss a').toString());
                return moment(date).format('DD/MM/YYYY, h:mm:ss a').toString();
            },
            getSubtotal(orderDetails){
                let total = 0;
                for(let x in orderDetails.products){

                    let actualPrice = orderDetails.products[x].price;
                    if(orderDetails.products[x].discount > 0){
                        const discount = orderDetails.products[x].price * (orderDetails.products[x].discount * .01);
                        actualPrice = orderDetails.products[x].price - discount;
                    }
                    total += actualPrice * orderDetails.products[x].qty;
                }
                return Math.round(total);
            },
            loadDeliveryPersonList() {
                this.isLoading = true;
                axios.get("api/delivery_person").then( ({ data }) => {
                    this.deliveryPersons = data;
                    this.isLoading = false;
                });
            },
            assignDeliveryPerson(order) {
                this.deliveryAssignForm.post(`/api/orders/${order}/assign_delivery_person`).then(()=>{
                    this.loadOrderDetails(order);
                    swal.fire(
                        'Updated!',
                        'Order has been updated.',
                        'success'
                    );
                }).catch(err => console.log(err));
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadOrderList();
            this.loadDeliveryPersonList();
            Fire.$on('LoadOrder', () => this.loadOrderList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}


nav > .nav.nav-tabs{

  border: none;
    color:#fff;
    background:#272e38;
    border-radius:0;

}
nav > div a.nav-item.nav-link,
nav > div a.nav-item.nav-link.active
{
  border: none;
    padding: 18px 25px;
    color:#fff;
    background:#272e38;
    border-radius:0;
}

nav > div a.nav-item.nav-link.active:after
 {
  content: "";
  position: relative;
  bottom: -60px;
  left: -10%;
  border: 15px solid transparent;
  border-top-color: #e74c3c ;
}
.tab-content{
  background: #fdfdfd;
    line-height: 25px;
    border: 1px solid #ddd;
    border-top:5px solid #e74c3c;
    border-bottom:5px solid #e74c3c;
    padding:30px 25px;
}

nav > div a.nav-item.nav-link:hover,
nav > div a.nav-item.nav-link:focus
{
  border: none;
    background: #e74c3c;
    color:#fff;
    border-radius:0;
    transition:background 0.20s linear;
}

/* Invoice Style */
.invoice {
    position: relative;
    background: #fff;
    border: 1px solid #f4f4f4;
    padding: 20px;
    margin: 10px 25px;
}
</style>
