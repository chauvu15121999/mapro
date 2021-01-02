@extends('backend.layout.index')
@section('content')
<style>
    .nav-headerpf li{
        width: 200px;
        height: 50px;
        background-color: #D4CDCD;
        text-align: center;
    }
    .nav-headerpf li:hover {
      background-color: white;
    }
    .nav-headerpf li a {
       text-decoration: none;
       color: black;
       font-size: 18px;
       font-weight: 700;
    }
    li .active {
      background-color: white;
    }
</style>      
        <!-- partial -->
<div style="max-width: 100%; max-height: 100%;"   class="container ">
<div id="appprofile">
  <?php $url =  $_SERVER['REQUEST_URI']; $user_name  = Auth::user()->user_name;  ?>
  <div class="jumbotron flex-row flex-nowrap" style="min-height:100px; ">
    <h1 align="center">{{Auth::user()->user_name}}</h1>
      <div class="nav-headerpf"  >
        <ul class=" nav nav-tabs mb-n5 w-100 justify-content-center">
          <li class="mr-1 nav-item" @if($url == "/Trello/profile/${user_name}") style="background-color: red;" @endif >
            <a  class="nav-link" href="profile/{{Auth::user()->user_name}}">Hồ sơ</a>
          </li>
          <li class="mr-1 nav-item " @if($url == "/Trello/activity/${user_name}") style="background-color: white;" @endif >
            <a class="nav-link" href="activity/{{Auth::user()->user_name}}">Activity</a>
          </li>
        </ul>
        </div>
  </div> 
  <div class="row">
     @yield('profile')
  </div>
</div>
</div>

</body>
</html>
@endsection 
{{-- @section('script')
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script>
var VueInstance = new Vue({
    el: '#appprofile', 
    data: {
        isActive: false,
    },
    computed: {
        hanldeActive(){
            this.isActive = true;
        }
    }
});
</script>
  
@endsection     --}}     