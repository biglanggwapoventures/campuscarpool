<template>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-4 offset-lg-4 offset-md-2">
                <div class="card">
                    <img class="card-img-top img-fluid" :src="logoUrl" alt="Card image cap">
                    <div class="card-block text-xs-center">
                        <p class="card-text">Campus Carpool is a university-wide carpooling community for students and employees. Enter your home and work location to be matched with friendly colleagues or classmates. Catch a ride to campus for the cost of gas!</p>
                      </div>
                      <div class="card-block">
                        <form  v-on:submit.prevent="login()">
                            <form-input v-model="auth.id_number" label="ID Number" :errors="errors.id_number"></form-input>
                            <form-input v-model="auth.password" label="Password" input-type="password" :errors="errors.password"></form-input>
                             <button type="submit" class="btn btn-success" v-bind:disabled="loading"><i class="fa fa-spin fa-spinner" v-show="loading"></i> Login</button>
                           <!--<router-link class="btn btn-primary" to="home">Login</router-link>-->
                          <hr>
                            
                            <p class="mb-0">No account yet? Get one <router-link :to="{name:'register'}">here</router-link></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    // var auth = require('@websanova/vue-auth');
    export default {
        components: {
            'callout': require('./Callout.vue')
        },
        data(){
            return {
                logoUrl: './../images/LOGO5.png',
                auth: {
                    id_number: '',
                    password: ''
                },
                errors: {},
                loading: false
            }
        },
        methods: {
            login(){
                this.loading = true;
                this.authError = false;
                this.$auth.login({
                    body: this.auth,
                    redirect: false,
                    error(res) {
                        this.loading = false;
                        this.errors = res.body.errors;
                        this.auth.password = '';
                    },
                    success(res){
                        switch(res.body.user.role){
                            case 'DRIVER': 
                                this.$router.replace({'name' : 'driver-routes'});
                                
                                break;
                                
                            case 'COMMUTER':
                                this.$router.replace({'name' : 'browse-routes'})
                                 break;

                            case 'ADMIN': 
                                this.$router.replace({'name' : 'admin.users'})
                                break;
                        }
                    }
                });
            }
        },
        
        
    }
</script>
