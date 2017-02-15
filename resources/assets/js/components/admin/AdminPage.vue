<template>
    <div class="main-content" id="admin">
        <user-navbar ref="navRef"></user-navbar>
        <div class="container"> 
            <div class="row">
                <div class="col-md-3">
                    <me></me>
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
        components: {
            'me' : require('./AdminCard.vue'),
            'user-navbar' : require('./../UserNavbar.vue')
        }
    }
</script>


<style lang="css">
</style>