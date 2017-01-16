<template>
    <div id="back-to-dashboard">
        <router-link :to="routeName"><i class="fa fa-chevron-left"></i> {{text}}</router-link> 
    </div>
</template>

<script>
    export default {
        props: {
            'route-name': Object,
            text: String
        }
    }
</script>
