<style  type="text/css" >
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  max-width: 100%;
  height: auto;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  margin-left: 20px;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
.error span{
  color: red;
}  
</style>
<template>
 <!-- Modal -->
<div class="modal-mask ">
    <div class="modal-wrapper">
      <div class="row">
         <div class="modal-container col-md-9">
          <div class="error alert-danger" v-if="errors.length">
             <span v-for="(err, index) in errors" :key="index">
                 {{ err }}
             </span><br> 
          </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="modal-header">
                  <slot name="header">
                   <h3>Hãy xây dựng một đội</h3>
                  </slot>
                </div>
                 <p>Tăng năng suất của bạn bằng cách giúp mọi người truy cập bảng ở một vị trí dễ dàng hơn</p>
                  <form action="addteam" method="post">
                    <div class="card">
                    <div class="card-body">
                        <!-- @csrf -->
                        <div class="form-group">
                          <label  for="exampleInputUsername1">Tên nhóm</label>
                          <input v-model="team.team_name" name="name" type="text" class="form-control" id="exampleInputUsername1"placeholder="Username">
                        </div>

                        <input style="display: none;" name="id_user" value="user_id" type="text">
                        <div class="form-group">
                          <label for="exampleSelectGender">Loại nhóm</label>
                          <select v-model="team.type_team"   name="typeteam" class="form-control" id="exampleSelectGender"> 
                              <option 
                               v-for="type in teamtype" v-bind:value="type._id">
                                {{type.name}}
                              </option>      
                          </select>
                        </div>
                        <div class="form-group">
                        <label for="exampleTextarea1">Mô tả nhóm</label>
                        <textarea v-model="team.desciption" name="desciption" class="form-control" id="exampleTextarea1" rows="4"></textarea>
                        </div>
                        <input type="hidden" name="_token" :value="csrf">
                        <!-- thêm dữ liệu -->
                        <button type="submit" v-on:click="createTeam()" class="btn btn-gradient-primary col-12 mr-2">Thêm</button>               
                    </div>
                  </div>
                  </form>
                  </div>
              
              <div style="   
                      background-repeat: no-repeat; 
                      background-size: 100%; 
                      background-image: url(https://a.trellocdn.com/prgb/dist/images/organization/empty-board.286f8fc83e01c93ed27e.svg);
                      " 
              class="col-sm-5 float-md-left">
              <!-- tắt popup -->
                    <button @click="$emit('close')" style="float: right; padding: 0; border: none; background: none; " type=""><i class="mdi mdi-close"></i></button>
              </div>
          </div>    
            </div>  
        </div>
      </div>
       
    </div>
</div>
</template>
<script>
    export default {
        props: ['teamtype'],
        components: {
            
        },
        data() {
              return{
                team: {
                  team_name: '',
                  type_team: '',
                  desciption: '',
                },
                errors: [],
              }
        },
        methods: {
        },
        computed: {
            csrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
              },
        }
        
    }
</script>


