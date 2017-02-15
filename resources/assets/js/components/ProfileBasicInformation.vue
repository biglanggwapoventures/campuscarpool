<template>
    <form v-on:submit.prevent="save">
        
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">ID Number</label>
            <div class="col-sm-9">
                <p class="form-control-static mb-0">{{ basicInformation.id_number }}</p>
            </div>
        </div>
        <input-file-inline label="Display Photo" v-model="basicInformation.display_photo" :errors="errors.display_photo"></input-file-inline>
        <input-inline label="First Name" v-model="basicInformation.firstname" :errors="errors.firstname"></input-inline>
        <input-inline label="Last Name" v-model="basicInformation.lastname" :errors="errors.lastname"></input-inline>
        <input-inline label="Email Address" v-model="basicInformation.email" :errors="errors.email"></input-inline>
        <div class="form-group row">
            <div class="offset-sm-3 col-sm-9">
                <button type="submit" class="btn btn-success" v-bind:disabled="loading">Update Basic Information</button>
            </div>
        </div>
        <div class="form-group row">
            <div class="offset-sm-3 col-sm-9">
                <callout callout-class="bs-callout-success" :content="calloutMessage"></callout>
            </div>
        </div>
        
    </form>
</template>

<script>
    export default {
        components: {
            'callout': require('./Callout.vue'),
            'input-inline': require('./InputInline.vue'),
            'input-file-inline': require('./InputFileInline.vue')
        },
        mounted() {
            this.basicInformation = {
                firstname: this.$auth.user().firstname,
                lastname: this.$auth.user().lastname,
                email: this.$auth.user().email,
                id_number: this.$auth.user().id_number,
                display_photo: null
            }
        },
        data() {
            return {
                basicInformation: {},
                payload: new FormData(),
                errors: {},
                showCallout: false,
                calloutMessage: false,
                loading: false
            }
        },
        methods: {
            save() {
                this.loading = true;
                this.$http.post('auth/update-basic-information', this.payload)
                    .then((res) => {
                        this.$auth.fetch();
                        this.calloutMessage = 'Your basic information has been updated successfully!';
                        this.loading = false;
                        this.errors = {};
                    }, (err) => {
                        this.errors = err.data.errors;
                        this.loading = false;
                    })
            }
        },
        watch: {
            'basicInformation.firstname' (val) {
                this.payload.set('firstname', val)
            },
            'basicInformation.lastname' (val) {
                this.payload.set('lastname', val)
            },
            'basicInformation.email' (val) {
                this.payload.set('email', val)
            },
            'basicInformation.display_photo' (val) {
                if(val)  this.payload.set('display_photo', val)
                   
            }
        }
    }

</script>

<style lang="css">

</style>