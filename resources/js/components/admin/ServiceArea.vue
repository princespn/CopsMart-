<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>ServiceArea</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Service Area List</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#serviceAreaNew"> <i class="fa fa-serviceArea-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tr>
                                <th>Sr. No</th>
                                <th>Name</th>
                                <th>On / Off</th>
                                <th>Range</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
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

                            <tr v-for="(serviceArea , index) in serviceAreas" :key="serviceArea.id">
                                <td>{{ (1+index)}}</td>
                                <td>{{ serviceArea.name | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+serviceArea.id"  :checked="serviceArea.is_active" @change="changeActiveStatus(serviceArea)">
                                        <label class="custom-control-label" :for="'onOff'+serviceArea.id" ></label>
                                    </div>
                                </td>
                                <td> {{ serviceArea.range > 0 ? serviceArea.range*.001 : 'Not Defined' }} Km</td>
                                <td>
                                    <a target="_blank" :href="getMapUrl(serviceArea)" class="btn btn-sm btn-primary"> <i class="fa fa-map-marker"></i> View Location </a>
                                </td>
                                <td>
                                    <button @click="editForm(serviceArea)"  data-toggle="modal" data-target="#serviceAreaNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteServiceArea(serviceArea.id)" class="btn btn-danger btn-sm">
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
        <div class="modal fade" id="serviceAreaNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-serviceArea"></i> {{ editMode ? 'Edit' : 'Add'}} ServiceArea</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateServiceArea() : createServiceArea()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control"  v-model="form.name" placeholder="ServiceArea Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Range in meters</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="form.range" :class="{ 'is-invalid': form.errors.has('range') }">
                                    <has-error :form="form" field="range"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Latitude</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="form.latitude" step=".000000000001" :class="{ 'is-invalid': form.errors.has('latitude') }">
                                    <has-error :form="form" field="latitude"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Longitude</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="form.longitude"  step=".000000000001" :class="{ 'is-invalid': form.errors.has('longitude') }">
                                    <has-error :form="form" field="longitude"></has-error>
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
                    id:'',
                    name:'',
                    range: null,
                    latitude: null,
                    longitude: null,
                    admin_id:'',
                    is_active: true,
                }),
                serviceAreas: {},
                editMode: false
            }
        },
        methods :{
            createServiceArea(){
                this.$Progress.start();
                     var a =$('#authid').val();
                     this.form.admin_id=a;
                this.form.post('api/service_area').then( ()=>{
                    Fire.$emit('LoadServiceArea');
                    $('#serviceAreaNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'ServiceArea Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadServiceAreaList();
            },
            updateServiceArea(){
                this.$Progress.start();
                this.form.put('api/service_area/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadServiceArea');
                    $('#serviceAreaNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadServiceAreaList() {
                var a =$('#authid').val();
                axios.get("api/service_area").then( ({ data }) => (this.serviceAreas = data) );
            },
            deleteServiceArea(id){
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
                        this.form.delete('/api/service_area/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'ServiceArea has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadServiceArea');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `ServiceArea can't  be deleted.`,
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
            changeActiveStatus(serviceArea){
                this.editForm(serviceArea);
                this.form.is_active = !this.form.is_active ;
                this.updateServiceArea();
            },
            getMapUrl(serviceArea){
                return serviceArea.latitude != null ? `http://maps.google.com/maps?q=${serviceArea.latitude},${serviceArea.longitude}` : '#';
            },
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadServiceAreaList();
            Fire.$on('LoadServiceArea', () => this.loadServiceAreaList() );
        }


    }
</script>
