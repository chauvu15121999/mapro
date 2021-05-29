<template>
  <!-- Modal -->
  <div id="myModal" class="modal-card">
    <!-- Modal content -->
    <div
      class="modal-content-card h-auto mx-auto mr-n5 col-lg-8 col-sm-12 col-12 mt-5"
    >
      <div class="row mt-2 mb-2">
        <span class="col-lg-1 col-sm-2 col-2" style="font-size: 28px"
          ><i class="mdi mdi-equal-box"></i
        ></span>
        <TextareaAutosize
          class="col-lg-10 col-sm-9 col-9 card_name"
          placeholder="Type something here..."
          v-model="card.card_name"
          rows="1"
          :min-height="10"
          :max-height="350"
          @change="changeNameCard()"
        />
        <button @click="$emit('close')" class="close col-lg-1 col-sm-1 col-1">
          <i class="mdi mdi-close"></i>
        </button>
        <p style="color: #9a9696" class="ml-5 pl-4">
          in list: <u>{{ list.list_name }}</u>
        </p>
      </div>
      <div class="row">
        <!-- main -->
        <div class="col-xl-9 col-lg-9 col-sm-9 col-8">
          <!-- Member -->
          <div class="row">
            <div class="ml-4 pl-4 col-12">
              <p style="color: #888585; font-weight: blod">REPORTER</p>
            </div>
            <div class="ml-4 pl-4 col-6">
                  <img :src="reporterOfCard.avarta" class="img_member rounded-circle ml-2" />
                  <span>{{reporterOfCard.name}}</span>
            </div>
          </div>
          <div class="row mt-3 mb-2">
            <div class="col-12 ml-4">
              <div class="row">
                <tasks
                  v-if="task.tasks != null"
                  :task="task.tasks"
                  :card="card"
                  :nofication="noficationOfCard"
                  :userReceinofication="userReceinofication"
                  :user="user"
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
            <description class="col-lg-12" 
            :nofication="noficationOfCard"
            :userReceinofication="userReceinofication"
            :card="card"
            :user="user" />
          </div>
          <!-- EndDescription -->
          <!-- CheckList -->
          <div class="row mt-2">
            <checkList
              class="col-lg-12 ml-2"
              :card="card"
              :nofication="noficationOfCard"
              :userReceinofication="userReceinofication"
              :user="user"
              :checkLists="checkList.checkLists"
              v-on:hanldeDeleteCheckList="hanldeDeleteCheckList"
            />
          </div>
          <!-- Endchecklist -->
          <!-- Files -->
          <div v-if="files.getFiles.length > 0" class="row mt-2">
            <file
              class="col-lg-12"
              :files="files.getFiles"
              :card="card"
              :nofication="noficationOfCard"
              :userReceinofication="userReceinofication"
              :user="user"
              v-on:handleDeleteFile="getAllFile()"
            />
          </div>
          <!-- Endfiles -->
          <!-- comments -->
          <div class="row mt-2">
            <comments 
              class="col-lg-12" 
              :nofication="noficationOfCard"
              :userReceinofication="userReceinofication"
              :card="card" 
              :user="user" />
          </div>
          <!-- EndComments -->
        </div>
        <!--  nút -->
        <div class="col-xl-3 col-lg-3 col-sm-3 col-4">
          <!-- SUGGESTED -->
          <div class="row">
            <div class="col-9 pt-1 pb-1">
              <p style="color: #888585; font-weight: blod; font-size: 12px m-0">
                ASSIGNEE
              </p>
            </div>
            <div class="col-1 mt-3">
              <i
                class="mdi mdi-settings-box"
                style="color: #888585; font-size: 20px"
              ></i>
            </div>
          </div>
          <!-- assignee -->
          <div v-if="assignOFCard" class="row">
            <div class="col-11">
                <img :src="assignOFCard.avarta" class="img_member rounded-circle ml-2" />
                <span>{{assignOFCard.name}}</span>
            </div>
          </div>
          <!-- end join -->
          <!-- end SUGGESTED -->
          <!--   ADD TO CARD  -->
          <div class="row">
            <div class="col-10">
              <p style="color: #888585; font-weight: blod; font-size: 12px">
                ADD TO CARD
              </p>
            </div>
          </div>
          <!-- showALlMember -->
          <div class="row">
            <div
              v-on:click="handleShowAllMember($event)"
              class="col-11 selector pt-2"
            >
              <span class="select_name"
                ><i class=" mdi mdi-account-outline"></i>Assignee</span
              >
            </div>
          </div>
          <!-- end showALlMember -->
          <!--  Checklist -->
          <div class="row mt-2">
            <div
              v-on:click="hanldeShowAddCheckList($event)"
              class="col-11 selector pt-2"
            >
              <span class="select_name"
                ><i class="mdi mdi-checkbox-marked-outline"></i> CheckList</span
              >
            </div>
          </div>
          <!-- end Checklist -->
          <!-- Attachment -->
          <div class="row mt-2">
            <div v-on:click="hanldeRef($event)" class="col-11 selector pt-2">
              <input
                type="file"
                name="file"
                @change="hanldeAddAttchment($event)"
                ref="fileInputAttachment"
                style="display: none"
              />
              <span class="select_name"
                ><i class="mdi mdi-attachment d-none"></i> Attachment</span
              >
            </div>
          </div>
          <!-- Attachment -->
          <div class="row mt-2">
            <div
              v-on:click="hanldeShowAddTask($event)"
              class="col-11 selector pt-2"
            >
              <span class="select_name"
                ><i class="mdi mdi-timelapse"></i> Dou to</span
              >
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-10">
              <p style="color: #888585; font-weight: blod; font-size: 12px">
                MODIFY CARD
              </p>
            </div>
          </div>
          <div class="row">
            <div v-on:click="storage" class="col-11 selector pt-2">
              <span class="select_name"
                ><i class="mdi mdi-timelapse"></i> Storage</span
              >
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
      :user="user"
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
      :user="user"
      :memberBoard="memberBoard"
      :memberCards="Members.getMembers"
      v-on:handleCheckMember="handleCheckMember"
      @close="
        Members.ShowAllMember.isShowAllMember = false;
        Members.ShowAllMember.styleShowALlMember.top = 0;
      "
    />
    <!-- end -->
    <!-- AddCheckList -->
    <addCheckList
      v-if="checkList.isShowAddCheckList == true"
      :stylist="checkList.style"
      :card="card"
      :user="user"
      :nofication="noficationOfCard"
      :userReceinofication="userReceinofication"
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
      :nofication="noficationOfCard"
      :userReceinofication="userReceinofication"
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
  overflow-x: hidden; /* Enable scroll if needed */
  overflow-y: auto;
  background-color: rgb(0, 0, 0); /* Fallback color */
  background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}
