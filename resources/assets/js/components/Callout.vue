<template>
    <div class="bs-callout" v-bind:class="calloutClass" v-show="content">
        <h4 v-if="title">{{ title }}</h4>
        <ul class="list-unstyled mb-0" v-if="typeof content == 'string'">
            <li>{{ content }}</li>
        </ul>
        <ul class="list-unstyled" v-else>
            <li v-for="e in content" >{{ e }}</li>
        </ul>
        
    </div>
</template>


<script>
    export default {
        props: {
            'content': {
                default: ''
            },
            'title': String,
            'callout-class': String
        },
        watch: {
        
        }
    }
</script>

<style lang="css">

</style>