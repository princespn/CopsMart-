<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Admin Categories</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Categories List</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal"
                                data-target="#categoryNew"> <i class="fa fa-category-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Total Sub-Categories</th>
                                <th>On / Off</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                            

                            <tr v-for="(category, index) in categories" :key="category.id">
                                <td>{{(1+index)}}</td>
                                <td>{{ category.name | upText }}</td>
                                <td><router-link :to="getCatLink(category)" class="btn btn-primary btn-sm">{{ category.count}}</router-link></td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+category.id"  :checked="category.is_active" @change="changeActiveStatus(category)">
                                        <label class="custom-control-label" :for="'onOff'+category.id" ></label>
                                    </div>
                                </td>
                                <td> <img :src="getImageUrl(category)" alt="" class="img img-responsive" ></td>
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
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-category"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Super-Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="editMode ? updateCategory() : createCategory()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" v-model="form.name"
                                                placeholder="Category Name"
                                                :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Image</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control" @change="fileSelected"
                                                id="staticEmail" :class="{ 'is-invalid': form.errors.has('image') }">
                                            <has-error :form="form" field="image"></has-error>
                                        </div>
                                    </div>
                                   
                                   

                                   
                                     <div class="form-row" v-if="!editMode">
                                      <div class="form-group col-md-12">
                                        <div class="table-responsive">  
                                          <table class="table table-bordered" id="app" >
                                                <thead>
                                                    <tr>
                                                      <th class="col-xs-5">Sub-Category Name <span class="text-danger"></span></th>
                                                      <th class="col-xs-1">
                                                          <div>
                                                              <button title="Add row" type="button" @click="addRow" class="btn btn-success btn-sm"><b>Add Sub-Category</b></button>
                                                          </div>
                                                      </th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                <tr v-for="(input, index) in inputs"> 
                                                    <td>
                                                      <input type="text" name="subcategory_name[]" class="form-control subcategory_name" placeholder="Sub-Category Name">
                                                    </td>
                                                    <td>
                                                        <button title="Delete row" type="button" @click="deleteRow(index)" class="btn btn-danger btn-sm"><b><i class="fa fa-trash"></i></b></button>
                                                    </td>
                                                </tr>
                                               </tbody>
                                          </table>
                                        </div>
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
                    id:'',
                    name:'',
                    image: '',
                    is_active: true,
                    subcategory_name:[],
                    
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
                var subcategory_name = [];
               
                $(".subcategory_name").each(function(){ subcategory_name.push($(this).val())});
                this.form.subcategory_name=subcategory_name;
                

                this.form.post('api/admin_category').then( ()=>{
                    Fire.$emit('LoadCategory');
                    $('#categoryNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Category Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadCategoryList();
            },
            updateCategory(){
                this.$Progress.start();
                this.form.put('api/admin_category/' + this.form.id).then( ()=>{
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
                axios.get("api/admin_category_all").then( ({ data }) => (this.categories = data) );
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
                        console.log(result)
                    if(result.value){
                        this.form.delete('/api/admin_category/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Category has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadCategory');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Category can not  be deleted.',
                            'danger'
                            )
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.form.discount_percentage=0;
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
                return category.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/public/uploads/images/admin_category/' + category.image;
            },
            loadCommodityList() {
                axios.get("api/commodity_type").then( ({ data }) => {
                    this.commodities = data;
                }).catch( err => console.log(err));
            },
            
            addRow() {
              this.inputs.push({
                one: '',
                two: ''
              })
            },
            deleteRow(index) {
              this.form.subcategory_name.splice(index,1);
              this.inputs.splice(index,1);
            },
            
            getCatLink(category){
                return `/admin_category/${category.id}/subcat`;
            }
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
