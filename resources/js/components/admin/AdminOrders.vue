<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <div class="col-xs-12">
                <h1>Admin Orders</h1>
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
                                <td>{{ order.delivery_status  != null ? order.delivery_status.name: 'Pending'}}</td>
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
                                                <b>&nbsp;</b><br>
                                                <br>
                                                <b>Phone :</b>{{ orderDetails.user != null ? orderDetails.user.mobile : ''}} <br>
                                                <b>Email :</b> {{ orderDetails.user != null ? orderDetails.user.email : ''}}<br>
                                                
                                            </div><!-- /.col -->
                                            <div class="col-sm-3 invoice-col">
                                                <b>Order Number: #{{orderDetails.id}}</b><br>
                                                <br>
                                                <b>Amount:</b> {{orderDetails.amount}}<br>
                                                <b>Payment Method:</b> {{ orderDetails.payment != null ? orderDetails.payment.name : ''}}<br>
                                                <span v-if="orderDetails.payment_method!=null && !orderDetails.payment_method.is_postpaid">
                                                    <b>Payment Status:</b> <label class="btn btn-sm" :class="{ 'btn-success': orderDetails.payment_status == 1, 'btn-danger':orderDetails.payment_status == 0, 'btn-warning':orderDetails.payment_status == null }"> {{ orderDetails.payment_status == 1 ? 'Complete' : 'Incomplete'}}</label><br>

                                                </span>
                                                <b>Order Status:</b> {{ orderDetails.status != null ? orderDetails.status.name : 'Pending'}}</br>
                                                <b>Delivery Status:</b> {{ orderDetails.delivery != null ? orderDetails.delivery.name : 'Pending'}}
                                            </div><!-- /.col -->
                                           
                                            <div class="col-sm-3 invoice-col">
                                                <b>[Change Order and Delivery Status]</b><br>
                                                <br>
                                                <p v-if="orderDetails.orderStatus">
                                                    [Order Status]
                                                    <select v-if="orderDetails.orderStatus.length>0" v-model="deliveryAssignForm.order_status_id" :class="{ 'is-invalid': deliveryAssignForm.errors.has('order_status_id') }" >
                                                        <option v-for="os in orderDetails.orderStatus" :key="os.id" :value="os.id" :selected="checkSelected(os)">{{os.name}}</option>
                                                    </select>
                                                        
                                                 </p>
                                                <p v-if="orderDetails.deliveryStatus">
                                                    [Delivery Status]
                                                    <select v-if="orderDetails.deliveryStatus.length>0" v-model="deliveryAssignForm.delivery_status_id" :class="{ 'is-invalid': deliveryAssignForm.errors.has('delivery_status_id') }" >
                                                        <option v-for="ds in orderDetails.deliveryStatus" :key="ds.id" :value="ds.id" :selected="checkSelected(ds)">{{ds.name}}</option>
                                                    </select>
                                                        <button type="button" @click="changeOrderStatus(orderDetails.id)" class="btn btn-primary"> Submit</button>
                                                </p>
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
                                                            <td>Rs {{ product.sell_price}}</td>
                                                            <td>Rs {{ product.sell_price * product.qty}}</td>
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
                                                                <td>Rs {{orderDetails.delivery_charges_for_cust}}</td>
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

                                        <!-- this row will not appear when printing -->
                                        <!-- <div class="row no-print">
                                            <div class="col-xs-12">
                                                <a href="" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                                                <button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment</button>
                                                <button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>
                                            </div>
                                        </div> -->
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
        </div>
    </div>
</template>

<script>
    import { moment } from 'moment';
    import $ from "jquery";
    export default {
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
                    order_status_id: null,
                    delivery_status_id: null,
                }),
            }
        },
        methods :{
            createOrder(){
                this.$Progress.start();

                this.form.post('api/admin_orders').then( ()=>{
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
            loadOrderList(type = 'pending') {
                this.isLoading = true;
                axios.get('api/admin_orders?type='+type ).then( ({ data }) => {
                    this.orders = data;
                    this.isLoading = false;
                }).catch(err =>{
                    console.log(err);
                    this.isLoading = false;
                });
            },
            checkSelected(subCategory){
                return subCategory.id == this.deliveryAssignForm.order_status_id
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
                        this.form.delete('/api/admin_orders/'+id).then(() => {
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
                axios.get('/api/admin_orders/' + orderId).then(({data}) =>{
                    this.orderDetails = data;
                    this.$Progress.finish();
                }).catch(err =>{
                    this.$$Progress.fail();
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

                    let actualPrice = orderDetails.products[x].sell_price;
                    total += actualPrice * orderDetails.products[x].qty;
                }
                return Math.round(total);
            },
            changeOrderStatus(order) {
                this.deliveryAssignForm.post(`/api/admin_orders/${order}/change_order_status`).then(()=>{
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
