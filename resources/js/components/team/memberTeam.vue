<template>
<div class="container">
     <div class="row"> 
      <div class="col-sm-12 ml-5 border">
        <div class="row">
            <div class="col-sm-7">
                <div class="row">
                    <h2>Invite your Team</h2><br>
                  <div class="alert alert-success">
                      <p>Mapro makes teamwork your best work. Invite your new team members to get going!</p> 
                  </div>
                </div>
                <div class="row">
                    <div v-if="errors_exist" class="alert alert-danger" role="alert">
                          Whoops! Something didn't work. 
                          <ul>
                            <div v-for="error in errors">
                              <li>{{ error[0] }}</li>
                            </div>
                          </ul>
                        </div> 
                    <div  class="form-group col-sm-12">
                        <label for="exampleInputUsername1">Team Members: </label>
                        <input v-model="emailMember" name="member" type="email" class="form-control" id="exampleInputUsername1" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <button v-bind:disabled="isButtonDisabled ? emailMember == '' : false" v-on:click="addMember()" class="col-sm-12 btn btn-outline-success btn-fw">Invite</button>
                    </div>
                </div> 
            </div>
            <div class="col-sm-4">
                <img style="width: 100%; height: 100%;"  src="https://a.trellocdn.com/prgb/dist/images/organization/empty-board.286f8fc83e01c93ed27e.svg" alt="">
            </div>
        </div>
        <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Thành vên nhóm</h4>
                    <table class="table">
                      <tbody>
                        <tr v-for="member in Members" :key="member.user_email">
                          <td>{{member.user_email}}</td>
                          <td v-if="member.role === 1"><label class="badge badge-success">Quản trị viên</label></td>
                           <td v-else><label class="badge badge-secondary">Thành viên</label></td>
                           <td><button v-if="isRemoveDisabled ? member.user_email == user.email : false"  v-on:click="DeleteMember(member.user_email)" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-close"></i>Leave</button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        </div>       
      </div>
    </div>
</div>
</template>
<style>

</style>
<script>
    export default {
        props: ['team',"user"],
        components: {
           
        },
        data() {
            return{
                emailMember: '',
                isButtonDisabled: true,
                errors_exist: false,
                Members: [],
                isRemoveDisabled: true,       
            }
        },
        created() {
            this.getMember();
        },
        methods: {
           addMember(){
                 axios.post('addMemberTeam/'+this.team._id,{
                        members: this.emailMember,
                 })
                .then(response => {
                    this.Members = this.getMember();
                })
                .catch(error => {
                    if(error.response.status === 422) {
                         this.errors_exist = true;
                        this.errors = error.response.data.errors || {};
                      }
                })
           },
        getMember(){
            axios.get('getMemberTeam/'+this.team._id,{
                 })
                .then(response => {
                    this.Members = response.data;
                })
                .catch(error => {
                    if(error.response.status === 422) {
                         this.errors_exist = true;
                        this.errors = error.response.data.errors || {};
                      }
                })
            },
        DeleteMember(email){
                this.axios.get("deleteMember/"+this.team._id+"/"+email,{     
                })
                .then(response => {
                  this.Members.splice(this.Members.indexOf(email), 1);
                }).catch(error => {
                    if(error.response.status === 422) {
                        this.errors_exist = true;
                        this.errors = error.response.data.errors || {};
                      }
                });
                window.location = 'home/'+this.user.user_name+'dashboard.html';
            }
        }

        
    }
</script>