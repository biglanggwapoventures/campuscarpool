<template>

    <form class="form-inline" v-on:submit.prevent="searchRoutes">
        <div class="form-group">
            <ccselect v-model="type" :options="[{value: 'HOME', text: 'I am heading to home'}, {value: 'CAMPUS', text: 'I am heading to campus'}]" select-placeholder="Choose a carpool type" :hide-placeholder="true"></ccselect>
        </div>
        <i class="fa fa-at fa-fw"></i>
        <div class="form-group">
            <datepicker v-model="datetime"></datepicker>
        </div>
        <button type="submit" class="btn btn-success" v-bind:disabled="loading"><i class="fa fa-spinner fa-spin" v-show="loading"></i> Search</button>
    </form>
</template>
<script>
    export default {
        mounted() {
            // console.log('SearchRide component mounted.')
            this.searchRoutes();
            this.$parent.$on('search-routes-finished', () => {
                // console.log('event:search-routes-finished triggered')
                this.loading = false;
            })
        },
        components: {
            'datepicker': require('./DateTimePicker.vue'),
            'ccselect' : require('./Select.vue')
        },
        data(){
            return {
                type:'HOME',
                datetime: Date.now(),
                loading: false
            }
        },
        methods: {
            searchRoutes(){
                this.loading = true;
                this.$emit('search-routes', {
                    type: this.type,
                    datetime: this.datetime
                });
            }
        }
    }
</script>