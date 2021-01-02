<template>
  <div  class="row mt-5">
    <div class="top col-sm-12 mt-auto jumbotron flex-row flex-nowrap">
      <div class="row">
        <div class="col-sm-12 d-flex  justify-content-center mt-3">
          <div class="col-sm-5">
            <img class="img_team" src="public/backend/assets/images/team/teamwork.png" alt="">
          </div>
          <div class="col-sm-7" v-if="isEdit === false">
            <h2 class="" >{{teams.team_name}}</h2>
            <span class="status" v-if="team.status === 1" ><span class="mdi mdi-lock-outline"></span>privite</span>
            <span class="status" v-else ><span class="mdi mdi-earth"></span>public</span><br>
            <button v-on:click="isEdit = true" type="button" class="btn btn-gradient-info btn-fw mt-2"><i class="mdi mdi-pencil mr-2"></i>Edit team details</button>
          </div>
          <div class="col-sm-7 " v-if="isEdit">
              <div v-if="errors_exist" class="alert alert-danger" role="alert">
                  Whoops! Something didn't work. 
                  <ul>
                    <div v-for="error in errors">
                      <li>{{ error[0] }}</li>
                    </div>
                  </ul>
                </div>           
                <div class="form-group col-sm-5">
                  <label for="exampleInputUsername1">Name</label>
                  <input v-model="teams.team_name" name="name" type="text" class="form-control" />
                </div>
                <div class="form-group col-sm-5">
                  <label for="exampleSelectGender">Loại nhóm</label>
                  <select name="typeteam" class="form-control" v-model="teams.type_team">
                      <option>Chọn loại team</option>
                      <option 
                         v-for="(option,key) in teamtype"
                         :key ="key"
                         :value="option._id"
                         v-bind:selected = "option._id == teams.type_team"
                        >{{ option.name }}</option>
                   </select>
                </div>
                 <div class="form-group col-sm-5">
                        <label for="exampleTextarea1">Mô tả nhóm</label>
                        <textarea v-model="teams.desciption" name="desciption" class="form-control" id="exampleTextarea1" rows="4">{{teams.desciption}}</textarea>
                </div>
              <button v-on:click="save()" type="button" class="btn btn-gradient-success btn-rounded btn-sm ml-5">save</button>
              <button v-on:click="cancel()" type="button" class="btn btn-gradient-danger btn-rounded btn-sm">cancel</button>
          </div>                 
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 d-flex justify-content-center mb-n5" >
          <div v-bind:class="{active: isBroads}" v-on:click="showBroads"  class="action col-sm-1 mt-sm-5 ml-sm-2">
            <p  class="ml-2 mt-3 link">Broads</p>
          </div>
          <div v-bind:class="{active: isMembers}" v-on:click="showMembers"  class="action col-sm-1 mt-sm-5 ml-sm-2">
            <p class="ml-2 mt-3 link">Members</p>
          </div>
          <div v-bind:class="{active: isSetting}" v-on:click="showSettings" class="action col-sm-1 mt-sm-5 ml-sm-2">
            <p class="ml-2 mt-3 link">Settings</p>
          </div>                                
        </div>
      </div>       
    </div>
    <div class=" bot col-sm-12 mt-auto  flex-row flex-nowrap">
      <broads v-if="isBroads">
      </broads>
      <member :user="user" v-bind:team="team" v-if="isMembers">>
      </member> 
      <setting v-if="isSetting">
      </setting>
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
.status{
  margin-left: 10px;
  margin-top: 10px;
}
.img_team{
  float: right;
  width: 100px;
  height: 100px;
}
.action{
  background-color: #E5D7F7;
/*  margin-left: 10px;*/
  margin-bottom: -20px;
}
@media screen and (max-width: 900px) {
  .action {
    margin-bottom: 0;
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
    import member  from '../team/memberTeam.vue';
    import broads  from '../team/broadTeam.vue';
    import setting  from '../team/settingTeam.vue'; // vue addteam
    export default {
        props: ["team","teamtype","user"],
        components: {
            member,
            broads,
            setting
        },
        data() {
              return{
                  errors_exist: false,
                  isEdit: false,
                  isBroads: false,
                  isMembers: false,
                  isSetting: false,
                  teams: {},
                  errors: [],
              }
        },
        created() {
          this.getTeam();
        },
        methods: {
          // hàm lấy data 
             getTeam() {
                axios.get('getTeam/'+this.team._id)
                .then(response => {
                    this.teams = response.data
                    // this.list_products.forEach(item => {
                    //     Vue.set(item, 'isEdit', false)
                    // })
                })
                .catch(error => {
                    this.errors.push = error.response.data.errors
                })
            },
            cancel(){
              this.teams = this.getTeam();
              this.isEdit = false;
            },
            // hàm cập nhật data
            save(){
                axios.put('editTeam/'+this.team._id,{
                    name: this.teams.team_name, 
                    typeteam: this.teams.type_team, 
                    desciption: this.teams.desciption,
                })
                .then(response => {
                     this.teams = response.data;
                     this.isEdit = false;
                })
                .catch(error => {
                      if(error.response.status === 422) {
                         this.errors_exist = true;
                        this.errors = error.response.data.errors || {};
                      }
                });
                
           },
           showBroads(){
              this.isBroads = true;
              this.isMembers = false;
              this.isSetting = false;
           } ,
           showMembers(){
              this.isBroads = false;
              this.isMembers = true;
              this.isSetting = false;
           },
           showSettings(){
              this.isBroads = false;
              this.isMembers = false;
              this.isSetting = true;
           }
        }
        
    }
</script>