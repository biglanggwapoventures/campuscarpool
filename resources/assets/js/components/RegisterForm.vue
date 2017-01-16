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
                                            <input v-model="tempUser.role" type="radio" value="COMMUTER" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Sign up to ride</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input v-model="tempUser.role" type="radio" value="DRIVER" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Sign up to drive</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="clearfix">
                                <a class="btn btn-info float-xs-right" href="javascript:void(0)" v-on:click="next()" v-bind:class="{'disabled' : !tempUser.role}" role="button">Next</a>
                            </div>
                        </div>
                        <div class="card-block " v-show="step === 2">
                            <form v-on:submit.prevent="submitBasicInfo()"> 
                            <div class="row">

                                <div class="col-sm-6">
                                    <form-input v-model="tempUser.firstname" label="First name" :errors="errorMessages.firstname"></form-input>
                                </div>
                                <div class="col-sm-6">
                                    <form-input v-model="tempUser.lastname" label="Last name" :errors="errorMessages.lastname"></form-input>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <form-input v-model="tempUser.id_number" label="ID Number" :errors="errorMessages.id_number"></form-input>
                                </div>
                                <div class="col-sm-6">
                                    <form-input v-model="tempUser.email" label="Email address" input-type="email" :errors="errorMessages.email"></form-input>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-sm-6">
                                    <form-input v-model="tempUser.password" label="Password" input-type="password" :errors="errorMessages.password"></form-input>
                                </div>
                                <div class="col-sm-6">
                                    <form-input v-model="tempUser.password_confirmation" label="Password Confirmation" input-type="password" :errors="errorMessages.password_confirmation"></form-input>
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
                                    <file-input id="school-id" v-on:file-changed="addPhoto" :errors="errorMessages.school_id" label="School ID"  file-label="school_id"></file-input> 
                                </div>
                                <div class="col-sm-6" v-if="tempUser.role === 'DRIVER'">
                                    <file-input id="drivers-license" v-on:file-changed="addPhoto" :errors="errorMessages.drivers_license" label="School ID"  file-label="drivers_license"></file-input>
                                </div>
                            </div>
                            <div v-if="tempUser.role === 'DRIVER'">   
                                <div class="row">
                                    <div class="col-sm-6">
                                         <form-select 
                                            v-model="tempUser.requirements.vehicle_type" 
                                            label="Vehicle Model" 
                                            :errors="errorMessages.vehicle_type" 
                                            :options="[{value:'MOTORCYCLE', text: 'Motorcycle'}, {value:'CAR', text: 'Car'}]">
                                        </form-select>
                                    </div>
                                    <div class="col-sm-6">  
                                        <form-input v-model="tempUser.requirements.vehicle_plate_number" label="Plate Number" input-type="text" :errors="errorMessages.vehicle_plate_number"></form-input>
                                    </div>
                                </div>
                                <form-input v-model="tempUser.requirements.vehicle_model" label="Vehicle Model" input-type="text" :errors="errorMessages.vehicle_model"></form-input>
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
        components: {
            'file-input': require('./File.vue'),
            'form-select' : require('./Select.vue')
        },
        data(){
            return {
                logoUrl: './../images/LOGO5.png',
                user: new FormData(),
                requirements: new FormData(),
                tempUser : {
                    firstname: '',
                    lastname: '',
                    id_number: '',
                    role: '',
                    password: '',
                    password_confirmation: '',
                    email: '',
                    requirements: {
                        vehicle_type: null,
                        vehicle_model: null,
                        vehicle_plate_number: null,
                        school_id: null,
                        drivers_license: null
                    },
                    id:null
                },
                errorMessages: {},
                loading: false,
                step: 1
            }
        },
        watch: {
            'tempUser.firstname' (val){
                this.user.set('firstname', val)
            },
            'tempUser.lastname'(val){
                this.user.set('lastname', val)
            },
            'tempUser.id_number'(val){
                this.user.set('id_number', val)
            },
            'tempUser.role'(val){
                this.user.set('role', val)
            },
            'tempUser.password'(val){
                this.user.set('password', val)
            },
            'tempUser.password_confirmation'(val){
                this.user.set('password_confirmation', val)
            },
            'tempUser.email'(val){
                this.user.set('email', val)
            },
            'tempUser.requirements.vehicle_type'(val){
                console.log(val)
                this.requirements.set('vehicle_type', val)
            },
            'tempUser.requirements.vehicle_model'(val){
                this.requirements.set('vehicle_model', val)
            },
            'tempUser.requirements.vehicle_plate_number'(val){
                this.requirements.set('vehicle_plate_number', val)
            },
        },
        methods: {
            submitBasicInfo(e){

                this.loading = true;
                this.errorMessages = {};
                this.$http.post('auth/register', this.user, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((res) => {
                    this.$auth.login({
                        body: {
                            id_number: this.tempUser.id_number,
                            password: this.tempUser.password
                        },
                        success(res) {
                            this.loading = false;
                            this.next();
                        },
                        error (err){

                        },
                        rememberMe: false,
                        redirect: false
                    });
                }, (err) => {
                    this.errorMessages = err.body.errors;
                    this.loading = false
                })
            },
            submitRequirements(e){
                this.loading = true;
                this.errorMessages = {};
                this.$http.post('requirements', this.requirements)
                    .then((res) => {
                        this.$router.go({'name': 'dashboard'})
                    }, (err) => {
                        this.errorMessages = err.body.errors;
                        this.loading = false
                    })
            },
            addPhoto(value){
                console.log(value)
                this.requirements.set(value.label, value.file);
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
