<template>
    <div :style="stylist" class="menuList col-lg-3 col-sm-6 col-10">
        <div class="row   mb-2" style="border-bottom: 1px solid #DEDCDC">
              <p class="col-lg-10 col-sm-10 col-10 text-center mt-3">List Actions</p>
              <button   @click="$emit('close')" class="close col-lg-2 col-sm-2 col-2 "><i class="mdi mdi-close"></i></button>
        </div>
        <div class="row mt-1" style="border-bottom: 1px solid #DEDCDC">
          <div v-on:click="addCart(0)" class="col-lg-12 select_list_menu" >
             <p class="mt-1">Add Card...</p>
          </div>
          <div v-on:click="watch()" class="col-lg-12 select_list_menu">
             <p class="mt-1">Watch</p>
          </div>
        </div>
        <div  class="row mt-1" >
          <div v-on:click="storage()" class="col-lg-12 select_list_menu">
             <p class="mt-1">storage this list</p>
          </div>
        </div>
    </div>
</template>
<style>
.menuList{
  background-color: white;
  position: absolute;
  z-index: 1000;
  height: auto;
  max-width: 300px;
}
.select_list_menu{
  cursor: pointer;
}
.select_list_menu:hover {
  background: #D2D2D7;
}
</style>
<script>
    export default{
      props: ['stylist','list','user'],
      components: {
      },
      data(){
        return{

        }
      },
      updated(){
      },
      methods: {
        storage(){
          if(this.user._id === this.list.by_user){
        if(confirm("Bạn có muốn lưu trữ thẻ này ?")){
          axios.post('api/stogareList/'+this.list._id , 
          {user: this.user})
          .then((response) => {
            console.log(response.data);
            this.$emit('removeList');
          });
            }
          }else{
            alert("chỉ có người tạo mới có thể list thẻ này !");
          }
        },
        addCart(position){
          var data = {
            position : position ,
            id : this.list._id,
          };
          this.$emit('addCard',data);
        }
      }
    }
</script>
