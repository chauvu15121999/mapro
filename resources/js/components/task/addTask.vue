<template>
  <div :style="stylist"  class="addTast col-lg-2 col-sm-5 col-10">
    <div class="row ml-1 mr-1 mt-2" style="border-bottom: 1px solid #716A6A;">
        <p class="col-lg-11 text-center mt-2" style="font-size: 15px; ">Change Due Date</p>
        <button @click="$emit('close')" class="close col-lg-1 col-sm-1 col-1"><i class="mdi mdi-close"></i></button>       
    </div> 
    <div class="row ml-1 mr-1 mt-2" >
        <div class="row">
          <div class="col-6" >
            <span>Date</span>
            <input type="text" v-model="date" class="form-control">
          </div>
          <div class="col-6">
            <span>Time</span>
            <input type="text" v-model="time" class="form-control">
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-12">
            <span>Select date</span>
            <input v-model="date" type="date" class="form-control">
          </div>
          <div class="col-12 mt-2">
            <span>Select time</span>
            <input v-model="time" type="time" class="form-control">
          </div>
        </div>
    </div>
    <div class="row ml-1 mr-1 mt-2">
      <div class="col-12">
        <span>Set Due Date Reminder</span>
        <select v-model="option" class="col-12 border-dark">
          <option value="None">None</option>
          <option value="at">At time of due date</option>
          <option value="5m">5 Minutes before</option>
          <option value="10m">10 Minutes before</option>
          <option value="15m">15 Minutes before</option>
          <option value="1h">1 hours before</option>
          <option value="2h">2 hours before</option>
          <option value="1d">1 day before</option>
          <option value="2d">2 day before</option>
        </select>
      </div>
    </div>
    <div class="row ml-1 mr-1 mt-2">
      <div class="col-12">
        <p style="color: #AFA8A8">Reminder will be sent to all members</p>
      </div>
      <div class="col-12">
        <button v-on:click="addTask()" class="btn btn-primary btn-sm">Save</button>
        <button v-on:click="revokeTask()" class="btn btn-danger btn-sm mr-auto float-right">Remove</button>
      </div>
    </div>   
  </div>
</template>
<style>
.addTast {
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
select option {
  margin: 40px;
  background: white;
  color: black;
  font-size: 15px;
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
    	props: ['stylist','card','user','task','nofication','userReceinofication'],
      components: {
          
      },
    	data(){
    		return {
            date: '',
            time: '',
            option: 'None',
    		}
    	},
    	created(){
        this.getNow();
    	}, 
      computed: {
        id_board() {
          return this.$route.params.id_board;
        }
      },
    	methods: {
        getNow() {
          // Nếu chưa có task sẽ lấy ngày tháng hiện tại.
          if(this.task === null){
            const today = new Date();
            this.date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+(today.getDate() + 1 );
            this.time = today.getHours() + ":" + today.getMinutes();
          }else{ // nếu có rồi thì sẽ lấy ngày tháng trong db.
            this.date = this.task.date;
            this.time = this.task.time;
            this.option = this.task.option;
          }  
        },
        addTask(){
          axios.post('api/addTask/'+this.card._id,{
            time: this.time,
            date: this.date,
            reminder: this.option,
            user: this.user,
            id_board: this.id_board,
            nofication: 'add task in '+ this.nofication,
            userReceived: this.userReceinofication
          }).then(response => {
            this.$emit('hanldeAddTask');
          })
        },
        revokeTask(){
          if(confirm('Bạn có muốn xóa task này')){
            axios.post('api/revokeTask/'+this.card._id,{
            user: this.user,
            id_board: this.id_board,
            nofication: 'remove task in '+ this.nofication,
            userReceived: this.userReceinofication
            }).then(response => {
              this.$emit('hanldeAddTask');
            });
          }
        }
      }

}
</script>
