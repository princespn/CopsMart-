<template>
    <div class="container p-0">
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-3">Tags List</h3>

                        <div class="card-tools">
                            
                            <button type="button" @click="newtForm" class="btn btn-primary btn-sm mr-3" data-toggle="modal"
                                data-target="#categoryNew"> <i class="fa fa-plus"></i> Add Tag</button>
                           
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-2">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Activate / Deactivate</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                           

                            <tr v-for="(category, index) in categories" :key="category.id">
                                <td>{{(1+index)}}</td>
                                <td>{{ category.name | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+category.id"  :checked="category.is_active" @change="changeActiveStatus(category)">
                                        <label class="custom-control-label" :for="'onOff'+category.id" ></label>
                                    </div>
                                </td>
                                 <td>{{ category.type | upText }}</td>
                                <td>
                                    <button @click="editForm(category)"  data-toggle="modal" data-target="#categoryNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteCategory(category.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div><!-- /.row -->


                <!-- Modal -->
                <div class="modal fade" id="categoryNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-category"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Brand</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="editMode ? updateCategory() : createCategory()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" style="text-transform: capitalize;" class="form-control" v-model="form.name"
                                                placeholder="Category Name"
                                                :class="{ 'is-invalid': form.errors.has('name') }">
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
                 <!-- Modal -->
              
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
                    type: '',
                    is_active: true,
                    vendor_id:'',
                }),
                categories: {},
                multipartForm : new FormData,
                commodities: [],
                inputs: [],
                editMode: false,
            }
        },
        methods :{
            createCategory(){
                this.multipartForm = new FormData;
                this.$Progress.start();
                var a =$('#authid').val();
                this.form.vendor_id=a;
                this.form.post('api/attribute').then( ()=>{
                    Fire.$emit('LoadCategory');
                    $('#categoryNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Category Created Successfully'
                    });
                    // setTimeout(()=>{
                    // window.location.reload();
                    // }, 1000);
                    this.$Progress.finish();
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });

                // this.loadCategoryList();
            },
            updateCategory(){
                this.$Progress.start();
                this.form.put('api/attribute/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadCategory');
                    $('#categoryNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },

            loadCategoryList() {
                var a =$('#authid').val();
                axios.get("api/attribute_data/"+a).then( ({ data }) => (this.categories = data) );
            },
            
            deleteCategory(id){
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
                    if(result.value)
                    {
                        this.form.delete('/api/attribute/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Category has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadCategory');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Category can't  be deleted.`,
                            'danger'
                            )
                        })
                    }
                })
            },
            

            newForm(){
                this.form.reset();
                this.form.type='attribute';
                this.editMode = false;
            },
            newtForm(){
                this.form.reset();
                this.form.type='tag';
                this.editMode = false;
            },
            editForm(data){
                this.form.reset();
                this.form.fill(data);
                this.form.image = null;
                this.editMode = true;
            },
            fileSelected(e){
                console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        // console.log('RESULT converted base 64');
                        this.form.image=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }

            },
           
            changeActiveStatus(category){
                this.editForm(category);
                this.form.is_active = !this.form.is_active ;
                this.updateCategory();
            },
            getImageUrl(category){
                return category.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/public/uploads/images/brnad/' + category.image;
            },
           



           



        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadCategoryList();
            Fire.$on('LoadCategory', () => this.loadCategoryList() );
        }
    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
