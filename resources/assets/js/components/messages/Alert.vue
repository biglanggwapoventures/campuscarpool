<template>
    <!-- Modal -->
<div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body text-xs-center">
        {{ message }}
      </div>
      <div class="modal-footer" v-show="showActions">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
    var modal = null;
    export default {
        mounted(){
            modal = $('#'+this.id);
        },
        props: {
            id: {
                type: String,
                default: 'myModal'
            }
        },
        data (){
            return {
                message: null,
                showActions: true
            }
        },
        methods: {
            show(message){
                if(typeof message === 'string'){
                    this.message = message
                }else{
                    this.message = message.message;
                    this.showActions = message.showActions;
                }
                modal.modal('show');
            },
            hide(){
                modal.modal('hide');
            },
            changeMessage(message){
                if(typeof message === 'string'){
                    this.message = message
                }else{
                    this.message = message.message;
                    this.showActions = message.showActions;
                }
            }
        }
    }
</script>