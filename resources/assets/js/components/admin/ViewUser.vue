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
                                <a href="javascript:void(0)" class="card-link text-danger" @click="startAction('ban')" v-if="user.status.code != 3 && !user.banned"><i class="fa fa-ban"></i> Ban</a>
                                <a href="javascript:void(0)" class="card-link text-success" @click="startAction('unban')" v-if="user.status.code == 3 && user.banned"><i class="fa fa-ban"></i> Unban</a>
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
                <div class="bs-callout bs-callout-danger" v-if="user.reports.length">
                    {{ user.name.fullname }} has <strong>{{ user.reports.length }}</strong> reports in total!
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
                                <tr>
                                    <td>School ID Photo</td>
                                    <td>
                                        <a v-if="user.requirements.school_id"  @click="view('school_id')" class="text-success">View</a>
                                        <span v-else>User has not uploaded a school id</span>
                                    </td>   
                                </tr>
                                <tr v-if="user.account_type === 'Driver'">
                                    <td>Driver's License Photo</td>
                                    <td>
                                        <a v-if="user.requirements.drivers_license" @click="view('drivers_license')" class="text-success">View</a>
                                        <span v-else>User has not uploaded a driver's' license</span>
                                    </td>  
                                </tr>
                                <tr v-if="user.account_type === 'Driver'">
                                    <td>Vehicle Model</td>
                                    <td>
                                        <span v-if="user.requirements.vehicle_model">{{ user.requirements.vehicle_model }}</span>
                                        <span v-else>User has not specified a vehicle model</span>
                                    </td>   
                                        
                                </tr>
                                <tr v-if="user.account_type === 'Driver'">
                                     <td>Vehicle Plate Number</td>
                                     <td>
                                        <span v-if="user.requirements.plate_number">{{ user.requirements.plate_number }}</span>
                                        <span v-else>User has not specified a vehicle plate number</span>
                                    </td>   
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content" style="background-color: transparent;background-clip: padding-box;border: 0">
                    <div class="modal-header p-0" style="border-bottom: 0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0 text-xs-center">
                        <img :src="viewPhoto" v-bind:alt="viewPhoto" class="img-fluid">
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
                user: {requirements: {}, name: {}, status: {}, reports: []},
                actionStarted: false,
                action: null,
                loading: false,
                viewPhoto: null
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
            },
            view(file) {
                this.viewPhoto = this.user.requirements[file];
                $('#view').modal('show')
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