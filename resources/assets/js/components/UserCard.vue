<template>
    <div>
        <div class="card">
            <div class="card-block text-xs-center">
                <img :src="profilePhotoUrl" class="rounded mx-auto d-block rounded-circle img-fluid img-thumbnail" alt="..." style="margin-bottom:1rem">
                <h4 class="card-title mb-0">{{ $auth.user().firstname  + ' ' + $auth.user().lastname }}</h4>
                <p class="card-text mb-0" style="text-transform:capitalize">{{ humanize($auth.user().role) }}</p>
                <p class="card-text text-xs-center">
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
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
        },
        methods: {
            humanize: function(str){
                var _str = str.toLowerCase();
                return _str.charAt(0).toUpperCase() + _str.slice(1)
            }
        }
    }
</script>