/* Modal Content */
.modal-content-card {
  background-color: #f6f6f6;
  border: 1px solid transparent; /* border-color: none */
  width: 100%;
}
.card_name {
  background-color: #f6f6f6;
  border: transparent;
  font-size: 23px;
  font-weight: blod;
}
.card_name:focus {
  background-color: #f6f6f6;
  box-shadow: inset 0 0 10px #0945f2;
}
.selector {
  background: #e4e5e8;
  height: 30px;
  cursor: pointer;
}
.selector:hover {
  background: #cdccd2;
}
.select_name {
  color: #888585;
  font-size: 15px;
}
.img_member{
  width: 30px;
  height: 30px;
}
/* end */
</style>
<script>
import allMember from "./allMember.vue";
import infoMember from "./infoMember.vue";
import description from "./Description.vue";
import addCheckList from "../checkList/addCheckList.vue";
import checkList from "../checkList/checkList.vue";
import comments from "../comments/comment.vue";
import file from "./file.vue";
import addTask from "../task/addTask.vue";
import tasks from "../task/task.vue";
import _ from 'lodash';
export default {
  props: ["InfoCard", "list", "user", "memberBoard", "board"],
  components: {
    allMember,
    infoMember,
    description,
    addCheckList,
    checkList,
    file,
    addTask,
    tasks,
    comments,
  },
  data() {
    return {
      card: this.InfoCard,
      memberCard: '',
      Members: {
        ShowAllMember: {
          isShowAllMember: false,
          styleShowALlMember: {
            top: 0,
            left: 0,
          },
        },
        checkMember: 1,
        ShowMember: {
          info: "",
          isShowMember: false,
          styleShowMember: {
            top: 0,
            left: 0,
          },
        },
      },
      checkList: {
        isShowAddCheckList: false,
        style: {
          top: 0,
          left: 0,
        },
        checkLists: [],
      },
      files: {
        fileUpload: "",
        getFiles: [],
      },
      task: {
        isShowAddTask: false,
        style: {
          top: 0,
          left: 0,
        },
        tasks: [],
      },
    };
  },
  created(){
    this.getInfoCard();
  },
  mounted() {
    // this.getMemberCard();
    this.getCheckList();
    this.getAllFile();
    this.getTasks();
    Echo.channel("updateC." + this.InfoCard._id).listen("updateCards", (e) => {
      this.getCheckList();
      this.getAllFile();
      this.getTasks();
    });
  },
  updated() {
    // this.checkMembers();
    // this.changeNameCard();
  },
  computed: {
      reporterOfCard(){
        return {
          "name": _.get(this.memberCard, 'by_user.user_name') || '',
          "avarta": _.get(this.memberCard,'by_user.avatar.encoded'),
          "email": _.get(this.memberCard,'by_user.email')
        }
      },
      assignOFCard(){
        if(this.memberCard.assign != null){
             return {
              "name": _.get(this.memberCard, 'assign.user_name') || '',
              "avarta": _.get(this.memberCard,'assign.avatar.encoded'),
              "email": _.get(this.memberCard,'assign.user_email')
            }
        }else{
          return null
        }
      },
      noficationOfCard(){
        let name_board = this.board.board_name
        let name_card = this.InfoCard.card_name
        return 'card name '+ name_card + ' in board ' + name_board
      },
      userReceinofication(){
        if(this.InfoCard.assign){
          if(this.InfoCard.by_user._id === this.user._id){
            return this.InfoCard.assign.user_email
          }else{
            return this.InfoCard.by_user.email
          }
            
        }
      },
      id_board() {
        return this.$route.params.id_board;
      }
  },
  methods: {
    changeNameCard(e) {
      axios
        .post("api/changeNameCard/" + this.card._id + "/" + this.board._id, {
          name: this.card.card_name,
          user: this.user,
          id_board : this.id_board,
          nofication: 'have change name ' + this.noficationOfCard,
          userReceinofication: +this.userReceinofication
        })
        .then((response) => {
          // this.$emit('updateCard');
        });
    },
    // hàm mở tab mời
    handleShowInfoMember(e, member) {
      this.Members.ShowMember.isShowMember = !this.Members.ShowMember
        .isShowMember;
      this.Members.ShowMember.info = member;
      this.Members.ShowMember.styleShowMember.top = e.pageY + "px";
      this.Members.ShowMember.styleShowMember.left = e.pageX + "px";
    },
    // hàm mở tất cả member
    handleShowAllMember(e) {
      this.Members.ShowAllMember.isShowAllMember = !this.Members.ShowAllMember
        .isShowAllMember;
      if (this.Members.checkMember == 1) {
        this.Members.ShowAllMember.styleShowALlMember.left = 62.5 + "%";
        this.Members.ShowAllMember.styleShowALlMember.top = 30 + "%";
      } else {
        this.Members.ShowAllMember.styleShowALlMember.left = 62.5 + "%";
        this.Members.ShowAllMember.styleShowALlMember.top = 33 + "%";
      }
    },
    // mở tab thêm checklist
    hanldeShowAddCheckList(e) {
      this.checkList.isShowAddCheckList = !this.checkList.isShowAddCheckList;
      if (this.Members.checkMember == 1) {
        this.checkList.style.top = 38 + "%";
        this.checkList.style.left = 62.5 + "%";
      } else {
        this.checkList.style.top = 41 + "%";
        this.checkList.style.left = 62.5 + "%";
      }
    },
    // Lấy tất cả các checklist
    getCheckList() {
      axios.get("api/getCheckList/" + this.card._id, {}).then((response) => {
        this.checkList.checkLists = response.data;
      });
    },
    // Hàm nhận xóa checklist
    hanldeDeleteCheckList(data) {
      this.getCheckList();
      this.$emit("updateCard");
    },
    hanldeRef(e) {
      this.$refs.fileInputAttachment.click();
    },
   hanldeAddAttchment(e) {
      this.files.fileUpload = this.$refs.fileInputAttachment.files[0]; // Lấy tên file
      const data = new FormData(); // Tạo 1 đối tượng lưu file
      data.append("files", this.files.fileUpload);
      data.append("user", this.user._id);  
      data.append("nofication", 'add file to '+this.noficationOfCard); 
      data.append("id_board", this.id_board);
      data.append("userReceived", +this.userReceinofication);       // Lưu file vào đối tượng
      axios
        .post("api/uploadFiles/" + this.card._id,data)
        .then((response) => {
          this.getAllFile();
          this.$emit("updateCard");
        })
        .catch((err) => {
          if (err.response.status === 413) {
            alert("File quá lớn , kích cỡ tối đa là 10MB");
          }
        });
    },
    getAllFile() {
      axios.get("api/getAllFile/" + this.card._id).then((response) => {
        this.files.getFiles = response.data;
      });
    },
    hanldeShowAddTask(e) {
      this.task.isShowAddTask = !this.task.isShowAddTask;
      this.task.style.top = 13 + "%";
      this.task.style.left = e.pageX + "px";
    },
    getTasks() {
      axios.get("api/getTask/" + this.card._id).then((response) => {
        this.task.tasks = response.data;
      });
    },
    storage() {
      if (this.user._id === this.card.by_user._id) {
        if (confirm("Bạn có muốn lưu trữ thẻ này ?")) {
          axios.post("api/stogareCard/" + this.card._id, {user: this.user}).then((response) => {
            this.$emit("close");
            this.$emit("updateCard");
          });
        }
      } else {
        alert("chỉ có người tạo mới có thể xóa thẻ này !");
      }
    },
    handleCheckMember() {
      this.Members.ShowAllMember.isShowAllMember = false
      this.getInfoCard();
      this.$emit("updateCard");    
    },
    hanldeAddCheckList() {
      this.getCheckList();
      this.$emit("updateCard");
    },
    hanldeAddTask() {
      this.getTasks();
      this.$emit("updateCard");
    },
    handleDeleteFile() {
      this.getAllFile();
      this.$emit("updateCard");
    },
    updateTask() {
      this.getTasks();
      this.$emit("updateCard");
    },
    getInfoCard(){
      axios.get('api/card/'+this.InfoCard._id)
      .then((response) => {
        this.memberCard = response.data
      })
    }
  },
};
</script>


