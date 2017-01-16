<template>
    <div class="card card-block">
        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Destination</th>
                    <th>Status</th>
                    <th>Rating</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr is="request-row" v-for="r in requests"
                    :id="r.id"
                    :driver-id="1"
                    :driver="r.driver"
                    :destination="r.destination"
                    :departure="r.departure"
                    :status="r.status"
                    v-on:open-chat="openChat({id: r.driver_id, name: r.driver})">
                </tr>
            </tbody>
        </table>
        <chat-box-single ref="chat"></chat-box-single>
    </div>
</template>

<script>
    export default {
        mounted(){
            this.$http.get('commuter/requests').then((res) => {
                this.requests = res.body.data
            }, (err) => {

            })
        },
        data() {
            return {
                requests: []
            }
        },
        methods: {
            openChat(driveId){
                this.$refs.chat.open(driveId);
            }
        },
        components: {
            'request-row': require('./RequestRow.vue'),
            'chat-box-single': require('./../messages/ChatBoxSingle.vue')
        }
    }
</script>

<style lang="css">

</style>