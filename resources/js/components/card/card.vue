<template>
      <ul class="row list-group">
        <li v-if="addCard.checkPosition == 0 && addCard.isAddCard == true && addCard.id_list  == list._id "  class="mt-1 mb-1 col-12  h-auto ">
          <div class="row add-Card">
            <TextareaAutosize
            :min-height="10"
            :max-height="350"
            v-model="inputNameCard" 
            class="col-12 ml-n3 addCard_input" 
            style=" min-width: 105%; border: none;" 
            rows="3" 
            cols="30" 
            placeholder = "Enter a title for this card…" />
          </div>
          <div class="row mt-1">
            <button v-on:click="handleAddCard()" 
            style="max-height: 35px;" class="btn-success btn-sm ml-1">Add Cart</button>
            <button    @click="$emit('close')" class="close col-lg-2 col-sm-2 col-2 ml-1"><i class="mdi mdi-close"></i></button>
          </div>

        </li>
      <!-- Danh sách các thẻ trong lis -->
      <draggable    v-model="cards" draggable=".card"  
      :options="{animation: 200, handle:'.card', group:'visiblity'}" 
      @change="checkMove()"
      @add="onAdd($event,list._id)"
      ghostClass = "listMove">  
      <template v-for="card in cards">
        <li  v-if='card.storage == false' class="mt-1 mb-1 col-12 h-auto card" :key="card._id" :data-id="card._id">
          <div v-on:click="showInfoCard(card)" class="list-cart-details" style="height: auto  min-width: 100%; ">
            <p style="height: auto; max-width: 240px;">{{card.card_name}}</p>
            <img v-if='card.assign' :src="card.assign.avatar.encoded" class="image_member rounded-circle " alt=""><br>
            <span v-if="card.tasks != null" 
            :style="card.tasks.active == 0 ? 'backgroundColor: yellow;' : 'backgroundColor: #09F636;'">{{card.tasks.reminder}}</span>
            <span v-if="card.attachment.length > 0"><i class="mdi mdi-attachment"></i>{{card.attachment.length}}</span> 
            <span v-if="card.checkList != null"><i class="mdi mdi-checkbox-marked-outline"></i>{{card.checkList.length}}</span>
          </div> 
        </li>
      </template>
      </draggable>
      <!-- end -->
      <!-- Thông tin thẻ  -->
      <infoCard
      v-if="isInfo == true"
      @close="isInfo = false"
      :InfoCard = "InfoCard"
      :list="list"
      :user="user"
      :board="board"
      :memberBoard="memberBoard"
      v-on:updateCard="updateCard" >
      </infoCard>
      <!-- end -->
        <li v-if="addCard.checkPosition == 1 && addCard.isAddCard == true && addCard.id_list  == list._id"  class="mt-1 mb-1 col-12  h-auto  ">
          <div class="row add-Cart">
            <TextareaAutosize
            :min-height="10"
            :max-height="350"
            v-model="inputNameCard" 
            class="col-12 ml-n3 addCard_input" 
            style=" min-width: 105%; border: none;" 
            rows="3" 
            cols="30" 
            placeholder = "Enter a title for this card…" />
          </div>
          <div class="row mt-1">
            <button  v-on:click="handleAddCard()"
            style="max-height: 35px;" class="btn-success btn-sm ml-1">Add Cart</button>
            <button   @click="$emit('close')" class="close col-lg-2 col-sm-2 col-2 ml-1"><i class="mdi mdi-close"></i></button>
          </div>
        </li>
      </ul>
</template>
<style >
/* width */
.add-Card{
  border-width: 1px;
  border-style: outset;
}
.addCard_input::placeholder {
  color: #BFBDBD;
}
.card{
  border-width: 1px;
  border-style: outset;
  background-color: white;
  cursor: pointer;

}
.card:hover {
  background-color: #F7F7F7;
}
.cardMove{
    opacity: 0.5;
  background: black;
}

</style>
<script>
// import { Mentionable } from 'vue-mention'
import moment from "moment";
import draggable from 'vuedraggable'
import infoCard from './infoCard'
    export default{
    	props: ['addCard','list','user','board','memberBoard'],
    	components: {
        draggable,
        infoCard
    	},
    	data(){
    		return{
    		    inputNameCard: '',
            cards: [],
            isInfo: false,
            InfoCard: '',
    		}
    	},
       computed: {
          id_board() {
            return this.$route.params.id_board
          }
        },
      mounted(){
        this.getCards();
          Echo.channel('updateB.'+this.id_board).listen('updateBoards',(e) => {
                this.getCards();
          });
      },	 
    	methods : {
        getCards(){
          axios.get('api/getAllCard/'+this.list._id,{

          }).then(response => {
            this.cards = response.data;
          })
        },
        handleAddCard(){
          axios.post('api/addCard/'+this.board._id,{
              cart_name: this.inputNameCard,
              list_id: this.list._id,
              user : this.user,
              board: this.board._id,
              position : this.addCard.checkPosition,
          }).then(response =>{
            this.getCards();
            this.inputNameCard = '';
            this.$emit('close');
          });
        },
        // Thay đổi vị trí của thẻ
        checkMove(id_list){
          setTimeout(() => this.cards.map((card,index) =>{
                card.order = index + 1;
              }), 500);
          setTimeout(() => axios.post('api/updatePositionCard/'+this.board._id,{
                user: this.user,
                cards : this.cards,
              }).then(response => {
                console.log(response.data);
              }), 500);
        },
        // Kiểm tra xem có thẻ nào vừa đc thêm vô không
        onAdd(e , id_list){
          let id = e.item.getAttribute("data-id");
          axios.post('api/changeListOfCard/'+id+'/'+this.board._id,{
            user: this.user,
            id_list : id_list,
          }).then(response =>{
            // console.log('sucsesss');
          })
        },
        showInfoCard(card){
          this.InfoCard = card;
          this.isInfo = true;
        },
        updateCard(){
            this.getCards();
            axios.post('api/updateCard/'+this.board._id,{
              user: this.user
            });        
        }
    	}
    }
</script>