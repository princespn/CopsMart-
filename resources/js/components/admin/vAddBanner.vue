<template>
    <div class="container p-0">
        <!-- /.row -->
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"> 
                        <h3 class="card-title ml-3"> Add Top Banner </h3>
                    </div> 
                    <!-- /.card-header-->
                    <div class="card-body p-3">
                         <form @submit.prevent="createProduct()">
                          <div class="form-group row">
                               <div class="col-sm-4">
                                    <label class="col-form-label">Banner Title</label>
                                    <input type="text"  class="form-control"  v-model="iform.title" placeholder="" :class="{ 'is-invalid': errors.title }">

                                </div>
                                <div class="col-sm-3">
                                    <label class="col-form-label">Banner Type</label>
                                
                                    <select class="form-control" v-model="iform.type" placeholder="Select Type" :class="{ 'is-invalid': errors.type }" v-on:change="getTypeselected(this)">
                                        <option value="Product">Product</option>
                                        <option value="Category">Category</option>
                                        <option value="Brand">Brand</option>
                                    </select>
                                    
                                </div>
                                 
                          </div>
                         

                         <!-- hide coloums -->
                            <div class="form-group row prorow disnone">
                                <div class="col-sm-5">
                                    <label class="col-form-label">Select Product </label>
                                    <v-select   v-model="iform.product_idx"  :options="options" label="name" @search="setSelectedx"   @input="getProductx" :class="{ 'is-invalid': errors.product_id }"/>
                                </div>
                            </div>
                                    <div class="form-group row catrow disnone">
                                        <div class="col-sm-12 ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="col-form-label">Category</label>
                                                    <select class="form-control" v-model="iform.category_id" placeholder="Select Category" :class="{ 'is-invalid': errors.category_id }" v-on:change="getSuperSubCategory(this)">
                                                        <option v-for="cat of Categories" :key="cat.id" :value="cat.id" :selected="checkSelected(cat)" >{{cat.name}}</option>
                                                    </select>
                                                    <span class="label label-danger" v-if="errors.category_id">{{errors.category_id}} </span>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="col-form-label">Sub Category</label>
                                                    <select class="form-control" v-model="iform.sub_category_id" placeholder="Select Category" :class="{ 'is-invalid': errors.sub_category_id }" v-on:change="getSubCategory(this)">
                                                        <option v-for="subCategory of supSubCategories" :key="subCategory.id" :value="subCategory.id" :selected="checkSelected(subCategory)" >{{subCategory.name}}</option>
                                                    </select>
                                                    <span class="label label-danger" v-if="errors.sub_category_id">{{errors.sub_category_id}} </span>
                                                </div>
                                               <div class="col-sm-4">
                                                    <label class="col-form-label">Sub Sub Category</label>
                                                    <select class="form-control" v-model="iform.sub_sub_category_id" placeholder="Select Category" :class="{ 'is-invalid': errors.sub_sub_category_id }" >
                                                        <option v-for="subsubCategory of subsubCategories" :key="subsubCategory.id" :value="subsubCategory.id" :selected="checkSelected(subsubCategory)" >{{subsubCategory.name}}</option>
                                                    </select>
                                                    <span class="label label-danger" v-if="errors.sub_sub_category_id">{{errors.sub_sub_category_id}} </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row brandrow disnone">
                                      <div class="col-sm-4">
                                        <label class="col-form-label">Select Brand</label>
                                        <select class="form-control" v-model="iform.brand_id" placeholder="Select Vendor" :class="{ 'is-invalid': form.errors.has('brand_id') }" >
                                            <option v-for="cat of brands" :key="cat.id" :value="cat.id" :selected="checkSelected(cat)" >{{cat.name}}</option>
                                        </select>
                                        <span class="label label-danger" v-if="errors.brand_id">{{errors.brand_id}} </span> 
                                      </div>
                                    </div>
                         <!-- end -->
                          
                          <div class="form-group row">

                               <div class="col-sm-4">
                                   
                                    <label class="col-form-label text-center">Till Date</label>
                                    <input class="form-control"  type="date" v-model="iform.till_date" :class="{ 'is-invalid': errors.till_date }">
                                   
                                
                              </div>
                              <div class="col-sm-4">
                                   
                                    <label class="col-form-label text-center">Image</label>
                                    <input ref="fileInput1" class="form-control" @change="fileSelected" type="file" @input="pickFile1" :class="{ 'is-invalid': errors.image }">
                                    <span class="label label-danger" v-if="errors.image">{{errors.image}} </span>
                                
                              </div>
                              <div class="col-sm-4">
                                   <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage1})` , 'background-repeat': 'no-repeat' }"
                                      @click="selectImage1">
                                    </div>
                              </div>
                          </div>
                            
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <!-- <div class="text-center"> -->
                            <button class="btn btn-danger text-center mt-3 mr-3">Reset</button>
                        <!-- </div> -->
                        <!-- <div class="col-sm-6"> -->
                            <button class="btn btn-primary text-center mt-3">Submit</button>
                            <!-- </div> -->
                        </div>
                    </div>
                 
                    </form>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>


         <br><br>
        <!-- Modal -->
         <orders />
       </div>
        </div>
        <hr>
          
         </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import GetOrder  from './GetOrder';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
import { log } from 'util';
    export default {
        
        components:{  
            CImage,
            CBtn,
            'orders': GetOrder,
            CToggle,
             'v-select': vSelect,
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
                errors:{},
                multipartForm: new FormData,
                products: [],
                supSubCategories: {},
                subsubCategories:{},
                subCategories: {},
                Categories: {},
                brands: {},
                options: [],
                Vendors:{},
                previewImage1:'',
            }
        },
        methods :{
           
            loadBrandList() {
                var a =$('#authid').val();
                axios.get("api/brand_data/"+a).then( ({ data }) => (this.brands = data) );
            },

            createProduct(){
                var a =$('#authid').val();
                this.$Progress.start();
                // this.multipartForm = new FormData;
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.iform.vendor_id = a;
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }
                axios.post('api/AddTopBanner', this.multipartForm, config).then( ()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Banner Created successfully'
                    });
                    setTimeout(()=>{
                    window.location.reload();
                    }, 1000);
                    this.$Progress.finish();
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
            },

           //aniket new
            getTypeselected(id)
            {
              var id=this.iform.type;
              if(id=='Product')
              {    
               $('.prorow').addClass('dsnone');
               $('.catrow').removeClass('dsnone');
               $('.catrow').addClass('disnone');; 
               $('.brandrow').removeClass('dsnone');
               $('.brandrow').addClass('disnone');
                    this.iform.category_id='';
                    this.iform.sub_category_id='';
                    this.iform.sub_sub_category_id= '';
                    this.iform.brand_id= '';
                       this.supSubCategories= {};
                 this.subsubCategories={};
                 this.subCategories= {};
                this. Categories= {};
              }
              else if(id=='Category')
              {  
                $('.prorow').removeClass('dsnone');
                $('.prorow').addClass('disnone');
                $('.catrow').removeClass('disnone');
                $('.catrow').addClass('dsnone');;
                $('.brandrow').removeClass('dsnone');
                $('.brandrow').addClass('disnone');;
                this.loadCategoryList();
                this.iform.brand_id= '';
                this.iform.product_id= '';
                this.options=[];
              }
              else if(id=='Brand')
              { 
                $('.prorow').removeClass('dsnone');  
                $('.prorow').addClass('disnone');
                $('.catrow').removeClass('dsnone');  
                $('.catrow').addClass('disnone');;
                $('.brandrow').removeClass('disnone');
                $('.brandrow').addClass('dsnone');;
                this.loadBrandList();
                this.iform.category_id='';
                this.iform.sub_category_id='';
                this.iform.sub_sub_category_id= '';
                this.iform.product_id= '';
                this.options=[];
                this.supSubCategories= {};
                this.subsubCategories={};
                this.subCategories= {};
                this. Categories= {};
              }
            },

            setSelectedx(barcode)
            {   
                // console.log('aaaaaaaaa');
                if(barcode.length>0)
                {
                    var a =$('#authid').val();
                       axios.get("api/some/"+a+"/getproDetails/"+barcode).then(data=>{
                       this.options = data.data;        
                    });
                }
            },

            getProductx()
            {  
               this.iform.product_id=this.iform.product_idx.id;
                console.log(this.iform.product_id);
            },

           


            // aaaa


          
            newForm()
            {
                this.errors = {};
                this.iform =this.blank;
                this.editMode = false;
                this.multipartForm = new FormData;
                this.supSubCategories = {};
                this.subCategories = {};
                this.Categories = {};
            },
            
            fileSelected(e){
                // console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        console.log('RESULT converted base 64');
                        this.iform.images=  reader.result;
                        // console.log(this.iform.image);
                    }
                    reader.readAsDataURL(file);
                }

            },
        
            getSuperSubCategory(){
                var cat_id = this.iform.category_id;
                axios.get("api/superSubcategory/"+cat_id).then(data=>{
                   this.supSubCategories = data.data;
                }); 
                
            },

            getSubCategory(){
                var sub_cat_id = this.iform.sub_category_id;
               // alert(sub_cat_id);
                //get subcategory
                axios.get("api/category_by_subcat/"+sub_cat_id).then(data=>{

                   this.subsubCategories = data.data;
                }); 
            },
          
            loadCategoryList() {
                var a =$('#authid').val();
                axios.get("api/supercategory/"+a).then(data=>{
                   this.Categories = data.data
                });

            },

          
            setSelected(data){
                console.log(this.selected);
            },
            checkSelected(subCategory){
                return subCategory.id == this.form.sub_Category_id
            },
            checkSelectedCategory(Category){
                return Category.id == this.form.category_id
            },
            
          

            checkSubCategory(value)
            {
                return value.sub_category_id == this.scatFilter || this.scatFilter==null;
            },
        selectImage1 () {
        this.$refs.fileInput1.click();
        },
        pickFile1 () {
        let input = this.$refs.fileInput1
        let file = input.files
        if (file && file[0]) {
        let reader = new FileReader
        reader.onload = e => {
        this.previewImage1 = e.target.result
        }
        reader.readAsDataURL(file[0])
        this.$emit('input', file[0])
        }
        },

 


           

        },
        mounted() 
        {
            this.isLoading = true;  
        },
        created(){
            Fire.$on('LoadProduct', () => this.loadProductList() );
        }
    }
   
</script>

<style scoped>
img
{
    max-width : 3vh;
    max-height : 3vh
}
.disnone{display: none!important;}
table input, table select{width: 85%!important;}
table .btn-sm, .btn-group-sm > .btn {
    padding: 0.25rem 0.25rem!important;}
</style>

<style scoped lang="scss">
.imagePreviewWrapper {
    width: 100px;
    height: 100px;
    display: block;
    cursor: pointer;
    margin: 0 auto 10px;
    background-size: contain;
    background-position: center center;
    background-repeat: no-repeat ;
    border:1px solid black;
}
.card-title {
    margin-top: 0.75rem!important;
    margin-bottom: 0px!important;
}

.disnone{display:none!important;}
.dsnone{display:block!important;}
</style>