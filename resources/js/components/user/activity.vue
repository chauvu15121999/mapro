<template>
    <div class="container">
    <div class="row"> 
      <div class="col-md-6 mx-auto">
          <img src="https://a.trellocdn.com/prgb/dist/images/member-home/taco-privacy.ced1e262c59e0225e3aa.svg" alt="">
          <h2>Manage your personal information</h2>
          <div class="alert alert-success">
              <p>This is an Atlassian account. Edit your personal information and visibility settings through your Atlassian profile.</p> 

              <p>To learn more, view our Terms of Service or Privacy Policy.</p>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mx-auto grid-margin stretch-card">
       
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Thông tin hoạt động</h4>
                    <form action="" method="post" class="forms-sample">
                      <div class="form-group">
                        <label for="exampleInputUsername1">Username</label>
                        <input value="" name="user_name" type="text" class="form-control" id="exampleInputUsername1" placeholder="Username">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" value="" disabled="" class="form-control" id="exampleInputEmail1" placeholder="Email">
                      </div>
                        <div class="form-group">
                        <label for="exampleInputUsername1">Tiểu sử</label>
                        <textarea style="width: 100%;" name="description" id="" cols="50" rows="10"></textarea>
                      </div>
                      <button type="submit" class="btn btn-gradient-primary col-12 mr-2">save</button>
                    </form>
                  </div>
                </div>
      </div>
    </div>         
  </div>  
</template>

<style>
</style>
<script>
// import nav from './nav/nav'
    export default{
    }
</script>