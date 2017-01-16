<template>
    <tr>
        <td class="text-xs-center">
            <span v-if="!isAccepted && !isRejected">
                <span v-show="!loading">
                    <span v-show="!confirmPhase">
                        <button type="button" class="btn btn-sm btn-success" @click="showConfirmation('a')"><i class="fa fa-check"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" @click="showConfirmation('r')"><i class="fa fa-ban"></i></button>
                    </span>
                    <span v-show="confirmPhase">
                        Are you sure? <a href="javascript:void(0)" @click="confirmAction(true)">Y</a> / <a @click="confirmAction(false)" href="javascript:void(0)">N</a> 
                    </span>
                </span>
                <span v-show="loading"><i class="fa fa-spinner fa-pulse"></i></span>
            </span>
            <i class="fa fa-check text-success" v-else-if="isAccepted"></i>
            <i class="fa fa-ban text-danger" v-else></i>
        </td>
        <td>{{ commuterName }}</td>
        <td>{{ numSeats }}</td>
        <td>{{ requestedAt }}</td>
        <td><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i></td>
        <td><a href="javascript:void(0)" class="btn btn-sm btn-secondary" @click="openChat"><i class="fa fa-envelope"></i> Message</a></td>
    </tr>
</template>

<script>
    export default {
        props: {
            'id': Number,
            'commuter-id': Number,
            'commuter-name': String,
            'accepted': Boolean,
            'rejected': Boolean,
            'num-seats': Number,
            'requested-at': String
        },
        mounted() {
            this.isAccepted = this.accepted;
            this.isRejected = this.rejected;
        },
        methods: {
            confirmAction(confirmation) {
                if(!confirmation){ 
                    this.confirmPhase = false;
                    return;
                }
                this.loading = true;
                this.$http.patch(`driver-routes/requests/${this.id}`, {status: this.statusType})
                    .then((res) => {
                        if(this.statusType === 'a') 
                            this.isAccepted = true;
                        else 
                            this.isRejected = true;
                        this.loading = false;
                        this.confirmPhase = false;
                    }, (err) => {
                        this.loading = false;
                        this.confirmPhase = false;
                    })
            },
            showConfirmation(statusType) {
                this.statusType = statusType;
                this.confirmPhase = true;
            },
            openChat(){
                this.$emit('open-chat');
            }
        },
        data(){
            return {
                loading: false,
                isAccepted: false,
                isRejected: false,
                confirmPhase: false,
                statusType: null
            }
        }
    }
</script>

<style>

</style>
