<template>
    <div id="route-page" class="container-fluid p-0">
        <div class="row">
            <div class="col-md-3 pr-0 detail-section">
                <back-to-dashboard :route-name="{name: 'browse-routes'}" text="Back to browsing routes"></back-to-dashboard>
                <div class="card-block">
                    <dl class="row">
                        <dt class="col-sm-3">From</dt>
                        <dd class="col-sm-9">{{ routeInfo.route_from }}</dd>
                        <dt class="col-sm-3">To</dt>
                        <dd class="col-sm-9">{{ routeInfo.route_to }}</dd>
                        <dt class="col-sm-3">Departure</dt>
                        <dd class="col-sm-9">{{ routeInfo.departure_datetime }}</dd>
                        <dt class="col-sm-3">Vehicle</dt>
                        <dd class="col-sm-9">{{ routeInfo.driver.data.vehicle || '' }}</dd>
                        <dt class="col-sm-3">Space</dt>
                        <dd class="col-sm-9">{{ routeInfo.num_seats_taken }} / {{ routeInfo.num_seats_max }}</dd>
                    </dl>
                    <hr>
                    <form v-on:submit.prevent="sendRequest">
                        <form-input id="waypoint" label="Pickup point" input-class-name="form-control-sm" :errors="requestErrors.latitude"></form-input>
                        <form-input id="message" v-model="message" label="Message" input-class-name="form-control-sm" :errors="requestErrors.message"></form-input>
                        <ccbutton btn-type="submit" :loading="requestLoading" btn-class="btn-success btn-block"><i class="fa fa-paper-plane"></i> Send Request</ccbutton>
                        <div class="alert alert-danger" v-show="requestException" id="request-exception">{{ requestException }}</div>
                    </form>
                    
                </div>
            </div>
            <div class="col-md-9 pl-0">
                <div id="map"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>

<style lang="css">

</style>