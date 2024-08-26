<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Settlement Gateway API Settings</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Paytm - Payment Gateway Settings </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                         <form @submit.prevent="createProduct()">
                          <div class="form-group row">
                               <div class="col-sm-4">
                                    <label class="col-form-label">Key</label>
                                    <input type="text"  class="form-control"  v-model="iform.other" placeholder="" :class="{ 'is-invalid': errors.other }">
                                    <span class="label label-danger" v-if="errors.category_id">{{errors.category_id}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label class="col-form-label">Secret Key</label>
                                    <input type="text"  class="form-control"  v-model="iform.other" placeholder="" :class="{ 'is-invalid': errors.other }">
                                    
                                    <span class="label label-danger" v-if="errors.category_id">{{errors.category_id}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label class="col-form-label">Account Number</label>
                                    <input type="text"  class="form-control"  v-model="iform.other" placeholder="" :class="{ 'is-invalid': errors.other }">
                                    
                                    <span class="label label-danger" v-if="errors.category_id">{{errors.category_id}} </span>
                                </div>
                          </div>
                            
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <!-- <div class="text-center"> -->
                            <!-- <button class="btn btn-success text-center mt-3 mr-3">Edit</button> -->
                        <!-- </div> -->
                        <!-- <div class="col-sm-6"> -->
                            <button class="btn btn-primary text-center mt-3">Save</button>
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
       
       </div>
        </div>
        <hr>
          
         </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
import { log } from 'util';
    export default {
        
        components:{  
            deleteRow(i) {
               this.rowData.splice(i,1);
            },
            CImage,
            CBtn,
            CToggle,
            'v-select': vSelect,
        },
        data() {
            return {
                rowId: 10,
                rowData:[{val:"1"}],                
                types : [
                    {
                        "id": "5",
                        "value": "5 %"
                    },
                    {
                        "id": "12",
                        "value": "12 %"
                    },
                    {
                        "id": "18",
                        "value": "18 %"
                    },
                    {
                        "id": "28",
                        "value": "28 %"
                    },
                ],
                selected: [],
                options: [],
                form : new Form({
                    id:'',
                    name:'',
                    hsn: '',
                    barcode: '',
                    gst: '',
                    maxqty: '',
                    description: '',
                    weight: '',
                    brand_id: '',
                    other:'',
                    category_id:'',
                    sub_category_id:'',
                    sub_sub_category_id: '',
                    vendor_id:'',
                    tags: '',
                    images:[],
                    selected:[],
                    attributes:'',
                    attributesub:'',
                    is_active: true,
                }),
                iform:{
                    id:'',
                    name:'',
                    hsn: '',
                    barcode: '',
                    gst: '',
                    maxqty: '',
                    description: '',
                    weight: '',
                    brand_id: '',
                    other:'',
                    category_id:'',
                    sub_category_id:'',
                    sub_sub_category_id: '',
                    vendor_id:'',
                    tags: '',
                    images:[],
                    selected:[],
                    attributes:'',
                    attributesub:'',
                    is_active: true,
                },
                blank:{
                    id:'',
                    name:'',
                    hsn: '',
                    barcode: '',
                    gst: '',
                    maxqty: '',
                    description: '',
                    weight: '',
                    brand_id: '',
                    other:'',
                    vendor_id:'',
                    category_id:'',
                    sub_category_id:'',
                    sub_sub_category_id: '',
                    tags: '',
                    images:[],
                    selected:[],
                    attributes:'',
                    attributesub:'',
                    is_active: true,
                    
                },
                errors:{},
                multipartForm: new FormData,
                products: [],
                supSubCategories: {},
                subsubCategories:{},
                subCategories: {},
                coSubCategories: {},
                Categories: {},
                brands: {},
                tags: '',
                selected:[],
                attributes: [],
                editMode: false,
                scatFilter: null,
                filtered: [],
                attachments: [],
                commodities: [],
                product_packages: [],
                isLoading: false,
                previewImage1: null,
                previewImage2: null,
                previewImage3: null,
                previewImage4: null,
                previewImage5: null,
            }
        },
        methods :{
            addRow(i){
                let row = {
                    val : this.selected,
                };
                this.rowData.splice(i, 0, row);
                this.selected = '';
            },
            deleteRow(i) {
               this.rowData.splice(i,1);
            },
            removeRow(obj){
                console.log(obj);
                $(obj).closest('tr').remove();
            },
            loadBrandList() {
                var a =$('#authid').val();
                axios.get("api/brand_data/"+a).then( ({ data }) => (this.brands = data) );
            },

            loadTagList() {
                var a =$('#authid').val();
                axios.get("api/tags_data/"+a).then( ({ data }) => (this.tags = data) );
            },

            loadAttributeList() {
                var a =$('#authid').val();
                axios.get("api/attributes_data/"+a).then( ({ data }) => (this.attributes = data) );
            },

            createProduct(){
                var a =$('#authid').val();
                this.$Progress.start();
                // this.multipartForm = new FormData;
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.iform.tags = [];
                this.iform.vendor_id = a;
                // alert(this.selected);
                for (let x in this.selected){
                    this.iform.tags.push(this.selected[x].id);
                }
                var attributes=[];
                var attributesub=[];
               $(".attributes").each(function(){attributes.push($(this).val())});
                this.iform.attributes=attributes;
               
               $(".attributesub").each(function(){attributesub.push($(this).val())});
                this.iform.attributesub=attributesub;
                                
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }
                
                //console.log(this.multipartForm);
                axios.post('api/product', this.multipartForm, config).then( ()=>{
                    //Fire.$emit('LoadProduct');
                    toast.fire({
                        type: 'success',
                        title: 'Product Created successfully'
                    });
                    // setTimeout(()=>{
                    // window.location.reload();
                    // }, 1000);
                    this.$Progress.finish();
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });

                // this.loadProductList();
            },
          
            newForm(){
                this.errors = {};
                this.iform =this.blank;
                this.editMode = false;
                this.multipartForm = new FormData;
                this.supSubCategories = {};
                this.subCategories = {};
            },
            editForm(data){
                this.multipartForm = new FormData;
                this.errors = {};
                this.iform =this.blank;
                this.iform = data;
                this.iform.package = data.package_id;
                this.iform.pack_row_id = data.pack_row_id;
                this.getSuperSubCategory();
                this.getSubCategory();
                this.getCoSubCategory();
                // this.iform.sub_Category_id = data.sub_category_id;
                // this.form.image = null;
                this.editMode = true;
                // this.product_packages = data.packages;
                // axios.get("api/product_package/"+data.id).then(data=>{

                //    this.product_packages = data.data.product_package;
                // });
            },
            fileSelected(e){
                console.log('file slected', e);
                if(e.target.files != 'undefined' && e.target.files.length > 0 )
                {
                    for(let i=0;i<e.target.files.length;i++){
                        this.multipartForm.append('images[]', e.target.files[i]);
                         console.log('file slected222222',e.target.files[i]);
                    }
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

            getnameattr(data)
            {
              alert(data);
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
            
            setSelectedx(data){   
                if(data.length==0)
                {
                   
                }
                else
                {
                    console.log();
                }
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

        selectImage2 () {
        this.$refs.fileInput2.click();
        },
        pickFile2 () {
        let input = this.$refs.fileInput2
        let file = input.files
        if (file && file[0]) {
        let reader = new FileReader
        reader.onload = e => {
        this.previewImage2 = e.target.result
        }
        reader.readAsDataURL(file[0])
        this.$emit('input', file[0])
        }
        },

        selectImage3 () {
        this.$refs.fileInput3.click();
        },
        pickFile3 () {
        let input = this.$refs.fileInput3
        let file = input.files
        if (file && file[0]) {
        let reader = new FileReader
        reader.onload = e => {
        this.previewImage3 = e.target.result
        }
        reader.readAsDataURL(file[0])
        this.$emit('input', file[0])
        }
        },

        selectImage4 () {
        this.$refs.fileInput4.click();
        },
        pickFile4 () {
        let input = this.$refs.fileInput4
        let file = input.files
        if (file && file[0]) {
        let reader = new FileReader
        reader.onload = e => {
        this.previewImage4 = e.target.result
        }
        reader.readAsDataURL(file[0])
        this.$emit('input', file[0])
        }
        },

        selectImage5 () {
        this.$refs.fileInput5.click();
        },
        pickFile5 () {
        let input = this.$refs.fileInput5
        let file = input.files
        if (file && file[0]) {
        let reader = new FileReader
        reader.onload = e => {
        this.previewImage5 = e.target.result
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
            this.loadCategoryList();
            this.loadTagList();
            this.loadAttributeList();
            this.loadBrandList();
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
</style>