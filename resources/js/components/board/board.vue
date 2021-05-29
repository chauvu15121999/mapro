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
        <!-- Hiển thị thành viên trong board -->
        <span v-for=" member in Member.getMembers.slice(0,5)" :key="member.user_email" class="ml-n2"> <img 
          :src="member.avatar.encoded" class="image_member rounded-circle " 
          v-on:click="handleShowInfoMember($event ,member)"
          /></span>
          <button
          v-on:click="handleShowInfoMember($event,Member.getMembers) "
          v-if="Member.getMembers.length > 5 " type="button" class="image_member btn btn-light rounded-circle btn-sm">+{{Member.getMembers.length - 5}} </button>
          <!-- Thông tin thành viên trong board -->
          <Infomember 
          v-if="Member.isShowInfoMember"
          v-bind:stylist="Member.inviteStyle"
          :board="boards"
          :AllMembers="Member.getMembers"
          :Members="Member.getOneMember"
          :user="users"
          :user_login="user"
          v-on:changeInfoMember="changeInfoMember()"
          v-on:close="Member.isShowInfoMember = false"
          ></Infomember> 
  <!-- Mời thêm thành viên -->
   <button v-on:click="invite($event)" class="ml-2 btn btn-secondary btn-sm">Mời</button>
        <inviteMember
        :boards="boards"
        :user='user'
        v-bind:stylist="Member.inviteStyle" 
        v-if="Member.isInvite"
        v-on:addMember="addMember()"
        v-on:close="Member.isInvite = false"
        ></inviteMember>
  <!-- end invite -->
        <button style="z-index: 1000;" v-on:click="isShowMenu = true" type="button" class="btn btn-secondary btn-sm float-right mx-auto">
          Show menu
        </button>
        <!-- Hiển thị chat -->
        <button style="z-index: 1000; height: 35px;" v-on:click="ShowChat" type="button" class="btn btn-gradient-info btn-rounded btn-icon float-right mr-2">
          <i class="mdi mdi-facebook-messenger"></i>
        </button>
        <menuBoards 
          v-if="isShowMenu == true"
          :member="Member.getMembers"
          :board="boards"
          :users="users"
          :user="user" 
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
<!-- Danh sách task -->
    <List 
      :board = "boards"
      :user = "user"
      :memberBoard="Member.getMembers"
    >  
    </List>
<!-- Danh sách task -->

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
        computed: {
          id_board() {
            return this.$route.params.id_board
          }
        },
        mounted() {
            this.getInfoBoard();
            this.getMember();
            Echo.channel('chat.'+this.id_board).listen('MessageSent',(e) => {
               this.isShowChat = true;  // load lại dữ liệu  
            });
            Echo.channel('updateB.'+this.id_board).listen('updateBoards',(e) => {
                this.getInfoBoard();
                this.getMember();
                console.log('board')
            });
        },
        updated(){
          this.getUser();
        },
        methods: {
          // Lấy user đang đăng nhập.
           getUser(){
                for(var i = 0; i < this.Member.getMembers.length; i ++){
                    if (this.Member.getMembers[i].user_email == this.user.email)
                    {
                        this.users =  this.Member.getMembers[i];
                    }
                }
            },
            // Lấy thông tin
          getInfoBoard(){
            axios.get('api/getInfoBoard/'+this.id_board)
            .then(response => {
                this.boards = response.data;
            }).catch((err) => {
                this.$router.push({name: 'home'})
            })
          },
          // Thay đổi tên bảng 
          changeName(event){
            if( event.target.value != ''){ //Nếu giá trị khác rỗng thì cập nhật lại tên 
              axios.post('api/chanNameBoards/'+this.id_board,{
                user: this.user,
                name : event.target.value
             }).then(response =>{
                console.log(response.data); 
             })
            }else{
                this.getInfoBoard()
            }          
          },
          // Mở component mòi thành viên 
          invite(e){
              this.Member.isInvite = !this.Member.isInvite;
              this.Member.inviteStyle.left = e.pageX + 'px'; // lấy vị trí click
              this.Member.inviteStyle.top = e.pageY + '%';// lấy vị trí click
          },
          // Lấy các thành viên của board 
          getMember(){
            axios.get('api/getMemberBoard/'+this.id_board,)
            .then(response =>{
                this.Member.getMembers = response.data;
            });
          },
          //  thêm thành viên 
          addMember(){
            this.Member.isInvite = false;
            axios.get('api/getMemberBoard/'+this.id_board,)
            .then(response =>{
                this.Member.getMembers = response.data;
            });    
          },
          // hiển thị thông tin thành viên cụ thể 
          handleShowInfoMember(e,data){
            this.Member.isInvite = false;
            this.Member.isShowInfoMember = !this.Member.isShowInfoMember;
            this.Member.inviteStyle.left = e.pageX + 'px';// lấy vị trí click
            this.Member.inviteStyle.top = e.pageY + '%';// lấy vị trí click
            this.Member.getOneMember = data;
          },
          //  Thay đổi thông tin thành viên 
          changeInfoMember(){
              this.Member.isShowInfoMember = false;
              axios.get('api/getMemberBoard/'+this.id_board,)
              .then(response =>{
                this.Member.getMembers = response.data;
              });
          },
          //  Cập nhật hình nền 
          updateBackground(){
              axios.get('api/getInfoBoard/'+this.id_board)
              .then(response => {
                  this.boards = response.data;
              })  
          },
          // Hiển thị khung chat 
          ShowChat(){
            this.isShowChat = !this.isShowChat;
          },
    },

}
</script>