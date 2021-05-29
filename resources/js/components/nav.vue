<template>
    <div class="nav-main">      
    <nav :class="{bg_nav: bgNav}" class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row broad">
        <div :class="{hidden_nav : bgNav}" class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">      
          <router-link :to="{ name: 'home'}"  class="navbar-brand brand-logo">
            <img src="public/backend/assets/images/logo.svg" alt="logo" />
          </router-link>
          <a class="navbar-brand brand-logo-mini"><img src="public/backend/assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button :class="{hidden_nav : bgNav}" class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
              </div>
            </form>
          </div>
          <ul class="navbar-nav navbar-nav-right">
            <li  id="testShow" class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle " id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                    <img :src="user.avatar.encoded" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black">{{user.user_name}}</p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown " aria-labelledby="profileDropdown">
                <router-link class="dropdown-item" :to="{name: 'profile'}">
                  <i class="mdi mdi-account mr-2 text-primary"></i>Hồ sơ</router-link>
                <div class="dropdown-divider"></div>
                <router-link class="dropdown-item" :to="{name: 'activity'}">
                  <i class="mdi mdi-cached mr-2 text-success"></i> Hoạt động </router-link>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.html">
                  <i class="mdi mdi-logout mr-2 text-primary"></i> Đăng xuất </a>
              </div>
            </li>
            <li class="nav-item d-none d-lg-block full-screen-link">
              <a class="nav-link">
                <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a v-on:click="getNofications()" class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count-symbol bg-danger"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <h6 class="p-3 mb-0">Notifications</h6>
                <template v-for="(nofication , index) in orderByNofications"> 
                <div :key="'line'+index" class="dropdown-divider"></div>
                <router-link :to="{ name: 'board', params: { id_board: nofication.id_board } }"  :key="'content' + index" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img :src="nofication.user_send.avatar.encoded" class="img_member rounded-circle ml-2" />
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">{{nofication.user_send.user_name}}</h6>
                    <p class="text-gray ellipsis mb-0"> {{nofication.content}} </p>
                    <time-ago :refresh="60" :datetime="nofication.time" locale="en" tooltip="right">ago </time-ago>    
                  </div>
                </router-link>
                </template>
                 <h6 @click="seeAllNofication" class="p-3 mb-0 text-center">See all notifications</h6>
              </div>
            </li>
            <li class="nav-item nav-logout d-none d-lg-block">
              <a class="nav-link" href="#">
                <i class="mdi mdi-power"></i>
              </a>
            </li>
            <li class="nav-item nav-settings d-none d-lg-block">
              <a class="nav-link" href="#">
                <i class="mdi mdi-format-line-spacing"></i>
              </a>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
    </div>
</template>

<script>
import TimeAgo from "vue2-timeago";
export default {
    props: ["user"],
    data(){
      return {
        bgNav: false,
        nofications: null
      }
      
    },
    computed: {
        curUrl(){
            return this.$route.name;
        },
        orderByNofications: function () {
          let  arr =  _.reverse(this.nofications); 
          return _.slice(arr, 0 , 5 )
        }
    },
    watch: {
      curUrl(){
        this.changeBgNav()
      }
    },
    mounted(){
      this.changeBgNav();
      this.getNofications();
    },
    methods:{
      changeBgNav(){
        if(this.curUrl == 'board')
          this.bgNav = true;
        else 
          this.bgNav = false
      },
      getNofications(){
        axios.get('api/getNofications/'+this.user._id)
        .then((Response) => {
          this.nofications = Response.data
        })
      },
      seeAllNofication(){
        this.$router.push({ name: 'activity'});
      }
    },
    components: {
      TimeAgo
    }
}
</script>

<style>
  .bg_nav {
    background: rgba(0, 0, 0, 0.5);
  }
  .hidden_nav {
    visibility: hidden;
  }
  .dropdown .dropdown-menu {
    min-width: 300px;
  }
  .dropdown .dropdown-menu .dropdown-item:hover {
    color: black !important;
  }
  .navbar .navbar-menu-wrapper .navbar-nav 
  .nav-item.dropdown .dropdown-menu.navbar-dropdown .dropdown-item .ellipsi:hover {
    word-wrap: break-word !important;
  }
</style>