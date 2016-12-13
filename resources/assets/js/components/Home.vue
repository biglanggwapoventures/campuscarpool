<template>
    <div class="main-content">
        <user-navbar></user-navbar>
        <div class="container">
            
            <div class="row">
                <div class="col-sm-3">
                    <user-card></user-card>
                </div>
                <div class="col-sm-7">
                    <div v-if="$auth.user().role === 'DRIVER'">
                        <post-route></post-route>
                    </div>
                    <div v-if="$auth.user().role === 'COMMUTER'">
                        <div class="alert alert-info" role="alert">
                            Found <b>3 route matches</b> for your search
                        </div>
                        <search-ride></search-ride>
                        <driver-route
                            profilePhotoUrl="./../images/user4-128x128.jpg"
                            driverName="Kathleen Joanne Montecillo"
                            routeFrom="Campus"
                            routeTo="Mactan New Town, Lapu-lapu"
                            timePosted="1 day ago"
                            vehicle="Ford Ranger BHG-009"
                            :spaceOccupied="2"
                            :spaceMax="5"
                            departureTime="Dec 2, 2016 @ 4:30PM">
                        </driver-route>
                        <driver-route
                            profilePhotoUrl="./../images/user8-128x128.jpg"
                            driverName="Mark Christian Biglang-awa"
                            routeFrom="Campus"
                            routeTo="Ayala Malls, Cebu"
                            timePosted="6 hours ago"
                            vehicle="Toyota Fortuner YYD-671"
                            :spaceOccupied="0"
                            :spaceMax="4"
                            departureTime="Dec 3, 2016 @ 4:00PM">
                        </driver-route>
                        <driver-route
                            profilePhotoUrl="./../images/user6-128x128.jpg"
                            driverName="Jett Francis Branzuela"
                            routeFrom="Barangay Tipolo, Mandaue City"
                            routeTo="Campus"
                            timePosted="1 hour ago"
                            vehicle="Kia Picanto ADD-877"
                            :spaceOccupied="1"
                            :spaceMax="3"
                            departureTime="Dec 3, 2016 @ 9:30AM">
                        </driver-route>
                    </div>
                    
                </div>
                <div class="col-sm-2">
                    <div class="card card-block card-outline-success">
                        <p class="card-text"><b>Campus Carpool&reg;</b> is also available on mobile!</p>
                        <img :src="googleplay" alt="" class="img-fluid">
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {
        data: function(){
            return {
                googleplay: 'https://www.wunder.org/images/googleplay.png',
                sample : ''
            }
        }
    }
</script>