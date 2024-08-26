<template>
    <img :src="getUrl()" :alt="data.name" class="img">
</template>

<script>
    export default {
        props: [ 'url', 'data', 'meta'],
        mounted(){
            // console.log(this.data, this.meta.url);
        },
        methods:{
            getUrl(){
                return this.data.image!= null ? this.meta.url + this.data.image : ''; // to generate full image url
            }
        }
    }
</script>

<style scoped>
img {
    height:10vh;
    /* width:10vh; */
}
</style>
