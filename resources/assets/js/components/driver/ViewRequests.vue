<template>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link :to="{name: 'driver-routes'}">My Routes</router-link></li>
            <li class="breadcrumb-item active">View Ride Requests</li>
        </ol>
        <div class="card card-block">
            <table class="table">
                <thead class="thead-inverse">
                    <tr>
                        <th class="text-xs-center"></th>
                        <th>Name</th>
                        <th>Seats</th>
                        <th>Requested at</th>
                        <th>Rating</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-show="isLoading"><td colspan="6" class="text-xs-center"><i class="fa fa-spin fa-spinner"></i></td></tr>
                    <tr v-if="!isLoading && !rideRequests.length"><td colspan="6" class="text-xs-center">No ride requests.</td></tr>
                    <tr is="request-row" v-for="c in rideRequests" 
                        :id="c.id"
                        :commuter-id="c.commuter_id"
                        :commuter-name="c.commuter.data.name"
                        :accepted="c.accepted"
                        :rejected="c.rejected"
                        :num-seats="c.num_seats"
                        :requested-at="c.requested_at"
                        v-on:open-chat="openChat({id: c.commuter_id, name: c.commuter.data.name})">
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
            'request-row': require('./RequestRow.vue'),
            'chat-box-single': require('./../messages/ChatBoxSingle.vue')
        },
        mounted(){
           this.id = this.$route.params.id;
           this.getData();
        },
        methods: {
            getData() {
                this.$http.get(`driver-routes/${this.id}/requests`)
                    .then((res) => {
                       this.rideRequests = res.body.data
                       this.isLoading = false;
                    }, (err) => {
                        this.isLoading = false;
                    })
            },
            openChat(commuterId){
                this.$refs.chat.open(commuterId);
            }
        },
        data() {
            return {
                id: null,
                rideRequests: [],
                isLoading: true
            }
        }
    }
</script>

<style lang="css">

</style>
