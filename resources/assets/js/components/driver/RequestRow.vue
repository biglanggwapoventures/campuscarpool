<template>
    <tr>
        <td>
            <div>
                {{ commuterName }}
            </div>
            <template v-if="status === 'Pending'">
                <div v-show="!actionStarted" class="font-weight-bold">
                    <a href="javascript:void(0)" @click="startAction('accept')" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a>
                    <a href="javascript:void(0)" @click="startAction('reject')" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                </div>
                <transition name="slide-fade">
                    <div v-show="actionStarted">    
                        <a href="javascript:void(0)" class="text-info">Are you sure?</a>
                        <div>
                            <a href="javascript:void(0)" @click="confirmAction" class="btn btn-secondary btn-sm">Yes</a>
                            <a href="javascript:void(0)" @click="endAction"  class="btn btn-secondary btn-sm">Cancel</a>
                        </div>
                    </div>
                </transition>
            </template>
            
        </td>
        <td>
            <div>
                {{ location }}
            </div>
            
        </td>
        <td>
            <a href="javascript:void(0)" class="btn btn-sm btn-secondary" @click="openChat"><i class="fa fa-envelope"></i></a>
            <router-link :to="{name: 'preview-route', params: {routeId:$route.params.id, id:id}}" class="btn btn-sm btn-secondary"><i class="fa fa-map-marker"></i></router-link>
        </td>
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
            'requested-at': String,
            'location': String,
            'status': String

        },
        methods: {
            openChat(){
                this.$emit('open-chat');
            },
            startAction(action) {
                this.actionStarted = true;
                this.action = action
            },
            endAction() {
                this.actionStarted = false;
                this.action = null
            },
            confirmAction() {
                 this.$http.post(`ride-requests/driver/${this.id}/${this.action}`)
                    .then((res) => {
                         this.$emit('action-done');
                    }, (err) => {
                       window.alert('An internal server error has occured!');
                    })
            }
        },
        data(){
            return {
                loading: false,
                actionStarted: false,
                action: null
            }
        },
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