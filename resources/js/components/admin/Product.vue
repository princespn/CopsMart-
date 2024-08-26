<template>
    <div class="container p-0">
        <!-- /.row -->
       
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
   

                        <div class="row">
                          <div class="col-md-2">
                                <h3 class="card-title ml-2">Product List</h3>
                          </div>
                          <div class="col-md-7"></div>
                          <div class="col-md-3">
                            <button  @click="newForm" data-toggle="modal" data-target="#categoryNew" class="btn btn-primary btn-sm">
                                Bulk Add
                            </button>
                            <router-link to="/addproduct" class="nav-link" style="display:inline-block!important;"><a type="button" class="btn btn-sm btn-primary mr-3"><i class="fa fa-plus"></i> Add Product </a></router-link>
                          </div>
                         



                          <!-- <div class="col-md-3">
                            <a type="button" class="btn btn-sm btn-success mr-3 mt-1"  > <i class="fa fa-plus"></i> Transfer</a>
                          </div> -->
                       </div>
                    </div>
                    
                      <div class="card-header">
                        <div class="row">

                            <div class="col-md-3 ml-2">
                            <label class="ncol-form-label">Category</label>
                                <select class="form-control-sm" v-model="cata_id" placeholder="Select Category" v-on:change="getfCategory(this)">
                                    <option value="All">All Categories</option>
                                    <option v-for="category of Categories" :key="category.id" :value="category.id">{{category.name}}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="ncol-form-label">Sub-Category</label>
                                <select class="form-control-sm" v-model="sub_cata_id" placeholder="Select Category" v-on:change="getSubCategory(this)">
                                    <option value="All">All Sub-Categories</option>
                                    <option v-for="subCategory of supSubCategories" :key="subCategory.id" :value="subCategory.id" :selected="checkfSelected(subCategory)" >{{subCategory.name}}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="ncol-form-label">Sub Sub Category</label>
                                <select class="form-control-sm" v-model="sub_sub_cata_id" placeholder="Select Category" :class="{ 'is-invalid': errors.sub_sub_category_id }" >
                                     <option value="All">All Sub-Sub-Categories</option>
                                    <option v-for="subsubCategory of subsubCategories" :key="subsubCategory.id" :value="subsubCategory.id" :selected="checksSelected(subsubCategory)" >{{subsubCategory.name}}</option>
                                </select>
                            </div>
                            <div class="col-md-1 text-left">
                              <button type="button" @click="getUrl" class="btn btn-success btn-sm">Submit</button>
                            </div>
                           
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive table-bordered p-3">
                       

                        <data-table
                            :classes = "tableClasses"
                            :url="this.url"
                            :columns="columns"
                            
                            :per-page="perPage">
                        </data-table>
                       </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div><!-- /.row -->
      <orders />

        <!-- Modal -->
      
 <!-- Modal -->
 <div class="modal fade" id="categoryNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-category"></i>
                                    Add Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="updateloadCsv()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <div class="col-sm-7"></div>
                                        <div class="col-sm-5">
                                            <a class="btn btn-sm btn-primary mb-2" href="/images/product.csv" download>Dummy</a>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Csv File</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control" @change="fileSelected"
                                                id="staticEmail" :class="{ 'is-invalid': form.errors.has('csv') }">
                                            <has-error :form="form" field="csv"></has-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Upload CSV </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                 <!-- Modal -->
    </div>
</template>

<script>
    import CImage from '../common/CImage.vue';
    import CBtn from '../common/CBtn.vue';
    import GetOrder  from './GetOrder';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import $ from "jquery";
    import "vue-select/dist/vue-select.css";
