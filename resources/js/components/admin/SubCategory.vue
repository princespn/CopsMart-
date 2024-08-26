<template>
    <div class="container p-0">
        <!-- /.row -->
       
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3">
                                 <h4 class="card-title ml-2">Sub Sub Category List</h4>
                            </div>
                            <div class="col-md-3">
                                <label class="ncol-form-label">Category</label>
                                <select class="form-control-sm" v-model="sform.category_id" placeholder="Select Category" v-on:change="getfCategory(this)">
                                    <option value="All">All Categories</option>
                                    <option v-for="category of fcategories" :key="category.id" :value="category.id">{{category.name}}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="ncol-form-label">Sub-Category</label>
                                <select class="form-control-sm" v-model="sform.sub_category_id" placeholder="Select Category" >
                                     <option value="All">All Sub-Categories</option>
                                    <option v-for="subCategory of fsupSubCategories" :key="subCategory.id" :value="subCategory.id" :selected="checkfSelected(subCategory)" >{{subCategory.name}}</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                  <button type="button" @click="loadSub" class="btn btn-success btn-sm mr-2">Submit</button>
                            </div>
                            <div class="col-md-2">
                                <div class="card-tools">
                                    <button type="button" @click="newForm" class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#subCategoryNew"> <i class="fa fa-plus"></i> Add New</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-2">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Activate / Deactivate</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tr v-for="(subCategory, index) in subCategories" :key="'sc'+index">
                                <td>{{(1+index)}}</td>
                                <td>{{ subCategory.name | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+index" :checked="subCategory.is_active"  @change="changeActiveStatus(subCategory)">
                                        <label class="custom-control-label" :for="'onOff'+index" ></label>
                                    </div>
                                </td>
                                <td>{{ subCategory.super_category != null ? subCategory.super_category :  'Deleted Category'}}</td>
                                <td>{{ subCategory.category_name != null ? subCategory.category_name :  'Deleted Category'}}</td>
                                <td> <img :src="getImageUrl(subCategory)" alt="" class="img img-responsive"></td>
                                <td>
                                    <button @click="editForm(subCategory)"  data-toggle="modal" data-target="#subCategoryNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteSubCategory(subCategory.id)" class="btn btn-danger btn-sm">
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
        <div class="modal fade" id="subCategoryNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-subCategory"></i> {{ editMode ? 'Edit' : 'Add'}} Sub Sub Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateSubCategory() : createSubCategory()">
                        <div class="modal-body">
                             <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Category</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="form.super_category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('super_category_id') }" v-on:change="getCategory(this)">
                                        <option v-for="category of categories" :key="category.id" :value="category.id">{{category.name}}</option>
                                    </select>
                                    <has-error :form="form" field="category_id"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Sub-Category</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="form.category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('category_id') }">
                                        <option v-for="subCategory of supSubCategories" :key="subCategory.id" :value="subCategory.id" :selected="checkSelected(subCategory)" >{{subCategory.name}}</option>
                                    </select>
                                    <has-error :form="form" field="category_id"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Sub Sub Category Name</label>
                                <div class="col-sm-8">
                                    <input type="text" style="text-transform: capitalize;"  class="form-control"  v-model="form.name" placeholder="Sub Sub Category Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>
                           
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Image</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" @change="fileSelected" id="staticEmail" :class="{ 'is-invalid': form.errors.has('image') }">
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
          <orders />
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
                    image:'',
                    super_category_id : '',
                    category_id : '',
                    subcategory_name:[],
                    admin_id:'',
                    vendor_id:'',
                    is_active: true
                }),
                sform: new Form({
                    category_id:'',
                    sub_category_id:'',
                    vendor_id :'',
                }),
                subCategories: {},
                categories: {},
                supSubCategories:{},
                fcategories: {},
                fsupSubCategories:{},
                inputs: [],
                editMode: false
            }
        },
        methods :{
            createSubCategory(){
                this.$Progress.start();
                var a =$('#authid').val();
                this.form.admin_id=a;
                 this.form.vendor_id=a;
                var subcategory_name = [];
                $(".subcategory_name").each(function(){ subcategory_name.push($(this).val())});
                this.form.subcategory_name=subcategory_name;

                this.form.post('api/subCategory').then( ()=>{
                    Fire.$emit('LoadSubCategory');
                    $('#subCategoryNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'SubCategory Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadSubCategoryList();
            },
            updateSubCategory(){
                this.$Progress.start();
                this.form.put('api/subCategory/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadSubCategory');
                    $('#subCategoryNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'SubCategory Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadSub()
            {   
                var a =$('#authid').val();
                this.sform.vendor_id=a;
                this.sform.post('api/subCategoryPost').then( ({ data }) => (this.subCategories = data)).catch(()=>{
                    this.errors =data.response.data.errors;
                   // console.log('some error',data.response.data.errors);
                });
            },
            loadSubCategoryList() {
                var a =$('#authid').val();
                axios.get("/api/subCategory").then( ({ data }) => (this.subCategories = data) );
            },
            checkSelected(subCategory){
                return subCategory.id == this.form.sub_Category_id
            },
            deleteSubCategory(id){
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
                        this.form.delete('/api/subCategory/'+id).then((data) => {
                            if(data.data.status==202)
                            {
                                 swal.fire(
                                'Failed!',
                                 data.data.message,
                                'danger'
                                );
                            }
                            else
                            {
                            swal.fire(
                            'Deleted!',
                            'Sub Category has been deleted.',
                            'success'
                            );
                            }
                            Fire.$emit('LoadSubCategory');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Sub Category can't  be deleted.`,
                            'danger'
                            )
                        })
                    }
                })
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
            newForm(){
                this.form.reset();
                this.editMode = false;
                this.supSubCategories = {};
                this.inputs= [];
            },
            editForm(data){
                this.form.reset();
                this.form.fill(data);
                this.getCategory();
                this.form.image= '';
                this.editMode = true;
                this.inputs= [];
            },
            loadCategoryList() {
                var a =$('#authid').val();
                axios.get("api/category_all_data?a="+a).then( ({ data }) => (this.categories = data) );
            },
            floadCategoryList() {
                var a =$('#authid').val();
                axios.get("api/category_all_data?a="+a).then( ({ data }) => (this.fcategories = data) );
            },
            getCategory(){
                var cat_id = this.form.super_category_id;
                axios.get("api/sub_category_by_cat/"+cat_id).then(data=>{
                    console.log(data.data.sub_category);
                   this.supSubCategories = data.data.sub_category;
                }); 
            },
            getfCategory(){
                var cat_id = this.sform.category_id;
                axios.get("api/sub_category_by_cat/"+cat_id).then(data=>{
                   this.fsupSubCategories = data.data.sub_category;
                }); 
            },
            checkfSelected(subCategory){
                return subCategory.id == this.sform.sub_category_id
            },
            getSubCatLink(subCategory){
                return `/subCategory/${subCategory.id}/cat`;
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
            changeActiveStatus(subCategory){
               
                this.editForm(subCategory);
                this.form.is_active = !this.form.is_active ;
                this.updateSubCategory();
            },
            getImageUrl(subCategory){
                return subCategory.image  ? '/public/uploads/images/sub_category/' + subCategory.image : 'https://static.thenounproject.com/png/340719-200.png'  ;
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.sform.sub_category_id='All';
            this.sform.category_id='All';
            this.loadSub();
            this.loadCategoryList();
            this.floadCategoryList();
            Fire.$on('LoadSubCategory', () => this.loadSub() );
        }


    }
</script>

<style scoped>
img{
    max-width : 3vh;
    max-height : 3vh
}
</style>
