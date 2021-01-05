<template>
	<div class="checkList">
		<div v-for="checkList in checkLists" :key="checkList._id" class="row mb-2">
			<div  class="col-9">
	            <h4 style="font-weight: blod;"><i class="mdi mdi-checkbox-marked-outline"></i> {{checkList.checkList_name}}</h4>
        	</div>
        	<div class="col-2 pt-2 delete" v-on:click="deleteCheckList(checkList._id)">
        		<span>Delete</span>
        	</div>
	        <div class="col-12 mt-2">
	        	<div class="row">
	        		<div class="col-1">
	        			<span>{{item.style.width}}</span>
	        		</div>
	        		<div class="col-10">
	        			<div class="sad" style="background : #E1E4EA; width: 100%; height: 10px; border-radius: 5px; position: relative;">
	        				<div class="processs" :style="item.style">	        			
	        				</div>
	        			</div>        			
	        		</div>
	        	</div>

	     	</div>
	     	<div class="col-12 mt-2">
				<item
					:stylePercent="item"
					:card="card"
					:checkList = "checkList"
					v-on:hanldeFinishItem="hanldeFinishItem"
				/>	     		
	     	</div>
		</div>
	
	</div>
</template>
<style>
	.form-group{
		border: transparent;
	}
	.form-group:focus {
		border: 2px solid #2D58F7;
	}
	.form-group::placeholder{ color: #A0A0A1; }
	.delete{ background: #EEEFF1; cursor: pointer;}
	.delete:hover {background: #D2D3D7;}
</style>
<script >
import item from './item.vue';
	 export default {
	 	components: {
	 		item
	 	},
	 	props: ['card','checkLists'],
	 	data(){
	 		return{
	 			item: {
	 				style: {
	 					width: 0 + '%' , 
	 					background: 'green', 
	 					height: 100 + '%', 
	 					position: 'absolute' , 
	 					borderRadius: 5 +'px',
	 				},
	 				isShowAddItem: false,
	 				inputAdd: '',
	 			},
	 		}
	 	},
	 	created(){
	 		
	 	},
	 	updated(){
	 	},
	 	computed: {

	 	},
	 	methods: {
	 		deleteCheckList(data){
	 			if(confirm("Do you really want to delete?")){
		 			axios.post('deleteCheckList/'+data+'/'+this.card._id,{

		 			}).then(response =>{
		 				this.$emit('hanldeDeleteCheckList');
		 			});
	 			}
	 		},
	 		hanldeFinishItem(data){
				var soluongItem = data.length;
	 			var soLuongDaCheck = 0;
	 			for(var i = 0 ; i <  soluongItem ; i++){
	 				if(data[i].active == 1){
	 					soLuongDaCheck = soLuongDaCheck +1 ;
	 				}
	 			}
	 			var percent = (soLuongDaCheck/soluongItem) * 100;
	 			var percent = percent.toFixed(1);
	 			this.item.style.width = percent + '%';
	 		}
	 	}
	 }

</script>\
