<template>
 <!-- Modal -->
<div id="myModal" class="modal-card">
  <!-- Modal content -->
  <div class="modal-content-card h-auto mx-auto mr-n5 col-lg-6 col-sm-12 col-12 mt-5 ">
    <div class="row mt-2 mb-2">
      <span class="col-lg-1 col-sm-2 col-2  " style="font-size : 28px;" ><i class=" mdi mdi-equal-box"></i></span>
      <TextareaAutosize
      class=" col-lg-10 col-sm-9 col-9 card_name"
        placeholder="Type something here..."
        v-model="card.card_name"
        rows="1"
        :min-height="10"
        :max-height="350"
        @change="changeNameCard()"
      />
      <button @click="$emit('close')" class="close  col-lg-1 col-sm-1 col-1"><i class="mdi mdi-close"></i></button>
      <p style="color: #9A9696" class="ml-5 pl-4">in list: <u>{{list.list_name}}</u> </p>
    </div>
    <div class="row">
      <div class="col-xl-9 col-lg-9 col-sm-9 col-8">
        <!-- Member -->
        <div class="row">
          <div class="ml-4 pl-4 col-12">
            <p style="color: #888585; font-weight: blod;">MEMBERS</p>
          </div>
          <div class="ml-4 pl-4 col-6">
            <img
              v-for="member in Members.getMembers" 
              v-on:click="handleShowInfoMember($event , member)"
              :src="member.avatar.encoded" class="image_member rounded-circle " alt="">
          </div>
        </div>
        <div class="row mt-3 mb-2">
          <div class="col-12 ml-4">
              <div class="row">
                <tasks
                  v-if="task.tasks != null"
                  :task="task.tasks"
                  :card="card"
                   class="col-12"
                   v-on:updateTask="updateTask"
                   v-on:show="hanldeShowAddTask"
                />
              </div>
          </div>
        </div>
        <!-- End Member -->
        <!-- Description -->
        <div class="row mt-2">
            <description
              class="col-lg-12"
              :card="card"
            />
        </div>
        <!-- EndDescription -->
        <!-- CheckList -->
        <div class="row mt-2">
            <checkList
            class="col-lg-12 ml-2" 
              :card="card"
              :checkLists='checkList.checkLists'
              v-on:hanldeDeleteCheckList="hanldeDeleteCheckList"
            />
        </div>
        <!-- Endchecklist -->
        <!-- Files -->
        <div v-if= "files.getFiles.length > 0" class="row mt-2">
          <file 
            class="col-lg-12"
            :files = "files.getFiles"
            :card="card"
            v-on:handleDeleteFile="getAllFile()"
          />
        </div>
        <!-- Endfiles -->
      </div>
      <div class="col-xl-3 col-lg-3 col-sm-3 col-4">
        <!-- SUGGESTED -->
        <div class="row">
          <div class="col-9">
            <p style="color: #888585; font-weight: blod; font-size: 12px;">SUGGESTED</p>
          </div>
          <div class="col-1">
            <i class="mdi mdi-settings-box " style="color: #888585; font-size: 20px;"></i>
          </div>
        </div>
        <!-- join -->
        <div v-if="Members.checkMember != 1" v-on:click="Join()"  class="row" >
            <div class="col-11 selector pt-2">
              <span class=" select_name"><i  class="select_name mdi mdi-account-outline"></i>  Join</span>
            </div>
        </div>
        <!-- end join -->
        <!-- end SUGGESTED -->
        <!--   ADD TO CARD  -->
        <div class="row mt-3">
          <div class="col-10">
            <p style="color: #888585; font-weight: blod; font-size: 12px;">ADD TO CARD</p>
          </div>
        </div>
         <!-- showALlMember -->
        <div class="row">
          <div v-on:click="handleShowAllMember($event)" class="col-11 selector pt-2">
              <span class=" select_name"><i  class="select_name mdi mdi-account-outline"></i>  Members</span>
          </div>
        </div><!-- end showALlMember -->
        <!-- end Checklist -->
        <div class="row mt-2">
          <div v-on:click="hanldeShowAddCheckList($event)" class="col-11 selector pt-2">
            <span class="select_name "><i  class="mdi mdi-checkbox-marked-outline"></i>  CheckList</span>
          </div>
        </div>
        <!-- end Checklist -->
        <!-- Attachment -->
        <div class="row mt-2">
          <div v-on:click="hanldeRef($event)" class="col-11 selector pt-2">
               <input type="file"
                      name="file" 
                      @change="hanldeAddAttchment($event)"
                      ref="fileInputAttachment" 
                      style="display: none;">
              <span class="select_name "><i class="mdi mdi-attachment d-none"></i> Attachment</span>
          </div>
        </div>
        <!-- Attachment --> 
        <div class="row mt-2">
          <div v-on:click="hanldeShowAddTask($event)" class="col-11 selector pt-2">
               <span class="select_name"><i class="mdi mdi-timelapse"></i> Dou to</span> 
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-10">
            <p style="color: #888585; font-weight: blod; font-size: 12px;">MODIFY CARD</p>
          </div>
        </div>
        <div class="row">
          <div v-on:click="remove" class="col-11 selector pt-2">
               <span class=" select_name"><i class="mdi mdi-timelapse"></i> Remove</span> 
          </div>
        </div>          
        <!-- end  ADD TO CARD -->
      </div>

    </div>  
  </div>
          <!-- Show Info member -->
            <infoMember
            v-if="Members.ShowMember.isShowMember == true"
            :Members="Members.ShowMember.info"
            :card="card"
            :stylist="Members.ShowMember.styleShowMember"
            v-on:handleCheckMember="handleCheckMember"
            @close="Members.ShowMember.isShowMember = false"
              />
        <!-- end -->
        <!-- Show all Member in card -->
            <allMember 
            v-if="Members.ShowAllMember.isShowAllMember == true"
            :stylist="Members.ShowAllMember.styleShowALlMember"
            :card="card"
            :memberBoard="memberBoard"
            :memberCards="Members.getMembers"
            v-on:handleCheckMember="handleCheckMember"
            @close="Members.ShowAllMember.isShowAllMember = false; Members.ShowAllMember.styleShowALlMember.top = 0;"
          />
          <!-- end -->
          <!-- AddCheckList -->
            <addCheckList
                v-if="checkList.isShowAddCheckList == true"
                :stylist="checkList.style"
                :card="card"
                :user="user"
                v-on:hanldeAddCheckList="hanldeAddCheckList"
                @close="checkList.isShowAddCheckList = false"
            />
          <!-- end -->
          <!-- AddTask -->
            <addTask

                v-if="task.isShowAddTask == true"
                :stylist="task.style"
                :card="card"
                :user="user"
                :task="task.tasks"
                v-on:hanldeAddTask="hanldeAddTask"
                @close="task.isShowAddTask = false" 
            />
          <!-- end -->
