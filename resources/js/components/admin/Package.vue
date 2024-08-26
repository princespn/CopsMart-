<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Package List</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Package List</h3>

                        <div class="card-tools">
                           
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#packageNew"> <i class="fa fa-slab-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tr v-for="(mstpackage, index) in mstpackage" :key="'sc'+index">
                                <td>{{(1+index)}}</td>
                                <td>{{ mstpackage.name }}</td>
                                <td>
                                    <button @click="editForm(mstpackage)"  data-toggle="modal" data-target="#packageNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deletePackage(mstpackage.id)"  class="btn btn-danger btn-sm ani">
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
        <div class="modal fade" id="packageNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-slab"></i> {{ editMode ? 'Edit' : 'Add'}} Package</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updatePackage() : createPackage()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Name</label>
                                <div class="col-sm-10">
                                    <input type="charges" min="0" class="form-control"  v-model="form.name" placeholder="Package Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
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
                    name :null,
            admin_id:'',
                    
                }),
                mstpackage: {},
                editMode: false,
            }
        },
        methods :{
            createPackage(){
                this.$Progress.start();
var a =$('#authid').val();
this.form.admin_id=a;
                this.form.post('/api/mst_package').then( ()=>{
                    this.loadPackageList();
                    $('#packageNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Package Created successfully'
                    });
                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });
            },
            updatePackage(){
                this.$Progress.start();
                this.form.put('/api/mst_package/' + this.form.id).then( ()=>{
                    this.loadPackageList();
                    $('#packageNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Package Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadPackageList() {
                var a =$('#authid').val();
                axios.get('/api/mst_package?ad='+a).then( ({ data }) => (this.mstpackage = data) );
            },
            deletePackage(id){
                // sweet alert modal
              //  var a =$('#authid').val();
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
                        this.form.delete('/api/mst_package/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Package has been deleted.',
                            'success'
                            );
                            this.loadPackageList();
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Package can't  be deleted.`,
                            'danger'
                            )
                            this.loadPackageList();
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
            loadPackage() {
                axios.get("/api/mst_package").then( ({ data }) => (this.mstpackage = data) );
            },
        },
        mounted() {
            console.log('Component mounted.');
          //  var a =$('#authid').val();
          //  if(a!=1){alert('s');$('button').css('display','none');}
            this.loadPackageList();
            this.loadPackage();
        },
        created(){
            Fire.$on('LoadPacakge', () => this.loadPackageList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 3vh;
    max-height : 3vh
}
.dnone{display:none!important;}
</style>
