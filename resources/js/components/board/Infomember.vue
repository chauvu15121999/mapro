<template>
    <div :style="stylist" class="inviteMember col-lg-3 col-sm-6 col-10">
      <div class="test">
        <div class="row  mt-2 pb-2" style="border-bottom: 1px solid #DEDCDC">     
            <div v-if="show == 0" class="col-sm-12">
                <img :src="Infomember.avatar.encoded" class="img_member rounded-circle ml-2" />
                <span  class="col-lg-6 col-sm-6 col-6 text-center">{{Infomember.user_name}}</span>
              <button   @click="$emit('close')" class="close col-lg-2 col-sm-2 col-2 "><i class="mdi mdi-close"></i></button>            
            </div>
            <!-- title thay đổi quyền -->
            <div v-if="show == 1" class="col-sm-12">
                <i @click="show = 0" class="mdi mdi-chevron-left select_info_member" ></i>
                <span class="col-lg-6 col-sm-6 col-6 text-center">change Permissions 
                </span>
              <button   @click="$emit('close')" class="close col-lg-2 col-sm-2 col-2 "><i class="mdi mdi-close"></i></button>            
            </div>
            <!-- title xóa member -->
              <div v-if="show == 2" class="col-sm-12">
                <i @click="show = 0" class="mdi mdi-chevron-left select_info_member" ></i>
                <span  class="col-lg-6 col-sm-6 col-6 text-center">Remove Member ?</span>
              <button   @click="$emit('close')" class="close col-lg-2 col-sm-2 col-2 "><i class="mdi mdi-close"></i></button>            
            </div> 
        </div>
        <!-- Menu chọn -->
        <div v-if="show == 0" class="row  mt-3 mb-2">
            <div :style="user.role === 0 ? 'opacity: 0.5; cursor: context-menu;' : 'opacity: 1'" 
            v-on:click="checkAdmin()" 
            class="col-sm-12 pt-2 pb-n2 select_info_member">
                <p>Change permistion ... 
                    <span  v-if="Infomember.role == 0">(Normal)</span>
                    <span v-if="Infomember.role == 1">(Admin)</span></p>
            </div>
            <div  v-on:click="show = 2" class="col-sm-12 pt-2 pb-n2  select_info_member">
                <p>Remove from Board...</p>
            </div>
        </div>
        <!--  thay đổi quyền -->
        <div v-if="show == 1" class="row  mt-3 mb-2">
          <!-- Admin -->
            <div 
            v-on:click="changePermistion(Infomember.role,1)" 
            :style="Infomember.role == 1 ? 'opacity: 0.5; cursor: context-menu;' : 'opacity: 1'"  
            class="col-sm-12 pt-2 pb-n2 select_info_member">
                <span style="font-size: 13px; font-weight: 700;" >Admin</span>
                 <i v-if="Infomember.role == 1" class="mdi mdi-check"></i><br>
                <span style="font-size: 13px; font-weight: 300;">Can view and edit cards , remove member, and change setting for the board</span>
            </div>
          <!-- Normal -->
            <div 
            v-on:click="changePermistion(Infomember.role,0)" 
            :style="Infomember.role == 0 ? 'opacity: 0.5; cursor: context-menu;' : 'opacity: 1'" 
            class="col-sm-12 pt-2 pb-n2  select_info_member">
                <span style="font-size: 13px; font-weight: 700;">Normal</span>
                <i v-if="Infomember.role == 0" class="mdi mdi-check"></i><br>
                <span style="font-size: 13px; font-weight: 300;">Can view and edit cards. Can change some board setting</span>
            </div>
        </div>
        <!--  xóa member -->
        <div v-if="show == 2" class="row  mt-3 mb-2">
            <p class="pl-2 pr-2" style="font-size: 13px; font-weight: 300;">The member will be removed from all cards on this board. They will receive a notification</p>
            <button v-on:click="remove()" class="btn btn-danger col-sm-10 mx-auto">remove member</button>
        </div> 
      </div>      	         
    </div>
</template>
<style>
.inviteMember{
  background-color: white;
  position: absolute;
  z-index: 1000;
  height: auto;
  max-width: 300px;
}
.img_member{

  width: 45px; 
  height: 45px;
}
.img_member:hover {
  filter:  brightness(90%);
}
.select_info_member{
  background-color: white;
  cursor: pointer;
}
.select_info_member:hover {
  filter: brightness(90%);
}
</style>
<script>
    export default{
    	props: ['stylist','board','Members','user','AllMembers'],
      components: {
          
      },
    	data(){
    		return {
            show: 0,
    		}
    	},
    	created(){
        this.check();
    	},
      computed: {
          Infomember(){
              return this.Members;
          }
      },

    	methods: {
          check(){
            if(this.Members['user_name'] == ''){
                let name = this.Members['user_email'].indexOf('@');
                this.Members['user_name'] = this.Members['user_email'].slice(0,name);
            }
          },
          checkAdmin(){
            if(this.user.role == 1){
              this.show = 1;
            }
          },
          //-----------------------------------------------------
          changePermistion(role,number){
              if(role != number){ // tra xem đang ở trạng thái quyền nào 
                 if(number == 1){ 
                  // chuyển qua admin
                  axios.post('changePermissions/'+this.board._id,{
                      permission: number,
                      email: this.Infomember.user_email,
                    }).then(response =>{
                      this.$emit("changeInfoMember");
                    })
                 }else{  
                 // chuyển qua normal
                  var sum = 0;
                  // kiểm tra xem đang có bao nhiêu admin trong bảng 
                  // nếu chỉ có 1 thì không đc chuyển qua normal
                  for(var i = 0 ; i < this.AllMembers.length ; i++){         
                        if(this.AllMembers[i].role == 1){
                          sum = sum + 1;
                        }  
                  }
                  if(sum >= 2){
                    axios.post('changePermissions/'+this.board._id,{
                      permission: number,
                      email: this.Infomember.user_email,
                    }).then(response =>{
                       this.$emit("changeInfoMember");
                    })
                  }
                  else{
                    alert("Đang chỉ có 1 admin , cần thêm 1 admin nữa để chuyển qua normal");
                  }
                 } 
              }
          },
          remove(){
                  var sum = 0;
                  // kiểm tra xem đang có bao nhiêu admin trong bảng 
                  // nếu chỉ có 1 thì không đc chuyển qua normal
                  for(var i = 0 ; i < this.AllMembers.length ; i++){         
                        if(this.AllMembers[i].role == 1){
                          sum = sum + 1;
                        }  
                  }
                  if(sum >= 2 || this.Infomember.role ==0 ){
                    axios.post('removeMemberBoard/'+this.board._id,{
                        Infomember: this.Infomember,
                    }).then(response =>{
                          this.$emit("changeInfoMember");
                    })
                  }else{
                    alert("Đang chỉ có 1 admin , cần thêm 1 admin nữa để remove");
                  }
                
          },
    	}
}
</script>
