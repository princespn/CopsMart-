<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Splash</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Splash</h3>

                        <div class="card-tools">
                            <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div> -->
                            <button type="button" @click="editForm(splash)" class="btn btn-success" data-toggle="modal"
                                data-target="#splashNew"> <i class="fa fa-splash-plus"></i> Change Splash </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <div class="row" >
                            <div class="col-sm-8 offset-sm-2 col-xs-12">
                                <h2>Current Splash Image</h2>
                                <br><br>
                                <img :src="getImageUrl(splash)" alt="current splash image" class="img img-responsive col-sm-12">
                                <br><br>
                            </div>
                        </div>
                    </div>
                </div><!-- /.row -->


                <!-- Modal -->
                <div class="modal fade" id="splashNew" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-splash"></i>
                                    {{ editMode ? 'Edit' : 'Add'}} Splash</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="editMode ? updateSplash() : createSplash()">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Splash Image</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control" @change="fileSelected"
                                                id="staticEmail" :class="{ 'is-invalid': form.errors.has('image') }">
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
                    image:'',
                    admin_id:'',

                }),
                splash: {},
                editMode: false
            }
        },
        methods :{
            createSplash(){
                this.$Progress.start();
var a =$('#authid').val();
this.form.admin_id=a;
                this.form.post('api/splash').then( ()=>{
                    Fire.$emit('LoadSplash');
                    $('#splashNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Splash Created successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{

                    this.$Progress.fail();
                });

                // this.loadSplashList();
            },
            updateSplash(){
                this.$Progress.start();
                this.form.put('api/splash/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadSplash');
                    $('#splashNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title:  +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadSplashList() {
                var a =$('#authid').val();
                axios.get("api/splash").then( ({ data }) => (this.splash = data) );
            },
            deleteSplash(id){
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
                        this.form.delete('/api/splash/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Splash has been deleted.',
                            'success'
                            );
                            Fire.$emit('LoadSplash');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            `Splash can't  be deleted.`,
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
            changeActiveStatus(splash){
                this.editForm(splash);
                this.form.is_active = !this.form.is_active ;
                this.updateSplash();
            },
            getImageUrl(splash){
                return splash.name == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/public/uploads/images/splash/' + splash.name;
            },
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadSplashList();
            Fire.$on('LoadSplash', () => this.loadSplashList() );
        }


    }
</script>

<style scoped>

</style>
