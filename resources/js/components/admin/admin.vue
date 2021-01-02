<template>
  <div class="container-fluid page-body-wrapper">

    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <!-- nav -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image">
                  <img src="public/backend/assets/images/faces/face1.jpg" alt="profile">
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2">David Grey. H</span>
                  <span class="text-secondary text-small">Project Manager</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.html">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Basic UI Elements</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
                  <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel mt-n1">
          <div class="content-wrapper">
<div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Bordered table</h4>
                    <p class="card-description"> Add class <code>.table-bordered</code>
                    </p>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th> # </th>
                          <th> First name </th>                         
                          <th> Amount </th>
                          <th> Deadline </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="u in user">
                          <td> 1 </td>
                          <td> {{u.user_name}} </td>
                          <td> $ 77.99 </td>
                          <td> May 15, 2015 </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
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
.broad-home{
  /*background-color: yellow;*/
  background-size: cover;
  height: 100px;
  color: white;
  cursor: pointer;
}
.broad-home p{
  font-size: 15px;
}
.broad-home:hover {
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

    export default {
        props: ['user'],
        components: {

        },
        data() {
              return{

              }
        },
        created() {
           
        },
        methods: {
            //Thêm nhóm
            
          },
            // lấy danh sách background          
    }
</script>