<style  type="text/css" >

.modal {
  display: block; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 5000; /* Sit on top */
 /* padding-top: 10px;*/ /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
/* Modal Content */
.modal-content {
  background-color: transparent; /* background-color: none */
  border: 1px solid transparent;/* border-color: none */
  width: 100%;
}
.left{
	max-height: 160px;
  background-size: cover;
}
/* định dạng css cho from input*/
.title-board{
	background: rgba(240,240,240,0.0);
	border: none;
	height: 25px;
	color: white;
}
.title-board:hover {
	background: rgba(240,240,240,0.3);

}
::placeholder {
  color: white;
  opacity: 1; /* Firefox */
}
/* end */
/* Định dạng from select-option */
select option {
  margin: 40px;
  background: rgba(0, 0, 0, 0.8);
  color: #fff;
  text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
}
/* end */
/* định dạng from chọn background */
.background ul{list-style:none; padding:0; margin: 0;}

.background li{margin: 0 0 0 0; display: inline-block;}

.background label{cursor: pointer;}

.background label:hover{opacity: 0.7;}

.background input{display:none;}

.background input[type="radio"]:checked + .swatch{box-shadow: inset 0 0 0 2px white;}
 [type=radio] + img {
  cursor: pointer;
}

[type="radio"]:checked + img {
	/*box-shadow: inset 0 0 0 2px white;*/
	box-sizing: border-box;
	border : 4px solid green;
}
.swatch{
  display:inline-block;
  vertical-align: middle;
  height: 30px;
  width:30px;
  margin: 0 5px 0 0 ;
  border: 1px solid #d4d4d4;
}
/* end */
</style>
<template>
 <!-- Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content mx-auto mr-n5 col-sm-6 mt-5 ">
	<div class="row">
    <!-- Phần bên trái layout chứa form  -->
		<div :style="{ 'background-image': 'url(' + image + ')' }"  class="left col-sm-6">
      <form action="AddBoard" method="post">
        <input name="name_boards" v-model="nameBoard"  type="text" placeholder="Add board title" class="col-sm-10 mt-3 title-board" />
        <input type="text" name="id_background"  :value="id_background" class="d-none">
        <input type="text" name="url"  :value="image" class="d-none">
      <!-- btn close -->
        <button class="close title-board mt-3" @click="$emit('close')"><i class="mdi mdi-close "></i></button> 
        <!-- end btn close  -->
        <!-- select team -->
        <select style="cursor: pointer; max-width: 50%;" name="team" class="d-block mt-2 title-board">
          <option value="0">Personal Board</option>
          <option v-for="team in listTeam" :value="team._id">{{team.team_name}}</option>
        </select>
        <!-- select status -->
        <i style="color: white;" class="mdi mdi-account-multiple menu-icon"></i>
        <select  v-model="status"  style="cursor: pointer; max-width: 90%;"  name="status" class="mt-2 mb-2 title-board">
          <option value="">Team visible</option>
          <option value="0">private ( chỉ mình bạn được thấy bảng này )</option>
          <option value="1">Team ( Những người trong nhóm của bạn được thấy bảng này)</option>
          <option value="2">public ( Bất kì ai cũng có thể thấy bảng này)</option>
        </select>
        <button  type="submit" 
        v-bind:disabled="isButtonDisabled"
        :style="isButtonDisabled ? { 'cursor': 'not-allowed' } : { 'cursor': 'pointer' }" 
        class="d-block btn btn-success btn-sm mb-2">Create Board</button>
        <input type="hidden" name="_token" :value="csrf">
      </form>
		</div>
    <!-- end left -->
    <!-- chọn nhiều background -->
		<section  
		v-if="selectMoreBackground" class="col-lg-5 ml-3" style="z-index: 9999; background-color: white; height: 500px; width: 100px;">
			<button style="background-color: rgba(0,0,0,0.4);" class="close title-board mt-3 " @click="selectMoreBackground = false"><i class="mdi mdi-close"></i></button>
			<!-- listBackgroud -->
        <listBackgroud 
          v-bind:listAllBackgroud = "listAllBackgroud"
          v-on:hanldeListBackground="images">
        </listBackgroud>
      <!-- end listBackgroud -->
		</section>
    <!-- end -->
    <!-- Chọn background mặc định -->
		<div v-else  class="col-lg-3 col-sm-6 col-12 background">
				<ul>
				    <li class="mr-1" v-for="background in listBackgrouds">
				      <label>
				        <input type="radio" name="background" :value="background._id" 
                v-model="id_background">
				        <img class="swatch" 
				        v-bind:src="'./public/backend/assets/images/backgroud-boards/' + background.background_name"
				        >
				      </label>
				    </li>
				   	<li>
				      <label>
				        <input type="radio" name="color" value="green">
				        <!-- Mở list background -->
				        <span v-on:click="handleBackground()"  class="swatch" style="background-color: #EBE4E4"><i class="mdi mdi-library-plus ml-2 mt-3"></i></span> 
				      </label>
				    </li>
				  </ul> 			
		</div>
    <!-- end -->
	</div>  
  </div>
</div>
</template>
<script>
	import listBackgroud from './listBackgroud.vue'
    export default {
        props: ['listTeam'],
        components: {
            listBackgroud
        },
        data() {
              return{
                nameBoard: '',
                status: '',
                isButtonDisabled: true,
              	selectMoreBackground: false,
              	listBackgrouds: [], // 
              	listAllBackgroud: [], // lag
                id_background : "5ff10b29e4d2ea2b69d1e46d", // id defaul
                image : '', // lấy link hình 
                csrf: document.head.querySelector('meta[name="csrf-token"]').content
              }
        },
        created(){
        	this.getBackgrouds();
          this.getOneBackgroud();
        },
        updated(){
          this.getOneBackgroud();
          this.change();
        },
        methods: {
          change(e){
            if(this.nameBoard === '' || this.status === '' )
            {
              this.isButtonDisabled = true;
            }
            else{
              this.isButtonDisabled = false;
            }
          },
        	handleBackground(){
        		this.selectMoreBackground = true;
        		axios.get('getAllBackgrouds',{
        		})
        		.then(response => {
        			this.listAllBackgroud = response.data
        		})
        	},
        	getBackgrouds(){
        		axios.get('getBackgrouds',{
        		})
        		.then(response => {
        			this.listBackgrouds = response.data
        		})
        	},
          images(data){
              this.id_background =  data;
              // console.log(this.id_background);
              this.getOneBackgroud();
          },
          getOneBackgroud(){
            axios.post('getOneBackgroud',{
              id_background : this.id_background,
            })
            .then(response => {
              this.image = response.data
            })
          }
        },
        
    }
</script>


