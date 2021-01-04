<template>
  <div 
    :style="{ 'background-image': 'url(' + boards.background.url + ')' }"
    class="col-lg-12 col-sm-12 col-12 pt-5 main d-inline-block">
    <div class="row mt-4 pt-3 ">
      <div class="col-lg-12 col-sm-12 col-12">
        <input
        :disabled="users.role == 0" 
        v-autowidth="{maxWidth: '960px', minWidth: '20px', comfortZone: 0}"
        v-model="boards.board_name" @change="this.changeName"
        class="name_board mr-2" type="text" />
        <span v-for=" member in Member.getMembers.slice(0,5)" class="ml-n2"> <img 
          :src="member.avatar.encoded" class="image_member rounded-circle " 
          v-on:click="handleShowInfoMember($event ,member)"
          /></span>
          <button
          v-on:click="handleShowInfoMember($event,Member.getMembers) "
          v-if="Member.getMembers.length > 5 " type="button" class="image_member btn btn-light rounded-circle btn-sm">+{{Member.getMembers.length - 5}} </button>
          <Infomember 
          v-if="Member.isShowInfoMember"
          v-bind:stylist="Member.inviteStyle"
          :board="board"
          :AllMembers="Member.getMembers"
          :Members="Member.getOneMember"
          :user="users"
          v-on:changeInfoMember="changeInfoMember()"
          v-on:close="Member.isShowInfoMember = false"
          ></Infomember> 
  <!-- Mời thêm thành viên -->
   <button v-on:click="invite($event)" class="ml-2 btn btn-secondary btn-sm">Mời</button>
        <inviteMember
        :boards="board"
        v-bind:stylist="Member.inviteStyle" 
        v-if="Member.isInvite"
        v-on:addMember="addMember()"
        v-on:close="Member.isInvite = false"
        ></inviteMember>
  <!-- end invite -->
        <button style="z-index: 1000;" v-on:click="isShowMenu = true" type="button" class="btn btn-secondary btn-sm float-right mx-auto">
          Show menu
        </button>
        <button style="z-index: 1000; height: 35px;" v-on:click="ShowChat" type="button" class="btn btn-gradient-info btn-rounded btn-icon float-right mr-2">
          <i class="mdi mdi-facebook-messenger"></i>
        </button>
        <menuBoards 
          v-if="isShowMenu == true"
          :member="Member.getMembers"
          :board="board"
          v-on:updateBackground="updateBackground()"
          v-on:close="isShowMenu = false"/>
        <chat 
        v-if="isShowChat == true"
        :member="Member.getMembers"
        :board="board"
        :user="user"
        v-on:close="isShowChat = false"
        />
      </div>

    </div>
    <!-- List Card -->

    <List 
      :board = "board"
      :user = "user"
      :memberBoard="Member.getMembers"
    >  
    </List>

    <!-- End list Card -->
  </div>
</template>
<style>
.main{
  min-height: 100vh;
  max-height: 100vh;
  min-width: 100%;
  /* Center and scale the image nicely */
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
}
.name_board{
  background: rgba(0,0,0,0.2);
  border: none;
  color: white;
  height: 25px;
  cursor: pointer;
  font-size: 20px;
  font-weight: bold;
}
.name_board:focus  {
  background-color: white !important;
  color: black;
  border: 3px solid #5977F8;
  border-inline-style: dashed;
}
.name_board:hover {
  background: rgba(240,240,240,0.3);
  color: black;
}
.image_member{
  width: 35px; 
  height: 32px;
  cursor: pointer;
}
.image_member:hover {
  filter:  brightness(80%);
}
</style>
<script>
    import menuBoards from  './menu.vue'
    import chat from  './chat.vue'
    import inviteMember from './inviteMember.vue'
    import Infomember from './Infomember.vue'
    import List from '../list/list.vue'
    export default {
        props: ['board','user'],
        components: {
            menuBoards,
            inviteMember,
            Infomember,
            List,
            chat,
        },
        data() {
          return {
              isvalue: false,
              isShowMenu: false,
              isShowChat: false,
              ishowInfoMember: false,
              errors_exist: false,
              errors: [],
              boards: {
                background: {
                  url: '',
                },
              },
              users: [],
              Member: {
                  isInvite: false,
                  inviteStyle: {
                      left: 0,
                      top: 0,
                  },
                  getMembers: [{
                    avatar: {
                        encoded: ''
                    },
                  }],
                  isShowInfoMember: false,
                  getOneMember : '',
              }
          }
        },
        created() {
            this.getInfoBoard();
            this.getMember();
            Echo.channel('chat').listen('MessageSent',(e) => {
               this.isShowChat = true;  // load lại dữ liệu  
            });
        },
        updated(){
          this.getUser();
        },
        methods: {
          // Lấy user 
           getUser(){
                for(var i = 0; i < this.Member.getMembers.length; i ++){
                    if (this.Member.getMembers[i].user_email == this.user.email)
                    {
                        this.users =  this.Member.getMembers[i];
                    }
                }
                    if(this.users.length != 1){
                       window.history.go(-1);
                    }
            },
          getInfoBoard(){
            axios.get('getInfoBoard/'+this.board._id)
            .then(response => {
                this.boards = response.data;
            })
          },
          changeName(event){
            if( event.target.value != ''){
              axios.post('chanNameBoards/'+this.board._id,{
                name : event.target.value
             }).then(response =>{
                console.log(response.data); 
             })
            }else{
                this.getInfoBoard()
            }          
          },
          invite(e){
              this.Member.isInvite = !this.Member.isInvite;
              this.Member.inviteStyle.left = e.pageX + 'px'; // lấy vị trí click
              this.Member.inviteStyle.top = e.pageY + '%';// lấy vị trí click
          },
          getMember(){
            axios.get('getMemberBoard/'+this.board._id,)
            .then(response =>{
                this.Member.getMembers = response.data;
            });
          },
          addMember(){
            this.Member.isInvite = false;
            axios.get('getMemberBoard/'+this.board._id,)
            .then(response =>{
                this.Member.getMembers = response.data;
            });    
          },
          handleShowInfoMember(e,data){
            this.Member.isInvite = false;
            this.Member.isShowInfoMember = !this.Member.isShowInfoMember;
            this.Member.inviteStyle.left = e.pageX + 'px';// lấy vị trí click
            this.Member.inviteStyle.top = e.pageY + '%';// lấy vị trí click
            this.Member.getOneMember = data;
          },
          changeInfoMember(){
              this.Member.isShowInfoMember = false;
              axios.get('getMemberBoard/'+this.board._id,)
              .then(response =>{
                this.Member.getMembers = response.data;
              });
          },
          updateBackground(){
              axios.get('getInfoBoard/'+this.board._id)
              .then(response => {
                  this.boards = response.data;
              })  
          },
          ShowChat(){
            this.isShowChat = !this.isShowChat;
          }
    },

}
</script>