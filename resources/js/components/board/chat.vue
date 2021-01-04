<template>
<div class="chat chated mr-2"  :class="{chatClose : isDismiss}">
	<div class="row pb-2 pt-2 header-chat">
		<div class="col-lg-10 col-sm-9 col-8 ml-2">
			<span v-for=" mem in member"><img 
			          :src="mem.avatar.encoded" style="width: 35px; height:35px; " class="rounded-circle " 
			          />
			 </span>
		 </div>
	<div class="col-lg-1 "><button  v-on:click="close()" class="close"><i class="mdi mdi-close"></i></button></div>
	</div>
 	<div class="row">
 		<div class="col-lg-12">
 			<div v-chat-scroll="{always: false, smooth: true, scrollonremoved:true}" class="bdChat" id="bdChat">
      <div v-for = "mess in list_messages" class="ml-2 mr-2 mt-2 mess" 
        :style="mess.user_id == user._id ? ' text-align: right ;' : 'text-align : left;' ">
          <img 
          :src="mess.avatar.encoded" :style="mess.user_id == user._id ? ' display: none ; ' : 'display : inline;' "  style="width: 25px; height:25px; " class="rounded-circle ml-2" 
                  />
          <span class="pl-3 pt-1 pr-3 pb-1 text-mess" 
          :style="mess.user_id == user._id ? 'border-radius: 15px; background: #5A68F3; color: white;': 'border-radius: 15px; background: #EAEAEC; color: black;'">{{mess.message}}</span>
          <div class="clear">.</div>
      </div>
          	
 			</div>
 		</div> 		
 	</div>
 	<div class="row input-chat">
 		<div class="col-lg-12 ml-2">
			<input v-on:keyup.enter="sendMessage($event)" v-model="message" class="mt-2 col-lg-10 col-sm-8 col-10 input" type="text">
			<i v-on:click="sendMessage($event)"  class="mdi mdi-send ml-2  send"></i>
		 </div>
 	</div>
</div>
</template>
<style>
.chat {
  position: absolute;
  top: -500%;
  right: 0;
  width: 300px;
  display: inline-block;
  max-height: 400px;
  overflow-x: hidden;
  overflow-y: visible;
  white-space: pre-line;
  background: #F0F0F0;
  z-index: 2000;
  transform: translateY(100%);
  -webkit-transform: translateY(100%);
}

.chated {
   animation: chat-in 0.5s forwards;
  -webkit-animation: chat-in 0.5s forwards;
}

.chatClose {
  animation: chat-out 0.5s forwards;
  -webkit-animation: chat-out 0.5s forwards;
}

@keyframes chat-in {
  0% {
    -webkit-transform: translateY(200%);
  }
  100% {
    -webkit-transform: translateY(100%);
  }
}

@-webkit-keyframes chat-in {
  0% {
    transform: translateY(200%);
  }
  100% {
    transform: translateY(100%);
  }
}

@keyframes chat-out {
  0% {
    transform: translateY(100%);
  }
  100% {
    transform: translateY(200%);
  }
}

@-webkit-keyframes chat-out {
  0% {
    -webkit-transform: translateY(100%);
  }
  100% {
    -webkit-transform: translateY(200%);
  }
}
.header-chat{
  border-width: 1px;
  border-style: outset;
  height: 50px;
}
.bdChat{
  /*min-height: 295px;*/
  max-height: 295px;
  min-height: 295px;
  width: 100%;  
  overflow-y: auto;
  display: inline-block;
  white-space: pre-line;
  background: white;
}
.input-chat{
	height: 50px;
	border-width: 1px;
	border-style: outset;

}
.input{
	border-radius: 10px; 
	background-color: #DADDE2;
}
.send{
	color: #036BF8;
	cursor: pointer;
}
/*.mess{
  display: tabble;
}*/
.clear {
 clear: both;
 display: block;
 overflow: hidden;
 visibility: hidden;
 width: 0;
 height: 0;
}
.text-mess{
  max-width: 70%;
  word-wrap: break-word;
}
</style>
<script>
import VueChatScroll from 'vue-chat-scroll'
    export default {

        props: ['member','board','user'],
        components: {
          
        },
        data() {
          return {
          	isDismiss : false,
          	message: '',
          	list_messages: [],
          }
        },
        created() {
        	this.loadMessage();
          // Lắng nghe sự kiện 
           Echo.channel('chat').listen('MessageSent',(e) => {
               this.loadMessage();  // load lại dữ liệu  
            });
        },
        methods: {
            close(){
                this.isDismiss = true;
                setTimeout(() => this.$emit('close'), 500);
            },
            loadMessage() {
                axios.get('getMess/'+this.board._id)
                    .then(response => {
                        this.list_messages = response.data;
                    }).catch(error => {
                        console.log(error)
                    });
            },
            sendMessage() {
                axios.post('sendMess/'+this.board._id, {
                        message: this.message,
                        user: this.user
                  }).then(response => {
                        // console.log(response.data);
                        this.loadMessage();
                        this.message = '';
                    })
                    .catch(error => {
                        console.log(error)
                })
            },
        }

    }
</script>