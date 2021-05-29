<template>
  <div  class="row mt-5">
    <div class="top pb-4 col-sm-12 h-auto mt-auto jumbotron flex-row flex-nowrap">
      <div class="row pb-3">
        <div class="col-sm-12 d-flex  justify-content-center mt-3">
          <div class="col-sm-5">
            <img class="img_team rounded-circle" v-bind:src="InfoUser.avarta"  alt="">
          </div>
          <div class="col-sm-7" >
            <h2 class="" >{{InfoUser.name}}</h2>
            <p>{{InfoUser.email}}</p>
          </div>             
        </div>
      </div>
     <div class="row">
        <div class="col-sm-12 d-flex justify-content-center " >
          <div :class="{active: $route.path.includes('profile')}" v-on:click="showProfile" 
          class="action col-xl-1 col-md-2 col-4 ml-sm-2 mt-0">
            <p  class="ml-lg-3 ml-4 mt-3  link">Profile</p>
          </div>
          <div :class="{active: $route.path.includes('activity')}" v-on:click="showActivity" 
          class="action col-xl-1  col-md-2 col-4 ml-sm-2 mt-0">
            <p class="ml-lg-3 ml-4 mt-3  h-25 link">Activity</p>
          </div>  
          <div :class="{active: $route.path.includes('board')}"  v-on:click="showBoards" 
          class="action col-xl-1  col-md-2 col-4  ml-sm-2 mt-0">
            <p class="ml-lg-3 ml-4 mt-3  h-25 link">Boards</p>
          </div>                             
        </div>
      </div>     
    </div>
	<div class=" bot col-sm-12 mt-auto  flex-row flex-nowrap">
      <router-view :user='user'></router-view>
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
    import _ from 'lodash';
    export default {
        props: ['user'],
        components: {
        	profile,
        	activity
        },
        data() {
              return{
              }
        },
        computed: {
          InfoUser() {
            return {
                'name': this.user.user_name,
                'email': this.user.email,
                'avarta': this.user.avatar.encoded
            }
          },
        },
        methods: {
            update(data){
            	this.Info = data;
            },
            showProfile(){
              this.$router.push({ name: 'profile'});
            },
            showActivity(){
             this.$router.push({ name: 'activity'});
            },
            showBoards(){
             this.$router.push({ name: 'boardOfUSer'});
            }
          }
        
    }
</script>