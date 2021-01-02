<template>
  <div  class="row mt-5">
    <div class="top col-sm-12 h-auto mt-auto jumbotron flex-row flex-nowrap">
      <div class="row">
        <div class="col-sm-12 d-flex  justify-content-center mt-3">
          <div class="col-sm-5">
            <img class="img_team rounded-circle" v-bind:src="Info.avatar.encoded"  alt="">
          </div>
          <div class="col-sm-7" >
            <h2 class="" >{{Info.user_name}}</h2>
            <p>{{Info.email}}</p>
          </div>             
        </div>
      </div>
     <div class="row mt-5">
        <div class="col-sm-12 d-flex justify-content-center mb-n5" >
          <div v-bind:class="{active: isProfile}" v-on:click="showProfile"  class="action col-sm-1  ml-sm-2">
            <p  class="ml-2 mt-3  h-25 link">Profile</p>
          </div>
          <div v-bind:class="{active: isActivity}" v-on:click="showActivity"  class="action col-sm-1  ml-sm-2">
            <p class="ml-2 mt-3  h-25 link">Activity</p>
          </div>                              
        </div>
      </div>     
    </div>
	<div class=" bot col-sm-12 mt-auto  flex-row flex-nowrap">
      <profile 
      	v-on:update="update()"
      	:user="Info" 
      	v-if="isProfile">
      </profile>
      <activity v-if="isActivity">>
      </activity> 
    </div>   
  </div>
</template>
<style>
.top{
  background-color: #E9E8F9;
  width: 100%;
  height: auto;
}

#test{
  margin: 0 auto;
  margin-top: 150px;
}
/*.status{
  margin-left: 10px;
  margin-top: 10px;
}*/
.img_team{
  float: right;
  width: 100px;
  height: 100px;
}
.action{
  background-color: #E5D7F7;
/*  margin-left: 10px;*/
  margin-bottom: -20px;
  height: 50px;
  margin-top: 15px;
}
@media screen and (max-width: 850px) {
  .action {
    margin-bottom: -10px;
    max-width: 100%;
  }
}
.action:hover {
  background-color: white;
  cursor: pointer;
}
.action p{
  color: black;
  font-weight: 700;
  font-size: 15px;
}
.active{
  background-color: white;
}
</style>

<script>
    import profile  from '../user/profile.vue';
    import activity  from '../user/activity.vue'; // vue addteam
    export default {
        props: ['user'],
        components: {
        	profile,
        	activity
        },
        data() {
              return{
                 Info : {
                    avatar: {
                        encoded: '',
                    }
                 },
                 isProfile: true,
                 isActivity: false,
              }
        },
        created() {
         	this.getUser();
        },
        updata(){
          this.getUser();
        },
        methods: {
          // hàm lấy data 
	          getUser(){
                axios.get('getInfoUser/'+this.user._id)
               .then(response => {
                   this.Info = response.data
               })
               .catch(error => {
                    if(error.response.status === 422) {
                         this.errors_exist = true;
                        this.errors = error.response.data.errors || {};
                      }
               })
            },
            update(data){
            	this.Info = data;
            },
            showProfile(){
            	this.isProfile = true;
            	this.isActivity = false;
            },
            showActivity(){
            	this.isProfile = false;
            	this.isActivity = true;
            }
          }
        
    }
</script>