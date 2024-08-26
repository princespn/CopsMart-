<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Slab List</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Slab List - {{ category !=null ? category.name : ''}}</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#slabNew"> <i class="fa fa-slab-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Type</th>
                                    <th>Start Limit</th>
                                    <th>End Limit</th>
                                    <th>Delivery Charge</th>
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

                            <tr v-for="(slab, index) in slabs" :key="'sc'+index">
                                <td>{{(1+index)}}</td>
                                <td>{{ slab.type == 1 ? 'Distance' : 'Order Amount' }}</td>
                                <td>{{ slab.limit_start }}</td>
                                <td>{{ slab.limit_end }}</td>
                                <td>{{ slab.charges }}</td>

                                <td>
                                    <button @click="editForm(slab)"  data-toggle="modal" data-target="#slabNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteSlab(slab.id)" class="btn btn-danger btn-sm">
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
        <div class="modal fade" id="slabNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-slab"></i> {{ editMode ? 'Edit' : 'Add'}} Slab</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateSlab() : createSlab()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Limit Start</label>
                                <div class="col-sm-10">
                                    <input type="charges" min="0" class="form-control"  v-model="form.limit_start" placeholder="Slab Limit Start" :class="{ 'is-invalid': form.errors.has('limit_start') }">
                                    <has-error :form="form" field="limit_start"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Limit End</label>
                                <div class="col-sm-10">
                                    <input type="charges" min="0.1" class="form-control"  v-model="form.limit_end" placeholder="Slab Limit Start" :class="{ 'is-invalid': form.errors.has('limit_end') }">
                                    <has-error :form="form" field="limit_end"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Delivery Charge</label>
                                <div class="col-sm-10">
                                    <input type="charges" min="0.1" class="form-control"  v-model="form.charges" placeholder="Slab Limit Start" :class="{ 'is-invalid': form.errors.has('charges') }">
                                    <has-error :form="form" field="charges"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Rule Type</label>
                                <div class="col-sm-10">
                                    <select class="form-control"  v-model="form.type"  :class="{ 'is-invalid': form.errors.has('type') }">
                                        <option :value="0"> Order Amount</option>
                                        <option :value="1"> Distance</option>
                                    </select>
                                    <has-error :form="form" field="type"></has-error>
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
                    limit_start :null,
                    limit_end :null,
                    category_id : null,
                    charges : null,
                    type: 0,
                    is_active: true,
                    admin_id:'',
                }),
                slabs: {},
                editMode: false,
                categoryId: null,
                category:null,
            }
        },
        methods :{
            createSlab(){
                this.$Progress.start();
var a =$('#authid').val();
this.form.admin_id=a;
                this.form.post('/api/category/'+ this.categoryId +'/slab').then( ()=>{
                    Fire.$emit('LoadSlab');
                    $('#slabNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Slab Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadSlabList();
            },
            updateSlab(){
                this.$Progress.start();
                this.form.put('/api/category/'+ this.categoryId +'/slab/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadSlab');
                    $('#slabNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Slab Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadSlabList() {
                var a =$('#authid').val();
                axios.get('/api/category/'+ this.categoryId +'/slab').then( ({ data }) => (this.slabs = data) );
            },
            deleteSlab(id){
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
                        this.form.delete('/api/category/'+ this.categoryId +'/slab/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Sub Category has been deleted.',
                            'success'
                            );


                            Fire.$emit('LoadSlab');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Sub Category can't  be deleted.`,
                            'danger'
                            )


                            Fire.$emit('LoadSlab');
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.form.category_id = this.categoryId;
                this.editMode = false;
            },
            editForm(data){
                this.form.reset();
                this.form.fill(data);
                this.form.image= '';
                this.editMode = true;
            },
            changeActiveStatus(slab){
                console.log(slab);
                this.editForm(slab);
                this.form.is_active = !this.form.is_active ;
                this.updateSlab();
            },
            loadCategory() {
                axios.get("/api/category/"+this.categoryId).then( ({ data }) => (this.category = data) );
            },
        },
        mounted() {
            console.log('Component mounted.');
            this.categoryId = this.$route.params.category;
            this.loadSlabList();
            this.loadCategory();
        },
        created(){
            Fire.$on('LoadSlab', () => this.loadSlabList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 3vh;
    max-height : 3vh
}
</style>
