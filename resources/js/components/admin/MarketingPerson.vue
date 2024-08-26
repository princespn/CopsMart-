<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Marketing Person</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Marketing Person List</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <button type="button" @click="newForm" class="btn btn-success" data-toggle="modal"
                                data-target="#marketingPersonNew"> <i class="fa fa-marketingPerson-plus"></i> Add New</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">

                        <table class="table table-hover" v-if="marketingPersons.length > 0">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>On / Off</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>

                            <tr v-for="(marketingPerson, index) in marketingPersons" :key="marketingPerson.id">
                                <td>{{ (1+index)}}</td>
                                <td>{{ marketingPerson.name  }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+marketingPerson.id"  :checked="marketingPerson.is_active" @change="changeActiveStatus(marketingPerson)">
                                        <label class="custom-control-label" :for="'onOff'+marketingPerson.id" ></label>
                                    </div>
                                </td>
                                <td>{{marketingPerson.mobile}}</td>
                                <td>{{marketingPerson.email}}</td>
                                <td>
                                    <button @click="editForm(marketingPerson)"  data-toggle="modal" data-target="#marketingPersonNew" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="deleteMarketingPerson(marketingPerson.id)" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <router-link :to="getMarketingPersonWalletLink(marketingPerson)" class="btn btn-success btn-sm" >
                                        <i class="fa fa-rupee-sign"></i>
                                    </router-link>
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
                <div class="modal fade" id="marketingPersonNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-marketingPerson"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} MarketingPerson</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="editMode ? updateMarketingPerson() : createMarketingPerson()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" v-model="form.name"
                                                placeholder="MarketingPerson Name"
                                                :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Mobile</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" v-model="form.mobile" :class="{ 'is-invalid': form.errors.has('mobile') }">
                                            <has-error :form="form" field="mobile"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Driving License No</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" v-model="form.driving_license_no"  :class="{ 'is-invalid': form.errors.has('driving_license_no') }">
                                            <has-error :form="form" field="driving_license_no"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Aadhar No</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" v-model="form.aadhar_no"   :class="{ 'is-invalid': form.errors.has('aadhar_no') }">
                                            <has-error :form="form" field="aadhar_no"></has-error>
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
                    id:null,
                    name:null,
                    mobile: null,
                    email: null,
                    driving_license_no: null,
                    aadhar_no: null,
                    is_active: true,
                }),
                marketingPersons: {},
                editMode: false,
                serviceAreas: [],
                isLoading: false
            }
        },
        methods :{
            createMarketingPerson(){
                this.$Progress.start();

                this.form.post('api/marketing_person').then( ()=>{
                    Fire.$emit('LoadMarketingPerson');
                    $('#marketingPersonNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'MarketingPerson Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadMarketingPersonList();
            },
            updateMarketingPerson(){
                this.$Progress.start();
                this.form.put('api/marketing_person/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadMarketingPerson');
                    $('#marketingPersonNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadMarketingPersonList() {
                this.isLoading = true;
                axios.get("api/marketing_person").then( ({ data }) => {
                    this.marketingPersons = data;
                    this.isLoading = false;
                });
            },
            deleteMarketingPerson(id){
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
                        this.form.delete('/api/marketing_person/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'MarketingPerson has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadMarketingPerson');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `MarketingPerson can't  be deleted.`,
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
                this.editMode = true;
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
            changeActiveStatus(marketingPerson){
                this.editForm(marketingPerson);
                this.form.is_active = !this.form.is_active ;
                this.updateMarketingPerson();
            },
            getImageUrl(marketingPerson){
                return marketingPerson.image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/public/uploads/images/marketingPerson/' + marketingPerson.image;
            },
            getMarketingPersonWalletLink(marketingPerson){
                return  `/marketing_wallet/${marketingPerson.id}` ;
            },
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadMarketingPersonList();
            Fire.$on('LoadMarketingPerson', () => this.loadMarketingPersonList() );
        }


    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
</style>
