<template>
    <div>
        <div class="card">
            <div class="card-block text-xs-center">
                <img :src="profilePhotoUrl" class="rounded mx-auto d-block rounded-circle img-fluid img-thumbnail" alt="..." style="margin-bottom:1rem">
                <h4 class="card-title mb-0">Adrian Natabio</h4>
                <p class="card-text mb-0">Commuter</p>
                <p class="card-text text-xs-center">
                    <i class="fa fa-star text-success"></i>
                    <i class="fa fa-star text-success"></i>
                    <i class="fa fa-star text-success"></i>
                    <i class="fa fa-star text-success"></i>
                    <i class="fa fa-star-o"></i>
                </p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Lift Requests</li>
                <li class="list-group-item">Lift History</li>
                <li class="list-group-item">Activity Log</li>
            </ul>
        </div>
    </div>
    
</template>

<script>
    export default {
        data(){
            return {
                commuteType: '',
                profilePhotoUrl : './../images/user1-128x128.jpg'
            }
        }
    }
</script>
