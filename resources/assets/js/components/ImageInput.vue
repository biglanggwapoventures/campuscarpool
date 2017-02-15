<template>
    <div class="Image-input">
        <div class="Image-input__image-wrapper">
            <i v-show="! imageSrc" class="icon fa fa-picture-o"></i>
            <img v-show="imageSrc" class="Image-input__image" :src="imageSrc">
        </div>

        <div class="Image-input__input-wrapper">
            Choose
            <input @change="previewThumbnail" class="Image-input__input" name="thumbnail" type="file">
        </div>
    </div>
</template>

<script>
    export default {
        props: [ 'image-src' ],
        methods: {
            previewThumbnail: function(event) {
                var input = event.target;

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    var vm = this;

                    reader.onload = function(e) {
                        vm.imageSrc = e.target.result;
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }
        }
    }
</script>

<style lang="css">

    .Image-input {
        display: flex;
    }

    .Image-input__image-wrapper {
        flex-basis: 80%;
        height: 150px;
        flex: 2.5;
        border-radius: 1px;
        margin-right: 10px;
        overflow-y: hidden;
        border-radius: 1px;
        background: #eee;
        justify-content: center;
        align-items: center;
        display: flex;
    }

    .Image-input__image-wrapper > .icon {
        color: #ccc;
        font-size: 50px;
        cursor: default;
    }

    .Image-input__image {
        max-width: 100%;
        border-radius: 1px;
    }

    .Image-input__input-wrapper {
        overflow: hidden;
        position: relative;
        background: #eee;
        border-radius: 1px;
        float: left;
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        color: rgba(0,0,0,0.2);
        transition: 0.4s background;
    }

    .Image-input__input-wrapper:hover {
        background: #e0e0e0;
    }


    .Image-input__input {
        cursor: inherit;
        display: block;
        font-size: 999px;
        min-height: 100%;
        opacity: 0;
        position: absolute;
        right: 0;
        text-align: right;
        top: 0;
        cursor: pointer;
    }
</style>