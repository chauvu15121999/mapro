<template>
	<div class="checkList">
			<label  class="checkBox col-lg-9"> 
				<span>{{date}}</span>    
				<span class="ml-1" :style="backgroundColor">{{check}}</span>
				<input
				:checked="task.active == 1" 
				:value="task.active" 
				type="checkbox"
				@click="activity(task.active)" />
				<span class="checkmark"></span>
			</label>
			<span @click="$emit('show',$event)"  class="col-lg-2 show"><i class="mdi mdi-arrow-bottom-right"></i></span>
	</div>
</template>
<style>
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
.show{
	cursor: pointer;
}
.show:hover{
	background-color: #DFDFDF;
}
</style>
<script >
	import moment from "moment";
	 export default {
	 	components: {

	 	},
	 	props: ['card','task','user','nofication','userReceinofication'],
	 	data(){
	 		return{
	 			check: '',
	 			complete: 0,
	 		}
	 	},
	 	created(){
	 		this.checkDeadline();
	 	},
	 	updated(){
	 		this.checkDeadline();
	 	},
	 	  computed: {
			    date(){
			    	return moment(this.task.date+' '+this.task.time);
			    },
			    backgroundColor() {
			    	if(this.check == 'OVERDUE'){
			    		return 'backgroundColor : red';
			    	}else if (this.check == 'DUE SOON') {
			    		return 'backgroundColor : yellow';
			    	}else if (this.check == 'COMPLETE'){
			    		return 'backgroundColor :  #09F636';
			    	}
			    }
			  },
	 	methods: {
	 		checkDeadline() {
		 		if(this.task.active == 0){
		 			setInterval(() => {
			        var countDownDate = new Date(this.date).getTime();
				     var now = new Date().getTime();
				     var distance = countDownDate - now;
				     if (distance < 0) {
					    this.check = 'OVERDUE'
					  }else{
					  	this.check = 'DUE SOON'
					  }
			    	},1);
		 		}else{
		 			this.check = 'COMPLETE';
		 		}		     
			},
			activity(data){
				var active = 0;
				if(data == 0){
					active = 1;
					this.check = 'COMPLETE';
				}else{
					this.checkDeadline();
				}
				axios.post('api/changeActivedTask/'+this.card._id,{
					active: active,
					user: this.user,
					nofication: 'update task '+ this.nofication,
            		userReceived: this.userReceinofication
				}).then(response => {
					this.$emit('updateTask');
				})
			}
	 	}
	 }

</script>\
