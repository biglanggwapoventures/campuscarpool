<template>
<div class="content">
<div class="container">
  <div class="row">
  <div class="col-sm-8 offset-sm-2">
    <div class="card">
        <div class="row">
            <div class="col-sm-4 offset-sm-4">
                <img class="card-img-top img-fluid" :src="logoUrl" alt="Card image cap">
            </div>
        </div>
      <div class="card-block">
        <h4 class="card-title">Create an account by following these steps</h4>
        <nav class="breadcrumb registration mb-0">
            <a class="breadcrumb-item" href="javascript:void(0)" v-bind:class="{'active' : step === 1}">1 Choose type</a>
            <a class="breadcrumb-item" href="javascript:void(0)" v-bind:class="{'active' : step === 2}">2 Basic Information</a>
            <a class="breadcrumb-item" href="javascript:void(0)" v-bind:class="{'active' : step === 3}">3 Requirements</a>
        </nav>
      </div>
      <div class="card-block text-xs-center" v-show="step === 1">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="custom-control custom-radio">
                        <input v-model="user.role" type="radio" value="COMMUTER" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Sign up to ride</span>
                    </label>
                    <label class="custom-control custom-radio">
                        <input v-model="user.role" type="radio" value="DRIVER" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Sign up to drive</span>
                    </label>
                </div>
            </div>
        </div>
        <hr>
        <div class="clearfix">
            <a class="btn btn-info float-xs-right" href="javascript:void(0)" v-on:click="next()" v-bind:class="{'disabled' : !user.role}" role="button">Next</a>
        </div>
      </div>
      <div class="card-block " v-show="step === 2">
        <form v-on:submit.prevent="submitBasicInfo()"> 
          <div class="row">

            <div class="col-sm-6">
              <form-input v-model="user.firstname" label="First name" :errors="errorMessages.firstname"></form-input>
            </div>
            <div class="col-sm-6">
                <form-input v-model="user.lastname" label="Last name" :errors="errorMessages.lastname"></form-input>
            </div>
          </div>
          <div class="row">
              <div class="col-sm-6">
              <form-input v-model="user.id_number" label="ID Number" :errors="errorMessages.id_number"></form-input>
            </div>
          <div class="col-sm-6">
              <form-input v-model="user.email" label="Email address" input-type="email" :errors="errorMessages.email"></form-input>
          </div>
            </div>
          
          <div class="row">
            <div class="col-sm-6">
                <form-input v-model="user.password" label="Password" input-type="password" :errors="errorMessages.password"></form-input>
            </div>
            <div class="col-sm-6">
                <form-input v-model="user.password_confirmation" label="Password Confirmation" input-type="password" :errors="errorMessages.password_confirmation"></form-input>
            </div>
          </div>
          <hr>
          <div class="clearfix">
            <a class="btn btn-info" role="button" href="javascript:void(0)" v-on:click="previous()">Previous</a>
            <button class="btn btn-info float-xs-right" type="submit" href="javascript:void(0)" v-bind:disabled="loading"><i class="fa fa-spin fa-spinner" v-show="loading"></i> Next</button>
        </div>
        </form>
      </div>
      <div class="card-block" v-show="step === 3">
            <form v-on:submit.prevent="submitRequirements()"> 
          <div class="row">
              <div class="col-sm-6">
                  <div class="form-group">
                    <label class="mb-0">School ID</label>
                    <input type="file" class="form-control-file">
                </div>  
              </div>
              <div class="col-sm-6" v-show="user.role === 'DRIVER'">
                  <div class="form-group">
                    <label class="mb-0">Driver's License</label>
                    <input type="file" class="form-control-file">
                </div>  
              </div>
          </div>
          <hr>
          <div class="clearfix">
            <button class="btn btn-info float-xs-right" type="submit" href="javascript:void(0)" v-bind:disabled="loading"><i class="fa fa-spin fa-spinner" v-show="loading"></i> Finish</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component ready.')
        },
        data(){
          return {
              logoUrl: './../images/LOGO5.png',
              user : {
                firstname: '',
                lastname: '',
                id_number: '',
                role: '',
                password: '',
                password_confirmation: '',
                email: '',
              },
              errorMessages: {},
              loading: false,
              step: 1
          }
        },
        methods: {
          submitBasicInfo(e){
            this.loading = true;
             this.errorMessages = {};
           this.$auth.register({
                autoLogin: false,
                redirect: null,
               redirect: {name: 'register'},
               body: this.user,
               success: (res) => {
                   this.loading = false;
                    this.next();
               },
               error: function (res) {
                   this.errorMessages = res.body.errors;
                    this.loading = false
               },
               
           })
          },
          submitRequirements(e){
            this.loading = true;
             this.errorMessages = {};
           this.$http.post('/register', this.user).then((response) => {
               this.loading = false
           }, (response) => {
              this.errorMessages = response.body;
              this.loading = false
           })
          },
          next(){
              this.step++;
          },
          previous(){
              this.step--;
          }
        }
        
        
    }
</script>
