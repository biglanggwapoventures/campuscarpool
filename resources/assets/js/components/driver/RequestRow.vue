<template>
    <tr>
        <td>{{ commuterName }}</td>
        <td>{{ requestedAt }}</td>
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
            'status': String
        },
        methods: {
            openChat(){
                this.$emit('open-chat');
            }
        },
        data(){
            return {
                loading: false,
            }
        },
    }
</script>

<style>

</style>
