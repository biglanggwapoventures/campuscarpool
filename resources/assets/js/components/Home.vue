<template>
    <div class="main-content">
        <user-navbar ref="navRef"></user-navbar>
        <div class="container"> 
            <div class="row">
                <div class="col-md-3">
                    <user-card></user-card>
                </div>
                <div class="col-md-9">
                    <router-view v-if="!$auth.user().banned_at"></router-view>
                    <div v-else>
                        <div class="bs-callout bs-callout-danger"><h4> You have been banned for the following reason: </h4> <p>{{ $auth.user().ban_reason }}</p></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        components : {
            'user-card' : require('./UserCard.vue'),
            'user-navbar' : require('./UserNavbar.vue')
        },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                if(!vm.$auth.user().approved_at){
                    vm.$router.replace({name: 'review-account'});
                }
            })
        },
        created() {
            console.log('lol')
            switch(this.$auth.user().role){
                case 'COMMUTER':
                    this.$router.replace({name: 'browse-routes'});
                    break;
                case 'DRIVER':
                    this.$router.replace({name: 'driver-routes'});
                    break;
                case 'ADMIN':
                    this.$router.replace({name: 'admin.users'});
                    break;
                default:
                    break;
            }
            
        }
    }
</script>