</div>
</template>
<style  type="text/css" >

.modal-card {
  display: block; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 5000; /* Sit on top */
 /* padding-top: 10px;*/ /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow-x:  hidden; /* Enable scroll if needed */
  overflow-y: auto;
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
/* Modal Content */
.modal-content-card {
  background-color: #F6F6F6;
  border: 1px solid transparent;/* border-color: none */
  width: 100%;
}
.card_name{
  background-color: #F6F6F6;
  border: transparent;
  font-size: 23px;
  font-weight: blod;
}
.card_name:focus {
  background-color: #F6F6F6;
  box-shadow: inset 0 0 10px #0945F2; 
}
.selector{
  background: #E4E5E8;
  height: 30px;
  cursor: pointer;
}
.selector:hover{
  background: #CDCCD2;
}
.select_name{
  color: #888585; 
  font-size: 15px;
} 
/* end */
</style>
<script>
    import allMember from './allMember.vue';
    import infoMember from './infoMember.vue';
    import description from './Description.vue';
    import addCheckList from '../checkList/addCheckList.vue';
    import checkList from '../checkList/checkList.vue';
    import file from './file.vue';
    import addTask from '../task/addTask.vue';
    import tasks from '../task/task.vue';
    export default {
        props: ['InfoCard','list','user','memberBoard','board'],
        components: {
            allMember,
            infoMember,
            description,
            addCheckList,
            checkList,
            file,
            addTask,
            tasks,
        },
        data() {
              return{
                card : this.InfoCard,
                Members: {
                  getMembers: [{
                      avatar: {
                        encoded: '',
                      },
                  }],
                  ShowAllMember: {
                    isShowAllMember: false,
                    styleShowALlMember: {
                      top: 0,
                      left: 0,
                    },
                  },
                  checkMember: 1,
                  ShowMember: {
                    info: '',
                    isShowMember: false,
                    styleShowMember: {
                      top: 0,
                      left: 0,
                    }                   
                  }    
                },
                checkList:{
                  isShowAddCheckList: false,
                  style: {
                    top: 0,
                    left: 0,
                  },
                  checkLists: [],
                },
                files: {
                  fileUpload: '',
                  getFiles: [],
                },
                task: {
                  isShowAddTask: false,
                  style: {
                    top:0,
                    left: 0,
                  },
                  tasks: [],
                } 
              }
        },
        created(){
          this.getMemberCard();
          this.getCheckList();
          this.getAllFile();
          this.getTasks();
          Echo.channel('updateC.'+this.card._id).listen('updateCards',(e) => {
                this.getMemberCard();
                this.getCheckList();
                this.getAllFile();
                this.getTasks();
                 // load lại dữ liệu
                // axios.post('pushNoficationCard'+this.card._id,{
                //   user : e.user,
                //   content: e.message,
                // });

          });
        },
        updated(){
          this.checkMembers();
          // this.changeNameCard();         
        },
        methods: {
          // Kiểm tra user đang đăng nhập
          checkMembers(){
                let check = 0; 
                for(var i = 0 ; i < this.Members.getMembers.length; i++){
                  if(this.user.email == this.Members.getMembers[i].user_email){
                    check = 1;
                  }
                }
                if(check == 1){
                  this.Members.checkMember = 1;
                }else{
                  this.Members.checkMember = 0;
                }
            },
            // Thay đổi tên
          changeNameCard(e){
            axios.post('changeNameCard/'+this.card._id+'/'+this.board._id,{
                name: this.card.card_name,
            }).then(response =>{
              // this.$emit('updateCard');
            });
          },
          // Lấy danh sách member
          getMemberCard(){
            axios.get('getMemberCard/'+this.card._id,{
            }).then(response => {
                this.Members.getMembers = response.data;
            });
          },
          // Hàm tham gia card
          Join(){
              axios.post('joinCard/'+this.card._id,{
                  user: this.user,
              }).then(response => {
                this.getMemberCard();
                this.$emit('updateCard');
              })
          },
          // hàm mở tab mời 
          handleShowInfoMember(e , member){
            this.Members.ShowMember.isShowMember = !this.Members.ShowMember.isShowMember;
            this.Members.ShowMember.info = member;
            this.Members.ShowMember.styleShowMember.top = e.pageY + 'px';
            this.Members.ShowMember.styleShowMember.left = e.pageX + 'px';
          },
          // hàm mở tất cả member
          handleShowAllMember(e){
            this.Members.ShowAllMember.isShowAllMember = !this.Members.ShowAllMember.isShowAllMember;
            if(this.Members.checkMember == 1){
              this.Members.ShowAllMember.styleShowALlMember.left = 62.5  + '%';
              this.Members.ShowAllMember.styleShowALlMember.top = 30 + '%';
            }else{
              this.Members.ShowAllMember.styleShowALlMember.left = 62.5  + '%';
              this.Members.ShowAllMember.styleShowALlMember.top = 33  + '%';
            }          
          },
          // mở tab thêm checklist
          hanldeShowAddCheckList(e){
            this.checkList.isShowAddCheckList = !this.checkList.isShowAddCheckList;
            if(this.Members.checkMember == 1){
              this.checkList.style.top = 38 + '%';
              this.checkList.style.left =  62.5 + '%';
            }else{
              this.checkList.style.top = 41 + '%';
              this.checkList.style.left =  62.5 + '%';
            }
          },
          // Lấy tất cả các checklist
          getCheckList(){
            axios.get('getCheckList/'+this.card._id,{
            }).then(response => {
              this.checkList.checkLists = response.data;    
            })  
          },
          // Hàm nhận xóa checklist
          hanldeDeleteCheckList(data){
            this.getCheckList();
            this.$emit('updateCard');
          },
          hanldeRef(e){
            this.$refs.fileInputAttachment.click();
            
          },
          hanldeAddAttchment(e){
            this.files.fileUpload = this.$refs.fileInputAttachment.files[0];
            const data = new FormData();
            data.append('files', this.files.fileUpload);
            axios.post("uploadFiles/"+this.card._id, data).then(response =>{
              this.getAllFile();
              this.$emit('updateCard');
            }).catch(err => {
              if(err.response.status === 413){
                alert("File quá lớn , kích cỡ tối đa là 10MB");
              }
            })
        },
        getAllFile(){
          axios.get('getAllFile/'+this.card._id).then(response => {
            this.files.getFiles = response.data;
          })
        },
        hanldeShowAddTask(e){
          this.task.isShowAddTask = !this.task.isShowAddTask;
          this.task.style.top = 13 + '%';
          this.task.style.left =  e.pageX + 'px';
        },
        getTasks(){
          axios.get('getTask/'+this.card._id).then(response =>{
              this.task.tasks = response.data;
          })
        },
        remove(){
          if(this.user._id === this.card.by_user){
            if(confirm("Bạn có muốn xóa thẻ này ?")){
              axios.get('removeCard/'+this.card._id).then(response => {
                this.$emit('close');  
                this.$emit('updateCard');
              });
            }
          }else{
            alert("chỉ có người tạo mới có thể xóa thẻ này !");
          }

        },
        handleCheckMember(){
          this.getMemberCard();
          this.$emit('updateCard');
        },
        hanldeAddCheckList(){
          this.getCheckList();
          this.$emit('updateCard');
        },
        hanldeAddTask(){
          this.getTasks();
          this.$emit('updateCard');
        },
        handleDeleteFile(){
          this.getAllFile();
          this.$emit('updateCard');
        },
        updateTask(){
           this.getTasks();
            this.$emit('updateCard');
        }
    }
  }
</script>


