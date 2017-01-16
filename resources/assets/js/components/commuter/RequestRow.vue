<template>
    <tr>
        <td>{{ departure }}</td>
        <td>{{ driver }}</td>
        <td>{{ destination }}</td>
        <td>{{ getStatus }}</td>
        <td></td>
        <td><a href="javascript:void(0)" class="btn btn-sm btn-secondary"  @click="openChat"><i class="fa fa-envelope"></i> Message</a></td>
    </tr>
</template>

<script>
    export default {
        props: {
            'id': Number,
            'driver-id': Number,
            'driver': String,
            'status': String,
            'departure': String,
            'destination': String
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
        },
        computed: {
            getStatus (){
                switch(this.status){
                    case 'A': 
                        return 'Accepted'
                    case 'R': 
                        return 'Rejected'
                    case 'P': 
                        return 'Pending'
                }
            }
        }
    }
</script>

<style>

</style>
