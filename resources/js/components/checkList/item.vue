<template>
<div class="item">
		<div class="col-12">
			<div v-for="item in item.items " class="row mb-2">
				<label  class="checkBox col-lg-9"> 
					<span :style="item.active == 1 ? 'text-decoration:line-through ;' : 'text-decoration: none;'">{{item.name}}</span>
				  <input 
					  :value="item.active" 
					  v-bind:id="item.id" type="checkbox"
					  :checked="item.active == 1"
					  @click="actived($event , item)"  />
				  <span class="checkmark"></span>
				</label>
				<div v-on:click="deleteItem(item)" class="col-lg-2 pt-2 delete">
					<span>Delete</span>
				</div>
			</div>
		</div>
	     <div v-if="item.isShowAddItem == false" @click="item.isShowAddItem = true" class="col-3 pt-2 pb-2 delete">
	        <span>Add an item</span>
	     </div>
	      <div v-if="item.isShowAddItem == true"  class="col-12">
		    <TextareaAutosize
		    placeholder="Add an item"
	         class=" col-lg-12 form-group"
	         v-model="item.inputAdd"
	         :min-height="30"
	         :max-height="350"
	          />
	        <button v-on:click="addItem()" style="height: 35px;" class="btn-success btn-sm ml-auto float-left">Add</button>
	        <button @click="item.isShowAddItem = false" class="close  col-lg-1 col-sm-1 col-1 ml-auto float-left"><i class="mdi mdi-close"></i></button>
	   </div>		
</div>
</template>
<style>
	.form-group{
		border: transparent;
		background: transparent;
	}
	.form-group:focus {
		border: 2px solid #2D58F7;
	}
	.form-group::placeholder{ color: #A0A0A1; }
.delete{ background: #EEEFF1; cursor: pointer;}
.delete:hover {background: #D2D3D7;}
.checkBox{
  display: inline-block;
  position:relative;
  cursor:pointer;
  font-size:15px;
  user-select: none;
  padding-left:40px;
  margin-bottom:10px;
}
.checkBox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}
.checkmark{
  position:absolute;
  top:0;
  left:0;
  width:15px;
  height:15px;
  background:#eee;
  border-radius:3px;
}
.checkBox:hover .checkmark{
  background:#ccc;
}
.checkmark:after{
  content:"";
  position:absolute;
  display:none;
}
.checkBox .checkmark:after{
  top:50%;
  left:50%;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  transform:translate(-50%,-50%) rotate(45deg);
}
.checkBox input:checked ~ .checkmark{
  background:#2196F3;
}
.checkBox input:checked ~ .checkmark:after{
  display:block;
}
</style>
<script>

	 export default {
	 	components: {
	 		
	 	},
	 	props: ['card','checkList','stylePercent','user'],
	 	data(){
	 		return{
	 			item: {
	 				style: {
	 					width: '' , 
	 					background: 'green', 
	 					height: 100 + '%', 
	 					position: 'absolute' , 
	 					borderRadius: 5 +'px',
	 				},
	 				isShowAddItem: false,
	 				inputAdd: '',
	 				items: [],
	 				
	 			},
	 			checkeds: '',
	 		}
	 	},
	 	created(){
	 		this.getAllItem();
	 		 Echo.channel('updateC.'+this.card._id).listen('updateCards',(e) => {
                this.getAllItem();
          	});
	 		
	 	},
	 	updated(){
			this.hanldeFinishItem();
	 	},
	 	methods: {
	 		addItem(data){
	 			axios.post('api/addItem/'+this.checkList._id+'/'+this.card._id,{
	 				name: this.item.inputAdd,
					user: this.user
	 			}).then(response => {
	 				this.item.isShowAddItem = false;
	 				this.item.inputAdd = '';
	 				this.getAllItem();
	 				setTimeout(() => this.$emit('hanldeFinishItem',this.item.items ), 500);
	 			})
	 		},
	 		getAllItem(){
	 			axios.get('api/getItem/'+this.checkList._id,{

	 			}).then(response => {
	 				this.item.items = response.data;
	 			})
	 		},
	 		actived(e,data){
	 			axios.post('api/changeActived/'+this.checkList._id+'/'+this.card._id,{
	 				active: e.target.value,
	 				data: data,
					 user: this.user
	 			}).then(response => {
	 				this.getAllItem();
	 				setTimeout(() => this.$emit('hanldeFinishItem',this.item.items ), 500);
	 			})
	 		},
	 		hanldeFinishItem(){
	 			if(this.item.items.length > 0){
		 			var soluongItem = this.item.items.length;
		 			var soLuongDaCheck = 0;
		 			for(var i = 0 ; i <  soluongItem ; i++){
		 				if(this.item.items[i].active == 1){
		 					soLuongDaCheck = soLuongDaCheck +1 ;
		 				}
		 			}
		 			var percent = (soLuongDaCheck/soluongItem) * 100;
		 			var percent = percent.toFixed(1);
		 			this.stylePercent.style.width = percent + '%';
	 			}
	 		},
	 		deleteItem(data){
	 			if(confirm("Do you really want to delete?")){
		 			axios.post('api/deleteItem/'+this.checkList._id+'/'+this.card._id,{
		 				item : data,
						 user: this.user
		 			}).then(response => {
		 				this.getAllItem();
		 				setTimeout(() => this.$emit('hanldeFinishItem',this.item.items ), 500);
		 			})
	 			}
	 		}
	 	}
	 }

</script>