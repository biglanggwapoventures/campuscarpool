
<template>
    <div>
        <div class="card card-block text-xs-center p-1">
            <search-ride v-on:search-routes="getDriverRoutes"></search-ride>
        </div>
        <div class="bs-callout bs-callout-success">We found <strong class="text-success font-weight-bold">{{ routeFeed.length }} results</strong> from your search criteria!</div>
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
       <div class="row">
           <div class="col-sm-6" >
                
           </div>
       </div>
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
                this.$http.get('driver-routes', {
                    params: params
                }).then((response) => {
                    this.routeFeed = response.body.data
                }, () => {
                    
                })
            }
        },
        data () {
            return {
                googleplay: 'https://www.wunder.org/images/googleplay.png',
                routeFeed: []
            }
        }
    }
</script>
<style lang="css">
    
</style>