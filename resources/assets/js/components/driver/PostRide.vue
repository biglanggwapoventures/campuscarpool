<template>
    <div id="route-page" class="container-fluid p-0">
        <div class="row">
            <div class="col-md-3 pr-0 detail-section">
                <back :route-name="{'name':'dashboard'}" text="Back to dashboard"></back>
                <div class="card-block">
                    <form v-on:submit.prevent="submitRoute">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="custom-controls-stacked">
                                    <label class="custom-control custom-radio">
                                        <input v-model="routeData.type" value="CAMPUS" type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">I am heading to campus</span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input v-model="routeData.type" value="HOME"  type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">I am heading home</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <form-input 
                            v-model="routeData.place.formatted_address" 
                            :label="routeData.type === 'CAMPUS' ? 'Your origin address' : 'Your home address'" 
                            id="place" 
                            inputClassName="form-control-sm" 
                            labelClassName="mb-0"
                            :errors="errorMessages['place.id']">
                        </form-input>
                        <datetimepicker 
                            id="datetimepicker" 
                            label="Departure date and time"
                            v-model="routeData.departure_datetime"
                            :errors="errorMessages.departure_datetime">
                        </datetimepicker>
                        <div class="row">
                            <div class="col-sm-6">
                                <form-input 
                                    v-model="routeData.fare_contribution" 
                                    label="Fare contribution" 
                                    inputClassName="form-control-sm"  
                                    labelClassName="mb-0"
                                    :errors="errorMessages.fare_contribution">
                                </form-input>
                            </div>
                            <div class="col-sm-6">
                                <form-input 
                                    v-model="routeData.max_passenger" 
                                    label="Max passenger" 
                                    inputClassName="form-control-sm"  
                                    labelClassName="mb-0"
                                    :errors="errorMessages.max_passenger">
                                </form-input>
                            </div>
                        </div>
                        <form-input 
                            v-model="routeData.additional_details" 
                            label="Additional route details" 
                            inputClassName="form-control-sm" 
                             labelClassName="mb-0"
                             :errors="errorMessages.max_passenger">
                        </form-input>
                        <div class="list-group" v-show="routeChoices.length > 0">
                            <a href="javascript:void(0)" @click="renderRoute(c.index)" class="list-group-item list-group-item-action" v-for="c in routeChoices">
                                <h6 class="list-group-item-heading">via {{ c.path }}</h6>
                                <p class="list-group-item-text">
                                    <span><i class="fa fa-road"></i> {{ c.distance }} </span>
                                </p>
                            </a>
                        </div>
                        <button type="submit" class="btn btn-success mt-1">Submit</button>
                    </form>
                </div>
            </div>
            <div class="col-md-9 pl-0">
                <div id="map"></div>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        mounted (){
           
            var GoogleMapsLoader = require('google-maps');

            GoogleMapsLoader.KEY = 'AIzaSyCueneuLIZWACMGBDBQaYix4vE9X1UkP_0';
            GoogleMapsLoader.REGION = 'PH';  
            GoogleMapsLoader.LIBRARIES = ['places'];    
            
            GoogleMapsLoader.load((google)  => {
                this.google = google;
                this.initMap();
            });

            
        },
        props: [

        ],
       data (){
           return { 
                origin : null,
                destination : null,
                google : null,
                map : null,
                directionsService : null,
                directionsDisplay : null,
                distanceMatrixService : null,
                placesService : null,
                placeInput : null,
                placeAutocomplete : null,
                constants : {
                    CAMPUS_LATLNG: null
                },
                routeData: {
                    type: 'CAMPUS',
                    place: {
                        id: null,
                        latitude: null,
                        longitude: null,
                        formatted_address: null,
                        route_index: null
                    },
                    departure_datetime: null,
                    fare_contribution: null,
                    max_passenger: null,
                    additional_details: null
                },
                routeChoices: [ ],
                errorMessages: {}
           }
       },
       watch: {
            'routeData.type' (){
                this.drawRoute();
            },
        },
       methods: {
           initMap(){
                this.constants.CAMPUS_LATLNG = new this.google.maps.LatLng(10.351887545991266, 123.9138400554657);
                this.map = new this.google.maps.Map(document.getElementById('map'), {
                    center: this.constants.CAMPUS_LATLNG,
                    zoom: 18,
                    type: 'hybrid'
                });

                this.directionsService = new this.google.maps.DirectionsService;
                this.directionsDisplay = new this.google.maps.DirectionsRenderer({
                    map: this.map,
                    draggable: true,
                });
                this.placesService = new this.google.maps.places.PlacesService(this.map);
                this.distanceMatrixService = new this.google.maps.DistanceMatrixService();


                this.initPlaceAutocomplete();
           },
           initPlaceAutocomplete(){
                this.placeInput = document.getElementById('place');
                this.placeAutocomplete = new this.google.maps.places.Autocomplete(this.placeInput);
                this.placeAutocomplete.addListener('place_changed', (val) => {
                    var place = this.placeAutocomplete.getPlace();
                    if (!place.geometry) {
                        window.alert("Autocomplete's returned place contains no geometry");
                        return;
                    }
                    this.expandViewportToFitPlace(place);
 
                    this.routeData.place.id = place.place_id;
                    this.routeData.place.formatted_address = place.name + ', ' + place.formatted_address;
                    this.routeData.place.latitude = place.geometry.location.lat();
                    this.routeData.place.longitude = place.geometry.location.lng();

                    this.drawRoute();
                });
           },
           expandViewportToFitPlace(place){
                if (place.geometry.viewport) {
                    this.map.fitBounds(place.geometry.viewport);
                } else {
                    this.map.setCenter(place.geometry.location);
                    this.map.setZoom(18);
                }
           },
           drawRoute() {
                if (!this.routeData.place.id) return;

                this.origin = this.routeData.type === 'CAMPUS' ? {placeId: this.routeData.place.id} : this.constants.CAMPUS_LATLNG,
                this.destination = this.routeData.type === 'CAMPUS' ? this.constants.CAMPUS_LATLNG : {placeId: this.routeData.place.id};
                
                var departureTime = this.routeData.departureDateTime ? new Date(this.routeData.departureDateTime) : new Date();

                this.directionsService.route({
                    origin: this.origin,
                    destination: this.destination,
                    travelMode: 'DRIVING',
                    provideRouteAlternatives: true,
                }, (response, status) => {
                    if (status === 'OK') {
                        // console.log(response)
                        this.routeChoices = [];
                        this.directionsDisplay.setDirections(response);
                        this.routeData.route_index = 0;
                        response.routes.forEach((v, i) => {
                            this.routeChoices.push({
                                path: v.summary,
                                distance: v.legs[0].distance.text,
                                duration: v.legs[0].duration.text,
                                index: i
                            })
                         })
                    } else {
                        console.log('error on route');
                    }
                });
            },
            renderRoute(index){
                this.routeData.route_index = index;
                this.directionsDisplay.setRouteIndex(index);
            },
            submitRoute(){
                this.$http.post('driver-route', this.routeData)
                    .then((res) => {
                        this.$router.push({name: 'driver-routes'})
                    }, (err) => {
                        if(err.status === 422){
                            this.errorMessages = err.body.errors
                            return;
                        }
                        window.alert('Error while trying to save new route');
                    })
            }
       },
       components: {
            'datetimepicker': require('./../DateTimePicker.vue'),
            'back': require('./../BackTo.vue'),
        }
    }
</script>