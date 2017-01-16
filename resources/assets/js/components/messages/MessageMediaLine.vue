<template>
    <div class="media message">
        <a class="media-left">
            <img class="media-object" :src="photo" alt="Generic placeholder image">
        </a>
        <div class="media-body">
            <p class="media-heading font-weight-bold mb-0">{{ name }}</p>
            {{ message }}
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            photo: {
                type: String,
                default: './../images/user1-128x128.jpg'
            },
            name: {
                type: String,
                default: 'Campus Carpool'
            },
            message: {
                type: String,
                default: ' Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.'
            }
        }
    }
</script>



<style>
    .message{
        margin-bottom:0.8rem;
    }
    .message .media-body{
        font-size:0.9rem;
    }
    .message .media-object{
        height: 50px;
        width:50px;
    }
</style>