<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Suggession</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Suggession List</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal"
                                data-target="#couponNew"> <i class="fa fa-coupon-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tr>
                                <th>Sr. No</th>
                                 <th>Heading</th>
                                <th>Category</th>
                                <th>On / Off</th>
                                <th>Layout</th>
                                <th>Position</th>
                                <th>Product </th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="(suggession, index) in suggession" :key="suggession.id">
                                <td>{{(1+index)}}</td>
                                <td> {{ suggession.heading }} </td>
                                 <td> {{ suggession.category_name != null ? suggession.category_name : 'NA' }} </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+suggession.id"  :checked="suggession.is_active" @change="changeActiveStatus(suggession)">
                                        <label class="custom-control-label" :for="'onOff'+suggession.id" ></label>
                                    </div>
                                </td>
                                <td> {{ suggession.layout }} </td>
                                <td> {{ suggession.position }} </td>
                                <td> {{ suggession.product_name != null ? suggession.product_name : 'NA' }} </td>
                                <td>
                                    <button @click="editForm(suggession)"  data-toggle="modal" data-target="#couponNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteCoupon(suggession.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- Card body / -->
                    <div v-if="isLoading" class="overlay dark">
                        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                </div><!-- /.row -->


                <!-- Modal -->
                <div class="modal fade" id="couponNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-coupon"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Coupon</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="editMode ? updateSuggession() : createSuggession()">
                                <div class="modal-body">
                                    
                                    <!--<div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Marketing Person</label>
                                        <div class="col-sm-10">
                                            <select required type="text" class="form-control" v-model="form.marketing_person_id"
                                                placeholder="Coupon Name"
                                                :class="{ 'is-invalid': form.errors.has('marketing_person_id') }">
                                                <option :value="null" disabled>Select Marketing Person</option>
                                                <option v-for="mp in marketingPersons" :value="mp.id" :key="mp.id">{{ mp.name}}</option>
                                            </select>
                                            <has-error :form="form" field="marketing_person_id"></has-error>
                                        </div>
                                    </div>-->
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Heading</label>
                                        <div class="col-sm-8">
                                            <input type="text"  class="form-control" v-model="form.heading"
                                                :class="{ 'is-invalid': form.errors.has('heading') }">
                                            <has-error :form="form" field="heading"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Category Name</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" v-model="form.category_id" placeholder="Select Category" :class="{ 'is-invalid': form.errors.has('category_id') }" v-on:change="loadProductList(this)">
                                                <option v-for="cat of Categories" :key="cat.id" :value="cat.id" :selected="checkSelected(cat)" >{{cat.name}}</option>
                                            </select>
                                           <has-error :form="form" field="category_id"></has-error>
                                            
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Layout</label>
                                        <div class="col-sm-8">
                                            <select v-model="form.layout" class="form-control" :class="{ 'is-invalid': form.errors.has('layout') }">
                                                <option :value="1">1</option>
                                                <option :value="2">2</option>
                                                <option :value="3">3</option>
                                            </select>
                                            <has-error :form="form" field="layout"></has-error>
                                        </div>
                                     </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Product</label>
                                        <div class="col-sm-8">
                                            <v-select multiple :closeOnSelect="true" v-model="selected" :options="options" label="name" @input="setSelected"/>
                                            <has-error :form="form" field="product_id"></has-error>
                                        </div>
                                    </div>
                                    
                                     <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Position</label>
                                        <div class="col-sm-8">
                                            <select v-model="form.position" class="form-control" :class="{ 'is-invalid': form.errors.has('position') }">
                                                <option :value="1">1</option>
                                                <option :value="2">2</option>
                                                <option :value="3">3</option>
                                                <option :value="4">4</option>
                                                <option :value="5">5</option>
                                                <option :value="6">6</option>
                                                <option :value="7">7</option>
                                                <option :value="8">8</option>
                                            </select>
                                             <has-error :form="form" field="position"></has-error>
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
    import vSelect from 'vue-select';
    import "vue-select/dist/vue-select.css";
    import $ from "jquery";
    export default {
        components:{
            'v-select': vSelect,
        },
        data() {
            return {
                form : new Form({
                    id: null,
                    category_id:'',
                    layout: '',
                    position: '',
                    admin_id:'',
                    heading:'',
                    is_active: true,
                    product_id : [],
                }),
                Categories: {},
                selected: [],
                options: [],
                product_id: [],
                isLoading : false,
                editMode: false
            }
        },
        methods :{
            createSuggession(){
                this.$Progress.start();
                var a =$('#authid').val();
                this.form.admin_id=a;
                this.form.product_id = [];
                for (let x in this.selected){
                    this.form.product_id.push(this.selected[x].id);
                }
                var pro =this.form.product_id;
                var layout =this.form.layout;
                var position =this.form.position;
                if(layout!='' && position!='')
                {              
                    if(layout==1)
                    {
                        if(pro.length!=3)
                        {
                            toast.fire({
                                type: 'warning',
                                title: 'Please Select Three Products',
                            });
                            this.$Progress.fail();
                        }
                        else
                        {
                        var  condition=true;
                        }
                    }
                    else if(layout==2 || layout==3)
                    {
                        if(pro.length!=4)
                        {
                            toast.fire({
                                type: 'warning',
                                title: 'Please Select Four Products',
                            });
                            this.$Progress.fail();
                        }
                        else
                        {
                            var  condition=true;
                        }
                    }

                    if(condition==true)
                    {
                        this.form.post('api/suggession').then( ()=>{
                            Fire.$emit('LoadSuggestionList');
                            $('#couponNew').modal('hide');
                            toast.fire({
                                type: 'success',
                                title: 'Suggession Created successfully'
                            });
                           // location.reload();
                            this.$Progress.finish();
                        }).catch(()=>{

                            this.$Progress.fail();
                        });

                         
                    }
                }
                else
                {
                    toast.fire({
                            type: 'warning',
                            title: 'Please Select All Feilds',
                        });
                    this.$Progress.fail();
                }
                
                
            },
            updateSuggession(){
                this.$Progress.start();
                this.form.product_id = [];
                for (let x in this.selected){
                    this.form.product_id.push(this.selected[x].id);
                }

                var pro =this.form.product_id;
                var layout =this.form.layout;
                var position =this.form.position;
                if(layout!='' && position!='')
                {              
                    if(layout==1)
                    {
                        if(pro.length!=3)
                        {
                            toast.fire({
                                type: 'warning',
                                title: 'Please Select Three Products',
                            });
                            this.$Progress.fail();
                        }
                        else
                        {
                        var  condition=true;
                        }
                    }
                    else if(layout==2 || layout==3)
                    {
                        if(pro.length!=4)
                        {
                            toast.fire({
                                type: 'warning',
                                title: 'Please Select Four Products',
                            });
                            this.$Progress.fail();
                        }
                        else
                        {
                            var  condition=true;
                        }
                    }

                    if(condition==true)
                    {    


                        this.form.put('api/suggession/' + this.form.id).then( ()=>{
                            Fire.$emit('LoadSuggestionList');
                            $('#couponNew').modal('hide');
                            toast.fire({
                                type: 'success',
                                title: 'Suggession Updated successfully',
                            });

                            this.$Progress.finish();
                        }).catch(()=>{
                            this.$Progress.fail();
                        });
                           
                    }
                }
                else
                {
                    toast.fire({
                            type: 'warning',
                            title: 'Please Select All Feilds',
                        });
                    this.$Progress.fail();
                }
                
            },
            loadCategoryList() {
                axios.get("api/pro_category").then(data=>{
                   this.Categories = data.data.pro_category
                   console.log(data.data.pro_category);

                });

            },
            loadProductList() {
                 this.selected = [];
                var cat_id = this.form.category_id;
                //alert(cat_id);
                axios.get("/api/loadProductCat/"+cat_id).then(data=>{
                    console.log(data);
                   this.product_list = data.data.product;
                   this.options = this.product_list;
               });
            },
            checkSelected(product){
                return product.id == this.form.product_id
            },
            LoadSuggestionList() {
                this.isLoading = true;
                var a =$('#authid').val();
                axios.get("api/suggession").then( ({ data }) => {
                    this.suggession = data;
                    console.log(data);
                    this.isLoading = false;
                }).catch( err => this.isLoading=false);
            },
            setSelected(data){
                console.log(this.selected);
            },
            deleteCoupon(id){
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
                      //  console.log(result)
                    if(result.value){
                        this.form.delete('/api/suggession/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Suggession has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadSuggestionList');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Suggession can not  be deleted.',
                            'danger'
                            )
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.editMode = false;
                this.selected = [];
            },
            editForm(data){
                this.selected = [];
                this.form.reset();
                this.form.fill(data);
                this.editMode = true;
                this.loadProductList();
                if(data.product_id!=''){
                    var product_id = data.product_id.split(',');
                    var name = data.product_name.split(',');
                    for (let x in product_id){
                        this.selected.push({'id':product_id[x],'name':name[x]});
                    }
                }
                
                
            },
            fileSelected(e){
                console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        console.log('RESULT converted base 64');
                        this.form.image=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }

            },
            changeActiveStatus(suggession){
                this.editForm(suggession);
                this.form.is_active = !this.form.is_active ;
                this.updateSuggession();
            },
            // loadMarketingPersonList() {
            //     axios.get("api/marketing_person").then( ({ data }) => {
            //         this.marketingPersons = data;
            //     }).catch( err => console.log(err));
            // },
        },
        mounted() {
            console.log('Component mounted.');
        },
        created(){
            this.loadCategoryList();
            this.LoadSuggestionList();
            //this.loadVendorList();
            //this.loadMarketingPersonList();
            Fire.$on('LoadSuggestionList', () => this.LoadSuggestionList() );
        }

       
    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
