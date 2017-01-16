<template>
    <div class="form-group" v-bind:class="{ 'has-danger': errors.length > 0 }">
        <label class="form-control-label" v-show="label" v-bind:class="labelClassName ? labelClassName : ''">{{ label }}</label>
        <input ref="input" v-bind:type="inputType" :id="id" v-bind:value="value" v-on:input="updateValue($event.target.value)" class="form-control" v-bind:class="inputClassName ? inputClassName : ''">
        <div class="form-control-feedback" v-show="errors.length > 0">{{ errors[0] }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
                default: ''
            },
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
            updateValue (value) {
                this.$emit('input', value)
            }
        }
    }
</script>
