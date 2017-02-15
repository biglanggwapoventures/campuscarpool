<template>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Manage Users</li>
        </ol>
        <div class="row">
            <div class="col-sm-3">
                <div class="card card-block">
                    <form v-on:submit.prevent="changeFilter">
                        <form-input id="filter-name" v-model="filter.name" label="Filter by name:" label-class-name="mb-0 font-weight-bold"></form-input>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filter by status</label>
                                    <div class="custom-controls-stacked">
                                        <label class="custom-control custom-radio">
                                            <input v-model="filter.status" value="approved" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Approved</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input v-model="filter.status" value="pending" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Pending</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input v-model="filter.status" value="rejected"  type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Rejected</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input v-model="filter.status" value=""  type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">None</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filter by type</label>
                                    <div class="custom-controls-stacked">
                                        <label class="custom-control custom-radio">
                                            <input v-model="filter.type" value="driver" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Driver</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input v-model="filter.type" value="commuter"  type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Commuter</span>
                                        </label>
                                         <label class="custom-control custom-radio">
                                            <input v-model="filter.type" value=""  type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">None</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block" v-bind:disabled="loading"><i class="fa fa-spin fa-spinner" v-show="loading"></i> Filter</button>
                    </form>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="card card-block">
                    <table class="table table-sm">
                        <thead class="thead-inverse">
                            <tr>
                                <th>Name</th>
                                <th>ID #</th>
                                <th>Account Type</th>
                                <th>Date Joined</th>
                                <th></th>
                            </tr>
                        </thead>    
                        <tbody>
                            <tr v-show="!users.length && !loading"><td colspan="5" class="text-xs-center text-danger">No results to show.</td></tr>
                            <tr v-show="loading"><td colspan="5" class="text-xs-center"><i class="fa fa-spin fa-spinner"></i></td></tr>
                            <tr v-for="u in users">
                                <td> <router-link :to="{name: 'admin.users.view', params: {id: u.id}}">{{ u.name.fullname }}</router-link></td>
                                <td>{{ u.id_number }}</td>
                                <td>{{ u.account_type }}</td>
                                <td>{{ u.joined_at }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
             'ccselect' : require('./../Select.vue')
        },
        data() {
            return {
                accountType: [
                    {value: 'drivers', text: 'Driver (Vehicle Owners)'},
                    {value: 'commuters', text: 'Commuters'},
                ],
                filter: {
                    type: '',
                    status: '',
                    name: ''
                },
                users: [],
                loading: false
            }
        },
        methods: {
            changeFilter(){
                this.users = [];
                this.loading = true;
                this.$http.get('admin/users', {params: this.filter})
                    .then((res) => {
                        this.users = res.body.data;
                        this.loading = false;
                    }, (err) => {
                        this.loading = false;
                    })
            }
        }
    }
</script>


<style lang="css">

    #admin .breadcrumb{
        background: #fff
    }
</style>