<template>
<div class="container">
              <div @click='$router.go(-1)' class="row">
                  <div class="col-2">
                      <p class="pt-1 pb-1 text-center w-50 back"><i class="mdi mdi-keyboard-return"></i></p> 
                  </div>
              </div>
              <div class="row">
              <div class="col-lg-8 offset-lg-1 grid-margin stretch-card p-0">
                <div >
                  <div class="card-body">
                    <h4 class="card-title">Projects you are managing</h4>
                    <table class="table table">
                      <thead>
                        <tr>
                          <th> STT </th>
                          <th> Name </th>
                          <th> Created  </th>
                          <th> Updated </th>
                          <th> Public </th>
                          <th> Link</th>
                          <th> List of Card</th>
                        </tr>
                      </thead>
                      <tbody>
                        <template  v-for="(list,index) in lists"> 
                        <tr :key="list._id" class='nofications'>
                          <td class="pl-4">{{index + 1}}</td>
                          <td class="py-1 p-0 text-uppercase">
                            <span>{{list.list_name}}</span>
                          </td>
                          <td>{{list.created_at}}</td>
                          <td>{{list.updated_at}}</td>
                          <td @click="storage(list._id , list.storage)"  class="text-uppercase storage">
                            <span v-if="list.storage == false">storage list</span>
                            <span v-else>Restore list</span>
                          </td>
                          <td>
                              <span class="link" @click="linkToBoard($route.params.id_board)">Go to board</span>
                          </td>
                          <td>
                              <span class="link" @click="linkToCards(list._id)">Cards</span>
                          </td>
                        </tr>
                        </template>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              </div>

    </div>  
</template>

<script>
export default {
    props: ['user'],
      data() {
        return {
           lists: null
        } 
      },
      created() {
        this.getAll()
      },
      methods: {
         getAll(){
            axios.get('api/getAllList/'+this.$route.params.id_board,{
              }).then(response =>{
                this.lists = response.data;
              });
            },
          linkToBoard(id) {
            this.$router.push({ name: 'board', params: { id_board: id } })
          },
          storage(id , storage){
             let mess = ''
              if(storage == false){
                  mess = "Bạn có muốn lưu trữ thẻ này ?"
              }else{
                  mess = "Bạn có muốn khôi phục thẻ này ?"
              }
                if (confirm(mess)) { 
                    axios.post('api/stogareList/'+id , 
                      {user: this.user})
                      .then((response) => {
                        this.getAll()
                      })
                }
          
          },
          linkToCards(id){
             this.$router.push({ name: 'card', params: { id_list: id , id_board: this.$route.params.id_board } })
          }
      }
}
</script>

<style>

</style>