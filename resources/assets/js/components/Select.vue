<template>
   <div class="form-group" v-bind:class="{ 'has-danger': errors.length > 0 }">
        <label class="form-control-label" v-show="label" v-bind:class="labelClass">{{ label }}</label>
            <select :id="id" class="form-control" v-bind:class="selectClass" v-on:change="updateValue($event.target.value)">
                <option value=""></option>
                <option v-for="o in options" v-bind:value="o.value">{{o.text}}</option>
            </select>
        <div class="form-control-feedback" v-show="errors.length > 0">{{ errors[0] }}</div>
    </div>
</template>
<script>
    export default {
        props: {
            options: {
                type: Array,
                default: []
            },
            label: String,
            errors: {
                default: ''
            },
            id: String,
            'label-class': String,
            'select-class': String
        },
        methods: {
            updateValue(value){
                 this.$emit('input', value)
            }
        }
    }
</script>
<style lang="css">
    
</style>