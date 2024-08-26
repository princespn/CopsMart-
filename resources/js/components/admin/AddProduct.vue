<template>
    <div class="container p-0">
        <!-- /.row -->
       
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title ml-2"> Add Item/ Product</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3">
                         <form @submit.prevent="createProduct()">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Enter Item / Product Name</label>
                                    <input type="text" style="text-transform: capitalize!important;"  class="form-control"  v-model="iform.name" placeholder="Product Name" :class="{ 'is-invalid': errors.name }">
                                    <span class="label label-danger" v-if="errors.name">{{errors.name}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Barcode</label>
                                    <input type="text"  class="form-control"  v-model="iform.barcode" placeholder="Bracode" :class="{ 'is-invalid': errors.barcode }">
                                    <span class="label label-danger" v-if="errors.barcode">{{errors.barcode}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">HSN Code</label>
                                    <input type="text" id="hsnd" class="form-control" v-on:keyup="checkhsn()"  v-model="iform.hsn" placeholder="HSN Code" :class="{ 'is-invalid': errors.hsn }">
                                    <span class="label label-danger" v-if="errors.hsn">{{errors.hsn}} </span>
                                </div>
                            </div>
                             <div class="form-group row" >
                                <div class="col-sm-4">
                                    <label class="col-form-label">GST %</label>
                                    <select v-model="iform.gst" class="form-control sl" >
                                         <option value="" disabled selected>Select GST %</option>
                                        <option v-for="type of types" :key="type.id" :value="type.id" >
                                            {{ type.value }}
                                        </option>
                                    </select>
                                    <span class="label label-danger"  v-if="errors.gst">{{errors.gst}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Max. Qty to sell</label>
                                    <input type="text" id="qtyc"  class="form-control"  v-on:keyup="checkqty()" v-model="iform.maxqty" placeholder="Max. Qty to sell" :class="{ 'is-invalid': errors.maxqty }">
                                    <span class="label label-danger" v-if="errors.maxqty">{{errors.maxqty}} </span>
                                </div>
                                
                                <div class="col-sm-4">
                                    <label class="col-form-label">Description</label>
                                    <textarea class="form-control" style="text-transform: capitalize!important;"  v-model="iform.description" :class="{ 'is-invalid': errors.description }"> </textarea>
                                    <span class="label label-danger" v-if="errors.description">{{errors.description}} </span>
                                </div>
                            </div>

                            <div class="form-group row" >
                                <div class="col-sm-4">
                                    <label class="col-form-label">Item / Product Weight</label>
                                    <input type="text" style="text-transform: capitalize!important;"  class="form-control"  v-model="iform.weight" placeholder="Item / Product Weight" :class="{ 'is-invalid': errors.weight }">
                                    <span class="label label-danger" v-if="errors.weight">{{errors.weight}} </span>
                                </div>
                                <div class="col-sm-4">
                                    <label for="staticEmail" class="col-form-label">Other Details</label>
                                    <input type="text" style="text-transform: capitalize!important;" class="form-control"  v-model="iform.other" placeholder="Other Details" :class="{ 'is-invalid': errors.other }">
                                    <span class="label label-danger" v-if="errors.other">{{errors.other}} </span>
                                </div>
                                
                                <div class="col-sm-4">
                                    <label class="col-form-label">Select Brands</label>
                                    <select class="form-control" v-model="iform.brand_id" placeholder="Select Category" :class="{ 'is-invalid': errors.brand_id }">
                                         <option value="" disabled selected>Select Brand</option>
                                        <option v-for="brand of brands" :key="brand.id" :value="brand.id"  >{{brand.name}}</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.brand_id">{{errors.brand_id}} </span>
                                </div>
                            </div>
                            <div class="form-group row">  
                                <div class="col-sm-4">
                                    <label class="col-form-label"> Select Tags</label>
                                    <v-select multiple :closeOnSelect="true" v-model="selected" :options="tags" label="name" @input="setSelected"/>
                                    <has-error :form="iform" field="vendor_id"></has-error>
                                </div>
                               
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
                            </div>

                            <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label class="col-form-label">Sub Sub Category</label>
                                    <select class="form-control" v-model="iform.sub_sub_category_id" placeholder="Select Category" :class="{ 'is-invalid': errors.sub_sub_category_id }" >
                                        <option v-for="subsubCategory of subsubCategories" :key="subsubCategory.id" :value="subsubCategory.id" :selected="checkSelected(subsubCategory)" >{{subsubCategory.name}}</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.sub_sub_category_id">{{errors.sub_sub_category_id}} </span>
                                </div>

                                <div class="col-sm-4">
                                    <label class="col-form-label"> Select Size</label>
                                    <v-select multiple :closeOnSelect="true" v-model="selectedsize" :options="Sizeww" label="name" @input="setSelected"/>
                                    <has-error :form="iform" field="vendor_id"></has-error>
                                </div>

                                <div class="col-sm-4">
                                    <label class="col-form-label"> Select Color</label>
                                    <v-select multiple :closeOnSelect="true" v-model="selectedcolor" :options="Colorww" label="name" @input="setSelected"/>
                                    <has-error :form="iform" field="vendor_id"></has-error>
                                </div>
                            </div>


                              <div class="form-group row">  
                                 <div class="col-sm-4">
                                    <label class="col-form-label">MRP</label>
                                    <input type="text" id="mrpdata"  class="allow_decimal form-control"  v-model="iform.mrp" placeholder="MRP" :class="{ 'is-invalid': errors.mrp }"  v-on:keyup="checkmrp()">
                                    <span class="label label-danger" v-if="errors.mrp">{{errors.mrp}} </span>
                                </div>
                                 <div class="col-sm-4">
                                    <label class="col-form-label">Hide Product</label>
                                    <input type="text"   class="form-control"  v-model="iform.hide" placeholder="Hide Products" :class="{ 'is-invalid': errors.hide }"  >
                                    <span class="label label-danger" v-if="errors.hide">{{errors.hide}} </span>
                                </div>

                              
                            </div>
                            <!-- ------------- -->

                             <div class="form-group row"> 
                                 <div class="col-sm-1" style="margin-left:5px!important">
                                 </div> 
                                <div class="col-sm-2" style="margin-left:5px!important">
                                    <label class="col-form-label text-center">Image1</label>
                                    <input ref="fileInput1" class="form-control" @change="fileSelected" type="file" @input="pickFile1" >
                                    <span class="label label-danger" v-if="errors.image">{{errors.image}} </span>
                                    <br/>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage1})` , 'background-repeat': 'no-repeat' }"
                                      @click="selectImage1">
                                    </div>
                                </div>
                               
                                 <div class="col-sm-2" style="margin-left:5px!important">
                                    <label class="col-form-label text-center">Image2</label>
                                     <input ref="fileInput2" class="form-control" @change="fileSelected" type="file" @input="pickFile2" >
                                    <span class="label label-danger" v-if="errors.image">{{errors.image}} </span>
                                    <br/>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage2})` }"
                                      @click="selectImage2">
                                    </div>
                                </div>

                                <div class="col-sm-2" style="margin-left:5px!important">
                                    <label class="col-form-label text-center">Image3</label>
                                    <input ref="fileInput3" class="form-control" @change="fileSelected" type="file" @input="pickFile3" >
                                    <span class="label label-danger" v-if="errors.image">{{errors.image}} </span>
                                    <br/>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage3})` }"
                                      @click="selectImage3">
                                    </div>
                                </div>

                                <div class="col-sm-2" style="margin-left:5px!important">
                                    <label class="col-form-label text-center">Image4</label>
                                    <input ref="fileInput4" class="form-control" @change="fileSelected" type="file" @input="pickFile4" >
                                    <span class="label label-danger" v-if="errors.image">{{errors.image}} </span>
                                    <br/>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage4})` }"
                                      @click="selectImage4">
                                    </div>
                                </div>

                                <div class="col-sm-2" style="margin-left:5px!important">
                                    <label class="col-form-label text-center">Image5</label>
                                    <input ref="fileInput5" class="form-control" @change="fileSelected" type="file" @input="pickFile5" >
                                    <span class="label label-danger" v-if="errors.image">{{errors.image}} </span>
                                    <br/>
                                    <div class="imagePreviewWrapper" :style="{ 'background-image': `url(${previewImage5})` }"
                                      @click="selectImage5">
                                    </div>
                                </div>
                            </div>
                            
                            <!------------------------------------->

                        <div class="row text-center"> 
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary text-center pl-5 pr-5">Submit</button></div>
                            <div class="col-md-4"></div>
                            
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
            deleteRow(i) {
               this.rowData.splice(i,1);
            },
            CImage,
            CBtn,
            CToggle,
            'orders': GetOrder,
            'v-select': vSelect,
        },
        data() {
            return {
                rowId: 10,
                rowData:[{val:"1"}],                
                types : [
                    {
                        "id": "0",
                        "value": "0 %"
                    },
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
                    tags: [],
                    images:[],
                    hide:'',
                    selected:[],
                    selectedcolor:[],
                    selectedsize:[],
                    attributex:'',
                    attributesub:'',
                    is_active: true,
                    mrp:0,
                }),
                iform:{
                    id:'',
                    name:'',
                    hsn: '',
                    barcode: '',
                    gst: '',
                    maxqty: '',
                    mrp:0,
                    description: '',
                    weight: '',
                    brand_id: '',
                    other:'',
                    category_id:'',
                    sub_category_id:'',
                    sub_sub_category_id: '',
                    vendor_id:'',
                    hide:'',
                    tags: [],
                    images:[],
                    selected:[],
                    selectedcolor:[],
                    selectedsize:[],
                    attributex:[],
                    attributesub:[],
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
                    hide:'',
                    other:'',
                    vendor_id:'',
                    category_id:'',
                    sub_category_id:'',
                    sub_sub_category_id: '',
                     tags: [],
                    images:[],
                    selected:[],
                    selectedcolor:[],
                    selectedsize:[],
                    attributex:'',
                    attributesub:'',
                    is_active: true,
                    mrp:0,
                    
                },
                errors:{},
                multipartForm: new FormData,
                products: [],
                supSubCategories: {},
                subsubCategories:{},
                subCategories: {},
                coSubCategories: {},
                Categories: {},
                Sizeww:'',
                Colorww:'',
                brands: {},
                tags: [],
                selected:[],
                selectedsize:[],
                selectedcolor:[],
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
                 options: {},
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
            loadSizeList() {
                var a =$('#authid').val();
                axios.get("api/sizeget/"+a).then( ({ data }) => (this.Sizeww = data) );
            },
            loadColorList() {
                var a =$('#authid').val();
                axios.get("api/colorget/"+a).then( ({ data }) => (this.Colorww = data) );
            },

            createProduct(){
                var a =$('#authid').val();
                this.iform.vendor_id = a;
                this.$Progress.start();
                // this.multipartForm = new FormData;
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                 
                
                // alert(this.selected);
                for (let x in this.selected){
                    this.iform.tags.push(this.selected[x].id);
                }
              
                for (let x in this.selectedsize){
                    this.iform.attributex.push(this.selectedsize[x].id);
                }
                

               // alert(this.iform.attributex);
                for (let x in this.selectedcolor){
                    this.iform.attributesub.push(this.selectedcolor[x].id);
                }
                                
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }
                
               // console.log(this.multipartForm);
                axios.post('api/product', this.multipartForm, config).then( ()=>{
                    //Fire.$emit('LoadProduct');
                    toast.fire({
                        type: 'success',
                        title: 'Product Created successfully'
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
                         //console.log('file slected222222',e.target.files[i]);
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
        checkmrp()
        {
            var self = $('#mrpdata');
            self.val(self.val().replace(/[^0-9\.]/g, ''));
            if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57))
            {
                evt.preventDefault();
            }
        },

        checkqty()
        {
            var self = $('#qtyc');
            self.val(self.val().replace(/[^0-9\.]/g, ''));
            if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57))
            {
                evt.preventDefault();
            }
        },

        checkhsn()
        {
            var self = $('#hsnd');
            self.val(self.val().replace(/[^0-9\.]/g, ''));
            if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57))
            {
                evt.preventDefault();
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
            this.loadSizeList();
            this.loadColorList();
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
</style>