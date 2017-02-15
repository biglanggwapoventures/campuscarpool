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
                                        <span class="custom-control-description font-weight-bold">I am heading to campus</span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input v-model="routeData.type" value="HOME"  type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description font-weight-bold">I am heading home</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <ccplace 
                            label="Search for a specific place or address" 
                            id="search-place"
                            label-class="mb-0 font-weight-bold"
                            v-on:place-changed="placeChanged">
                        </ccplace>

                        <div class="form-group">
                            <label class="font-weight-bold mb-0">{{ placeLabel }}</label>
                            <p class="form-control-static pt-0 "><em>{{ routeData.place.formatted_address || 'Please use the address search above or choose a location on the map'}}</em></p>
                        </div>

                        <datetimepicker 
                            id="datetimepicker" 
                            label="Departure date and time"
                            label-class="mb-0 font-weight-bold"
                            v-model="routeData.departure_datetime"
                            :errors="errorMessages.departure_datetime">
                        </datetimepicker>
                        <div class="row">
                            <div class="col-sm-6">
                                <form-input 
                                    v-model="routeData.fare_contribution" 
                                    label="Fare contribution" 
                                    label-class-name="mb-0 font-weight-bold"
                                    :errors="errorMessages.fare_contribution">
                                </form-input>
                            </div>
                            <div class="col-sm-6">
                                <form-input 
                                    v-model="routeData.max_passenger" 
                                    label="Max passenger" 
                                    label-class-name="mb-0 font-weight-bold"
                                    :errors="errorMessages.max_passenger">
                                </form-input>
                            </div>
                        </div>
                        <form-input 
                            v-model="routeData.additional_details" 
                            label="Additional route details" 
                            label-class-name="mb-0 font-weight-bold"
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
                <!--<div id="map"></div>-->
                <ccmap :init-directions-service="true" :init-directions-renderer="true" ref="ccmap" v-on:map-clicked="mapClicked">
                    <i class="fa fa-spin fa-spinner"></i> 
                </ccmap>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
       data (){
           return { 
                origin : null,
                destination : null,
                constants : {
                    USC_PLACE_ID: "ChIJxwSgq-yYqTMRRbMQVEdFmDM"
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
      
       methods: {
           drawRoute() {
               if(!this.origin || !this.destination) return;

                this.$refs.ccmap.route(this.origin, this.destination)
                    .then((res) => {

                        this.routeChoices = [];
                        this.routeData.route_index = 0;
                        res.routes.forEach((v, i) => {
                            this.routeChoices.push({
                                path: v.summary,
                                distance: v.legs[0].distance.text,
                                duration: v.legs[0].duration.text,
                                index: i
                            })
                         })
                    })
                    .catch((err) => {
                        console.log(err);
                    })
            },
            renderRoute(index){
                if(index ===  this.routeData.route_index) return;
                this.routeData.route_index = index;
                this.$refs.ccmap.setRouteIndex(index);
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
            },
            placeChanged(place) {
                if (!place.geometry) {
                    this.alert.show('hello');
                    return;
                }
                this.$refs.ccmap.expandViewportToFitPlace(place);

                this.routeData.place.latitude = place.geometry.location.lat();
                this.routeData.place.longitude = place.geometry.location.lng();
                this.routeData.place.formatted_address = `${place.name}, ${place.formatted_address}`
                this.setLocation();
                this.drawRoute();
            },
            mapClicked(place){
                this.routeData.place.formatted_address = place.address
                this.routeData.place.latitude = place.lat;
                this.routeData.place.longitude = place.lng;

                this.setLocation();
                this.drawRoute();
            },
            setLocation(){
                var location = this.routeData.place.id ? { placeId: this.routeData.place.id } : new this.$refs.ccmap.google.maps.LatLng(this.routeData.place.latitude, this.routeData.place.longitude),
                    campus = { placeId: this.constants.USC_PLACE_ID };

                if(this.routeData.type === 'CAMPUS'){
                    this.origin = location;
                    this.destination = campus;
                }else{
                    this.origin = campus;
                    this.destination = location;
                }
            }
       },

       components: {
            'datetimepicker': require('./../DateTimePicker.vue'),
            'back': require('./../BackTo.vue'),
            'ccmap': require('./../map/Map.vue'),
            'ccplace': require('./../map/Place.vue')
        },
        computed: {
            placeLabel() {
                return this.routeData.type === 'CAMPUS' ? 'Your origin address' : 'Your home address';
            }
        },
        watch: {
            'routeData.type' (){
                this.drawRoute();
            },
        },
    }
</script>