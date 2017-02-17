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
            this.Flatpickr = require('flatpickr');
            new this.Flatpickr(document.getElementById(this.id), {
                enableTime: this.enableTime,
                altInput: true,
                altFormat: this.enableTime ? "m/j/Y h:i K" :"m/j/Y" ,
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
            'enable-time' : {
                type:Boolean,
                default: true
            },
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