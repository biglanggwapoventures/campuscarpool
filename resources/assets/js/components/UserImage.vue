<template>
    <img :src="imgSrc" :alt="imgAlt" />

</template>

<script>
    export default {
        methods: {
            mounted(){

            }
        },
        data() {
            return {

            }
        },
        props: {
            'img-src': String,
            'img-alt': String
        }
    }
</script>

<style lang="css">

</style>