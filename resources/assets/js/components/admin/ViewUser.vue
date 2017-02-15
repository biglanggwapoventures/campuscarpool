<template>
    <div>
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link :to="{'name': 'admin.users'}">Manage Users</router-link></li>
            <li class="breadcrumb-item active">View</li>
        </ol>
        <div class="row">
            <div class="col-sm-6 text-xs-center">
                <div class="card">
                    <div class="card-block" >
                        <div v-show="!loading">
                            <img :src="user.requirements.display_photo" class="rounded mx-auto d-block rounded-circle img-fluid img-thumbnail mb-1" alt="..." style="height:128px;width:128px;">
                                <h4 class="card-title mb-0">{{ `${user.name.fullname}` }}</h4>
                            <p>{{ user.status.label }}</p>
                            <div v-show="!actionStarted">
                                <a href="javascript:void(0)" class="card-link text-success" @click="startAction('approve')" v-if="user.status.code == 0"><i class="fa fa-check"></i> Approve</a>
                                <a href="javascript:void(0)" class="card-link text-warning" @click="startAction('reject')" v-if="user.status.code == 0"><i class="fa fa-times"></i> Reject</a>
                                <a href="javascript:void(0)" class="card-link text-danger" @click="startAction('remove')"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                            <transition name="slide-fade">
                                <div v-show="actionStarted">
                                    <a href="javascript:void(0)" class="card-link text-info">Are you sure?</a>
                                    <a href="javascript:void(0)" class="card-link" @click="confirmAction">Yes</a>
                                    <a href="javascript:void(0)" class="card-link" @click="endAction">Cancel</a>
                                </div>
                            </transition>
                        </div>
                        <div v-show="loading" class="text-xs-center">
                            <i class="fa fa-spin fa-spinner fa-3x"></i>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-block">
                    <div v-show="loading" class="text-xs-center">
                        <i class="fa fa-spin fa-spinner fa-3x"></i>
                    </div>
                    <div v-show="!loading">
                         <table class="table table-striped" id="basic">
                            <thead class="thead-inverse">
                                <tr>
                                    <th colspan="2" class="text-xs-center">Basic Information</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                <tr><td>ID #</td><td>{{ user.id_number }}</td> </tr>
                                <tr><td>Email</td><td>{{ user.email }}</td> </tr>
                                <tr><td>First Name</td><td>{{ user.name.firstname }}</td> </tr>
                                <tr><td>Last Name</td><td>{{ user.name.lastname }}</td> </tr>
                                <tr><td>Account Type</td><td>{{ user.account_type }}</td> </tr>
                                <tr><td>Registered At</td><td>{{ user.joined_at }}</td> </tr>
                            </tbody>
                        </table>
                        <table class="table table-striped mt-1" id="requirements">
                            <thead class="thead-inverse">
                                <tr>
                                    <th colspan="2" class="text-xs-center">Requirements</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                <tr><td>School ID Photo</td><td></td> </tr>
                                <tr v-if="user.account_type === 'Driver'"><td>Driver's License Photo</td><td></td> </tr>
                                <tr v-if="user.account_type === 'Driver'"><td>Vechile Model</td><td>{{ user.requirements.vehicle_model }}</td> </tr>
                                <tr v-if="user.account_type === 'Driver'"><td>Plate Number</td><td>{{ user.requirements.plate_number }}</td> </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        created(){
            this.id = this.$route.params.id;
           
        },
        mounted() {
            this.getData();
        },
        data () {
            return {
                id: null,
                user: {requirements: {}, name: {}, status: {}},
                actionStarted: false,
                action: null,
                loading: false
            }
        },
        methods: {
            getData(){
                 this.loading = true;
                this.$http.get(`admin/user/${this.id}`)
                    .then(res => {
                        this.user = res.body.data
                        this.loading = false;
                    }, err => {
                        this.loading = false;
                    })
            },
            startAction(action) {
                this.actionStarted = true;
                this.action = action
            },
            endAction() {
                this.actionStarted = false;
                this.action = null
            },
            confirmAction(){
                this.$http.patch(`admin/user/${this.id}`, {
                    action: this.action
                }).then(res => {
                    if(this.action === 'remove'){
                        this.$router.replace({'name': 'admin.users'})
                    }else{
                        this.getData();
                        this.endAction();
                    }
                }, err => {

                })
            }
        }
    }
</script>


<style lang="css">
        /* Enter and leave animations can use different */
    /* durations and timing functions.              */
    .slide-fade-enter-active {
    transition: all .3s ease;
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active for <2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
    }
</style>