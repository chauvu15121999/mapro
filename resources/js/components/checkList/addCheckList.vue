<template>
  <div :style="stylist"  class="addCheckList col-lg-3 col-sm-6 col-10">
    <div class="row mt-2" style="border-bottom: 1px solid #716A6A;">
        <p class="col-lg-11 text-center mt-2" style="font-size: 15px; ">Add Check List</p>
        <button @click="$emit('close')" class="close col-lg-1 col-sm-1 col-1"><i class="mdi mdi-close"></i></button>       
    </div> 
    <div class="row mt-2" >
        <span class="ml-3 mb-2" style="font-size: 15px; font-weight: blod;">Title</span>
        <input v-model="inputAddCheckList" type="text" class="form-control col-lg-11 ml-3">
        <div class="col-lg-12 mt-3">
          <button v-on:click="add()" class="btn btn-primary btn-sm">Add</button>
        </div>
    </div>   
  </div>
</template>
<style>
.addCheckList {
  background-color: white;
  position: absolute;
  z-index: 9999;
  height: auto;
  max-width: 350px;
}
.form-control{
  border: 1px solid #9F9494;
}
.form-control:focus {
  border: 3px solid #144AC6;
}
/*.select-Member{
  cursor: pointer;
}
.select-Member:hover{
  background: #EEEAEA;
}*/
</style>
<script>
    export default{
    	props: ['stylist','card','user'],
      components: {
          
      },
    	data(){
    		return {
          inputAddCheckList: 'Checklist',
    		}
    	},
    	created(){

    	}, 
      computed: {

      },
    	methods: {
        add(){
          axios.post('addCheckList/'+this.card._id,{
            checkList_name: this.inputAddCheckList,
            user: this.user,
          }).then(response => {
              this.$emit('close');
              this.$emit('hanldeAddCheckList')
          })
        }
      }

}
</script>
