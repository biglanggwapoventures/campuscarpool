<template>
    <tr>
        <td>{{ departure }}</td>
        <td>{{ driver }}</td>
        <td>
            <dl>
                <dt>From:</dt>
                <dd class="mb-0">{{ routeFrom }}</dd>
                <dt>To:</dt>
                <dd>{{ routeTo }}</dd>
            </dl>
        </td>
        <td>N/A</td>
        <td v-bind:class="status ? 'text-success' : 'text-danger'"> {{ status ? 'Finished' : 'Waiting' }}</td>
        <td>
            <a href="javascript:void(0)" class="btn btn-sm btn-secondary" @click="openChat"><i class="fa fa-fw fa-envelope"></i></a>
            <router-link class="btn btn-sm btn-secondary" :to="{name: 'preview-request', params: {requestId: this.id}}"><i class="fa  fa-fw fa-map-pin"></i></router-link>
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            'id': Number,
            'driver-id': Number,
            'driver': String,
            'status': Boolean,
            'departure': String,
            'destination': String,
            'route-from': String,
            'route-to': String,
        },
        mounted() {
            
        },
        methods: {
           openChat(){
                this.$emit('open-chat');
            }
        },
        data(){
           return {}
        }
    }
</script>

<style lang="css">

</style>
