<template>
    <div>
        <div class="card">
            <div class="card-block text-xs-center">
                <img :src="$auth.user().display_photo" class="rounded mx-auto d-block rounded-circle img-fluid img-thumbnail" alt="..." style="margin-bottom:1rem;height:128px;width:128px;">
                <h4 class="card-title">{{ `${$auth.user().firstname} ${$auth.user().lastname}` }}</h4>
                <p class="card-text mb-0 text-warning">
                    <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-diamond fa-stack-1x fa-inverse"></i>
                    </span>
                    Administrator
                </p>
            </div>
            <div class="list-group list-group-flush">
                <router-link class="list-group-item list-group-item-action" v-bind:class="{'active' : $route.name === 'admin.users'}" :to="{'name' : 'admin.users'}">Manage Users</router-link>
                <router-link class="list-group-item list-group-item-action" active-class="active" :to="{'name' : 'admin.reports'}">Reports</router-link>
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {
        
    }
</script>
