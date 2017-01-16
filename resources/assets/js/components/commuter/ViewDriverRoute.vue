<template>
    <div id="route-page" class="container-fluid p-0">
        <div class="row">
            <div class="col-md-3 pr-0 detail-section">
                <back-to-dashboard :route-name="{name: 'browse-routes'}" text="Back to browsing routes"></back-to-dashboard>
                <div class="card-block">
                    <dl class="row">
                        <dt class="col-sm-3">From</dt>
                        <dd class="col-sm-9">{{ routeInfo.route_from }}</dd>
                        <dt class="col-sm-3">To</dt>
                        <dd class="col-sm-9">{{ routeInfo.route_to }}</dd>
                        <dt class="col-sm-3">Departure</dt>
                        <dd class="col-sm-9">{{ routeInfo.departure_datetime }}</dd>
                        <dt class="col-sm-3">Vehicle</dt>
                        <dd class="col-sm-9">{{ routeInfo.driver.data.vehicle || '' }}</dd>
                        <dt class="col-sm-3">Space</dt>
                        <dd class="col-sm-9">{{ routeInfo.num_seats_taken }} / {{ routeInfo.num_seats_max }}</dd>
                    </dl>
                    <hr>
                    <form v-on:submit.prevent="sendRequest">
                        <form-input id="pick-up" label="Pick-up point" input-class-name="form-control-sm" :errors="requestErrors.pickup" label-class-name="mb-0"></form-input>
                        <form-input id="drop-off" label="Drop-off point" input-class-name="form-control-sm"  :errors="requestErrors.dropoff" label-class-name="mb-0"></form-input>
                        <form-input id="message" v-model="message" label="Message" input-class-name="form-control-sm" :errors="requestErrors.message" label-class-name="mb-0"></form-input>
                        <ccbutton btn-type="submit" :loading="requestLoading" btn-class="btn-success btn-block"><i class="fa fa-paper-plane"></i> Send Request</ccbutton>
                        <div class="alert alert-danger" v-show="requestException" id="request-exception">{{ requestException }}</div>
                    </form>
                    
                </div>
            </div>
            <div class="col-md-9 pl-0">
                <div id="map"></div>
            </div>
        </div>
        <alert id="loading" ref="alert"></alert>
    </div>
</template>

