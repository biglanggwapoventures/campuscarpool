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
                            <div class="alert alert-danger text-xs-center" v-show="authError">{{authError}}</div>
                            <form-input v-model="auth.id_number" label="ID Number"></form-input>
                            <form-input v-model="auth.password" label="Password" input-type="password"></form-input>
                          <!--<div class="form-group">
                            <label for="formGroupExampleInput" class="mb-0">Username</label>
                            <input type="text" class="form-control form-control-sm" id="formGroupExampleInput" v-model="auth.id_number">
                          </div>
                          <div class="form-group">
                            <label for="formGroupExampleInput" class="mb-0">Password</label>
                            <input type="text" class="form-control form-control-sm" id="formGroupExampleInput" v-model="auth.password">
                          </div>-->
                          <button type="submit" class="btn btn-primary" v-bind:disabled="loading"><i class="fa fa-spin fa-spinner" v-show="loading"></i> Login</button>
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
        data(){
            return {
                logoUrl: './../images/LOGO5.png',
                auth: {
                    id_number: '',
                    password: ''
                },
                authError: false,
                loading: false
            }
        },
        methods: {
            login(){
                this.loading = true;
                this.authError = false;
                this.$auth.login({
                    body: this.auth,
                    redirect: {name: 'home'},
                    error(res) {
                        this.authError = res.body.message;
                        this.loading = false;
                    }
                });
            }
        },
        
        
    }
</script>
