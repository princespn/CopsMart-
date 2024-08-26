<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Admin Sub-Category List</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sub-Category List - {{ category !=null ? category.name : ''}}</h3>

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
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tr v-for="(slab, index) in slabs" :key="'sc'+index">
                                <td>{{(1+index)}}</td>
                                <td>{{ slab.name }}</td>
                                <td> <img :src="getImageUrl(slab)" alt="" class="img img-responsive" ></td>
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
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-slab"></i> {{ editMode ? 'Edit' : 'Add'}} Sub-Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateSlab() : createSlab()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Name</label>
                                <div class="col-sm-10">
                                    <input type="charges" min="0" class="form-control"  v-model="form.name" placeholder="Category Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Image</label>
                                <div class="col-sm-10">
                                    <input type="file" @change="fileSelected" id="image" :class="{ 'is-invalid': form.errors.has('image') }">
                                    <has-error :form="form" field="image"></has-error>
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
                    image :null,
                    parent_id : null,
                    is_active: true
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

                this.form.post('/api/admin_category/'+ this.categoryId +'/subcat').then( ()=>{
                    Fire.$emit('LoadSlab');
                    $('#slabNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Category Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadSlabList();
            },
            updateSlab(){
                this.$Progress.start();
                this.form.put('/api/admin_category/'+ this.categoryId +'/subcat/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadSlab');
                    $('#slabNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Category Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
             fileSelected(e){
                console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.form.image=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }

            },
            loadSlabList() {
                axios.get('/api/admin_category/'+ this.categoryId +'/subcat').then( ({ data }) => (this.slabs = data) );
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
                        this.form.delete('/api/admin_category/'+ this.categoryId +'/subcat/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Category has been deleted.',
                            'success'
                            );

                            Fire.$emit('LoadSlab');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Category can not  be deleted.',
                            'danger'
                            )

                            Fire.$emit('LoadSlab');
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.form.parent_id = this.categoryId;
                this.editMode = false;
            },
            getImageUrl(slab){
                return slab.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/public/uploads/images/admin_sub_category/' + slab.image;
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
                axios.get("/api/admin_category/"+this.categoryId).then( ({ data }) => (this.category = data) );
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
