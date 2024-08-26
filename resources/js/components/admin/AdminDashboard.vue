<template>
    <div class="container">
        <div class="row">
            <!-- /.col -->
            <p>&nbsp;</p>
            <!-- /.col -->
        </div>
        <div class="row">
            <adminorders />
        </div>
    </div>
</template>

<script>
import AdminOrders  from './AdminOrders';
import $ from "jquery";
export default {
    components: {
        'adminorders': AdminOrders
    },
    data() {
        return {
            counts: {},
            isLoading: false
        }
    },
    
}
</script>

<style>

</style>
