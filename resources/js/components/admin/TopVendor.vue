<template>
    <div class="container">
        <!-- /.row -->
        <div class="row">
            <h2>Vendor</h2>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Vendor List</h3>
                      
                        <div class="card-tools">
                            <button @click="editForm()" type="button"   class="btn btn-primary btn-sm btn-edit">
                                <i class="fa fa-edit"></i>  Edit
                            </button>
                            <button type="submit" class="btn btn-success btn-sm btn-save">
                                <i class="fa fa-check"></i>  Save
                            </button>
                        </div>
                        
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 table-bordered">
                        
                           
                        <table class="table table-hover table-responsive" border="1">
                            <tr>
                                <th>Sr. No</th>
                                <th>Name</th>
                                <th>Shop name</th>
                                <th>Address</th>
                                <th>Shop Image</th>
                                <th>Contact No</th>
                                <th>Rank</th>
                                <th>Top</th>
                            </tr>
                            <!-- <tr>
                                <td>183</td>
                                <td>John Doe</td>
                                <td>11-7-2014</td>
                                <td><span class="tag tag-success">Approved</span></td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr> -->

                            <tr v-for="(vendor, index) in vendors" :key="vendor.id"  >
                                <td>{{(1+index)}}<input type="hidden" name="vendortopid[]" class="form-control renum" :value="vendor.id"></td>
                                <td>{{ vendor.name | upText }}</td>
                               <td>{{ vendor.shop_name | upText }}</td>
                                <td>{{ vendor.address}} </td>
                                <td><img :src="getImageUrl(vendor)" alt="" class="img img-responsive" ></td>
                                <td>{{ vendor.contact_no}} </td>
                                <td v-if="vendor.top != '0'"><input type="text" name="vendortop[]" class="form-control renum" :value="vendor.top" readonly></td>
                                <td v-else class="els"></td>
                                 <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" :id="'onOff'+vendor.id"  :checked="vendor.is_top" @change="changeActiveStatus(vendor)">
                                        <label class="custom-control-label" :for="'onOff'+vendor.id" ></label>
                                    </div>
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
        
        
    </div>
</template>

<script>
    import { moment } from 'moment';
    import $ from "jquery";
    export default {
        data() {
            return {
                form : new Form({
                    id:'',
                    name: null,
                    image:'',
                    address: null,
                    about_vendor: null,
                    service_range:12000,
                    super_category_id: null,
                    category_id: null,
                    latitude: null,
                    longitude: null,
                    email: null,
                    contact_no: null,
                    return_replacement: null,
                    slab:null,
                    shop_name:null,
                    top:null,
                    is_top:false,
                    is_active: true,
                }),
                vendors: {},
                editMode: false,
                super_categories: [],
                categories: []
            }
        },
        methods :{
            
           editForm()
           {
             $('.renum').attr('readonly',false);
             $('.btn-edit').css('display','none');
             $('.btn-save').css('display','block');
             $('.els').html('<input type="text" name="vendortop[]" class="form-control renum">');
           },

            updateVendor(){
                this.$Progress.start();
                this.form.put('api/vendors/' + this.form.id).then( ()=>{
                    Fire.$emit('LoadVendor');
                    $('#vendorNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: this.form.name +' Updated successfully'
                    });

                    this.$Progress.finish();
                }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            loadVendorList() {
                var a =$('#authid').val();
                axios.get("api/vendors?ad="+a).then( ({ data }) => (this.vendors = data) );
            },
            getImageUrl(vendor){
                return vendor.shop_image == '' ? 'https://static.thenounproject.com/png/340719-200.png' : '/uploads/images/vendor/' + vendor.shop_image;
            },           
              updateTopVendor(){
               // this.$Progress.start();
                this.form.post('api/settopvendors/').then( ()=>{
                   // Fire.$emit('LoadVendor');
                    // toast.fire({
                    //     type: 'success',
                    //     title: 'Updated successfully'
                    // });
                   // this.$Progress.finish();
                }).catch(()=>{
                   // this.$Progress.fail();
                });
            },



            changeTopStatus(vendor){
                this.editForm(vendor);
                this.form.is_top = !this.form.is_top ;
                this.updateVendor();
            }, 
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadVendorList();
            Fire.$on('LoadVendor', () => this.loadVendorList() );
        }
        

    }
</script>

<style scoped>
img{
    max-width : 5vh;
    max-height : 5vh
}
.btn-save{
    display:none;
}
/* .dis{
    display:none;
} */
</style>
