<template>
    <div :style="stylist" class="inviteMember col-lg-3 col-sm-6 col-10">
        	<div class="row mb-2" style="border-bottom: 1px solid #DEDCDC">
              <h3 class="col-lg-10 col-sm-10 col-10 text-center">Invite To Board</h3>
              <button   @click="$emit('close')" class="close col-lg-2 col-sm-2 col-2 "><i class="mdi mdi-close"></i></button>
            </div>          
            <img style="height: 150px; border: none; " class=" col-lg-12 col-sm-12 col-12" src="https://a.trellocdn.com/prgb/dist/images/workspaces-preamble/workspaces-preamble-prompt-board-invite-image.e96bd5132d7721dd55b1.png" alt="" />
            <div v-if="errors_exist" class="alert alert-danger" role="alert">
                  Whoops! Something didn't work. 
                  <ul>
                    <div v-for="error in errors">
                      <li>{{ error[0] }}</li>
                    </div>
                     <div v-for="err in error">
                      <li>{{ err }}</li>
                    </div>
                  </ul>
            </div>
            <div v-if="error_exist" class="alert alert-danger" role="alert">
                  Whoops! Something didn't work. 
                  <ul>
                     <div v-for="err in error">
                      <li>{{ err }}</li>
                    </div>
                  </ul>
            </div>
            <label class="mt-3" for="">Email member</label> 
            <input   v-model="inputMember"  class="col-sm-12  mb-3 border-success">
            <VueLoadingButton
            aria-label="Post message"
            :loading="isLoading"
            v-bind:disabled="isButtonDisabled"
            :style="isButtonDisabled ? { 'cursor': 'not-allowed' } : { 'cursor': 'pointer' }"
            :styled="isStyled"   
            @click.native="addMember()" 
            class="btn btn-gradient-success ml-3 col-sm-10 mb-2">Invite</VueLoadingButton>
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
</style>
<script>
import VueLoadingButton from "vue-loading-button";
    export default{
    	props: ['stylist','boards'],
      components: {
        VueLoadingButton
      },
    	data(){
    		return {
    			inputMember : '',
          error_exist: false,
    			errors_exist: false,
          errors: [],
          error: [],
          isButtonDisabled: true,
          isLoading: false,
          isStyled: false,
    		}
    	},
    	updated(){
    		this.changeInvite();
    	},
    	methods: {
    	changeInvite(){
              if(this.inputMember != '')
              {
                this.isButtonDisabled = false;
              }else{
                this.isButtonDisabled = true;
              }
      },
    	addMember(){
              this.isLoading = true;
	            axios.post('addMemberBoard/'+this.boards._id,{
	              members : this.inputMember,
	            }).then(response => {
                      this.$emit("addMember");
	            }).catch(error => {
	                 if(error.response.status === 422) {
                      alert("email nhập sai");
                      this.isLoading = false;
                      console.clear();
	                 }else if(error.response.status === 500){
                      alert("email đã tồn tại");
                      this.isLoading = false;
                      console.clear();
                   }else{
                    this.error_exist = false;
                   }
	            });
          },
    	}
    }
</script>
