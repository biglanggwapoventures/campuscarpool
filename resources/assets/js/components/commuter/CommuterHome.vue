
<template>
    <div>
        <search-ride></search-ride>
        <div class="alert alert-info" role="alert">
            Found <b>{{routeFeed.length}} route matches</b> for your search
        </div>
        <driver-route v-for="v in routeFeed"
            :route-id="v.id"
            :profile-photo-url="v.driver.data.display_photo_filename"
            :driver-name="v.driver.data.name"
            :route-from="v.route_from"
            :route-to="v.route_to"
            :time-posted="v.posted_at"
            vehicle="Ford Ranger BHG-009"
            :space-occupied="v.num_seats_taken"
            :space-max="v.num_seats_max"
            :departure-time="v.departure_datetime"
            v-on:view-route="viewRoute(v.id)">
        </driver-route>
        <map-modal :id="mapModal" ref="mapModal"></map-modal>
    </div>
</template>

<script>
    export default {
        components : {
            'driver-route' : require('./../DriverRoute.vue'),
            'search-ride' : require('./../SearchRide.vue'),
            'map-modal' : require('./../messages/ViewRoute.vue')
        },
        mounted (){
            this.getDriverRoutes()
        },
        methods: {
            getDriverRoutes(){
                this.$http.get('driver-routes')
                    .then((response) => {
                        // console.log(response)
                        this.routeFeed = response.body.data
                    }, () => {
                        // handle error
                    })
            },
            viewRoute(routeId){
                this.$refs.mapModal.open(routeId);
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