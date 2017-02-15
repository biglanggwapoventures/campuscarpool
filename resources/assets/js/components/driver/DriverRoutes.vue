<template>
    <div>
        <div class="card card-block text-xs-center p-1">
            <form class="form-inline" v-on:submit.prevent="getData">
                <div class="form-group">
                    <ccselect v-model="type" :options="[{value: 'ACTIVE', text: 'Active Routes'}, {value: 'DONE', text: 'Past Routes'}]" :hide-placeholder="true"></ccselect>
                </div>
                <button type="submit" class="btn btn-success" v-bind:disabled="requesting"><i class="fa fa-spin fa-spinner" v-show="requesting"></i> Filter</button>
            </form>
        </div>
        <div class="card card-block route-item"  v-for="r in postedRoutes" style="border-left:5px solid #5cb32a">
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
            <div class="row">
                <div class="col-xs-12 clearfix">
                   
                    <router-link :to="{name : 'driver-route-view-requests', params: { id: r.id }}" class="text-success">
                         <span class="fa-stack fa-lg">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-users fa-stack-1x fa-inverse"></i>
                        </span>
                    </router-link>
                    <router-link :to="{name : 'preview-route-all', params: { id: r.id }}" class="text-warning">
                        <span class="fa-stack fa-lg">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-map-signs fa-stack-1x fa-inverse"></i>
                        </span>
                    </router-link>
                    <a href="javascript:void(0)" class="text-info">
                         <span class="fa-stack fa-lg">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                        </span>
                    </a>
                    <a href="javascript:void(0)" class="text-danger float-xs-right">
                        <span class="fa-stack fa-lg">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-trash fa-stack-1x fa-inverse"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'ccselect' : require('./../Select.vue')
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                this.loading = true;
                this.$http.get('my-routes', {params: {
                    type: this.type
                }})
                .then((res) => {
                    this.postedRoutes = res.body.data; 
                     this.loading = false;
                }, (err) => {
                     this.loading = false;
                })
            }
        },
        data() {
            return {
                postedRoutes: [],
                type: 'ACTIVE',
                loading: null
            }
        },
        computed: {
            requesting() {
                return this.loading != null && this.loading;
            }
        }
    }
</script>

<style lang="css">
    .route-item dd{
        margin-bottom:0;
    }

    .route-item .tasks:hover{
        text-decoration: none;
    }
    
    
</style>