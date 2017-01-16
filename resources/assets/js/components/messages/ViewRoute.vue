<template>
        <!-- Modal -->
    <div class="modal fade" v-bind:id="id" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">{{ title }}</h4>
                </div>
                <div class="modal-body p-0">
                    <div id="route-map">
                        
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <form>
                        <div class="input-group" v-on:submit.preventDefault="request()">
                            <input type="text" class="form-control" placeholder="Your message" v-model="requestMessage">
                            <span class="input-group-btn">
                                <ccbutton btn-type="submit" :loading="requestLoading" btn-class="btn-success btn-block" v-on:btn-clicked="request()"><i class="fa fa-paper-plane"></i></ccbutton>
                            </span>
                        </div>
                        <p class="mb-0 text-danger" v-show="errorMessage !== null">{{errorMessage}}</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    var modal = null,
        GoogleMapsLoader = null;

    export default {
        mounted(){
            modal = $('#'+this.id)
            GoogleMapsLoader = require('google-maps');
            GoogleMapsLoader.KEY = 'AIzaSyCueneuLIZWACMGBDBQaYix4vE9X1UkP_0';
            GoogleMapsLoader.REGION = 'PH';  
            GoogleMapsLoader.LIBRARIES = ['places'];    
            
            GoogleMapsLoader.load((google)  => {
                this.google = google;
                this.initMap();
            });
        },
        methods: {
            open(routeId){
                this.routeId = routeId;
                this.errorMessage = null;
                modal.modal('show');
            },
            initMap(){
                // initialize map
                this.map = new this.google.maps.Map(document.getElementById('route-map'), {
                    zoom: 18,
                    type: 'hybrid'
                });
                // set modal listener to show map on open
                // this is a fix on bootstap modals not showing maps upon open
                modal.on('shown.bs.modal',  () => {
                    this.google.maps.event.trigger(this.map, 'resize');
                    //set center to USC TC
                    this.map.setCenter(new this.google.maps.LatLng(10.351887545991266, 123.9138400554657));
                })
            },
            getRouteData(){
                this.$http.get('driver-route/'+this.routeId) 
                    .then((res) => {
                        this.routeData = res.body.data;
                        console.log(res)
                        // console.log('gaiatay')
                        this.title = this.routeData.driver.data.name;
                    }, (err) => {
                        
                    })
            },
            drawRoute(){

            },
            request (){
                this.errorMessage = null;
                this.requestLoading = true;
                this.$http.post('driver-routes/request', {
                    driver_route_id: this.routeId,
                    num_seats: 1,
                    message: this.requestMessage
                }).then((res) => {
                    this.requestLoading = false;
                }, (res) => {
                    this.errorMessage = res.body.message;
                    this.requestLoading = false;
                })
            },
        },
        watch: {
            routeId(){
                this.errorMessage = null;
                this.getRouteData();
            },
            requestLoading(){
                // this.$refs.reqBtn.isLoading = this.requestLoading;
            }
        },
        props: {
            id: {
                type: String,
                default: 'myModal'
            }
        },
        data(){
            return {
                title: null,
                routeId: null,
                google: null,
                map: null,
                constants: {
                    CAMPUS_LATLNG: null
                },
                routeData: {

                },
                requestLoading: false,
                errorMessage: null,
                requestMessage: 'Hello, I would like to carpool with you!'
            }
        }
    }
</script>


<style lang="css">
    #route-map{
        height: 25rem;;
    }
    .modal-footer{
        text-align: left;
    }
</style>