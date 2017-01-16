<template>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">My Routes</li>
        </ol>
        <div class="card card-block route-item"  v-for="r in postedRoutes">
            <dl class="row">
                <dt class="col-sm-2">Posted at</dt>
                <dd class="col-sm-10">{{ r.posted_at}}</dd>
                <dt class="col-sm-2">Origin</dt>
                <dd class="col-sm-10">{{ r.route_from }}</dd>
                <dt class="col-sm-2">Destination</dt>
                <dd class="col-sm-10">{{ r.route_to }}</dd>
                <dt class="col-sm-2">Departure</dt>
                <dd class="col-sm-10">{{ r.departure_datetime }}</dd>
                <dt class="col-sm-2">Passengers</dt>
                <dd class="col-sm-10">{{ r.num_seats_taken }} / {{ r.num_seats_max }}</dd>
                <dt class="col-sm-2">Rating</dt>
                <dd class="col-sm-10"><i class="fa fa-star text-warning"></i> <i class="fa fa-star text-warning"></i> <i class="fa fa-star text-warning"></i> <i class="fa fa-star text-warning"></i> <i class="fa fa-star text-warning"></i></dd>
            </dl>
            <hr>
            <div class="row">
                <div class="col-xs-12 clearfix">
                    <router-link :to="{name : 'driver-route-view-requests', params: { id: r.id }}" class="tasks"><i class="fa fa-user"></i> Passengers</router-link>
                    <router-link :to="{name : 'preview-route', params: { id: r.id }}" class="tasks"><i class="fa fa-user"></i> Preview</router-link>
                    <a href="javascript:void(0)" class="tasks"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="javascript:void(0)" class="tasks text-danger float-sm-right"><i class="fa fa-trash"></i> Remove</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                this.$http.get('my-routes')
                    .then((res) => {
                        this.postedRoutes = res.body.data; 
                    }, (err) => {

                    })
            }
        },
        data() {
            return {
                postedRoutes: []
            }
        },
    }
</script>

<style lang="css">
    .route-item dd{
        margin-bottom:0;
    }
    .route-item .tasks{
        margin-right:1rem;
        /*font-size: 0.9rem;*/
    }
    .route-item .tasks:hover{
        text-decoration: none;
    }
    
    
</style>