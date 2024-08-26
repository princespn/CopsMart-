<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Franchisee</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Franchisee List</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <!-- <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal"
                                data-target="#customerNew"> <i class="fa fa-customer-plus"></i> Add New</button> -->
                         <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#productNew"> <i class="fa fa-product-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive table-bordered p-3">
                        <!-- <table class="table table-hover">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>On / Off</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="(customer, index) in customers" :key="customer.id">
                                <td>{{(1+index)}}</td>
                                <td>{{ customer.name | upText }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+customer.id"  :checked="customer.is_active" @change="changeActiveStatus(customer)">
                                        <label class="custom-control-label" :for="'onOff'+customer.id" ></label>
                                    </div>
                                </td>
                                <td> <img :src="getImageUrl(customer)" alt="" class="img img-responsive" ></td>
                                <td>
                                    <button @click="editForm(customer)"  data-toggle="modal" data-target="#customerNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteCustomer(customer.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    <router-link :to="getSlabLink(customer)" class="btn btn-warning btn-sm" @click="deleteRuleFromList(index)">

                                        <i class="fa fa-motorcycle"></i>
                                    </router-link>
                                </td>
                            </tr>

                        </table> -->
                        <data-table
                            :classes = "tableClasses"
                            url="/api/Franchisee"
                            :columns="columns"
                            :per-page="perPage">
                        </data-table>
                    </div>
                </div><!-- /.row -->
    
      <!-- Modal -->
        <div class="modal fade" id="productNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-product"></i> {{ editMode ? 'Edit' : 'Add'}} Franchisee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateProduct() : createProduct()">
                        <div class="modal-body" >
                            
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text"  class="form-control"  v-model="form.name" placeholder="Franchisee Name" :class="{ 'is-invalid': errors.name }">
                                    <span class="label label-danger" v-if="errors.name">{{errors.name}} </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-8">
                                    <input type="text"  class="form-control"  v-model="form.email" placeholder="Email" :class="{ 'is-invalid': errors.email }">
                                    <span class="label label-danger" v-if="errors.email">{{errors.email}} </span>
                                </div>
                            </div>
                             <div class="form-group row" v-if="!editMode">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Password</label>
                                <div class="col-sm-8">
                                    <input type="password"  class="form-control"  v-model="form.password" placeholder="Password" :class="{ 'is-invalid': errors.password }">
                                    <span class="label label-danger" v-if="errors.password">{{errors.password}} </span>
                                </div>
                            </div>
                             <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Commission</label>
                                <div class="col-sm-8">
                                    <input type="text"  class="form-control"  v-model="form.commission" placeholder="Commission in % i.e 10 , 20" :class="{ 'is-invalid': errors.commission }">
                                    <span class="label label-danger" v-if="errors.commission">{{errors.commission}} </span>
                                </div>
                            </div>
                             <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Mobile</label>
                                <div class="col-sm-8">
                                    <input type="text"  class="form-control"  v-model="form.mobile" placeholder="Mobile No." :class="{ 'is-invalid': errors.mobile }">
                                    <span class="label label-danger" v-if="errors.mobile">{{errors.mobile}} </span>
                                </div>
                            </div>
                            <div class="form-group row" >
                                <label for="staticEmail" class="col-sm-4 col-form-label">Image</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" @change="fileSelected" multiple id="staticEmail" :class="{ 'is-invalid': errors.image }">
                                    <span class="label label-danger" v-if="errors.image">{{errors.image}} </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Category</label>
                                <div class="col-sm-8">
                                    <!-- <select class="form-control" v-model="form.category_id" placeholder="Select Category" :class="{ 'is-invalid': errors.category_id }" >
                                        <option v-for="cat of Categories" :key="cat.id" :value="cat.id"  >{{cat.name}}</option>
                                    </select> -->
                                    <v-select multiple :closeOnSelect="true" v-model="selected" :options="options" label="name" @input="setSelected"/>
                                    <span class="label label-danger" v-if="errors.category_id">{{errors.category_id}} </span>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-4 col-form-label">Service Area</label>
                                    <div class="col-sm-8">
                                        <select v-if="serviceAreas.length > 0" class="form-control" v-model="form.service_id"  :class="{ 'is-invalid': errors.service_id }" >
                                            <option v-for="area in serviceAreas" :key="area.id" :value="area.id">{{ area.name }}</option>
                                        </select>
                                        <span class="label label-danger" v-if="errors.service_id">{{errors.service_id}} </span>
                                    </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Address</label>
                                <div class="col-sm-8">
                                    <textarea  class="form-control"  v-model="form.address" placeholder="Address" :class="{ 'is-invalid': errors.address }"></textarea>
                                     <span class="label label-danger" v-if="errors.address">{{errors.address}} </span>
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
    import CImage from '../common/CImage.vue';
    import CBtn from '../common/CBtn.vue';
    import CToggle from '../common/CToggle.vue';
    import { moment } from 'moment';
    import vSelect from 'vue-select';
    import "vue-select/dist/vue-select.css";
    export default {
        components:{
            CImage,
            CBtn,
            CToggle,
             'v-select': vSelect,
        },
        data() {
            return {
                perPage: ['10', '25', '50', '100'],
                columns: [
                    // {
                    //     label: 'ID',
                    //     name: 'id',
                    //     filterable: true,
                    // },
                    {
                        label: 'Name',
                        name: 'name',
                        filterable: true,
                    },
                    {
                        label: 'Mobile',
                        name: 'mobile',
                        filterable: false,
                    },
                    {
                        label: 'Email',
                        name: 'email',
                        filterable: false,
                    },
                    {
                        label: 'On/Off',
                        component: CToggle,
                        filterable: false,
                        meta:{
                            field: 'is_active',
                            action: this.changeActiveStatus

                        }
                    },
                    {
                        label: 'Commission',
                        name: 'commission',
                        filterable: false,
                    },
                    {
                        label: 'Service Area',
                        name: 'sername',
                        filterable: false,
                    },
                    {
                        label: 'Category',
                        name: 'vendor_name',
                        filterable: false,
                    },
                    {
                        label: 'Actions',
                        component: CBtn,
                        filterable: false,
                        meta:{
                            multiple: true,
                            btnList :[
                                {
                                    label: 'Edit',
                                    classes : 'btn-sm btn-primary mr-1',
                                    action: this.editForm,
                                    additionalAttributes : {'data-toggle':"modal", 'data-target':"#productNew" },
                                },
                                // {
                                //     label: 'Delete',
                                //     classes : 'btn-sm btn-danger',
                                //     action: this.deleteProduct,
                                // },
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
                    image: '',
                    email: '',
                    mobile: '',
                    service_id: '',
                    category_id: [],
                    password:'',
                    address: '',
                    commission: '',
                    is_active: 1,
                }),
                 blank:{
                    id:'',
                    name:'',
                    image: '',
                    email: '',
                    mobile: '',
                    service_id: '',
                    category_id: [],
                    password:'',
                    address: '',
                   commission: '',
                   is_active: 1,
                },
                errors:{},
                customers: {},
                Categories: {},
                 multipartForm: new FormData,
                commodities: [],
                serviceAreas: [],
                 selected: [],
                options:[],
                editMode: false,
                currentRule:{
                    limit_start:null,
                    limit_end:null,
                    charges:null,
                }
            }
        },
        methods :{
            loadCustomerList() {
                axios.get("api/Franchisee").then( ({ data }) => (this.customers = data) );
            },
            loadCategoryList() {
                axios.get("api/fpro_category").then(data=>{
                this.Categories = data.data.pro_category
               // console.log(this.Categories);
                this.options = this.Categories;
                });
           },
             newForm(){
                this.form.reset();
                this.errors = {};
                this.form =this.blank;
                this.editMode = false;
            },
               editForm(data){
                this.selected = [];
                this.form=this.blank;
                this.form = data;
                this.form.image = '';
                //console.log(data.category_id);
                if(data.category_id!='')
                {  
                    var category_id = data.category_id.split(',');
                   var name = data.vendor_name.split(',');
                    for (let x in category_id){
                        this.selected.push({'id':category_id[x],'name':name[x]});
                       
                    }
                }
                this.editMode = true;


            },
             setSelected(data){
                console.log(this.selected);
            },
             createProduct(){
                this.$Progress.start();
               this.multipartForm = new FormData;
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.form.category_id = [];
                for (let x in this.selected){
                    this.form.category_id.push(this.selected[x].id); 
                }
                 for (let x in this.form){
                    this.multipartForm.append(x, this.form[x]);
                }
               axios.post('api/AddFranchisee', this.multipartForm, config).then( ()=>{
                    Fire.$emit('LoadCustomer');
                    $('#productNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Franchisee Created successfully'
                    });
                    //console.log('some error',data.response.data.errors);
                    setTimeout(()=>{
                    window.location.reload();
                    }, 500);
                    this.$Progress.finish();
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });

                // this.loadProductList();
            },
             updateProduct(){
                this.$Progress.start();
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.form.category_id = [];
                for (let x in this.selected){
                    this.form.category_id.push(this.selected[x].id); 
                }
                for (let x in this.form){
                    this.multipartForm.append(x, this.form[x]);
                }  
                //this.multipartForm.append('package_id', this.package_id);
                axios.post('api/updateFranchisee/' + this.form.id, this.multipartForm, config).then( ()=>{
                    Fire.$emit('LoadProduct');
                    $('#productNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Franchisee Updated successfully'
                    });
                    setTimeout(()=>{
                    window.location.reload();
                    }, 500);
                    this.$Progress.finish();                    
                }).catch((data)=>{
                    this.errors =data.response.data.errors;
                    console.log('some error',data.response.data.errors);
                    this.$Progress.fail();
                });
            },
              changeActiveStatus(product){
               // this.multipartForm = new FormData;
                this.editForm(product);
                this.form.is_active = this.form.is_active == 1  ? 0 : 1 ;
                this.updateProduct();
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
             loadServiceAreaList() {
                axios.get("api/service_area").then( ({ data }) => (this.serviceAreas = data) );
            },
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadCustomerList();
             this.loadCategoryList();
                this.loadServiceAreaList();
            Fire.$on('LoadCustomer', () => this.loadCustomerList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
