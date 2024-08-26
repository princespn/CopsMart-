<template>
    <span>
        <div class="custom-control custom-switch" v-if="meta.field && meta.action">
            <input type="checkbox" class="custom-control-input" :id="'onOff'+data.id" :checked="data[meta.field]" @change="meta.action(data)">
            <label class="custom-control-label" :for="'onOff'+data.id" ></label>
        </div>
    </span>
</template>

<script>
    export default {
        props: [ 'meta', 'data'],
        mounted(){
            // console.log(this.data );
        },
        methods:{
        }
    }
</script>
