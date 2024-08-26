<template>
    <select class="selectpicker" data-live-search="true" :value="selected">
        <option :value="null"> </option>
        <option v-for="(option, index) of data" :value="getValue(option)" :key="index" :selected="getValue(option) == selected">{{getOptionText(option)}}</option>
    </select>
</template>

<script>
    export default {
        props: [ 'data', 'val', 'text', 'selected' ],
        mounted: function() {
            $(this.$el).selectpicker();
            $(this.$el).on('changed.bs.select', (e) => {
                // console.log('changed.bs.select deteced');
                // console.log($(this.$el).val());
                this.$emit('optionSelected',$(this.$el).val());
            });
        },
        beforeDestroy: function() {
            $(this.$el).selectpicker('hide');
        },
        afterUpdate: function(){
            $(this.$el).selectpicker('refresh');
        },
        methods:{
            getValue(option){
                return !this.val ? option : option[this.val];
            },
            getOptionText(option){
                return !this.text? option : option[this.text];
            }
        }
    }
</script>
