<template>
    <div class="form-group" v-bind:class="{ 'has-danger': errors.length > 0 }">
        <label class="form-control-label" v-show="label" v-bind:class="labelClass ? labelClass : ''">{{ label }}</label>
            <input type="file" :id="id" @change="fileChanged($event)" class="form-control-file" v-bind:class="inputClass ? inputClass : ''">
        <div class="form-control-feedback" v-show="errors.length > 0">{{ errors[0] }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            label: String,
            errors: {
                default: ''
            },
            id: String,
            inputClass: String,
            labelClass: String,
            'file-label': String
        },
        methods: {
            fileChanged(e){
                this.$emit('file-changed', {
                    label: this.fileLabel,
                    file: e.target.files[0]
                });
            }
        }
    }
</script>

<style>

</style>