<script>
    
    export default {
        mounted (){

            this.$refs.alert.show({
                message: 'Loading map...',
                showActions: false
            });
           
            var GoogleMapsLoader = require('google-maps');

            GoogleMapsLoader.KEY = 'AIzaSyCueneuLIZWACMGBDBQaYix4vE9X1UkP_0';
            GoogleMapsLoader.REGION = 'PH';  
            GoogleMapsLoader.LIBRARIES = ['places', 'geometry'];    
            
            GoogleMapsLoader.load((google)  => {
                this.google = google;
                this.getRouteData(this.$route.params.id);
            });
            
        },
        created(){
            
        },
        data (){
            return { 
                zoom: 18,
                message: null,
                map: null,
                directionsService: null,
                directionsDisplay: null,
                placesService: null,
                distanceMatrixService: null,
                waypoints: [],
                routePoints: [],
                markers: {
                    pickup: null,
                    dropoff: null
                },
                constants : {
                    CAMPUS_LATLNG: null
                },
                routeInfo: {
                    driver: {
                        data: {}
                    }
                },
                request: new FormData(),
                requestLoading: false,
                requestException: null,
                requestErrors: {
                }
            }
        },
        methods: {
            initMap(initFlag){
                // set usc tc constant coordinates
                this.constants.CAMPUS_LATLNG = new this.google.maps.LatLng(10.351887545991266, 123.9138400554657);

                // instantiate map
                this.map = new this.google.maps.Map(document.getElementById('map'), {
                    center: this.constants.CAMPUS_LATLNG,
                    zoom: this.zoom,
                    type: 'hybrid'
                });

                // set google map services
                this.directionsService = new this.google.maps.DirectionsService;
                this.directionsDisplay = new this.google.maps.DirectionsRenderer({
                    map: this.map,
                });
                this.placesService = new this.google.maps.places.PlacesService(this.map);
                this.distanceMatrixService = new this.google.maps.DistanceMatrixService();

                this.markers.pickup = new this.google.maps.Marker({ map : this.map, draggable : true });
                this.markers.dropoff = new this.google.maps.Marker({ map : this.map, draggable : true });

                // add auto complete for places in pickup and dropoff
                var pickup = new this.google.maps.places.Autocomplete(document.getElementById('pick-up')),
                    dropoff = new this.google.maps.places.Autocomplete(document.getElementById('drop-off'));

                
                this.google.maps.event.addListener(this.markers.pickup, 'dragend', (evt) => {
                    this.checkLocation(evt.latLng, 'pickup', (result) => {
                        this.setWaypoint('pickup', result);
                    })
                });

                this.google.maps.event.addListener(this.markers.dropoff, 'dragend', (evt) => {
                    this.checkLocation(evt.latLng, 'dropoff', (result) => {
                        this.setWaypoint('dropoff', result);
                    })
                });

                pickup.addListener('place_changed', (val) => {
                    var place = pickup.getPlace();
                    if (!place.geometry) {
                        window.alert("Autocomplete's returned place contains no geometry");
                        return;
                    }
                    this.checkLocation(place.geometry.location, 'pickup', (result) => {
                        this.setWaypoint('pickup', result);
                    })
                });

                dropoff.addListener('place_changed', (val) => {
                    var place = dropoff.getPlace();
                    if (!place.geometry) {
                        window.alert("Autocomplete's returned place contains no geometry");
                        return;
                    }
                    this.checkLocation(place.geometry.location, 'dropoff', (result) => {
                        this.setWaypoint('dropoff', result);
                    })
                });


                this.drawRoute(initFlag);
                // this.initPlaceAutocomplete();
            },
            expandViewportToFitPlace(place){
                // if (place.geometry.viewport) {
                //     this.map.fitBounds(place.geometry.viewport);
                // } else {
                //     this.map.setCenter(place.geometry.location);
                //     this.map.setZoom(18);
                // }
            },
            drawRoute(initFlag = false){

                this.$refs.alert.changeMessage('Rendering route...');

                var directionPlaceParam = {placeId: this.routeInfo.place.id},
                    origin = this.routeInfo.type === 'CAMPUS' ? directionPlaceParam : this.constants.CAMPUS_LATLNG,
                    destination = this.routeInfo.type === 'CAMPUS' ? this.constants.CAMPUS_LATLNG : directionPlaceParam;

                this.directionsService.route({
                    origin: origin,
                    destination: destination,
                    travelMode: 'DRIVING',
                    provideRouteAlternatives: true,
                    waypoints: this.waypoints,
                    optimizeWaypoints: true
                }, (response, status) => {
                    if (status === 'OK') {
                        this.routePoints = response.routes[this.routeInfo.place.route_index].overview_path;
                        this.directionsDisplay.setOptions({
                            directions: response,
                            routeIndex: this.routeInfo.place.route_index
                        });
                        this.$refs.alert.hide();
                    } else {
                        this.$refs.alert.changeMessage('An error has occured while trying to draw the route. Please refresh the page to try again');
                    }
                });
            },
            getRouteData(routeId) {
                this.$refs.alert.changeMessage('Fetching route details...');
                this.$http.get(`driver-route/${routeId}`) 
                    .then((res) => {
                        this.routeInfo = res.body.data;
                        this.request.set('driver_route_id', this.routeInfo.id)
                        this.request.set('num_seats', 1);
                        this.initMap(true);
                    }, (err) => {
                        this.$refs.alert.changeMessage('An error has occured while fetch route details. Please refresh the page to try again');
                    })
            },
            setWaypoint(type, latLng) {

                // console.log(latLng)
               
                var idx = type === 'pickup' ? 0 : 1,
                    coordinateString = latLng ? latLng.lat()+','+latLng.lng() : null;

                this.request.set(type, coordinateString);

                if(!coordinateString){
                    this.waypoints[idx] = null;
                }else{
                    this.waypoints[idx] = {
                        location: latLng,
                        stopover: true
                    } 
                }
                if(this.waypoints[0] !== null && this.waypoints[1] !== null){
                    this.drawRoute();
                }
            },
            sendRequest(){
                this.$refs.alert.show({
                    message: 'Submitting...',
                    showActions: false
                });
                this.requestException = null;
                this.requestErrors = {};
                this.requestLoading = true;
                this.$http.post('driver-routes/request', this.request)
                .then((res) => {
                    this.$refs.alert.hide();
                    this.requestLoading = false;
                    this.$router.push({name: 'commuter-requests'});
                }, (err) => {
                    this.$refs.alert.hide();
                    if(err.status === 400){
                        this.requestException = err.body.message;
                    }else if(err.status === 422){
                        this.requestErrors = err.body.errors;
                    }
                    this.requestLoading = false;
                })
            },
            checkLocation(latLng, type, callback){
                this.$refs.alert.show({
                    message: 'Locating nearest driveway...',
                    showActions: false
                });

                this.getRoad(latLng, (road) => {

                    if(!road) {
                        callback(null);
                        return;
                    }
                    
                    var polyline = new this.google.maps.Polyline({ path: this.routePoints });

                    this.markers[type].setPosition(road);

                    if(!this.google.maps.geometry.poly.isLocationOnEdge(road, polyline, 10e-4)) {
                        this.$refs.alert.changeMessage({
                            message: `Your ${type} point is off the driver's route. Please choose a location closer to the driver's route.`,
                            showActions:true
                        });
                        callback(false);
                    }else{
                        this.$refs.alert.hide();
                        callback(road);
                    }

                })
            },
            getRoad(latLng, callback){
                 this.$http.get('https://roads.googleapis.com/v1/nearestRoads', {
                    params: {
                        key:'AIzaSyCueneuLIZWACMGBDBQaYix4vE9X1UkP_0',
                        points: latLng.lat()+','+latLng.lng()
                    }
                }).then((res) => {
                    var points = res.data.snappedPoints[0].location;
                    callback(new this.google.maps.LatLng(points.latitude, points.longitude));
                }, (err) => {
                    callback(null);
                });
            }
        },
        components: {
            'back-to-dashboard': require('./../BackTo.vue'),
            'alert' : require('./../messages/Alert.vue')
        },
        watch: {
            message(val) {
                this.request.set('message', val)
            }
        }
    }
</script>

<style lang="css">
    #route-page dd{
        margin-bottom:0
    }
    #route-page hr{
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    #route-page #request-exception{
        margin-top: 0.5rem;
    }
</style>