<template>
    <div id="route-page" class="container-fluid p-0">
        <div class="row">
            <div class="col-md-3 pr-0 detail-section">
                <back :route-name="{name: 'driver-route-view-requests', params: {id: $route.params.routeId}}" text="Back to view requests"></back>
                <div class="card-block text-xs-center">
                    <div v-show="!loading">
                        <img :src="commuter.display_photo" class="rounded mx-auto d-block rounded-circle img-fluid img-thumbnail" alt="..." style="height:128px;width:128px;">
                        <h4 class="card-title mb-0">{{ `${commuter.firstname} ${commuter.lastname}`  }}</h4>
                        <p class="card-text text-xs-center">
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                        </p>
                        <div v-if="request.cancelled_at != null">
                            <p class="text-xs-center text-danger">Commuter has cancelled his/her request.</p>
                        </div>
                        <div v-else>
                            <div v-if="!request.rejected && !request.accepted">
                                <a href="javascript:void(0)" class="card-link btn btn-success" @click="accept"><i class="fa fa-check"></i> Accept</a>
                                <a href="javascript:void(0)" class="card-link btn btn-danger"  @click="reject"><i class="fa fa-times"></i> Reject</a>
                            </div>
                            <div v-else>
                                <div v-if="request.accepted">
                                    <span class="fa-stack fa-lg text-success">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-check fa-stack-1x fa-inverse"></i>
                                    </span>
                                    <div v-if="route.done">
                                        
                                    </div>
                                </div>
                                <div v-else>
                                    <span class="fa-stack fa-lg text-danger">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-show="loading" class="text-xs-center">
                        <i class="fa fa-spin fa-spinner fa-3x"></i>
                    </div>
                    <div class="bs-callout bs-callout-danger text-xs-center mt-1" v-show="errorMessage">
                        {{ errorMessage }}
                    </div>
                </div>
            </div>
            <div class="col-md-9 pl-0">
                <ccmap :init-directions-service="true" :init-directions-renderer="true" ref="ccmap" v-on:map-loaded="mapLoaded">
                    <i class="fa fa-spin fa-spinner"></i> 
                </ccmap>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'back': require('./../BackTo.vue'),
            'ccmap': require('./../map/Map.vue'),
        },
        created(){
            this.loading = true;
            this.id = this.$route.params.id;
        },
        data() {
            return {
                route: {},
                commuter: {},
                request: {},
                id :null,
                google : null,
                map : null,
                directionsService : null,
                directionsDisplay : null,
                constants: {
                    USC_PLACE_ID: "ChIJxwSgq-yYqTMRRbMQVEdFmDM"
                },
                loading: false,
                errorMessage: null 
            }
        },
        methods: {
            mapLoaded() {
                this.getData();
            },
            previewRoute() {
                var location = new this.$refs.ccmap.google.maps.LatLng(this.route.place_latitude, this.route.place_longitude),
                    campus = { placeId: this.constants.USC_PLACE_ID };

                if(this.route.type === 'CAMPUS'){
                    this.origin = location;
                    this.destination = campus;
                }else{
                    this.origin = campus;
                    this.destination = location;
                }

                this.$refs.ccmap.route(this.origin, this.destination, this.route.route_index)
                    .then((r) => {
                        this.$refs.ccmap.putWindow(
                            this.request.location_latitude, 
                            this.request.location_longitude,
                            this.request.location_address, 
                            true,
                            true
                        )
                    });


            },
            getData() {
                this.$http.get(`driver-routes/requests/${this.id}`)
                    .then((res) => {
                        this.route = res.body.data.route;
                        this.commuter = res.body.data.commuter;
                        this.request = res.body.data.request;
                        this.previewRoute();
                        setTimeout(() => {
                            this.loading = false;
                        }, 3000)
                        
                    }, (err) => {
                        this.loading = false;
                        this.errorMessage = err.body.message;
                    })
            },
            accept() {
                this.$http.post(`ride-requests/driver/${this.id}/accept`)
                    .then((res) => {
                         this.$router.replace({name: 'driver-route-view-requests', params: {id: this.$route.params.routeId}})
                    }, (err) => {
                        this.errorMessage = err.body.message;
                    })
            },
            reject() {
                this.$http.post(`ride-requests/driver/${this.id}/reject`)
                    .then((res) => {
                        this.$router.replace({name: 'driver-route-view-requests', params: {id: this.$route.params.routeId}})
                    }, (err) => {
                        this.errorMessage = err.body.message;
                    })
            },
        }

    }
</script>

<style lang="css">

</style>