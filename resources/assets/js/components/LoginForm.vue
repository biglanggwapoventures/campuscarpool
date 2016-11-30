<template>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-4 offset-lg-4 offset-md-2">
                <div class="card">
                    <img class="card-img-top img-fluid" :src="logoUrl" alt="Card image cap">
                    <div class="card-block text-xs-center">
                        <p class="card-text">Campus Carpool is a university-wide carpooling community for students and employees. Enter your home and work location to be matched with friendly colleagues or classmates. Catch a ride to campus for the cost of gas!</p>
                      </div>
                      <div class="card-block">
                        <form>
                          <div class="form-group">
                            <label for="formGroupExampleInput" class="mb-0">Username</label>
                            <input type="text" class="form-control form-control-sm" id="formGroupExampleInput" >
                          </div>
                          <div class="form-group">
                            <label for="formGroupExampleInput" class="mb-0">Password</label>
                            <input type="text" class="form-control form-control-sm" id="formGroupExampleInput" >
                          </div>
                           <router-link class="btn btn-primary" to="home">Login</router-link>
                          <hr>
                            <p class="mb-0">No account yet? Get one <router-link to="register">here</router-link></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component ready.')
        },
        data(){
            return {
                logoUrl: './../images/LOGO5.png'
            }
        }
    }
</script>
