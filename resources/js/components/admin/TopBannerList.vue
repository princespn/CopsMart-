<template>
    <div class="container p-0">
        <!-- /.row -->
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2">Top Banner List</h3>

                        <div class="card-tools">
                            <a  href="/vAddBanner" class="btn btn-primary  btn-sm pl-1 mt-1 mr-3" > <i class="fa fa-plus"></i> Add Top Banner</a>   
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-2">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Activate / Deactivate</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                         

                            <tr v-for="(category, index) in categories" :key="category.id">
                                <td>{{(1+index)}}</td>
                                <td>{{ category.title | upText }}</td>
                                 <td>{{ category.type | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+category.id"  :checked="category.is_active" @change="changeActiveStatus(category)">
                                        <label class="custom-control-label" :for="'onOff'+category.id" ></label>
                                    </div>
                                </td>
                                <td> <img :src="getImageUrl(category)" alt="" class="img img-responsive" ></td>
                                 
                                <td>
                                    <!-- <button @click="editForm(category)"  data-toggle="modal" data-target="#categoryNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button> -->
                                    <button @click="deleteCategory(category.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                </td>
                            </tr>

                        </table>
                    </div>
                </div><!-- /.row -->


            
                <orders />
              
            </div>
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
                    type:'',
                    title:'',
                    product_id:'',
                    product_idx:'',
                    description: '',
                    brand_id: '',
                    category_id:'',
                    sub_category_id:'',
                    sub_sub_category_id: '',
                    vendor_id:'',
                    images:'',
                    till_date:'',
                    is_active: true,
                }),
                iform:{
                    id:'',
                    type:'',
                    title:'',
                    product_id:'',
                    product_idx:'',
                    description: '',
                    brand_id: '',
                    category_id:'',
                    sub_category_id:'',
                    till_date:'',
                    sub_sub_category_id: '',
                    vendor_id:'',
                    images:'',
                    is_active: true,
                },
                blank:{
                   id:'',
                    type:'',
                    title:'',
                    product_id:'',
                    product_idx:'',
                    description: '',
                    brand_id: '',
                    category_id:'',
                    sub_category_id:'',
                    till_date:'',
                    sub_sub_category_id: '',
                    vendor_id:'',
                    images:'',
                    is_active: true,
                },
                multipartForm : new FormData,
                editMode: false,
                categories:{},
            }
        },
        methods :{

            updateCategory(){
                this.$Progress.start();
                this.form.post('api/UpdateTopBanner/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadCategory');
                    // $('#categoryNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.title +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },

            loadCategoryList() {
                var a =$('#authid').val();
                axios.get("api/TopBannerData/"+a).then( ({ data }) => (this.categories = data) );
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
                    if(result.value)
                    {
                        this.form.post('/api/deleteTopbanner/'+id).then(() => {
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
                return category.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/public/uploads/images/banner/' + category.image;
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
