<template>
    <div class="card card-block driver-routes">   
        <div class="media">
            <a class="media-left" href="#">
                <img class="media-object" :src="profilePhotoUrl" alt="Generic placeholder image" style="width:75px;height:auto;">
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
                <small class="text-muted">{{ timePosted }}</small>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" >
                <div class="route-details">
                    <dl class="row mb-0" >
                        <dt class="col-sm-2">Route</dt>
                        <dd class="col-sm-10 mb-0">{{ routeFrom }} <i class="fa fa-long-arrow-right fa-fw"></i> {{ routeTo }}</dd>
                        <dt class="col-sm-2">Date</dt>
                        <dd class="col-sm-10 mb-0">{{ departureTime }}</dd>
                        <dt class="col-sm-2">Vehicle</dt>
                        <dd class="col-sm-10 mb-0">{{ vehicle }}</dd>
                        <dt class="col-sm-2">Space</dt>
                        <dd class="col-sm-10 mb-0">{{ spaceOccupied }}/{{ spaceMax }}</dd>
                    </dl>
                </div>
                
            </div>
        </div>
         <hr>
         <a href="" class="btn btn-success btn-sm"><i class="fa fa-thumbs-o-up"></i> Request</a>
         <a href="" class="btn btn-secondary btn-sm"><i class="fa fa-mail-forward"></i> Message</a>
         <!--<div class="row">
             <div class="col-sm-6">
                 <a href="" class="btn btn-success btn-sm btn-block"><i class="fa fa-thumbs-o-up"></i> Request</a>
             </div>
             <div class="col-sm-6">
                 <a href="" class="btn btn-secondary btn-sm btn-block"><i class="fa fa-mail-forward"></i> Message</a>
             </div>
         </div>-->
         <!--<div class="route-actions">
            <a href=""><i class="fa fa-thumbs-o-up"></i> Request</a>
            <a href=""><i class="fa fa-mail-forward"></i> Message</a>
            <a href="" class="float-xs-right text-danger report"><i class="fa fa-warning"></i> Report</a>
        </div>-->
    </div>
</template>

<script>
    export default {
        props: {
            profilePhotoUrl: {
                type: String,
                default: './../images/user7-128x128.jpg'
            },
            driverName: {
                type: String,
                default: 'John Doe',
            },
            routeFrom: {
                type: String,
                default: 'Campus',
            },
            routeTo: {
                type: String,
                default: 'SM City Cebu',
            },
            timePosted: {
                type: String,
                default: '1 hour ago',
            },
            vehicle: {
                type: String,
                default: 'Kia Picanto ABC-123',
            },
            spaceOccupied: {
                type: Number,
                default: 1
            },
            spaceMax: {
                type: Number,
                default: 1
            },
            departureTime: {
                type: String,
                default: 'Dec 2, 2016 @ 4:30PM',
            }
        }
    }
</script>
