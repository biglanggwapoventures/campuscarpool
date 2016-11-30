<template>
<div class="card card-block card-outline-success">
    <form>
        <div class="row">
            <div class="col-sm-12 ">
                <label class="custom-control custom-radio">
                    <input value="home" v-model="commuteType" type="radio" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">I am heading home</span>
                </label>
                <label class="custom-control custom-radio">
                    <input value="campus" v-model="commuteType" type="radio" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">I am heading to campus</span>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <div class="form-group"  v-if="commuteType === 'campus'">
                    <input type="text" class="form-control" placeholder="Enter pick-up point">
                    <a class="form-text text-xs-right" href="#">Use most recent pick-up point <i class="fa fa-mouse-pointer fa-fw"></i> </a>
                </div>
                <div class="form-group"  v-if="commuteType === 'home'">
                    <input type="text" class="form-control" placeholder="Enter drop-off point">
                     <a class="form-text text-xs-right" href="#">Use most recent drop-off point  <i class="fa fa-mouse-pointer fa-fw"></i> </a>
                </div>
            </div>
            <div class="col-sm-2">
                <a href="#" class="btn btn-success btn-block">Find</a>
            </div>
        </div>
    </form>
</div>
</template>
<script>
    export default {
        data(){
            return {
                commuteType:'home'
            }
        }
    }
</script>