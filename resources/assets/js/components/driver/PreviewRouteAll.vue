<template>
    <div id="route-page" class="container-fluid p-0">
        <div class="row">
            <div class="col-md-3 pr-0 detail-section">
                <back :route-name="{name: 'driver-route-view-requests', params: {id: $route.params.id}}" text="Back to view requests"></back>
                <div class="card-block">
                    <div class="bs-callout" v-bind:class="route.done ? 'bs-callout-success' : 'bs-callout-primary'">
                        <div v-if="route.done">
                            <p class="mb-0 font-weight-bold">This carpool is completed</p>
                            <a class=" text-success" @click="showModal">
                                 {{ route.ratings_done ? 'View your commuter ratings!' : 'Rate your commuters!'}}
                            </a>
                        </div>
                        <div v-else>
                            This carpool is yet to commence
                        </div>
                    </div>
                    <dl class="row">
                        <dt class="col-sm-3 text-primary">From:</dt>
                        <dd class="col-sm-9">{{ origin }}</dd>
                        <dt class="col-sm-3 text-primary">To:</dt>
                        <dd class="col-sm-9">{{ destination }}</dd>
                        <dt class="col-sm-3 text-primary">Departure:</dt>
                        <dd class="col-sm-9">{{ route.departure_datetime }}</dd>
                        <dt class="col-sm-3 text-primary">Details:</dt>
                        <dd class="col-sm-9">{{ route.additional_details }}</dd>
                    </dl>
                    <div class="media" v-for="(c, i) in commuters" style="margin-top:10px;">
                        <a class="media-left" @click="zoom(i)">
                            <img class="media-object" style="height:50px;width:50px;" :src="c.commuter.display_photo">
                        </a>
                        <div class="media-body"  style="line-height:1">
                            <h5 class="media-heading mb-0">{{ `${c.commuter.firstname} ${c.commuter.lastname}` }}</h5>
                            <small>{{ c.location_address }}</small>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-sm-5">
                                    <star-rating :show-rating="false" :star-size="15" :rating="c.commuter.rating" :read-only="true"> </star-rating>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 pl-0">
                <!--<div id="map"></div>-->
                <ccmap :init-directions-service="true" :init-directions-renderer="true" ref="ccmap" v-on:map-loaded="mapLoaded">
                    <i class="fa fa-spin fa-spinner"></i> 
                </ccmap>
            </div>
        </div>
        <div class="modal fade" id="rate-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rate your commuters!</h5>
                    </div>
                    <form v-on:submit.prevent="saveRatings">
                        <div class="modal-body p-0">
                            <div class="bs-callout bs-callout-danger mb-0" v-show="rateRequestErrors">
                                {{ rateRequestErrors }}
                            </div>
                             <div class="bs-callout bs-callout-success mb-0" v-show="rateRequestDone">
                                {{ rateRequestMessage }}
                            </div>
                            <table class="table" style="table-layout:fixed">
                                <tbody>
                                    <tr v-for="(c, i) in commuters">
                                        <td>{{ `${c.commuter.firstname} ${c.commuter.lastname}` }}</td>
                                        <td><star-rating :read-only="route.ratings_done" :show-rating="false" :rating="c.commuter_rating" :star-size="15" @rating-selected="rate($event, i)"></star-rating></td>
                                        <!--<td class="text-xs-right"><a href="" class="text-danger"> <i class="fa fa-warning"></i> Report</a></td>-->
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" v-bind:disabled="rateRequestLoading" v-show="!route.ratings_done"><i v-show="rateRequestLoading" class="fa fa-spinner fa-spin"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    var modal;
    export default {
        components: {
            'back': require('./../BackTo.vue'),
            'ccmap': require('./../map/Map.vue'),
            'star-rating': require('vue-star-rating')
        },
        created(){
            
            this.id = this.$route.params.id;
        },
        mounted() {
            modal = $('#rate-modal');
        },
        data() {
            return {
                modal: null,
                route: {},
                commuters: [],
                driver: {},
                id :null,
                google : null,
                map : null,
                directionsService : null,
                directionsDisplay : null,
                constants: {
                    USC_PLACE_ID: "ChIJxwSgq-yYqTMRRbMQVEdFmDM"
                },
                ratings: [],
                rateRequestDone: false,
                rateRequestMessage: false,
                rateRequestLoading: false,
                rateRequestErrors: false
            }
        },
        methods: {
            mapLoaded() {
                this.getData();
            },
            zoom (idx) {
                this.$refs.ccmap.map.setCenter(new this.$refs.ccmap.google.maps.LatLng(this.commuters[idx].location_latitude, this.commuters[idx].location_longitude));
                this.$refs.ccmap.map.setZoom(20);
            },
            previewRoute(){

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
                         this.commuters.forEach((v, i) => {
                            var content  = `<img src=\"${v.commuter.display_photo}\" style=\"height:40px;width:40px;border-radius:50%\">`;
                            this.$refs.ccmap.putWindow(
                                v.location_latitude, 
                                v.location_longitude,
                                content,
                                true,
                                false
                            )
                         })
                    });
            },
            getData() {
                this.$http.get(`carpool/${this.id}`).then((res) => {
                    this.route = res.body.data.route;
                    this.commuters = res.body.data.commuters;
                    this.driver = res.body.data.driver;
                    this.previewRoute();
                }, (err) => {

                })
            },
            showModal() {
                modal.modal('show')
                console.log(modal)
            },
            rate(e, param){
                this.ratings.push({
                    commuter_id: this.commuters[param].commuter_id,
                    rating: e
                })
            },
            saveRatings() {
                this.rateRequestLoading = true;
                this.$http.post(`carpool/${this.id}/rating`, {ratings: this.ratings})
                    .then((res) => {
                        this.rateRequestLoading = false;
                        this.rateRequestDone = true;
                        this.rateRequestMessage = 'Your ratings has been saved!';
                        this.route.ratings_done = true;
                    }, (err) => {
                        this.rateRequestLoading = false;
                        this.rateRequestErrors = err.body.message;
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
    /*.gm-style-iw + div {display: none;}*/
    .gm-style-iw {
        text-align: center;
    }
</style>