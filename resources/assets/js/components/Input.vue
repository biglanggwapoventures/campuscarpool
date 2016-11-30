<template>
<div class="form-group" v-bind:class="{ 'has-danger': errors.length > 0 }">
    <label class="mb-0 form-control-label" v-if="label">{{ label }}</label>
    <input ref="input" v-bind:type="inputType" v-bind:value="value" v-on:input="updateValue($event.target.value)" class="form-control form-control-sm">
    <div class="form-control-feedback" v-if="errors.length > 0">{{ errors[0] }}</div>
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
            }
        },
        
        methods: {
            updateValue (value) {
                this.$emit('input', value)
            }
        }
    }
</script>
