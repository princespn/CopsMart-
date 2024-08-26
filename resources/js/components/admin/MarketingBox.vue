<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Marketing Box List</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Marketing Box List</h3>
                        <div class="card-tools">
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal" data-target="#marketingNew"> <i class="fa fa-slab-plus"></i> Add New</button>
                        </div>
                    </div>
                    
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Vendor</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tr v-for="(marketingbox, index) in marketingbox" :key="'sc'+index">
                                <td>{{(1+index)}}</td>
                                <td>{{ marketingbox.marketing_name }}</td>
                                <td> <img :src="getImageUrl(marketingbox)" alt="" class="img img-responsive" ></td>
                                <td>{{ marketingbox.name }}</td>
                                <td>
                                    <button @click="editForm(marketingbox)"  data-toggle="modal" data-target="#marketingNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteMarketing(marketingbox.id)" class="btn btn-danger btn-sm">
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
        <div class="modal fade" id="marketingNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-slab"></i> {{ editMode ? 'Edit' : 'Add'}} Marketing Box</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateMarketing() : createMarketing()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Name</label>
                                <div class="col-sm-10">
                                    <input type="charges" min="0" class="form-control"  v-model="form.marketing_name" placeholder="Name" :class="{ 'is-invalid': form.errors.has('marketing_name') }">
                                    <has-error :form="form" field="marketing_name"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Image</label>
                                <div class="col-sm-10">
                                    <input type="file" @change="fileSelected" id="marketing_image" :class="{ 'is-invalid': form.errors.has('marketing_image') }">
                                    <has-error :form="form" field="marketing_image"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Category</label>
                                <div class="col-sm-10">
                                    <select class="form-control" v-model="form.category_id" placeholder="Select Category" :class="{ 'is-invalid': errors.category_id }" v-on:change="loadVendorList(this)">
                                        <option v-for="cat of Categories" :key="cat.id" :value="cat.id" :selected="checkSelected(cat)" >{{cat.name}}</option>
                                    </select>
                                    <span class="label label-danger" v-if="errors.sub_category_id">{{errors.sub_category_id}} </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Vendor</label>
                                <div class="col-sm-10">
                                    <v-select multiple :closeOnSelect="true" v-model="selected" :options="options" label="name" @input="setSelected"/>
                                    <has-error :form="form" field="vendor_id"></has-error>
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
                    id:'',
                    marketing_name :null,
                    marketing_image :null,
                    category_id :null,
                    vendor_id : [],
                    
                }),
                vendors: {},
                errors:{},
                selected: [],
                options: [],
                Categories :[],
                vendor_list: [],
                marketingbox: {},
                editMode: false,
            }
        },
        methods :{
            createMarketing(){
                this.$Progress.start();
                this.form.vendor_id = [];
                for (let x in this.selected){
                    this.form.vendor_id.push(this.selected[x].id);
                   
                }
                this.form.post('/api/marketing_box').then( ()=>{
                    this.loadMarketingList();
                    $('#marketingNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Marketing Box successfully'
                    });
                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });
            },
            updateMarketing(){
                this.$Progress.start();
                this.form.vendor_id = [];
                for (let x in this.selected){
                    this.form.vendor_id.push(this.selected[x].id);
                   
                }
                this.form.put('/api/marketing_box/' + this.form.id).then( ()=>{
                    this.loadMarketingList();
                    $('#marketingNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Marketing Box Updated successfully'
                    });
                    this.$Progress.finish();
                }).catch(()=>{
                    this.errors =data.response.data.errors;
                    this.$Progress.fail();
                });
            },
            loadCategoryList() {
                axios.get("api/pro_category").then(data=>{
                   this.Categories = data.data.pro_category
                });
            },
            loadMarketingList() {
                
                axios.get('/api/marketing_box').then( ({ data }) => (this.marketingbox = data) );
            },
            loadVendorList() {
                var cat_id = this.form.category_id;
                axios.get("/api/loadVendorCat/"+cat_id).then(data=>{
                   this.vendor_list = data.data.vendor;
                   this.options = this.vendor_list;
               });
            },
             getImageUrl(marketingbox){
                return marketingbox.marketing_image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/public/uploads/images/marketingbox/' + marketingbox.marketing_image;
            },
            checkSelected(vendor){
                return vendor.id == this.form.vendor_id
            },
            setSelected(data){
                console.log(this.selected);
            },
            fileSelected(e){
                console.log('file slected', e);
                if(e.target.files.length > 0 ){
                    let file = e.target.files[0];
                    let reader= new FileReader();
                    reader.onloadend = () => {
                        this.form.marketing_image=  reader.result;
                    }
                    reader.readAsDataURL(file);
                }

            },
            deleteMarketing(id){
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
                        this.form.delete('/api/marketing_box/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Marketing Box has been deleted.',
                            'success'
                            );
                            this.loadMarketingList();
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'Marketing Box can not  be deleted.',
                            'danger'
                            )
                            this.loadMarketingList();
                        })
                    }
                })
            },
            newForm(){
                this.form.reset();
                this.editMode = false;
            },
            editForm(data){
                this.selected = [];
                this.form.reset();
                this.form.fill(data);
                this.editMode = true;
                this.form.marketing_image= '';
                var vendor_id = data.vendor_id.split(',');
                var name = data.name.split(',');
                this.loadVendorList();
                for (let x in vendor_id){
                    this.selected.push({'id':vendor_id[x],'name':name[x]});
                   
                }
                
            },
            loadMarketing() {
                axios.get("/api/marketing_box").then( ({ data }) => (this.marketingbox = data) );
            },
        },
        mounted() {
            console.log('Component mounted.');
            this.loadCategoryList();
            //this.loadVendorList();
            this.loadMarketingList();
            this.loadMarketing();
        }
    }
</script>

<style scoped>
img{
    max-width : 3vh;
    max-height : 3vh
}
</style>
