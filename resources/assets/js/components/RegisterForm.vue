<template>
<div class="content">
<div class="container">
  <div class="row">
  <div class="col-sm-6 offset-sm-3">
    <div class="card">
        <div class="row">
            <div class="col-sm-6">
                <img class="card-img-top img-fluid" :src="logoUrl" alt="Card image cap">
            </div>
        </div>
      <div class="card-block">
        <h4 class="card-title">Want to join the carpool community?</h4>
        <h6 class="card-subtitle text-muted">Let us start by filling up this form</h6>
      </div>
      <div class="card-block clearfix">
        <form v-on:submit.prevent="submit"> 
           <div class="row">
               <div class="col-sm-6">
                    <div class="form-group">
                        <label for="formGroupExampleInput2" class="mb-0">Sign up as</label>
                        <select class="form-control form-control-sm" v-model="user.role">
                            <option value=""></option>
                            <option value="COMMUTER">Commuter</option>
                            <option value="DRIVER">Driver</option>
                        </select>
                    </div>
               </div>
                <div class="col-sm-6">
                  <form-input v-model="user.id_number" label="ID Number" :errors="errorMessages.id_number"></form-input>
              </div>
           </div>
          <div class="row">
            <div class="col-sm-6">
              <form-input v-model="user.firstname" label="First name" :errors="errorMessages.firstname"></form-input>
            </div>
            <div class="col-sm-6">
                <form-input v-model="user.lastname" label="Last name" :errors="errorMessages.lastname"></form-input>
            </div>
          </div>
          <form-input v-model="user.email" label="Email address" input-type="email" :errors="errorMessages.email"></form-input>
          <div class="row">
            <div class="col-sm-6">
                <form-input v-model="user.password" label="Password" input-type="password" :errors="errorMessages.password"></form-input>
            </div>
            <div class="col-sm-6">
                <form-input v-model="user.password_confirmation" label="Password Confirmation" input-type="password" :errors="errorMessages.password_confirmation"></form-input>
            </div>
          </div>
          <div class="row">
              <div class="col-sm-6">
                  <div class="form-group">
                    <label class="mb-0">School ID</label>
                    <input type="file" class="form-control-file form-control-sm">
                </div>  
              </div>
              <div class="col-sm-6" v-show="user.role === 'DRIVER'">
                  <div class="form-group">
                    <label class="mb-0">Driver's License</label>
                    <input type="file" class="form-control-file form-control-sm">
                </div>  
              </div>
          </div>
          <hr>
          <button type="submit" class="btn btn-primary" v-bind:class="{'disabled' : loading}">Submit <i class="fa fa-spin fa-circle-o-notch" v-if="loading"></i></button>
          <router-link to="/" class="btn btn-default float-xs-right">Cancel</router-link>
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
              loading: false
          }
        },
        methods: {
          submit(e){
            this.loading = true;
             this.errorMessages = {};
           this.$http.post('/register', this.user).then((response) => {
              console.log(response)
               this.loading = false
           }, (response) => {
             this.errorMessages = response.body;
              this.loading = false
           })
          }
        }
        
    }
</script>
