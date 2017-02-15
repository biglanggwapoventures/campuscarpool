<template>
    <button :type="btnType" class="btn" :class="btnClass" @click="clicked" v-bind:disabled="loading || disabled">
        <span v-show="loading">
            <i class="fa fa-spin fa-spinner"></i>
        </span>
        <span v-show="!loading">
            <slot></slot>
        </span>
    </button>
</template>

<script>
    export default {
        mounted(){
            this.$on('ajax-start', () => {
                this.isLoading = true;
            });
            this.$on('ajax-end', () => {
                this.isLoading = false;
            });
        },
        props: {
            'btn-type': {
                type: String,
                default: 'button'
            },
            'btn-class': {
                type: String,
                default: 'btn-secondary'
            },
            loading: {
                type: Boolean,
                default: false
            },
            disabled: {
                type: Boolean,
                default: false
            }
        },
        watch: {
            loading (isLoading){
                this.isLoading = isLoading;
            }
        },
        data() {
            return {
               
            }
        },
        methods: {
            clicked(){
                this.$emit('btn-clicked')
            }
        }
    }
</script>

