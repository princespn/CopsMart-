<template>
    <div class="container">
        <div class="modal fade" id="notificas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="false">
             <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="exampleModalLongTitle">Notification Sound</h5>  
                    </div>
                        <div class="modal-body">
                            <h3>Do you want to enable notification sound ?</h3>
                        </div>
                        <div class="modal-footer">
                            <button  class="btn btn-sm btn-secondary" @click.prevent="notdone()">No</button>
                            <button  class="btn btn-sm btn-primary" @click.prevent="Soundenable()">Yes</button>
                        </div>
                </div>
            </div>
        </div>

            <div class="modal fade" id="ordernotification" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="false">
             <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="exampleModalLongTitle">You Recived A New Order</h5>  
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="Closemodal()">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <p class="text-center">
                            <router-link to="/OrderManagement">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-cart nav-icon green"></i> Go to Orders </button>
                            </router-link>
                            </p>
                        </div>
                     
          
            </div>
        </div>
        </div>
    </div>
    
</template>

<script>
import $ from "jquery";
export default {
    data() {
        return {
            counts: {},
            isLoading: false,
            Purchases:{},
            vendorId:'',
            message:'aaaaa',
            sound:false,
            soundurl : '/uploads/notify.mp3'
        }
    },
    methods :{
        playSound() {
          var audio = new Audio(this.soundurl);
          audio.play();
        },
        pauseSound() {
          var audio = new Audio(this.soundurl);
          audio.pause();
        },
        getTotalData()
        {       
            axios.get("/api/getgetneworderdata/"+this.vendorId).then( ({data})=>{
               // console.log(data);
                  //console.log(data.data);
                if(data.new_order!=0 || data.new_order!='')
                {   
                    $('#ordernotification').modal('show');
                    if($('#checknotification').val()=='on')
                    {
                        this.playSound();
                    }
                }
                });
        },
        Soundenable() 
        {
          $('#checknotification').val('on');  
          var audio = new Audio(this.soundurl);
          audio.pause();
          $('#notificas').modal('hide');
          $('#buttonc').css('display','none');
          this.sound=true;
          $('#modald').val('yes');  
        },
        Closemodal()
        {  
            this.pauseSound();
            $('#ordernotification').modal('hide');
        },
        notdone() 
        {
          $('#checknotification').val('off');  
          $('#buttonc').css('display','block');
          $('#notificas').modal('hide');
          this.sound=false;
          $('#modald').val('yes');  
        },
        
    },
      
        
    mounted() {
        // $('#ordernotification').modal('hide');  
        // var ds1= $('#modald').val();   
        // if(ds1=='')
        // {
        //     $('#notificas').modal('show');
        // }
        // this.pauseSound();
    },
    created(){
        // this.vendorId=$('#authid').val();
        //  setInterval(() => {
        //      this.getTotalData();
        // }, 40000); 
    }
}
</script>

<style>

</style>
