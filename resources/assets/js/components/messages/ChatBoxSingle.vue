<template>
    <div class="modal fade" id="chat-box-single" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" id="chat-body">
                    <message 
                        v-for="m in onScreenMessages" 
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
            this.modal = $('#chat-box-single');
            this.modal.on('shown.bs.modal', () => {
                $('#message-input').focus();
                this.scrollToBottom();
            });
        },
        methods: {
            open(recipient) {
                this.recipient = recipient;
                if(!this.messages.hasOwnProperty(this.recipient.id)){
                    this.$http.get(`conversation/${this.recipient.id}`)
                    .then((res) => {
                        this.onScreenMessages = this.messages[this.recipient.id]  = res.body.data.reverse();
                        if(!this.initFinish){
                            this.pollMessage();
                            this.initFinish = true;
                        }
                        
                    })
                }else{
                    this.onScreenMessages = this.messages[this.recipient.id];
                }
                this.modal.modal('show')
            },
            sendMessage(){
                this.$http.post('conversation', {
                    recipient_id: this.recipient.id,
                    message: this.message
                }).then((res) => {
                    var id = res.data.id;
                    var message = {
                        id: id,
                        message: this.message,
                        sender_id: this.$auth.user().id
                    };
                    this.messages[this.recipient.id].push(message);
                    // this.onScreenMessages.push(message);
                    this.ignoredIdOnPoll = id;
                    this.message = '';
                    this.scrollToBottom();
                }, (err) => {

                })
            },
            getLastMessageId(){
                // console.log(this.recipient);
                if(this.messages.hasOwnProperty(this.recipient.id)){
                    return this.messages[this.recipient.id][this.messages[this.recipient.id].length-1].id;
                }
                return 0;    
            },
            scrollToBottom(){
                $('#chat-body').animate({ scrollTop: $('#chat-body').prop('scrollHeight')}, 100);
            },
            pollMessage(){
                var lastMessageId = this.getLastMessageId();
                this.$http.get(`conversation/${this.recipient.id}/poll/${lastMessageId}`, {
                    before(request) {

                        if (this.previousRequest) {
                            this.previousRequest.abort();
                        }
                        this.previousRequest = request;
                    }

                })
                .then((res) => {
                    if(res.body.data.length) {
                        var messages = res.body.data.reverse();
                        if(this.messages.hasOwnProperty(this.recipient.id)){
                            messages.forEach((v) => {
                                this.messages[this.recipient.id].push(v)
                                this.scrollToBottom();
                            });
                        }else{
                            this.messages[this.recipient.id] = messages;
                            this.scrollToBottom();
                        }
                    }   
                    setTimeout(() => {
                        this.pollMessage();
                    }, 1000);
                    
                }, (err) => {
                    this.pollMessage();
                })
            },
        },
        destroyed(){
            if (this.previousRequest) {
                this.previousRequest.abort();
                console.log('poll destroyed')
            }
            console.log('chatbox destroyed')
        },
        data() {
            return {
                recipient: {},
                modal: null,
                messages: {},
                onScreenMessages: [],
                message: null,
                hasPolled: false,
                xhr: null,
                initFinish: false,
                ignoredIdOnPoll: 0
            }
        },
        watch: {
            // 'recipient.id' (val){
            //     this.messages = [];
            //     this.$http.get(`conversation/${val}`)
            //         .then((res) => {
            //             this.messages = res.body.data.reverse();
            //             // if(!this.hasPolled) this.pollMessage();
            //         })
            // },
            messages(){
                setTimeout(() => {
                    this.scrollBottom();
                }, 100);
                
            }
        }
    }
</script>

<style>
    #chat-box-single .actions #accept-btn{
        border-radius: 0 0 0 0.25rem;
    }
    #chat-box-single .actions #reject-btn{
        border-radius: 0 0 0.25rem 0;
    }
    #chat-box-single .modal-body{
        max-height: 40vh;
        overflow: auto;
    }
</style>