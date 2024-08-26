<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Commodity List</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Commodity List - {{ deliveryPerson !=null ? deliveryPerson.name : ''}}</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#commodityNew"> <i class="fa fa-commodity-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Commodity Type </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <!-- <tr>
                                <td>183</td>
                                <td>John Doe</td>
                                <td>11-7-2014</td>
                                <td><span class="tag tag-success">Approved</span></td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr> -->

                            <tr v-for="(ct, index) in deliveryCommodites" :key="'sc'+index">
                                <td>{{(1+index)}}</td>
                                <td>{{ ct.commodity.name }}</td>

                                <td>
                                    <button @click="deleteCommodity(ct.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div><!-- /.row -->


        <!-- Modal -->
        <div class="modal fade" id="commodityNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-commodity"></i> {{ editMode ? 'Edit' : 'Add'}} Commodity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateCommodity() : createCommodity()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Commodity</label>
                                <div class="col-sm-10">
                                    <select required type="text" class="form-control" v-model="form.commodity_type_id"
                                                placeholder="Coupon Name"
                                                :class="{ 'is-invalid': form.errors.has('commodity_type_id') }">
                                        <option :value="null" disabled>Select Commodity type</option>
                                        <option v-for="mp in commodities" :value="mp.id" :key="mp.id" :selected="mp.id == form.commodity_type_id">{{ mp.name}}</option>
                                    </select>
                                    <has-error :form="form" field="commodity_type_id"></has-error>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save </button>
                        </div>
                    </form>
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
                    commodity_type_id: null
                }),
                commodites: {},
                deliveryCommodites: [],
                editMode: false,
                deliveryPersonId: null,
                deliveryPerson:null,
            }
        },
        methods :{
            createCommodity(){
                this.$Progress.start();

                this.form.post('/api/delivery_person/'+ this.deliveryPersonId +'/commodity').then( ()=>{
                    Fire.$emit('LoadDeliveryCommodity');
                    $('#commodityNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Commodity Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadCommodityList();
            },
            updateCommodity(){
                this.$Progress.start();
                this.form.put('/api/delivery_person/'+ this.deliveryPersonId +'/commodity/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadCommodity');
                    $('#commodityNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Commodity Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadDeliveryCommodityList() {
                axios.get('/api/delivery_person/'+ this.deliveryPersonId +'/commodity').then( ({ data }) => (this.deliveryCommodites = data) );
            },
            deleteCommodity(id){
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
                        this.form.delete('/api/delivery_person/'+ this.deliveryPersonId +'/commodity/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Sub DeliveryPerson has been deleted.',
                            'success'
                            );


                            Fire.$emit('LoadDeliveryCommodity');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Sub DeliveryPerson can't  be deleted.`,
                            'danger'
                            )


                            Fire.$emit('LoadCommodity');
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.form.category_id = this.categoryId;
                this.editMode = false;
            },
            loadCommodityList() {
                axios.get("/api/commodity_type").then( ({ data }) => {
                    this.commodities = data;
                }).catch( err => console.log(err));
            },
            loadDeliveryPerson() {
                axios.get("/api/delivery_person/"+this.deliveryPersonId).then( ({ data }) => (this.deliveryPerson = data) );
            },
        },
        mounted() {
            console.log('Component mounted.');
            this.deliveryPersonId = this.$route.params.deliveryPerson;
            this.loadDeliveryCommodityList();
            this.loadCommodityList();
            this.loadDeliveryPerson();
        },
        created(){
            Fire.$on('LoadDeliveryCommodity', () => this.loadDeliveryCommodityList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 3vh;
    max-height : 3vh
}
</style>
