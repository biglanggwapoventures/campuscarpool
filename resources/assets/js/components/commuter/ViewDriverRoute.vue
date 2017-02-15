<template>
    <div id="route-page" class="container-fluid p-0">
        <div class="row">
            <div class="col-md-3 pr-0 detail-section">
                <back :route-name="{name: 'browse-routes'}" text="Back to browsing routes"></back>
                <div class="card-block">
                    <dl class="row">
                        <dt class="col-sm-3 font-weight-bold">From</dt>
                        <dd class="col-sm-9">{{ routeData.route_from }}</dd>
                        <dt class="col-sm-3 font-weight-bold">To</dt>
                        <dd class="col-sm-9">{{ routeData.route_to }}</dd>
                        <dt class="col-sm-3 font-weight-bold">Departure</dt>
                        <dd class="col-sm-9">{{ routeData.departure_datetime }}</dd>
                        <dt class="col-sm-3 font-weight-bold">Vehicle</dt>
                        <dd class="col-sm-9">{{ routeData.driver.data.vehicle || '' }}</dd>
                        <dt class="col-sm-3 font-weight-bold">Space</dt>
                        <dd class="col-sm-9">{{ routeData.num_seats_taken }} / {{ routeData.num_seats_max }}</dd>
                        <dt class="col-sm-3 font-weight-bold">Details</dt>
                        <dd class="col-sm-9">{{ routeData.additional_details }}</dd>
                    </dl>
                    <div class="bs-callout-danger bs-callout" v-if="activeRequest != null && activeRequest.result">
                        You currently have an active ride request. You are unable to make a new request at the moment
                        Click <router-link :to="{name: 'commuter-requests', params: {type: activeRequest.status}}">here</router-link> to view its status
                    </div>
                    <form v-on:submit.prevent="sendRequest" v-if="activeRequest != null && !activeRequest.result">
                        <ccplace 
                            label="Search for a specific place or address" 
                            id="search-place"
                            label-class="mb-0 font-weight-bold"
                            v-on:place-changed="placeChanged">
                        </ccplace>

                         <div class="form-group" v-bind:class="{'text-danger': requestErrors.coordinates}">
                            <label class="font-weight-bold mb-0">
                                {{ placeLabel }}
                                <i class="fa fa-check text-success" v-if="locationValid === true"></i>
                                <i class="fa fa-times text-danger" v-else-if="locationValid === false"></i>
                            </label>
                            <p class="form-control-static pt-0 "><em>{{ routeData.place.formatted_address || 'Please use the address search above or choose a location on the map'}}</em></p>
                        </div>

                        <form-input id="message" v-model="message" label="Send a message to the driver!" :errors="requestErrors.message" label-class-name="mb-0 font-weight-bold"></form-input>
                        <ccbutton btn-type="submit" :loading="requestLoading" btn-class="btn-success btn-block"><i class="fa fa-paper-plane"></i> Send Request</ccbutton>
                        <div class="bs-callout bs-callout-danger" v-show="requestException" id="request-exception">{{ requestException }}</div>
                    </form>
                    
                </div>
            </div>
            <div class="col-md-9 pl-0">
                <!--<div id="map"></div>-->
                <ccmap :init-directions-service="true" :init-directions-renderer="true" ref="ccmap" v-on:map-loaded="mapLoaded" v-on:map-clicked="mapClicked">
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
            'ccplace': require('./../map/Place.vue'),
            'callout' : require('./../Callout.vue'),
        },
        created(){
            this.id = this.$route.params.id;
        },
        data (){
            return { 
                activeRequest: {
                    
                },
                id: null,
                message: null,
                constants: {
                    USC_PLACE_ID: "ChIJxwSgq-yYqTMRRbMQVEdFmDM"
                },
                formatted_address : 'Please specify a location on the map',
                routeData: {
                    place: {
                        
                    },
                    driver: {
                        data: {}
                    },
                    route_from: null,
                    route_to: null,
                    departure_datetime:null

                },
                locationLoading: false,
                locationValid: null,
                locationMarker: null,
                locationWindow: null,
                request: new FormData(),
                requestLoading: false,
                requestException: null,
                requestErrors: {
                }
            }
        },
        methods: {
            mapLoaded (){
                this.userHasActiveRideRequest()
                    .then((res) => {
                        this.activeRequest = res.data;
                        this.getRouteData();
                    }, (err) => {
                        window.alert('An internal server error has occured please try again later. ');
                    })
            }, 
            getRouteData() {
                this.$http.get(`driver-route/${this.id}`).then((res) => {
                    this.routeData = res.body.data;
                    this.request.set('driver_route_id', this.id)
                    this.request.set('num_seats', 1);
                    console.log('data fetched... previewing route...')
                    this.previewRoute();
                }, (err) => {
                    // handle error
                })
            },
            previewRoute(){

                var directionPlaceParam = new this.$refs.ccmap.google.maps.LatLng(this.routeData.place.latitude, this.routeData.place.longitude, this.routeData.place.route_index),
                    campus = {placeId: this.constants.USC_PLACE_ID},
                    origin = this.routeData.type === 'CAMPUS' ? directionPlaceParam : campus,
                    destination = this.routeData.type === 'CAMPUS' ? campus : directionPlaceParam;

                this.$refs.ccmap.route(origin, destination, this.routeData.place.route_index);

            },
            mapClicked(place){
                this.requestErrors.coordinates = null;
                this.checkLocation(place.lat, place.lng, place.address);
            },
            placeChanged(place) {
                this.requestErrors.coordinates = null;
                this.$refs.ccmap.expandViewportToFitPlace(place);
                this.checkLocation(place.geometry.location.lat(), place.geometry.location.lng(), `${place, name}, ${place.formatted_address}`);
                // con
                // sole.log(place);
            },
            checkLocation(lat, lng, address){
                this.routeData.place.formatted_address = address;

                if(this.locationWindow  !== null){
                    this.locationWindow.setOptions({
                        position: {lat, lng},
                        content: `<i class="fa fa-spin fa-spinner"></i>`
                    });
                }else{
                    this.locationWindow = this.$refs.ccmap.putWindow(lat, lng, `<i class="fa fa-spin fa-spinner"></i>`);
                }
                
                if(this.$refs.ccmap.isLocationOnEdge(lat, lng)){
                    this.request.set('formatted_address', address);
                    this.request.set('coordinates', `${lat},${lng}`);
                    this.locationValid = true;
                    this.locationWindow.setContent(`<i class="fa fa-check text-success"></i> ${address}`)
                }else{
                    this.request.delete('formatted_address');
                    this.request.delete('coordinates');
                    this.locationValid = false;
                    this.locationWindow.setContent(`<i class="fa fa-times text-danger"></i> ${address}`)
                }
                
            },
            sendRequest(){
                this.requestException = null;
                this.requestErrors = {};
                this.requestLoading = true;
                this.$http.post('driver-routes/request', this.request)
                .then((res) => {
                    this.requestLoading = false;
                    this.$router.push({name: 'commuter-requests'});
                }, (err) => {
                    if(err.status === 400){
                        this.requestException = err.body.message;
                    }else if(err.status === 422){
                        this.requestErrors = err.body.errors;
                    }
                    this.requestLoading = false;
                })
            },
            userHasActiveRideRequest(){
                return this.$http.get('ride-requests/commuter/active');
            }
        },
        computed: {
            placeLabel() {
                return (this.routeData.type === 'CAMPUS' ? 'Pickup' : 'Dropoff') + ' location';
            }
        },
        watch: {
            message(val) {
                this.request.set('message', val)
            },
            formatted_address(val){
                // console.log(val)
            }
        }
    }
</script>

<style lang="css">
    #route-page dd{
        /*margin-bottom:0*/
    }
    #route-page hr{
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    #route-page #request-exception{
        margin-top: 0.5rem;
    }
</style>