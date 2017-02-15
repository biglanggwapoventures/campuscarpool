<template>
    <div class="card card-block">
        <form class="form-inline" v-on:submit.prevent="changeFilter">
            <div class="form-group">
                <ccselect v-model="filter.type"
                    :options="options" :hide-placeholder="true" :value="filter.type"></ccselect>
            </div>
            <button type="submit" class="btn btn-success" v-bind:disabled="loading"><i class="fa fa-spin fa-spinner" v-show="loading"></i> Filter</button>
        </form>
        <table class="table mt-1">
            <thead class="thead-inverse">
                <tr>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Destination</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-show="loading"><td colspan="6" class="text-xs-center"><i class="fa fa-spin fa-spinner"></i></td></tr>
                <tr v-if="!loading && !rideRequests.length"><td colspan="6" class="text-xs-center">Nothing to show.</td></tr>
                <tr is="request-row" v-for="r in rideRequests"
                    :id="r.id"
                    :driver-id="1"
                    :driver="r.driver"
                    :destination="r.destination"
                    :departure="r.departure"
                    :route-from="r.route_from"
                    :route-to="r.route_to"
                    :status="r.finished"
                    v-on:open-chat="openChat({id: r.driver_id, name: r.driver})">
                </tr>
            </tbody>
        </table>
        <chat-box-single ref="chat"></chat-box-single>
    </div>
</template>

<script>
    export default {
        created(){
            this.filter.type = this.$route.params.type;
            this.getData();
        },
        data() {
            return {
                rideRequests: [],
                options: [
                    {value: 'pending', text: 'Pending Requests'},
                    {value: 'accepted', text: 'Accepted Requests'},
                    {value: 'rejected', text: 'Rejected Requests'},
                    {value: 'cancelled', text: 'Cancelled Requests'}
                ],
                filter: {
                    type: null
                }
            }
        },
        methods: {
            openChat(driveId){
                this.$refs.chat.open(driveId);
            },
            getData(){
                this.rideRequests = [];
                this.loading = true;
                this.$http.get('ride-requests/commuter', {
                    params: {type: this.filter.type}
                }).then((res) => {
                    this.rideRequests = res.body.data;
                    this.loading = false;
                }, (err) => {
                    this.loading = false;
                })
            },
            changeFilter() {
                this.$router.push({'name': 'commuter-requests', params: {type: this.filter.type}});
            }
        },
        components: {
             'ccselect' : require('./../Select.vue'),
            'request-row': require('./RequestRow.vue'),
            'chat-box-single': require('./../messages/ChatBoxSingle.vue')
        },
        watch: {
            '$route.params.type'() {
                this.getData()
            }
        }
    }
</script>

<style lang="css">

</style>