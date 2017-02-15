<template>
    <form v-on:submit.prevent="save">
        <input-inline input-type="password" label="Old Password" v-model="password.old" :errors="errors.old"></input-inline>
        <input-inline input-type="password" label="New Password" v-model="password.new" :errors="errors.new"></input-inline>
        <input-inline input-type="password" label="New Password, Again" v-model="password.new_confirmation" :errors="errors.new_confirmation"></input-inline>
        <div class="form-group row">
            <div class="offset-sm-3 col-sm-9">
                <button type="submit" class="btn btn-success" v-bind:disabled="loading">Change Password</button>
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
            'input-inline': require('./InputInline.vue')
        },
        mounted() {
            this.password = {
                old: null,
                new: null,
                new_confirmation: null,
            }
        },
        data() {
            return {
                password: {},
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
                this.$http.post('auth/change-password', this.payload)
                    .then((res) => {
                        this.calloutMessage = 'Your password has been changed successfully!';
                        this.loading = false;
                        for(var x in this.password){
                            this.password[x] = null;
                        }
                        this.errors = {};
                    }, (err) => {
                        this.errors = err.data.errors;
                        this.loading = false;
                    })
            }
        },
        watch: {
            'password.old' (valerie) {
                this.payload.set('old', valerie)
            },
            'password.new' (valerie) {
                this.payload.set('new', valerie)
            },
            'password.new_confirmation' (valerie) {
                this.payload.set('new_confirmation', valerie)
            },
        }
    }

</script>

<style lang="css">

</style>