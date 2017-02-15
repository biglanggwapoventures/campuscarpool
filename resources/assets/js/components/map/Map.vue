<template>
    <div>
        <div v-show="loadingMap" style="top: 50%;left:50%;position:absolute;">
            <slot></slot>
        </div>
        <div id="map">
            
        </div>
    </div>
    

</template>

<script>
    
    var GoogleMaps = require('google-maps'),    
        key = 'AIzaSyD85clj7B85QRZPmO6m4Fky0Wi6P0MzVpA';

    export default {
        mounted(){
            var GoogleMapsLoader = require('google-maps');

            GoogleMapsLoader.KEY = key;
            GoogleMapsLoader.REGION = 'PH';  
            GoogleMapsLoader.LIBRARIES = ['places'];    
            
            GoogleMapsLoader.load((google)  => {

                this.google = google;

                this.constants.CAMPUS_LATLNG = new this.google.maps.LatLng(10.351887545991266, 123.9138400554657);

                this.map = new this.google.maps.Map(document.getElementById('map'), {
                    center: this.constants.CAMPUS_LATLNG,
                    zoom: this.zoom,
                    mapTypeId: this.mapType
                });

                if(this.initDirectionsService){
                    this.directionsService = new this.google.maps.DirectionsService;
                }

                if(this.initDirectionsRenderer){
                    this.directionsDisplay = new this.google.maps.DirectionsRenderer({
                        map: this.map,
                        polylineOptions: {
                            strokeColor: '#71bf44',
                        }
                    });
                }

                this.geocoder = new this.google.maps.Geocoder;

                this.google.maps.event.addListener(this.map, 'click', (event) => {
                    this.geocoder.geocode({'location': event.latLng}, (results, status) => {
                        if (status === 'OK') {
                            if (results[0]) {
                                this.$emit('map-clicked', {
                                    lat: event.latLng.lat(),
                                    lng: event.latLng.lng(),
                                    address: results[0].formatted_address
                                })
                            } else {
                                window.alert('No results found');
                            }
                        } else {
                            window.alert('Geocoder failed due to: ' + status);
                        }
                    });
                });

                this.loadingMap = false;
                this.$emit('map-loaded');

            });

        },
        methods: {
            initAutoComplete(element) {
                return new this.google.maps.places.Autocomplete(element);
            },
            route(origin, destination, index = 0) {
                // console.log(index)
                return new Promise((resolve, reject) => {
                    this.directionsService.route({
                        origin: origin,
                        destination: destination,
                        travelMode: 'DRIVING',
                        provideRouteAlternatives: true,
                    }, (response, status) => {
                        if (status === 'OK') {
                            this.path = response.routes[index].overview_path;
                            this.directionsDisplay.setDirections(response);
                            this.directionsDisplay.setRouteIndex(index);
                            resolve({
                                routes: response.routes
                            })
                        } else {
                            reject('error on route')
                        }
                    });
                });
            },
            setRouteIndex(index) {
                this.directionsDisplay.setRouteIndex(index);
            },
            putMarker(latitude, longitude, infoWindow = false){ 
                var marker =  new this.google.maps.Marker({
                    map: this.map,
                    position: new this.google.maps.LatLng(latitude, longitude)
                })
                
                if(infoWindow){
                     var infowindow  = new this.google.maps.InfoWindow({
                        content: infoWindow.content
                    });
                    infowindow.open(this.map, marker);
                    marker.addListener('click', () => {
                        infowindow.open(this.map, marker);
                    });
                    
                }

                return marker;
            },
            putWindow(latitude, longitude, content, open = true, setZoom = true){ 
                var pos = new this.google.maps.LatLng(latitude, longitude);
                var window =  new this.google.maps.InfoWindow({
                    content: content,
                    position: pos
                });
                if(open){
                    window.open(this.map);
                }
                if(setZoom){
                    setTimeout(() => {
                        this.map.setCenter(pos)
                        this.map.setZoom(18);
                    }, 100)
                    
                    console.log('lol')
                }
                return window;
            },
            expandViewportToFitPlace(place){
                if (place.geometry.viewport) {
                    this.map.fitBounds(place.geometry.viewport);
                } else {
                    this.map.setCenter(place.geometry.location);
                    this.map.setZoom(18);
                }
           },
           getNearestRoad(latitude, longitude){
                return new Promise((resolve, reject) => {
                    this.$http.get('https://roads.googleapis.com/v1/nearestRoads', { 
                        params : { key: key, points: `${latitude},${longitude}` }
                    }).then((res) => {
                        if(res.data.hasOwnProperty('snappedPoints')){
                            var points = res.data.snappedPoints[0].location;
                            resolve({ latitude: points.latitude, longitude: points.longitude });
                        }else{
                            reject('Selected area is not near in any roads.')
                        }
                    }, (err) => {
                        reject('An internal server error has occured')
                    });
              });
           },
           isLocationOnEdge(latitude, longitude, pathArray = false) {
                 var coordinates = new this.google.maps.LatLng(latitude, longitude),  
                    path = new this.google.maps.Polyline({ path: pathArray ? pathArray : this.path });

                return this.google.maps.geometry.poly.isLocationOnEdge(coordinates, path, 10e-5);
           }
        },
        data() {
            return {
                loadingMap: true,
                google: null,
                map: null,
                constants: {
                    CAMPUS_LATLNG:  null
                },
                geocoder: null,
                directionsService: null,
                directionsDisplay: null,
                polyline: null,
                path: []
            }
        },
        props: {
            'init-directions-service': Boolean,
            'init-directions-renderer': Boolean,
            'zoom': {
                type: Number,
                default: 18,
            },
            'map-type': {
                type: String,
                default: 'satellite'
            }
        }
    }

</script>

<style lang="css">
    .gm-style-iw + div {display: none;}
</style>