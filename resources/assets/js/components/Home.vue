<template>
    <div class="main-content">
        <user-navbar ref="navRef"></user-navbar>
        <div class="container"> 
            <div class="row">
                <div class="col-md-3">
                    <user-card></user-card>
                </div>
                <div class="col-md-9">
                    <router-view></router-view>
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
        }
    }
</script>