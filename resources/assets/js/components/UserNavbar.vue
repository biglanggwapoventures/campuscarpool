<template>
    <nav class="navbar navbar-light " style="margin-bottom:1rem;border-bottom:1px solid #ddd;background: #fff">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img :src="logoUrl"  height="30" class="d-inline-block align-top" alt="">
            </a>
            <ul class="nav navbar-nav float-xs-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="http://example.com" id="responsiveNavbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $auth.user().firstname }}</a>
                    <div class="dropdown-menu" aria-labelledby="responsiveNavbarDropdown">
                        <a class="dropdown-item" @click="openAlert()">Profile</a>
                        <a class="dropdown-item" href="#">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" @click="$auth.logout()">Logout</a>
                    </div>  
                </li>
            </ul>
        </div>
        <alert id="sample" ref="alertRef"></alert>
    </nav>
</template>

<script>
    export default {
        components: {
            'alert' : require('./messages/Alert.vue')
        },
        data(){
            return {
                logoUrl: './../images/LOGO5.png'
            }
        },
        methods: {
            openAlert(){
                this.$refs.alertRef.open();
            }
        }
    }
</script>