import { log } from 'util';
    export default {
        components:{  deleteRow(i) {
               this.rowData.splice(i,1);
            },
            'orders': GetOrder,
            CImage,
            CBtn,
            CToggle,
            'v-select': vSelect,
        },
        data() {
            return {
                rowId: 1,
                rowData:[{val:"1"}],                
                types : [
                    {
                        "id": "normal",
                        "value": "Normal Product"
                    },
                    {
                        "id": "f&b",
                        "value": "Fashion and Electronics Product"
                    },
                ],
                selected: [],
                options: [],
                perPage: ['50', '100', '150', '200', '500',],
                columns: [
                    {
                        label: 'SR. No',
                        name: 'srno',
                        filterable: true,
                    },
                    {
                        label: 'Name',
                        name: 'name',
                        filterable: true,
                    },
                    {
                        label: 'Size',
                        name: 'sizename',
                        filterable: true,
                    },
                    {
                        label: 'Color',
                        name: 'colorname',
                        filterable: true,
                    },
                    {
                        label: 'MRP',
                        name: 'mrp',
                        filterable: true,
                    },
                    {
                        label: 'Barcode',
                        name: 'barcode',
                        filterable: true,
                    },
                    {
                        label: 'HSN',
                        name: 'hsn',
                        filterable: true,
                    },
                    {
                        label: ' GST %',
                        name: 'gst',
                        filterable: true,
                    },
                    {
                        label: 'Image',
                        component: CImage,
                        filterable: false,
                        meta:{
                            url : '/public/uploads/images/product/',
                        }
                    },
                    {
                        label: 'Activate / Deactivate',
                        component: CToggle,
                        filterable: false,
                        meta:{
                            field: 'is_active',
                            action: this.changeActiveStatus

                        }
                    },
                    
                    {
                        label: 'Actions',
                        component: CBtn,
                        class:'nowrap',
                        filterable: false,
                        meta:{
                            multiple: true,
                            btnList :[
                                {
                                    // label: 'Edit',
                                    classes : 'btn-sm btn-primary mr-1 fas fa-edit',
                                    action: this.getEdit,
                                    // additionalAttributes : {'data-toggle':"modal", 'data-target':"#productNew" },
                                },
                                {
                                    // label: 'Edit',
                                    classes : 'btn-sm btn-success mr-1 fas fa-eye',
                                    action: this.getview,
                                    // additionalAttributes : {'data-toggle':"modal", 'data-target':"#productNew" },
                                },
                                

                                {
                                    // label: 'Delete',
                                    classes : 'btn-sm btn-danger fa fa-trash',
                                    action: this.deleteProduct,
                                },
                            ]

                        }
                    },
                ],
                tableClasses:{
                    "table-container": {
                        "table-responsive": true,
                    },
                    "table": {
                        "table": true,
                        "table-striped": true,
                        "table-dark": false,
                    },
                    
                    
                },
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
                    selectedcolor:[],
                    selectedsize:[],
                    attributex:'',
                    attributesub:'',
                    is_active: true,
                    is_featured:false,
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
                    tags: [],
                    images:[],
                    selected:[],
                    selectedcolor:[],
                    selectedsize:[],
                    attributex:[],
                    attributesub:[],
                    is_active: true,
                    is_featured:false,
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
                    selectedcolor:[],
                    selectedsize:[],
                    attributex:'',
                    attributesub:'',
                    is_active: true,
                    is_featured:false,
                    
                },
                errors:{},
                multipartForm: new FormData,
                products: [],
                supSubCategories: {},
                subsubCategories:{},
                subCategories: {},
                coSubCategories: {},
                Categories: {},
                Sizeww:{},
                Colorww:{},
                editMode: false,
                scatFilter: null,
                filtered: [],
                attachments: [],
                commodities: [],
                product_packages: [],
                isLoading: true,
                url:'',
                cata_id:'',
                sub_cata_id:'',
                sub_sub_cata_id:'',
                sform: new Form({
                        csv :'',
                        vendor_id:'',
                    }),
                multipartForm: new FormData,
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
                //console.log(obj);
                $(obj).closest('tr').remove();
            },
            
            getId()
            {
              return this.rowId=parseFloat(this.rowId)+parseFloat(1);
            },
            floadCategoryList() {
                var a =$('#authid').val();
                axios.get("/api/category_all_data?a="+a).then( ({ data }) => (this.Categories = data) );
            },
            getfCategory(){
                var cat_id = this.cata_id;
                axios.get("/api/sub_category_by_cat/"+cat_id).then(data=>{
                   this.supSubCategories = data.data.sub_category;
                }); 
            },
            checkfSelected(subCategory){
                return subCategory.id == this.sub_cata_id
            }, 
            checksSelected(subCategory){
                return subCategory.id == this.sub_sub_cata_id
            },

            


          changeItem: function changeItem(rowId, event)
          { 
            var id=event.target.value;
          },
           

            updateProduct(){
                this.$Progress.start();
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };

                
                for (let x in this.iform){
                    this.multipartForm.append(x, this.iform[x]);
                }

                
                //this.multipartForm.append('package_id', this.package_id);
                axios.post('api/product_update/' + this.iform.id, this.multipartForm, config).then( ()=>{
                    Fire.$emit('LoadProduct');
                    $('#productNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Product Updated successfully'
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
            },
            
 
            deleteProduct(product){
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
                        this.form.delete('/api/product/'+product.id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Product has been deleted.',
                            'success'
                            );
                            setTimeout(()=>{

                    window.location.reload();
                    }, 1000);
                            Fire.$emit('LoadProduct');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Product can't  be deleted.`,
                            'danger'
                            )
                        })
                    }
                })
            },
            newForm()
            {

            },
            editForm(data){
                this.multipartForm = new FormData;
                this.errors = {};
                this.iform =this.blank;
                this.iform = data;
                this.editMode = true;
            },
            getSubCategory(){
                var sub_cat_id = this.sub_cata_id;
               // alert(sub_cat_id);
                //get subcategory
                axios.get("api/category_by_subcat/"+sub_cat_id).then(data=>{

                   this.subsubCategories = data.data;
                }); 
                
            },

            changeActiveStatus(product){
                this.multipartForm = new FormData;
               // console.log(product);
                this.editForm(product);
                this.iform.is_active = this.iform.is_active == 1  ? 0 : 1 ;
                this.updateProduct();
            },

            changeRecommendedStatus(product){
                this.multipartForm = new FormData;
                console.log(product.is_featured);
                this.editForm(product);
                this.iform.is_featured = this.iform.is_featured == 1  ? 0 : 1 ;
                this.updateProduct();
            },
            getImageUrl(product){
                return product.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/uploads/images/product/' + product.image;
            },

            getUrl()
            {    var a=$('#authid').val();
                 this.url='/api/product_datatable/'+a+'/cat/'+this.cata_id+'/subcat/'+this.sub_cata_id+'/subsub/'+this.sub_sub_cata_id;
                 return this.url;
            },

            getEdit(order)
            {   
                var a=order.id;
                 location.href ='editProduct/'+a;
            },

            getview(order)
            {   
                var a=order.id;
                location.href ='ViewProduct/'+a;
            },

            updateloadCsv()
            {
                    var a =$('#authid').val();
                    this.sform.vendor_id = a;
                    this.$Progress.start();
                    for (let x in this.sform){
                        this.multipartForm.append(x, this.sform[x]);
                    }
                    const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                    axios.post('/api/productcsv', this.multipartForm, config).then( (data)=>{
                        if(data.data.resid==200){
                        toast.fire({
                            type: 'success',
                            title: data.data.message
                        });
                    }else
                    {
                        toast.fire({
                            type: 'success',
                            title: data.data.message
                        });
                    }
                        setTimeout(()=>{
                        window.location.reload();
                        }, 2000);
                        this.$Progress.finish();
                    }).catch((data)=>{
                        this.errors =data.response.data.errors;
                        console.log('some error',data.response.data.errors);
                        this.$Progress.fail();
                    });

                    // this.loadProductList();
                
  },
  
  
      fileSelected(e){
          console.log('file slected', e);
          if(e.target.files != 'undefined' && e.target.files.length > 0 )
          {
              for(let i=0;i<e.target.files.length;i++){
                  this.multipartForm.append('csv', e.target.files[i]);
                   //console.log('file slected222222',e.target.files[i]);
              }
          }
      },
           
            loadproductlist() {
                var a =$('#authid').val();
                axios.get("api/productall?ad="+a).then(data=>{
                   this.products = data.data
                });

            },

        },
        mounted() {
            this.isLoading = true;
            console.log('Component mounted.');  
        },
        created(){
            console.log('Component created.');  
            this.cata_id='All';
            this.sub_cata_id='All';
            this.sub_sub_cata_id='All';
            // this.loadPackage();
            this.isLoading=true;
            this.floadCategoryList();
            this.getUrl();
            Fire.$on('LoadProduct', () => this.getUrl() );
        }


    }
    
    
</script>

<style scoped>
img{
    max-width : 3vh;
    max-height : 3vh
}
.disnone{display: none!important;}
table input, table select{width: 85%!important;}
table .btn-sm, .btn-group-sm > .btn {
    padding: 0.25rem 0.25rem!important;}
    thead{text-align:center!important;}
    thead td{text-align:center!important;white-space:nowrap!important;}
</style>
