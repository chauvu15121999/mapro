<template>
<div class="containe">
     <div class="row"> 
      <div class="col-sm-12 ml-5 border">
            <div class="row">
              <div class="col-sm-12">
                <h1>Broad for team you</h1>
              </div>
              
            </div>
            <div class="row">
              <div  class="broads mr-3 mt-3 col-sm-2">
                 <p>aaa</p>
              </div>      
            </div>                   
      </div>
    </div>
</div>
</template>
<style>
.broads{
  background-color: blue;
  height: 100px;
}
</style>
<script>
    export default {
        props: ['team'],
        components: {
           
        },
        data() {
              return{
                
              }
        },
        created() {
            
        },
        methods: {
            getBoards(){
              this.get('getBoards')
            }
        }
        
    }
</script>