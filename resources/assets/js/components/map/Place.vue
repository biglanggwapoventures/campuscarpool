<template>
    <div class="form-group" v-bind:class="{ 'has-danger': errors.length > 0 }">
        <label v-bind:for="id" v-if="label" v-bind:class="labelClass">{{ label }}</label>
        <input type="text" class="form-control" :id="id" v-bind:class="inputClass" /> 
        <div class="form-control-feedback" v-show="errors.length > 0">{{ errorMessage }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            id: String,
            label: String,
            'label-class': String,
            'input-class': String,
            'errors': {
                default: ''
            }
        },
        mounted() {
            var GoogleMapsLoader = require('google-maps');

            GoogleMapsLoader.KEY = 'AIzaSyD85clj7B85QRZPmO6m4Fky0Wi6P0MzVpA';
            GoogleMapsLoader.REGION = 'PH';  
            GoogleMapsLoader.LIBRARIES = ['places'];    
            
            GoogleMapsLoader.load((google)  => {
              
                var autocomplete = new google.maps.places.Autocomplete(document.getElementById(this.id));

                autocomplete.addListener('place_changed', () => {
                    var place = autocomplete.getPlace(),
                        formattedAddress = place.name + ', ' + place.formatted_address;
                    this.$emit('place-changed', place);
                    this.$emit('input', formattedAddress);
                });

                // $('input').keyboard({
                //     change : (e, keyboard, el) => {
                //         google.maps.event.trigger( el, 'focus', {} );
                //     }
                // })

                

            });
        },
        computed: {
            errorMessage() {
                return typeof this.errors === 'string' ? this.errors : this.errors[0];
            }
        }
    }
</script>

<style lang="css">

</style>