<template>
    <div>
        <div class="card">
            <div class="card-block text-xs-center">
                <img :src="$auth.user().display_photo" class="rounded mx-auto d-block rounded-circle img-fluid img-thumbnail" alt="..." style="margin-bottom:1rem;height:128px;width:128px;">
                <h4 class="card-title mb-0">{{ $auth.user().firstname  + ' ' + $auth.user().lastname }}</h4>
                <p class="card-text mb-0" style="text-transform:capitalize">{{ humanize($auth.user().role) }}</p>
                <p class="card-text text-xs-center">
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                </p>
            </div>
            <div class="list-group list-group-flush" v-if="$auth.user().role === 'DRIVER'">
                <router-link active-class="active" :to="{name: 'driver-routes'}" class="list-group-item list-group-item-action">My Routes</router-link>
                <router-link active-class="active" :to="{name: 'new-route'}" class="list-group-item list-group-item-action">New Route</router-link>
                <router-link active-class="active" :to="{name: 'profile-basic-information'}" class="list-group-item list-group-item-action">Profile</router-link>
            </div>
            <div class="list-group list-group-flush" v-else-if="$auth.user().role === 'COMMUTER'">
                <router-link active-class="active" :to="{name: 'browse-routes'}" class="list-group-item list-group-item-action">Browse Routes</router-link>
                <router-link active-class="active" :to="{name: 'commuter-requests'}" class="list-group-item list-group-item-action">
                    My Requests 
                    <span class="badge float-xs-right"><i class="fa fa-exclamation"></i> 3</span>
                </router-link>
                <router-link active-class="active" :to="{name: 'profile-basic-information'}" class="list-group-item list-group-item-action">Profile</router-link>
            </ul>
        </div>
    </div>
    
</template>

<script>
    export default {
        mounted(){
            console.log(this.$auth.user())
        },
        data(){
            return {
                commuteType: '',
                profilePhotoUrl : `./${this.$auth.user().display_photo}`
            }
        },
        methods: {
            humanize: function(str){
                var _str = str.toLowerCase();
                return _str.charAt(0).toUpperCase() + _str.slice(1)
            }
        }
    }
</script>
