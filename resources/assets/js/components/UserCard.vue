<template>
    <div>
        <div class="card">
            <div class="card-block text-xs-center">
                <img :src="$auth.user().display_photo" class="rounded mx-auto d-block rounded-circle img-fluid img-thumbnail" alt="..." style="margin-bottom:1rem;height:128px;width:128px;">
                <h4 class="card-title mb-0">{{ $auth.user().firstname  + ' ' + $auth.user().lastname }}</h4>
                <p class="card-text mb-0" style="text-transform:capitalize">{{ humanize($auth.user().role) }}</p>
                <div class="row">
                    <div class="col-sm-6 offset-md-3">
                        <p class="card-text text-xs-center">
                            <star-rating :show-rating="false" :star-size="15" :rating="$auth.user().rating" :read-only="true" :increment="0.01"> </star-rating>
                        </p>
                    </div>
                </div>
            </div>
            <div class="list-group list-group-flush" v-if="$auth.user().role === 'DRIVER'">
                <router-link v-bind:class="{'active' : $route.name === 'driver-routes'}" :to="{name: 'driver-routes'}" class="list-group-item list-group-item-action">My Routes</router-link>
                <router-link active-class="active" :to="{name: 'new-route'}" class="list-group-item list-group-item-action">New Route</router-link>
                <router-link active-class="active" :to="{name: 'profile-basic-information'}" class="list-group-item list-group-item-action">Profile</router-link>
            </div>
            <div class="list-group list-group-flush" v-else-if="$auth.user().role === 'COMMUTER'">
                <router-link v-bind:class="{'active' : $route.name === 'browse-routes'}"  :to="{name: 'browse-routes'}" class="list-group-item list-group-item-action">Browse Routes</router-link>
                <router-link active-class="active" :to="{name: 'commuter-requests'}" class="list-group-item list-group-item-action">
                    My Requests 
                </router-link>
                <router-link active-class="active" :to="{name: 'profile-basic-information'}" class="list-group-item list-group-item-action">Profile</router-link>
            </ul>
        </div>
    </div>
    
</template>

<script>
    export default {
        components: {
            'star-rating': require('vue-star-rating')
        },
        mounted(){
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
