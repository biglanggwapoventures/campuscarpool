<template>
    <div class="card driver-routes" style="border-left:5px solid #5cb32a">   
        <div class="card-block" style="border-bottom: 1px solid #eceeef;">
            <div class="row">
                <div class="col-sm-9">
                    <div class="media">
                        <a class="media-left" href="#">
                            <img class="media-object img-thumbnail" :src="profilePhotoUrl" alt="Generic placeholder image" style="height:75px;width:auto;">
                        </a>
                        <div class="media-body">
                            <h5 class="media-heading card-title">{{ driverName }}</h5>
                            <p class="card-text mb-0">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star-o"></i>
                            </p>
                            <!--<span >Capacity: <strong class="text-success"></strong></span>-->
                            <!--<small class="text-muted">{{ timePosted }}</small>-->
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 text-xs-right">
                    <h2 v-bind:class="{'text-danger': full}">{{ spaceOccupied }}/{{ spaceMax }}</h2>
                </div>
            </div>
             
        </div>
        <div class="card-block p-0">
            <table class="table table-sm route-table-details mb-0">
                <tbody>
                    <tr>
                        <td class="text-xs-right">
                            <router-link :to="{ name: 'view-route', params: { id: routeId }}" class="text-danger ">
                                <span class="fa-stack fa-lg ">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-map-marker fa-spin fa-stack-1x fa-inverse"></i>
                                </span>
                            </router-link>
                        </td>
                        <td colspan="3">{{ routeFrom }} <i class="fa fa-long-arrow-right fa-fw"></i> {{ routeTo }}</td>
                    </tr>
                    <tr>
                        <td class="text-warning text-xs-right" style="width:10%">
                            <span class="fa-stack fa-lg ">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-clock-o fa-stack-1x fa-inverse"></i>
                            </span>
                        </td>
                        <td style="width:40%">{{ departureTime }}</td>
                        <td class="text-info text-xs-right" style="width:10%">
                            <span class="fa-stack fa-lg ">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-car fa-stack-1x fa-inverse"></i>
                            </span>
                        </td>
                        <td style="width:40%">{{ vehicle }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--<a href="javascript:void(0)" class="btn btn-success btn-sm" @click="request()"><i class="fa fa-thumbs-o-up"></i> Request</a>
         <a href="" class="btn btn-secondary btn-sm"><i class="fa fa-mail-forward"></i> Message</a>-->

    </div>
    
</template>

<script>
    
    export default {
        props: {
            'route-id': {
                type: Number
            },
            'profile-photo-url': {
                type: String,
                default: './../images/user7-128x128.jpg'
            },
            'driver-name': {
                type: String,
                default: 'John Doe',
            },
            'route-from': {
                type: String,
                default: 'Campus',
            },
            'route-to': {
                type: String,
                default: 'SM City Cebu',
            },
            'time-posted': {
                type: String,
                default: '1 hour ago',
            },
            'vehicle': {
                type: String,
                default: 'Kia Picanto ABC-123',
            },
            'space-occupied': {
                default: 1
            },
            'space-max': {
                default: 1
            },
            'departure-time': {
                type: String,
                default: 'Dec 2, 2016 @ 4:30PM',
            }
        },
        methods: {
            viewRoute(){
                console.log('lol');
                this.$emit('view-route')
            }
        },
        computed: {
            full(){
                return this.spaceOccupied == this.spaceMax;
            }
        }
    }
</script>


<style lang="css">
    .route-table-details td{
        vertical-align: middle;
        /*padding: 0.25rem;*/
    }
    .route-table-details td:first-child{
        /*padding-left: 1.25rem;*/
    }
    .route-table-details td:last-child{
        /*padding-right: 1.25rem;*/
    }
    .route-table-details tr:first-child td{
       border-top:0;
    }
    .driver-routes .card-block{
        
    }
</style>