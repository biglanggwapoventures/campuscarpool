<template>
    <div class="form-group" :class="errors.length ? 'has-danger' : ''">
        <label class="mb-0">{{ label }}</label>
        <input type="text" class="form-control-sm" :id="id" :value="value" />
        <div class="form-control-feedback" v-show="errors.length > 0">{{ errors[0] }}</div>
    </div>
</template>
<script>
    export default {
        mounted(){
            this.Flatpickr = require('flatpickr');
            new this.Flatpickr(document.getElementById(this.id), {
                enableTime: true,
                altInput: true,
                altFormat: "m/j/Y h:i K",
                minuteIncrement: 1,
                minDate: new Date(),
                onClose: (selectedDates, dateStr, instance) => {
                    this.$emit('input', dateStr);
                }
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
                default: 'Input label'
            },
            value: {
                type: String,
                default: ''
            },
            errors: {
                default: ''
            }
        }
        
    }

</script>
<style>
    
</style>