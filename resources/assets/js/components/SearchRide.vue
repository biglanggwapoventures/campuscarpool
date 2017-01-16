<template>
<div class="card card-block">
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
        <div class="form-group"  v-if="commuteType === 'campus'">
                <div class="input-group">
                <input type="text" class="form-control" placeholder="Enter pick-up point">
                <span class="input-group-btn">
                    <button class="btn btn-success" type="button"><i class="fa fa-search"></i></button>
                </span>
            </div>
            <a class="form-text text-xs-right" href="#">  <i class="fa fa-mouse-pointer fa-fw"></i> Use most recent pick-up point </a>
        </div>
        <div class="form-group"  v-if="commuteType === 'home'">
                <div class="input-group">
                <input type="text" class="form-control" placeholder="Enter drop-off point">
                <span class="input-group-btn">
                    <button class="btn btn-success" type="button"><i class="fa fa-search"></i></button>
                </span>
            </div>
                <a class="form-text text-xs-right" href="javascript:void(0)">  <i class="fa fa-mouse-pointer fa-fw"></i> Use most recent drop-off point</a>
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