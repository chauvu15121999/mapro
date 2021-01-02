<template>
 	<div class="backgrouds">
 		<h4>List backgrouds board</h4>
		<ul>
			<li v-for="(background,index) in listAllBackgroud">
				<label>
				  <input :checked="index ? 0 : false"  type="radio" name="color" id="background.background_name" :value="background._id" 
				  v-on:input="pickeds($event)">
				  <img 
				   v-bind:src="'./public/backend/assets/images/backgroud-boards/' + background.background_name" 
				  />
				</label>
			</li>										
	</ul>  		
    </div>
</template>
<style>
.backgrouds h4{ color:#C2C0CE;}

.backgrouds ul{list-style:none; padding:0; margin: 0;}

.backgrouds li{margin: 0 0 0 0; display: inline;}

/*.backgrouds label{cursor: pointer;}*/

.backgrouds label:hover{opacity: 0.7;}

.backgrouds input{display:none;}

 [type=radio] + img {
  cursor: pointer;
}

[type="radio"]:checked + img {
	/*box-shadow: inset 0 0 0 2px white;*/
	box-sizing: border-box;
	border : 4px solid green;
}

img{
  display:inline-block;
  vertical-align: middle;
  height: 50px;
  width: 100px;
  margin: 0 0 0 0 ;
  border: 1px solid #d4d4d4;
}  
</style>
<script>
// import nav from './nav/nav'
// import VLazyImage from "v-lazy-image";
    export default{
    	props: ['listAllBackgroud'],
    	 data(){
            return{
              	// listBackgroud: [],
              	picked: '',
            }
        },
        created(){
        	// this.getAllBackgrouds();	
        },
        methods: {
        	pickeds(e){
        		this.$emit('hanldeListBackground',e.target.value);
        	}
        },
    }
</script>