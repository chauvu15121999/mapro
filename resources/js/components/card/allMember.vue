<template>
    <div :style="stylist"  class="Allmember col-lg-3 col-sm-6 col-10">
      <div class="row mt-2" style="border-bottom: 1px solid #716A6A;">
        <p class="col-lg-11 text-center" style="font-size: 15px;  ">Members</p>
        <button @click="$emit('close')" class="close col-lg-1 col-sm-1 col-1"><i class="mdi mdi-close"></i></button>       
      </div>
      <div class="row mt-2">
        <div class="col-lg-12">
          <div class="form-group">
            <input v-model="inputSearchMembers" type="text" class="form-control " id="exampleInputUsername1" placeholder="Search Members">
          </div>
        </div>
        <div class="col-lg-12">
          <p style="font-size: 15px;" >BOARD MEMBERS</p>
        </div>
      </div>
      <div class="row">
        <div v-for="member in filteredList" 
        v-on:click="handleCheckMember(member)" 
        class="col-lg-12 mb-2 select-Member">
          <img :src="member.avatar.encoded" class="image_member rounded-circle " alt="">
          <span class="ml-2">{{member.user_name}}</span>
          <i v-for="check in memberCards" v-if="check.user_email == member.user_email" class="mdi mdi-check mr-auto float-right"></i>
        </div>
      </div>       
    </div>
</template>
<style>
.Allmember {
  background-color: white;
  position: absolute;
  z-index: 1000;
  height: auto;
  max-width: 350px;
}
.form-control:focus {
  border: 2px solid #144AC6;
}
.select-Member{
  cursor: pointer;
}
.select-Member:hover{
  background: #EEEAEA;
}
</style>
<script>
    export default{
    	props: ['stylist','memberBoard','memberCards','card'],
      components: {
          
      },
    	data(){
    		return {
          inputSearchMembers: '',
    		}
    	},
    	created(){
        this.check();
    	}, 
      computed: {
        filteredList() {
          return this.memberBoard.filter(post => {
            return post.user_name.toLowerCase().includes(this.inputSearchMembers.toLowerCase())
          })
        }
      },
    	methods: {
         check(){
          for(var i = 0 ; i < this.memberBoard.length ; i++){
            if(this.memberBoard[i]['user_name'] == ''){
                let name = this.memberBoard[i]['user_email'].indexOf('@');
                this.memberBoard[i]['user_name'] = this.memberBoard[i]['user_email'].slice(0,name);
            }
          }
        },
      handleCheckMember(member){
        axios.post('addMemberToCard/'+this.card._id,{
          members : member,
        }).then(response =>{
            this.$emit('handleCheckMember');
        })
      }
  }

}
</script>
