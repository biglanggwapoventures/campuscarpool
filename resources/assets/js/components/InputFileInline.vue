<template>
    <div class="form-group row" v-bind:class="{ 'has-danger': errors.length > 0 }">
        <label class="col-sm-3 col-form-label" v-show="label" v-bind:class="labelClassName ? labelClassName : ''">{{ label }}</label>
        <div class="col-sm-9">
            <input ref="input" type="file" :id="id"  @change="fileChanged($event)"  class="form-control-file" v-bind:class="inputClassName ? inputClassName : ''">
            <div class="form-control-feedback" v-show="errors.length > 0">{{ errors[0] }}</div>
        </div>
        
    </div>
</template>

<script>
    export default {
        props: {
            label: {
                type: String,
                default: ''
            },
            inputType: {
                type: String,
                default: 'text'
            },
            errors: {
                default: ''
            },
            id: {
                default: ''
            },
            inputClassName: {
                type: String,
                default: ''
            },
            labelClassName: {
                type: String,
                default: ''
            },
        },
        
        methods: {
            fileChanged(e){
                // console.log( e.target.files[0]);
                this.$emit('input', e.target.files[0]);
            }
        }
    }
</script>
