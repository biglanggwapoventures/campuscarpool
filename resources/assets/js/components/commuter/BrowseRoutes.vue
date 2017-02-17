
<template>
    <div>
        <div class="card card-block text-xs-center p-1">
            <search-ride v-on:search-routes="getDriverRoutes"></search-ride>
        </div>
        <div v-show="!loading">
            <div class="bs-callout bs-callout-success" v-if="routeFeed.length">We found <strong class="text-success font-weight-bold">{{ routeFeed.length }} matches!</strong></div>
            <div class="bs-callout bs-callout-success" v-else>No routes available!</div>
        </div>
        
        <driver-route  v-for="v in routeFeed"
            :route-id="v.id"
            :profile-photo-url="v.driver.data.display_photo_filename"
            :driver-name="v.driver.data.name"
            :route-from="v.route_from"
            :route-to="v.route_to"
            :time-posted="v.posted_at"
            :vehicle="v.driver.data.vehicle"
            :space-occupied="v.num_seats_taken"
            :space-max="v.num_seats_max"
            :departure-time="v.departure_datetime">
        </driver-route>
    </div>
</template>

<script>
    import _ from 'lodash';
    export default {
        components : {
            'driver-route' : require('./../DriverRoute.vue'),
            'search-ride' : require('./../SearchRide.vue'),
            'alert' : require('./../messages/Alert.vue'),
        },
        methods: {
            getDriverRoutes(params){
                this.loading = true;
                this.$http.get('driver-routes', {
                    params: params
                }).then((response) => {
                    this.routeFeed = response.body.data
                    this.$emit('search-routes-finished');
                    this.loading = false;
                }, () => {
                    this.loading = true;
                })
            }
        },
        data () {
            return {
                googleplay: 'https://www.wunder.org/images/googleplay.png',
                routeFeed: [],
                loading: false
            }
        }
    }
</script>
<style lang="css">
    
</style>