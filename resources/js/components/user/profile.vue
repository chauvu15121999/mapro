<template>
<div class="container">
    <div class="row"> 
      <div class="col-sm-6 mx-auto">
          <img style="width: 100%; height: 150px;"  src="https://a.trellocdn.com/prgb/dist/images/member-home/taco-privacy.ced1e262c59e0225e3aa.svg" alt="">
          <h2>Manage your personal information</h2>
          <div class="alert alert-success">
              <p>This is an Atlassian account. Edit your personal information and visibility settings through your Atlassian profile.</p> 

              <p>To learn more, view our Terms of Service or Privacy Policy.</p>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 mx-auto grid-margin stretch-card">
        <div class="row">
          <div  v-if="errors_exist" class="alert alert-danger col-sm-12" role="alert">
                  Whoops! Something didn't work. 
                  <ul>
                    <div v-for="(error,index) in errors" :key="index">
                      <li>{{ error[0] }}</li>
                    </div>
                  </ul>
          </div>
          <div class=" col-sm-12">
                  <div class="card-body">
                    <h4 class="card-title">Thông tin tài khoản</h4>
                      <div class="form-group">
                        <label for="exampleInputUsername1">Name</label>
                        <input v-model="user.user_name" value="" name="user_name" type="text" class="form-control" id="exampleInputUsername1" placeholder="Username">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input v-model="user.email" type="email" value="" disabled="" class="form-control" id="exampleInputEmail1" placeholder="Email">
                      </div>
                        <div class="form-group">
                        <label for="exampleInputUsername1">Tiểu sử</label>
                        <textarea style="width: 100%;" name="description" id="" cols="50" rows="10"></textarea>
                      </div>
                      <button  v-on:click="save()" class="btn btn-gradient-primary col-12 mr-2">save</button>
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
    export default{
      props: ['user'],
      data(){
        return {
            errors_exist: false,
        }
      },
      methods: {
            save(){
                axios.post('getInfoUser/'+this.user._id,{
                    name: this.user.user_name
                })
               .then(response => {
                   // this.user = response.data
                   $emit('update' , response.data)
               })
               .catch(error => {
                    if(error.response.status === 422) {
                         this.errors_exist = true;
                        this.errors = error.response.data.errors || {};
                      }
               })
            }
      }
    }
</script>