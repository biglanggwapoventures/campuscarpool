<template>
    <div class="card card-block driver-routes">   
        <div class="row">
            <div class="col-sm-2 text-xs-center">
                <img :src="profilePhotoUrl" alt="..." class="rounded img-fluid img-thumbnail" >
            </div>
            <div class="col-sm-10">
                <h5 class="card-title mb-0">Kathleen Montecillo</h5>
                <p class="card-text mb-0">
                    <i class="fa fa-star text-success"></i>
                    <i class="fa fa-star text-success"></i>
                    <i class="fa fa-star text-success"></i>
                    <i class="fa fa-star text-success"></i>
                    <i class="fa fa-star-o"></i>
                </p>
                <hr>
                <dl class="row mb-0">
                    <dt class="col-sm-2">Route</dt>
                    <dd class="col-sm-10 mb-0">Degree 9, Panagdait Rd,. Mandaue City <i class="fa fa-long-arrow-right fa-fw"></i> Campus</dd>
                    <dt class="col-sm-2">Date</dt>
                    <dd class="col-sm-10 mb-0">Dec 1, 2016 @ 1:30 PM</dd>
                    <dt class="col-sm-2">Vehicle</dt>
                    <dd class="col-sm-10 mb-0">Toyota Vios YHM 199</dd>
                    <dt class="col-sm-2">Space</dt>
                    <dd class="col-sm-10 mb-0">0/6</dd>
                </dl>
                <div class="row">
                    <div class="col-sm-6">
                        
                    </div>
                    <div class="col-sm-6">
                        <dl class="row">
                            
                        </dl>
                    </div>
                </div>
                
                <a href="" class="btn btn-sm btn-success">Request</a>
                <a href="" class="btn btn-sm btn-info">Send Message</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                commuteType: '',
                profilePhotoUrl : './../images/user7-128x128.jpg'
            }
        }
    }
</script>
