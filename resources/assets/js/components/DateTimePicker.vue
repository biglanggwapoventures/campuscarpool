<template>
    <div class="form-group" :class="errors.length ? 'has-danger' : ''">
        <label class="mb-0" v-bind:class="labelClass" v-if="label">{{ label }}</label>
        <input type="text" :id="id" :value="value" />
        <div class="form-control-feedback" v-show="errors.length > 0">{{ errors[0] }}</div>
    </div>
</template>
<script>
    export default {
        mounted(){
            console.log(this.value)
            this.Flatpickr = require('flatpickr');
            new this.Flatpickr(document.getElementById(this.id), {
                enableTime: true,
                altInput: true,
                altFormat: "m/j/Y h:i K",
                minuteIncrement: 1,
                defaultDate: this.value, 
                minDate: this.value,
                onClose: (selectedDates, dateStr, instance) => {
                    this.$emit('input', dateStr);
                },
                onReady : (selectedDates, dateStr, instance) => {
                    this.$emit('input', dateStr);
                },
            });
        },
        data(){
            return {
                Flatpickr : null
            }
        },
        props: {
            id: {
                type: String,
                default: 'datetimepicker'
            },
            label: {
                type: String,
            },
            value: {
                default: ''
            },
            errors: {
                default: ''
            },
            'label-class': {
                type: String,
                default: ''
            }
        }
        
    }

</script>
<style>
    
</style>