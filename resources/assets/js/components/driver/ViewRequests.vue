<template>
    <div>
        <div class="card card-block">
            <form class="form-inline" v-on:submit.prevent="changeFilter">
                <div class="form-group">
                    <ccselect v-model="filter.type"
                        :options="options" :hide-placeholder="true"></ccselect>
                </div>
                <button type="submit" class="btn btn-success" v-bind:disabled="loading"><i class="fa fa-spin fa-spinner" v-show="loading"></i> Filter</button>
            </form>
            <table class="table mt-1">
                <thead class="thead-inverse">
                    <tr>
                        <th>Name</th>
                        <th>Dropoff / Pickup</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-show="loading"><td colspan="6" class="text-xs-center"><i class="fa fa-spin fa-spinner"></i></td></tr>
                    <tr v-if="!loading && !rideRequests.length"><td colspan="6" class="text-xs-center">Nothing to show.</td></tr>
                    <tr is="request-row" v-for="c in rideRequests" 
                        :id="c.id"
                        :commuter-id="c.commuter_id"
                        :commuter-name="c.commuter.data.name"
                        :accepted="c.accepted"
                        :rejected="c.rejected"
                        :num-seats="c.num_seats"
                        :requested-at="c.requested_at"
                        :status="c.status"
                        :location="c.location"
                        v-on:open-chat="openChat({id: c.commuter_id, name: c.commuter.data.name, photo: c.commuter.data.display_photo_filename})"
                        v-on:action-done="getData">
                    </tr>
                </tbody>
            </table>
        </div>
        <chat-box-single ref="chat"></chat-box-single>
    </div>
    
</template>

<script>
    export default {
        components: {
            'ccselect' : require('./../Select.vue'),
            'request-row': require('./RequestRow.vue'),
            'chat-box-single': require('./../messages/ChatBoxSingle.vue')
        },
        mounted(){
            this.filter.type = this.$route.params.type;
            this.id = this.$route.params.id;
            this.getData();
        },
        methods: {
            
            getData() {
                this.loading = true;
                this.rideRequests = [];
                this.$http.get(`driver-routes/${this.id}/requests`, {
                    params: {type: this.filter.type}
                })
                .then((res) => {
                    this.rideRequests = res.body.data
                    this.loading = false;
                }, (err) => {
                    this.loading = false;
                })
            },
            openChat(commuterId){
                this.$refs.chat.open(commuterId);
            },
            changeFilter() {
                this.$router.push({ name: 'driver-route-view-requests', params: {id: this.id, type: this.filter.type} })
            }
        },
        data() {
            return {
                id: null,
                rideRequests: [],
                loading: true,
                type: 'PENDING',
                filter: {
                    type: null
                },
                options: [
                    {value: 'pending', text: 'Pending Requests'},
                    {value: 'accepted', text: 'Accepted Requests'},
                    {value: 'rejected', text: 'Rejected Requests'},
                    {value: 'cancelled', text: 'Cancelled Requests'}
                ]
            }
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
