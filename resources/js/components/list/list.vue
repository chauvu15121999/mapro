<template>
    <div  class="testimonial-group mb-2">
      <div @scroll.passive="handleScroll" class="row  flex-nowrap  mt-1  pt-2">
      <draggable    v-model="lists" draggable=".listCard"  
      :options="{animation: 200, handle:'.handle-list' }" 
      :move="checkMove"
      ghostClass = "listMove"
      class="draggable-list ml-4"> 
       <!--  -->
       <div v-for="list in lists " :key="list._id" :data-id="list._id" class=" pr-5 h-auto listCard">
          <div class="row">
            <div class="col-11 group-lisCard" >
              <div class="row mt-2 mb-1 handle-list" style="height: 30px;">
                <!-- Thay đổi tên list -->
                <div class="col-12">
                  <input v-model="list.list_name"
                  @change="chageNameList($event , list._id)"  
                  style="height: 30px; width: 100%;"  class="col-11  border-info name-list">
                  <!-- Hiển thị menu -->
                  <button v-on:click='showMenu($event , list)'    type="button" class="col-1 w-100 h-100 btn btn-inverse-dark btn-icon">
                            <i class="mdi mdi-more"></i>
                  </button>
                </div>
              </div>
              <!-- End -->
              <!-- Hiển thi card -->
              <div class="row">
                <div class="col-lg-12 mx-auto  body-listCard">
                    <Card
                      :list="list"
                      :addCard="Cards"
                      :user="user"
                      :board="board"
                      :memberBoard="memberBoard"
                      @close = "Cards.isAddCard = false"
                    ></Card>
                </div>
              </div>
              <!-- And -->
              <!-- Thêm thẻ mới -->
              <div class="row" style="height: 30px;">
                <div v-on:click="addCard({position: 1 , id: list._id})"  class="col-12 mx-auto header-lisCard-add">
                  <a class="text-decoration-none" style="color: black; " v-on:click="Cards.isAddCard = true"><i class="mdi mdi-plus"></i> Add anorther </a>
                </div>
              </div>
              <!-- End -->
            </div>
          </div>           
        </div>
        <!-- Thêm list khác  -->
      <div class="pr-5  h-auto addCard">
          <div  class="row">
            <button v-if="!isAddlist" v-on:click="isAddlist = true" class="col-12 btn btn-inverse-dark "><i class="mdi mdi-plus"></i>Add another list</button>
          </div>
          <div v-if="isAddlist" class="row">
            <div class="col-11 group-lisCard" >
              <div class="row mt-2 mb-1" style="height: 30px;">
                <div class="col-xl-12 col-lg-12 col-sm-12 col-12">
                  <input v-model="inputNameList" style="height: 30px; width: 100%;"  class="col-12  border-info">
                </div>
              </div>
               <div style="height: 40px;" class="row">
                <div class="col-12">
                  <button  
                  v-bind:disabled="isButtonDisabled"
              :style="isButtonDisabled ? { 'cursor': 'not-allowed' } : { 'cursor': 'pointer' }" 
                  v-on:click = "addList()" style="height: 30px;" class="btn btn-success btn-sm mb-2 ml-auto float-left">Add</button>
                  <button  @click="isAddlist = false" class="close col-2 ml-auto float-left"><i class="mdi mdi-close"></i></button>
                </div>
              </div>
          </div>
          </div>           
        </div>
     <!-- end  -->
      </draggable>
<!-- Hiển thị các option của list -->
         <menuList
                v-if="isShowMenu == true"
                :stylist="styleList"
                :list = "getList"
                :user="user"
                v-on:removeList="removeList()"
                @close="isShowMenu = false"
                v-on:addCard="addCard"
              > 
              </menuList>
      </div>
    </div>
</template>
<style >
/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: rgba(0,0,0,0.5);
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgba(250,250,250,0.5);
}

