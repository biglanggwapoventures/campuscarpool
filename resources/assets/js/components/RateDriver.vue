<template>
    <div class="text-xs-center">
        <p v-if="!defaultRate" class="font-weight-bold">How was your carpool experience? </p>
        <p v-else class="font-weight-bold">Your rated this carpool</p>
        <star-rating :show-rating="false" :star-size="40" @rating-selected="rate" :read-only="done" :rating="defaultRate">
        </star-rating>
        <button v-show="!done && !defaultRate" type="button" class="btn btn-success mt-1" @click="submit" v-bind:disabled="loading"><i v-show="loading" class="fa fa-spinner fa-spin"></i> Submit</button>
        <div class="bs-callout bs-callout-success mt-1" v-show="done">Thank you for your feedback!</div>
    </div>
</template>

<script>
    export default {
        components: {
            'star-rating': require('vue-star-rating')
        },
        props: ['ride-request-id', 'default-rating'],
        data () {
             return {
                 rating: 0,
                 loading: false,
                 done: false
             }
        },
        mounted(){
            if(this.defaultRate){
                this.done = true;
            }
        },
        methods: { 
            rate(val){
                this.rating = val;
            },
            submit() {
                this.loading = true;
                this.$http.post(`ride-requests/commuter/${this.rideRequestId}/rate`, {
                    rating: this.rating
                }).then((res) => {
                    this.loading = false;
                    this.done = true;
                }, (err) => {
                    this.loading = false;
                })
            }
        },
        computed: {
            defaultRate (){
                return parseInt(this.defaultRating)
            }
        }
    }
</script>

<style lang="css">
    .star{
        margin: 0 auto;
    }
</style>