<template>
  <div class="container-fluid page-body-wrapper">

    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <!-- nav -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a v-bind:href="'home/'+user.user_name+'/dashboard.html'" class="nav-link">
                <div class="nav-profile-image">
                  <img v-bind:src="user.avatar.encoded" alt="profile">
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2">{{user.user_name}}</span>
                  <span class="text-secondary text-small">quản lý dự án</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="'home/'+user.user_name+'/dashboard.html'">
                <span class="menu-title">Bảng</span>
                <i class="mdi mdi-table menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" v-bind:href="'home/'+user.user_name+'/dashboard.html'">
                <span class="menu-title">Trang chủ</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="javascript:0;" style="cursor: unset;">
                <span class="menu-title">Team</span>
                <i style="cursor: pointer;" v-on:click="addTeam()" class="mdi mdi-plus menu-icon"></i>
              </a>
              <li class="nav-item ml-3" v-for="team in list_teams">
                <a v-bind:href="'team/'+team.team_name+'/'+team._id" class="nav-link">
                  <span class="menu-title">{{team.team_name}}</span>
                  <i class="mdi mdi-account-multiple menu-icon"></i>
                </a>
              </li> 
            </li>                             
          </ul>
        </nav>
         <addTeam v-bind:teamtype="teamtype" v-if="isAddteam"  v-on:close="isAddteam = false"></addTeam>
         <addBoard
            v-bind:listTeam="list_teams"
            v-if="isAddBorad"  
            v-on:close="isAddBorad = false" 
          ></addBoard>
        <!-- partial -->
        <div class="main-panel mt-n1">
          <div class="content-wrapper">
            <div class="row border-bottom">
              <!-- Bảng cá nhân -->
              <div class="col-sm-12">
                  <div  class="Personal row col-sm-12">
                     <i class="mdi mdi-account-circle"></i>
                     <span  class="font-weight-bold text-sm-left">Personal</span>
                  </div>
                  <div class="row mt-2 col-sm-12">
                      <div 
                        v-for="board in boards"
                        v-if="board.team == 0"
                        :style="{ 'background-image': 'url(' + board.background.url + ')' }"
                        v-on:click="redirect(board._id,board.board_name)"
                        class="col-lg-2 col-sm-3 col-5 ml-4 mt-2 board-home">
                          <p class="text-left mt-2 font-weight-bold">{{board.board_name}}</p>
                      </div>
                      <div v-on:click="isAddBorad = true" class="col-lg-2 col-sm-3 col-5 ml-3 mt-2 board-home add-board">
                          <p class="pt-3 mt-3 ml-2">Create new borad</p>
                      </div>                                                                  
                  </div>
              </div>
            </div>
            <!-- end bảng cá nhân -->
            <!-- Bảng theo nhóm -->
            <div v-for="teams in list_teams" class="row mt-5">
              <div class="col-sm-12">
                  <div  class="Personal row col-sm-11">
                     <i class="mdi mdi-account-multiple"></i>
                     <span  class="font-weight-bold text-sm-left">{{teams.team_name}}</span>
                     <a class="ml-auto mr-5 btn btn-outline-secondary btn-sm" :href="'team/'+teams.team_name+'/'+teams._id">Information Team</a>
                  </div>
                  <div class="row mt-2 col-sm-12">
                      <div 
                        v-for="board in boards"
                        v-if="board.team == teams._id"
                        :style="{ 'background-image': 'url(' + board.background.url + ')' }"
                        v-on:click="redirect(board._id,board.board_name)"
                        class="col-lg-2 col-sm-3 col-5 ml-4 mt-2 board-home">
                          <p class="text-left mt-2 font-weight-bold">{{board.board_name}}</p>
                      </div>
                      <div v-on:click="isAddBorad = true" class="col-lg-2 col-sm-3 col-5 ml-3 mt-2 board-home add-board">
                          <p class="pt-3 mt-3 ml-2">Create new borad</p>
                      </div>                                                                 
                  </div>                
              </div>
              <!-- end theo nhóm -->
            </div>
          </div>
        </div>
        <!-- main-panel ends -->
      </div>
     </div>
</template>
<style>
.listTeam {
    color :black !important;
    font-size: 15px !important;
    margin-left: 40px;
    list-style-type: disc !important;
    display: list-item !important; 
}
.Personal{
  font-size: 20px;
}
.board-home{
  /*background-color: yellow;*/
  background-size: cover;
  height: 100px;
  color: white;
  cursor: pointer;
}
.board-home p{
  font-size: 15px;
}
.board-home:hover {
  filter: brightness(50%);
}
.add-board{
  background-color: #D8CDF7;
  color: black;
}
.add-board:hover {
  background: rgba(0, 0, 0, 0.8);
  color: white;
  font-weight: 900;
  cursor: pointer;
}
</style>
<script>
    import addTeam  from '../team/addteam.vue'; // vue addteam
    import addBoard  from '../board/addBoard.vue';
    export default {
        props: ['user','teamtype',],
        components: {
            addTeam,
            addBoard
        },
        data() {
              return{
                isAddteam: false,
                isAddBorad: false,
                list_teams: [],
                boards: [],
              }
        },
        created() {
            this.getListTeam();
            this.getAllBoards();
            Echo.channel('updateB').listen('updateBoards',(e) => {
                // this.getListTeam();
                this.getAllBoards();
                // load lại dữ liệu  
            });
        },
        methods: {
            //Thêm nhóm
            addTeam(e){
               this.isAddteam = true;
            }, 
            // lấy danh sách team
            getListTeam(){
                axios.get('getAllTeam')
               .then(response => {
                   this.list_teams = response.data
               })
               .catch(error => {
                   this.errors = error.response.data.errors.name
               })
            },
            getAllBoards(){
              axios.get('getAllBoards')
              .then(response =>{
                  this.boards = response.data;
              })
            },
            redirect(id,name){
                console.log(id +  name);
                window.location = "b/" + id  +'/'+ name ; 
            }
          },
            // lấy danh sách background          
    }
</script>