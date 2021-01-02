<template>
    <div :style="stylist" class="member col-lg-3 col-sm-6 col-10">
        <div class="row  mt-2 pb-2" style="border-bottom: 1px solid #DEDCDC">     
            <div v-if="show == 0" class="col-sm-12">
                <img :src="member.avatar.encoded" class="img_member rounded-circle ml-2" />
                <span  class="col-lg-6 col-sm-6 col-6 text-center">{{member.user_name}}</span>
              <button   @click="$emit('close')" class="close col-lg-2 col-sm-2 col-2 "><i class="mdi mdi-close"></i></button>            
            </div>  
        </div>
        <div class="row mt-2 select_info_member" v-on:click="handleRemoveMember()">
              <p>Remove from card</p>
        </div>     	         
    </div>
</template>
<style>
.member{
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
    	props: ['stylist','Members','card'],
      components: {
          
      },
    	data(){
    		return {
            show: 0,
            member : this.Members,
    		}
    	},
    	created(){
    	},
      computed: {
      },

    	methods: {
        handleRemoveMember(){
        axios.post('addMemberToCard/'+this.card._id,{
          members : this.member,
        }).then(response =>{
            this.$emit('close');
            this.$emit('handleCheckMember');
        })
    	}
    }
}
</script>