.testimonial-group > .row {
  overflow-x: auto;
  white-space: nowrap;
  min-height: 82vh;
  max-height: 82vh;
  /*min-width: 100%;*/
  position: relative;
}
.testimonial-group > .row > .draggable-list > .addCard{
    display: inline-block;
    float: none;
}
.addCard{
  position: relative;
  padding-bottom: 20px; 
  padding-top: 18px;
}
.testimonial-group > .row >  .draggable-list{
  display: inline-block;
  float: none;
}
.testimonial-group > .row > .draggable-list > .listCard {
  display: inline-block;
  float: none;
}
/* Decorations */
.listCard {
  position: relative;
  padding-bottom: 20px; 
  padding-top: 18px;
  min-width: 20%;
}
.testimonial-group > .row > .draggable-list > .listCard > .group-lisCard{
  display: inline-block;
  float: none;
}
.group-lisCard{
  background-color: #D8D3D3;
  border-radius: 5px;
}

.body-listCard{
  overflow-y: auto;
  white-space: pre-line;
  max-height: 65vh;
}
.header-lisCard-add{
  cursor: pointer;
}
.header-lisCard-add:hover {
 background-color:  #CBC3C3;

}
.name-list{
	background-color: #D8D3D3;
	color: black;
	border: none;
	cursor: pointer;
}
.name-list:focus{
	background-color: white;
	color: black;
	border: 2px solid #5977F8;
  	border-inline-style: solid;
}
.listMove{
  opacity: 0.5;
  background: black;
}

</style>
<script>
import Card from '../card/card.vue'
import draggable from 'vuedraggable'
import menuList from './menuList.vue'
    export default{
    	props: ['board','user','memberBoard'],
    	components: {
			menuList,
      Card,
    	},
    	data(){
    		return{
    			isButtonDisabled: true,
    			isAddlist:  false,
    			inputNameList: '',
    			lists: [],
    			isShowMenu: false,
    			styleList: {
    				left: 0,
    				top: 0,
    			},
         getList: [],
         scrollPos: 0,
         Cards: {
            checkPosition: '',
            isAddCard: false,
            id_list : '',
         },
    		}
    	},
    	 created(){
    		this.getAll();
          Echo.channel('updateB.'+this.board._id).listen('updateBoards',(e) => {
                this.getAll();
                // load lại dữ liệu 
                // axios.post('pushNoficationBoard/'+this.board._id,{
                //   user : e.user,
                //   content: e.message,
                // }); 
          });
    	},
      updated(){
        this.change();
      },
    	methods : {
          handleScroll (e) {
            this.scrollPos = e.target.scrollLeft;
          },
    		change(){
    			if(this.inputNameList === ''){
    				this.isButtonDisabled =  true;
    			}else{
    				this.isButtonDisabled =  false;
    			}
    		},
    		addList(){
    			axios.post('addList',{
    				id_board : this.board._id,
    				user : this.user._id,
    				list_name : this.inputNameList,
    			}).then(response =>{
    				this.getAll();
    				this.inputNameList = '';
    			});
    			
    		},
    		getAll(){
    			axios.get('getAllList/'+this.board._id,{

    			}).then(response =>{
    				this.lists = response.data;
    			});
    		},
        // Thay đổi tên 
    		chageNameList(event,id_list){
    			if( event.target.value != ''){
	              axios.post('chanNameList/'+id_list,{
	                name : event.target.value
	             }).then(response =>{
	               this.getAll();
	             })
	            }else{
	               this.getAll()
	            } 
    		},
    		showMenu(e,list){
              this.isShowMenu = true;
              this.styleList.left = e.pageX + this.scrollPos - 30  + 'px'; // lấy vị trí click
              this.styleList.top = 12 + '%';// lấy vị trí click
              this.getList = list;
          },
        removeList(){
          this.isShowMenu = false;
          this.getAll();
        },
        // Kiểm tra nếu có sự sắp xếp thứ tự các danh sách 
          checkMove: function(e){
            // Cập nhật order
             setTimeout(() => this.lists.map((list,index) =>{
                list.order = index + 1;
              }), 500);
             // Cập nhật vào csdl
              setTimeout(() => axios.post('updatePositionList/' + this.board._id,{
                lists : this.lists,
              }).then(response => {
                console.log(response.data);
              }), 500);
          },
          addCard(data){
            this.Cards.checkPosition = data.position;
            this.Cards.id_list = data.id;
            this.Cards.isAddCard = true;
          }
    	}
    }
</script>