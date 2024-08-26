<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Commodity</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Commodity List</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal"
                                data-target="#commodityNew"> <i class="fa fa-commodity-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tr>
                                <th>Sr. No</th>
                                <th>Name</th>
                                <th>On / Off</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="(commodity, index) in commodities" :key="commodity.id">
                                <td>{{ (1+index)}}</td>
                                <td>{{ commodity.name | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+commodity.id"  :checked="commodity.is_active" @change="changeActiveStatus(commodity)">
                                        <label class="custom-control-label" :for="'onOff'+commodity.id" ></label>
                                    </div>
                                </td>
                                <td> {{ commodity.description }} </td>
                                <td> {{ commodity.priority }} </td>
                                <td>
                                    <button @click="editForm(commodity)"  data-toggle="modal" data-target="#commodityNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteCommodity(commodity.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- Card body / -->
                    <div v-if="isLoading" class="overlay dark">
                        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                </div><!-- /.row -->


                <!-- Modal -->
                <div class="modal fade" id="commodityNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-commodity"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Commodity</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="editMode ? updateCommodity() : createCommodity()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input required type="text" class="form-control" v-model="form.name"
                                                placeholder="Commodity Name"
                                                :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Priority </label>
                                        <div class="col-sm-10">
                                            <input required type="number" min="0" class="form-control" v-model="form.priority"
                                                placeholder="Commodity Priority"
                                                :class="{ 'is-invalid': form.errors.has('priority') }">
                                            <has-error :form="form" field="priority"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Description</label>
                                        <div class="col-sm-10">
                                            <input type="text" min="1" class="form-control" v-model="form.description"
                                                :class="{ 'is-invalid': form.errors.has('description') }">
                                            <has-error :form="form" field="description"></has-error>
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
                    id: null,
                    name: null,
                    description: null,
                    is_active: true,
                    priority: null,
                    admin_id:'',
                }),
                commodities: {},
                marketingPersons: [],
                isLoading : false,
                editMode: false
            }
        },
        methods :{
            createCommodity(){
                this.$Progress.start();
var a =$('#authid').val();
this.form.admin_id=a;
                this.form.post('api/commodity_type').then( ()=>{
                    Fire.$emit('LoadCommodity');
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
                this.form.put('api/commodity_type/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadCommodity');
                    $('#commodityNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadCommodityList() {
                this.isLoading = true;
                var a =$('#authid').val();

                axios.get("api/commodity_type").then( ({ data }) => {
                    this.commodities = data;
                    this.isLoading = false;
                }).catch( err => this.isLoading=false);
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
                        this.form.delete('/api/commodity_type/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Commodity has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadCommodity');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Commodity can't  be deleted.`,
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
                this.editMode = true;
            },
            changeActiveStatus(commodity){
                this.editForm(commodity);
                this.form.is_active = !this.form.is_active ;
                this.updateCommodity();
            },
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadCommodityList();
            Fire.$on('LoadCommodity', () => this.loadCommodityList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
