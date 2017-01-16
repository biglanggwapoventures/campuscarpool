<template>
    <div class="modal fade" id="chatBoxSingle" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" id="chat-body">
                    <message 
                        v-for="m in messages" 
                        :message="m.message" 
                        :name="m.sender_id === recipient.id ?  recipient.name : 'You'">
                    </message>
                </div>
                <div class="modal-footer p-0" style="border-top: none">
                    <form v-on:submit.prevent="sendMessage">
                        <div class="input-group">
                            <input type="text" class="form-control" style="border-radius: 0px" id="message-input" v-model="message">
                            <span class="input-group-btn">
                                <button class="btn btn-info" type="submit" style="border-radius: 0px;"><i class="fa fa-paper-plane"></i></button>
                            </span>
                        </div>
                    </form>
                    <!--<div class="row actions">
                        <div class="col-xs-6 pr-0">
                            <button type="button" class="btn btn-block btn-success mb-0 float-xs-left" id="accept-btn">Accept request</button>
                        </div>
                        <div class="col-xs-6 pl-0">
                            <button type="button" class="btn btn-block btn-danger mb-0 float-xs-left" id="reject-btn">Reject request</button>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'message' : require('./MessageMediaLine.vue')
        },
        mounted() {
            this.modal = $('#chatBoxSingle');
            this.modal.on('shown.bs.modal', () => {
                $('#message-input').focus();
            })
            $('#chat-body').animate({ scrollTop: $('#chat-body').prop("scrollHeight")}, 500);
        },
        methods: {
            open(recipient) {
                this.recipient = recipient;
                this.modal.modal('show')
            },
            sendMessage(){
                this.$http.post('conversation', {
                    recipient_id: this.recipient.id,
                    message: this.message
                }).then((res) => {
                    this.message = '';
                }, (err) => {

                })
            },
            pollMessage(){
                var lastMessageId = this.getLastMessageId();
                this.$http.get(`conversation/${this.recipient.id}/poll/${lastMessageId}`)
                    .then((res) => {
                        if(res.body.data.length) 
                            res.body.data.reverse().forEach((v) => this.messages.push(v));

                        setTimeout(() => { this.pollMessage() }, 1000);
                        this.hasPolled = true;
                    })
            },
            getLastMessageId(){
                if(!this.messages.length)
                    return 0;
                return this.messages[this.messages.length-1].id;
            },
            scrollBottom(){
                $('#chat-body').animate({ scrollTop: $('#chat-body').prop('scrollHeight')}, 500);
            }
        },
        data() {
            return {
                recipient: {},
                modal: null,
                messages: [],
                message: null,
                hasPolled: false,
                xhr: null
            }
        },
        watch: {
            'recipient.id' (val){
                this.messages = [];
                this.$http.get(`conversation/${val}`)
                    .then((res) => {
                        this.messages = res.body.data.reverse();
                        // if(!this.hasPolled) this.pollMessage();
                    })
            },
            messages(){
                setTimeout(() => {
                    this.scrollBottom();
                }, 100);
                
            }
        }
    }
</script>

<style>
    #chatBoxSingle .actions #accept-btn{
        border-radius: 0 0 0 0.25rem;
    }
    #chatBoxSingle .actions #reject-btn{
        border-radius: 0 0 0.25rem 0;
    }
    #chatBoxSingle .modal-body{
        max-height: 40vh;
        overflow: auto;
    }
</style>