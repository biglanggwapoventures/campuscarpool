<template>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Reports</li>
        </ol>
        <div class="row">
            <div class="col-sm-3">
                <div class="card card-block">
                    <form v-on:submit.prevent="changeFilter">
                        <form-input id="filter-name" v-model="filter.name" label="Filter by recipient:" label-class-name="mb-0 font-weight-bold"></form-input>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <datetimepicker 
                                        id="datestart" 
                                        label="Start date"
                                        label-class="mb-0 font-weight-bold"
                                        v-model="filter.start_date"
                                        :enable-time="false">
                                </datetimepicker>
                                
                                <datetimepicker 
                                        id="dateend" 
                                        label="End date"
                                        label-class="mb-0 font-weight-bold"
                                        v-model="filter.end_date"
                                        :enable-time="false">
                                </datetimepicker>
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
                                <th>Date</th>
                                <th>Sender</th>
                                <th>Recipient</th>
                                <th>Message</th>
                            </tr>
                        </thead>    
                        <tbody>
                            <tr v-show="!reports.length && !loading"><td colspan="5" class="text-xs-center text-danger">No results to show.</td></tr>
                            <tr v-show="loading"><td colspan="5" class="text-xs-center"><i class="fa fa-spin fa-spinner"></i></td></tr>
                            <tr v-for="r in reports">
                                <td>{{ r.created_at }}</td>
                                <td>{{ r.sender }}</td>
                                <td> <router-link :to="{name: 'admin.users.view', params: {id: r.recipient_id}}">{{ r.recipient }}</router-link></td>
                                <td>{{ r.message }}</td>
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
             'ccselect' : require('./../Select.vue'),
             'datetimepicker' : require('./../DateTimePicker.vue')
        },
        data() {
            return {
                accountType: [
                    {value: 'drivers', text: 'Driver (Vehicle Owners)'},
                    {value: 'commuters', text: 'Commuters'},
                ],
                filter: {
                    start_date: '',
                    end_date: '',
                },
                reports: [],
                loading: false
            }
        },
        methods: {
            changeFilter(){
                this.reports = [];
                this.loading = true;
                this.$http.get('admin/reports', {params: this.filter})
                    .then((res) => {
                        this.reports = res.body.data;
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