<template>
    <div>
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-sm-4">
                        <ul class="nav nav-pills nav-stacked">
                            <li class="nav-item">
                                <router-link class="nav-link" v-bind:class="{'active' : $route.name === 'profile-basic-information'}" :to="{name: 'profile-basic-information'}">Basic Information</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" active-class="active" :to="{name: 'profile-change-password'}">Change Password</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" active-class="active" :to="{name: 'profile-requirements'}">Requirements</router-link>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-8">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        mounted(){
            console.log(this.$route)
        }
    }

</script>

<style lang="css">

</style>