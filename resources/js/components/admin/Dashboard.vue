<template>
    <div class="container">
        <div class="row">
            <br>
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Orders</span>
                        <span class="info-box-number">
                            {{counts.new_orders}}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Products</span>
                        <span class="info-box-number">{{ counts.products}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Sales</span>
                        <span class="info-box-number">{{ counts.sales}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Customers</span>
                        <span class="info-box-number">{{ counts.customers }} </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
           
           <!-- notification -->
            <orders />
           <!-- end -->
    </div>
    
</template>

<script>
import GetOrder  from './GetOrder';
import $ from "jquery";
export default {
    components: {
         'orders': GetOrder
    },
    data() {
        return {
            counts: {},
            isLoading: false,
            soundurl : 'http://soundbible.com/mp3/analog-watch-alarm_daniel-simion.mp3'
            
        }
    },
    methods :{

        loadDashboardCounts() {
            var a=$('#authid').val();
            axios.get('/api/dashboard_counts/'+a).then( ({ data }) => {
                this.counts = data;
            });
        },
     
    },
    mounted() {
        // console.log('Component mounted.');
        this.loadDashboardCounts();

    },
    created(){
        Fire.$on('loadDashboardCounts', () => { this.loadDashboardCounts(); });
    }
}
</script>

<style>

</style>
