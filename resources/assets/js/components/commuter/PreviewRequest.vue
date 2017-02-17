<template>
    <div id="route-page" class="container-fluid p-0">
        <div class="row">
            <div class="col-md-3 pr-0 detail-section">
                <back :route-name="{name: 'commuter-requests', params: {type: type}}" text="Back to view requests"></back>
                <div class="card-block">
                    <div v-show="!loading">
                        <img :src="driver.display_photo"  class="rounded mx-auto d-block rounded-circle img-fluid img-thumbnail" alt="..." style="height:128px;width:128px;">
                        <h4 class="card-title mb-0 text-xs-center">{{ `${driver.firstname} ${driver.lastname}` }}</h4>
                        <div class="row">
                             <div class="col-sm-6 offset-md-3">
                                <p class="card-text text-xs-center">
                                    <star-rating :show-rating="false" :star-size="15" :rating="driver.rating" :read-only="true" :increment="0.01"> </star-rating>
                                </p>
                            </div>
                        </div>

                        <p class="text-success text-xs-center" v-if="request.accepted"><i class="fa fa-check-circle"></i> Accepted</p> 
                        <p class="text-danger text-xs-center" v-else-if="request.rejected"><i class="fa fa-times"></i> Rejected</p>
                        <p class="text-info text-xs-center " v-else><i class="fa fa-hourglass"></i> Pending</p>

                         <dl class="row">
                            <dt class="col-sm-3 text-primary">From:</dt>
                            <dd class="col-sm-9">{{ origin }}</dd>
                            <dt class="col-sm-3 text-primary">To:</dt>
                            <dd class="col-sm-9">{{ destination }}</dd>
                            <dt class="col-sm-3 text-primary">Departure:</dt>
                            <dd class="col-sm-9">{{ route.departure_datetime }}</dd>
                            <dt class="col-sm-3 text-primary">Details:</dt>
                            <dd class="col-sm-9">{{ route.additional_details }}</dd>
                            <dt class="col-sm-3 text-primary">{{ pointLabel }}:</dt>
                            <dd class="col-sm-9">{{ request.location_address }}</dd>
                        </dl>
                        <hr>
                        <div v-if="!request.rejected">
                            <div v-if="request.cancelled_at == null">
                                <div v-if="request.rejected">
                                    
                                </div>
                                <div v-else>
                                    <div v-if="route.done">
                                        <rating :ride-request-id="id" :default-rating="request.driver_rating"></rating>
                                         <hr>
                                         <div  class="text-danger text-xs-center">
                                            <span v-if="report"><i class="fa fa-thumbs-down"></i> You have reported this driver!</span>
                                            <a @click="showReportModal"  v-else  class=" text-danger"><i class="fa fa-thumbs-down"></i> Report this driver</a> 
                                        </div>
                                    </div>
                                    <div v-else  class="text-xs-center">
                                        <transition>
                                            <button class="btn btn-danger" @click="cancel('show')" v-show="!cancelRequest"><i class="fa fa-times"></i> Cancel request</button>
                                        </transition>
                                        <transition name="slide-fade">
                                            <div v-show="cancelRequest">
                                                <p class="text-xs-center font-weight-bold">Are you sure you want to cancel this carpool?</p>
                                                <button class="btn btn-danger" @click="confirmCancel">Yes</button>
                                                <button class="btn btn-success" @click="cancel('hide')">Cancel</button>
                                            </div>
                                        </transition>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p class="text-danger text-xs-center">You have opted to cancel from this carpool</p>
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
        <!-- REPORT -->
        <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">

                     <form v-on:submit.prevent="submitReport">
                        <div class="modal-body">
                        
                            <div class="form-group mb-0">
                                <label for="">Enter your message</label>
                                <textarea name="" id="" class="form-control" v-model="message"></textarea>
                            </div>
                            
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'back': require('./../BackTo.vue'),
            'ccmap': require('./../map/Map.vue'),
            'rating': require('./../RateDriver.vue'),
            'star-rating': require('vue-star-rating')
        },
        created(){
            this.loading = true;
            this.id = this.$route.params.requestId;
        },
        data() {
            return {
                route: {},
                request: {},
                driver: {},
                report: {},
                id :null,
                google : null,
                map : null,
                directionsService : null,
                directionsDisplay : null,
                constants: {
                    USC_PLACE_ID: "ChIJxwSgq-yYqTMRRbMQVEdFmDM"
                },
                loading: false,
                errorMessage: null ,
                cancelRequest: false,
                message: null
            }
        },
        methods: {
            mapLoaded() {
                this.getData();
            },
            previewRoute() {
                var location = new this.$refs.ccmap.google.maps.LatLng(this.route.place_latitude, this.route.place_longitude),
                    campus = { placeId: this.constants.USC_PLACE_ID },
                    origin, destination;

                if(this.route.type === 'CAMPUS'){
                    origin = location;
                    destination = campus;
                }else{
                    origin = campus;
                    destination = location;
                }

                this.$refs.ccmap.route(origin, destination, this.route.route_index)
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
                this.$http.get(`ride-requests/commuter/${this.id}`)
                    .then((res) => {
                        this.route = res.body.data.route;
                        this.driver = res.body.data.driver;
                        this.request = res.body.data.request;
                        this.report = res.body.data.report;
                        this.previewRoute();
                        this.loading = false;
                    }, (err) => {
                        this.loading = false;
                        this.errorMessage = err.body.message;
                    })
            },
            cancel(state) {
                this.cancelRequest = state === 'show' ? true : false
            },
            confirmCancel() {
                this.loading = true;
                this.$http.delete(`ride-requests/commuter/${this.id}`).then((res) => {
                    this.request.cancelled_at = Date.now();
                    this.loading = false;
                }, (err) => {
                    this.errorMessage = err.body.message;
                    this.loading = false;
                })
            },
            showReportModal() {
                $('#report').modal('show');
            },
            closeReportModal() {
                $('#report').modal('hide');
            },
            submitReport() {
                this.$http.post('report', {
                    recipient_id: this.route.created_by,
                    message: this.message,
                    driver_route_id: this.route.id
                }).then((res) => {
                    this.report = true
                    this.closeReportModal()
                    
                }, (err) => {
                    window.alert('An internal server error has occured!');
                })
            }
        },
        computed: {
            type(){
                if(this.request.cancelled_at != null) return 'cancelled'
                if(this.request.accepted) return 'accepted'
                if(this.request.rejected) return 'rejected'
                return 'pending';
            },
            origin() {
                if(this.route.type === 'HOME') return 'USC-TC'
                return this.route.place_formatted_address
            },
            destination(){
                if(this.route.type === 'CAMPUS') return 'USC-TC'
                return this.route.place_formatted_address
            },
            pointLabel(){
                if(this.route.type === 'CAMPUS') return 'Pickup'
                return 'Dropoff'
            }
        }

    }
</script>

<style lang="css">
    /* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for <2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
</style>