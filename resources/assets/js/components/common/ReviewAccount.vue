<template>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 offset-lg-3 offset-md-2">
                    <div class="card">
                        <img class="card-img-top img-fluid" :src="logoUrl" alt="Card image cap">
                        <div class="card-block">
                            <div v-show="!fetchingData">
                                <p class="mb-0">Hello, <strong>{{ `${user.firstname} ${user.lastname}` }}</strong>!</p>
                                <p>Your registered account type: <strong>{{ user.role }}</strong></p>
                                <div v-show="!rejected">
                                    <p> Your account is still being reviewed. Please refer to the information below:</p>
                                    <form v-on:submit.prevent="submit()" v-show="lacking">
                                        <div class="bs-callout bs-callout-danger">
                                            You have lacking requirements. Please comply with the following:
                                        </div>
                                        <div class="row"> 
                                            <div class="col-sm-6">
                                                <file-input id="school-id" :errors="errorMessages.school_id" label="School ID" v-model="requirements.school_id" ></file-input> 
                                            </div>
                                            <div class="col-sm-6" v-if="$auth.user().role === 'DRIVER'">
                                                <file-input id="drivers-license"  :errors="errorMessages.drivers_license" label="Driver's License" v-model="requirements.drivers_license" ></file-input> 
                                            </div>
                                        </div>
                                        <div v-if="$auth.user().role  === 'DRIVER'">   
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <form-select 
                                                        v-model="requirements.vehicle_type" 
                                                        label="Vehicle Type" 
                                                        :errors="errorMessages.vehicle_type" 
                                                        :options="[{value:'MOTORCYCLE', text: 'Motorcycle'}, {value:'CAR', text: 'Car'}]">
                                                    </form-select>
                                                </div>
                                                <div class="col-sm-6">  
                                                    <form-input v-model="requirements.vehicle_plate_number" label="Plate Number" input-type="text" :errors="errorMessages.vehicle_plate_number"></form-input>
                                                </div>
                                            </div>
                                            <form-input v-model="requirements.vehicle_model" label="Vehicle Model" input-type="text" :errors="errorMessages.vehicle_model"></form-input>
                                        </div>
                                        <div class="clearfix">
                                            <button class="btn btn-success float-xs-right" type="submit" href="javascript:void(0)" v-bind:disabled="loading"><i class="fa fa-spin fa-spinner" v-show="loading"></i> Submit</button>
                                        </div>
                                    </form>
                                    <div class="bs-callout bs-callout-success" v-show="!lacking">
                                        You have already submitted all the requirements.
                                    </div>
                                </div>
                                <div v-show="rejected"  class="bs-callout bs-callout-danger">
                                    Your account has been rejected.
                                </div>
                            </div>
                            
                            
                          
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'file-input': require('./../FileInput.vue'),
            'form-select' : require('./../Select.vue')
        },
        data() {
            return {
                user: {},
                fetchingData: true,
                logoUrl: './../images/LOGO5.png',
                requirements: {
                    vehicle_type: null,
                    vehicle_model: null,
                    vehicle_plate_number: null,
                    school_id: null,
                    drivers_license: null
                },   
                lacking: false,
                rejected: false,
                payload: new FormData(),
                errorMessages: {},
                loading: false,
            }
        },
        methods:  {
            getRequirements() {
                this.$http.get('auth/lacking-requirements').then(res => {
                    // this.requirements = res.body.data
                    this.lacking = res.body.data.lacking;
                    this.rejected = res.body.data.rejected;
                    if(this.rejected || !this.lacking){
                        this.logout();
                    }
                    this.fetchingData = false;
                }, err => {})
            },
            submit(){
                this.loading = true;
                this.errorMessages = {};
                this.$http.post('requirements', this.payload)
                    .then((res) => {
                        this.lacking = false;
                        this.logout();
                    }, (err) => {
                        this.errorMessages = err.body.errors;
                        this.loading = false
                    })
            },
            logout() {
                this.$auth.logout({
                    makeRequest: false,
                    redirect: false
                });
            }
        },
        created() {
            this.user = this.$auth.user();
            this.getRequirements();
        },
        watch: {
            'requirements.vehicle_type'(val){
                this.payload.set('vehicle_type', val)
            },
            'requirements.vehicle_model'(val){
                this.payload.set('vehicle_model', val)
            },
            'requirements.vehicle_plate_number'(val){
                this.payload.set('vehicle_plate_number', val)
            },
            'requirements.school_id'(val){
                console.log('lol')
                if(val) this.payload.set('school_id', val)
                else this.payload.remove('school_id')
            },
            'requirements.drivers_license'(val){
                if(val) this.payload.set('drivers_license', val)
                else this.payload.remove('drivers_license')
            },
        },
        computed: {
            requirementsDone() {
                    
            }
        }
    }
</script>

<style lang="css">

</style>