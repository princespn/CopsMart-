<template>
    <span class="p-1">
        <span   v-if="meta.btnList && meta.btnList.length > 0">
            <button v-for="(btn, index) in meta.btnList" :key="index" type="button" class="btn" :class="btn.classes" @click="btn.action(data)"  v-bind="btn.additionalAttributes !='' ? btn.additionalAttributes : {}">{{btn.label}}</button>
        </span>
        <button v-if="meta.label && meta.action" type="button" :class="meta.classes" class="btn" @click="meta.action(data)">{{meta.label}}</button>
    </span>
</template>

<script>
    export default {
        props: ['url', 'data', 'classes', 'meta'],
        mounted(){
            // console.log(this.data, this.url);
        },
        methods:{
            getUrl(){
                return this.url + this.data.image; // to generate full image url
            }
        }
    }
</script>
