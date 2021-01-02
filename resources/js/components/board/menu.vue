<template>
<div class="notification-container selected"  :class="{dismiss : isDismiss}">
  <div v-if="show == 0" class="main">
    <div class="row menu ml-1 pt-2 pb-1" >
        <h3 class="text-center col-lg-8 col-sm-8 col-8 ml-5 font-weight-bold" style="font-size: 15px; ">Menu</h3>
        <button   v-on:click="close()" class="close col-lg-2 col-sm-2 col-2 "><i class="mdi mdi-close"></i></button>    
    </div>
    <div class="row menu ml-1 mt-2 mb-2">
        <ul class="list-group col-lg-12 col-sm-12 col-12">
          <li @click="show = 1" class="list-group-item"> <span class="font-weight-bold">About This Board</span> <p>Add a description to your board</p></li>
          <li @click="show = 2" class="list-group-item">Change Background</li>
          <li class="list-group-item">Member</li>
          <li  class="list-group-item"><button v-on:click="remove" class="btn-danger">Delete</button></li>
        </ul>
    </div>
    <div class="row activity-menu ml-1 mt-2 mb-2">
          <a style="text-decoration: none; color: black;" class="list-group-item  col-lg-12 col-sm-12 col-12"><span class="font-weight-bold">Activity</span></a><br>
    </div> 
  </div>
  <!-- thông tin bảng -->
  <div v-if="show == 1" class="about">
     <div class="row menu ml-1" >
      <i @click="show = 0" class="mdi mdi-chevron-left mt-2 pt-n1" style="cursor: pointer; font-size: 30px;"></i>
        <h3 class="text-center col-lg-6 col-sm-6 col-6 ml-5 pb-2 font-weight-bold" style="font-size: 15px; ">
        About this cart</h3>
        <button   v-on:click="close()" class="close col-lg-2 col-sm-2 col-2 "><i class="mdi mdi-close"></i></button>    
    </div>
    <div class="row menu ml-1 mt-2 mb-2">
      <div class="col-lg-12 col-sm-12 col-12">
        <div class="row">
          <i class="mdi mdi-account-outline" style="font-size: 25px;"></i>
          <h4 class="mt-1 ml-3 text-info">Make by</h4>
        </div>
        <div class="row mt-1 mb-2">
      <img :src="member[0].avatar.encoded" class="img_member rounded-circle ml-2" />
          <h4 class="d-inline-block ml-2 mt-2">{{member[0].user_name}}</h4> 
        </div>
        <div class="row mt-1 mb-2">
            <i class="mdi mdi-sort-variant ml-1" style="font-size: 25px;"></i>
            <h4>Description</h4>
            <textarea class="form-control ml-2" id="exampleTextarea1" style="max-width: 70vh;" rows="4"></textarea>
            <button class="btn btn-gradient-primary btn-sm ml-1 mt-2">Save</button>
        </div>
      </div>  
    </div>
  </div> 
  <!-- thay đổi hình nền -->
  <div v-if="show == 2" class="background">
    <div class="row menu ml-1" >
      <i @click="show = 0" class="mdi mdi-chevron-left mt-2 pt-n1" style="cursor: pointer; font-size: 30px;"></i>
        <h3 class="text-center col-lg-6 col-sm-6 col-6 ml-5 pb-2 font-weight-bold" style="font-size: 15px; ">
        About this cart</h3>
        <button   v-on:click="close()" class="close col-lg-2 col-sm-2 col-2 "><i class="mdi mdi-close"></i></button>    
    </div>
    <div class="row menu ml-1 mt-2 mb-2">
      <background
      v-bind:listAllBackgroud = "listAllBackgroud"
      v-on:hanldeListBackground = "updateBackground"
      ></background>
    </div>   
  </div>
</div>
</template>
<style>
.notification-container {
  position: absolute;
  top: 0;
  right: 0;
  width: 300px;
  display: inline-block;
  height: 600px;
  overflow-x: hidden;
  overflow-y: auto;
  white-space: pre-line;
  background: #F0F0F0;
  z-index: 1000;
  transform: translateX(100%);
  -webkit-transform: translateX(100%);
}

.selected {
   animation: slide-in 0.5s forwards;
  -webkit-animation: slide-in 0.5s forwards;
}

.dismiss {
  animation: slide-out 0.5s forwards;
  -webkit-animation: slide-out 0.5s forwards;
}

@keyframes slide-in {
  0% {
    -webkit-transform: translateX(100%);
  }
  100% {
    -webkit-transform: translateX(0%);
  }
}

@-webkit-keyframes slide-in {
  0% {
    transform: translateX(100%);
  }
  100% {
    transform: translateX(0%);
  }
}

@keyframes slide-out {
  0% {
    transform: translateX(0%);
  }
  100% {
    transform: translateX(100%);
  }
}

@-webkit-keyframes slide-out {
  0% {
    -webkit-transform: translateX(0%);
  }
  100% {
    -webkit-transform: translateX(100%);
  }
}
.menu{
  border-bottom: 1px solid #D1C7C7;
}
.list-group-item{
  background: #F0F0F0 !important;
  border: none;
  cursor: pointer; 
}
.list-group-item:hover {
  filter: brightness(90%);
}
</style>
<script>
    import background from './listBackgroud.vue'
    export default {
        props: ['member','board'],
        components: {
          background
        },
        data() {
          return {
            isDismiss : false,
            show: 0,
            listAllBackgroud: [],
          }
        },
        created() {
           this.getBackgrouds();
        },
        computed: {
          // byMember() {          
          //     return this.member[0];
          // }
        },
        methods: {
            close(){
                this.isDismiss = true;
                // 
                setTimeout(() => this.$emit('close'), 500);
            },
        getBackgrouds(){
            axios.get('getAllBackgrouds',{
            })
            .then(response => {
              this.listAllBackgroud = response.data
            })
          },
          updateBackground(data){
            axios.post("updateBackground/"+this.board._id,{
              id_background : data,
            }).then(response => {
              this.$emit("updateBackground");
            })
          },
          remove(){
            if(confirm("Bạn có chắc chắn muốn xóa ? ")){
                window.location.assign('removeBoards/'+this.board._id);
              }
            }
        },
        

    }
</script>