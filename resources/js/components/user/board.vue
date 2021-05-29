<template>
<div class="container">
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
                        <template  v-for="(board,index) in boards"> 
                        <tr  :key="board._id" class='nofications'>
                          <td class="pl-4">{{index + 1}}</td>
                          <td class="py-1 p-0 text-uppercase">
                            <span>{{board.board_name}}</span>
                          </td>
                          <td>{{board.created_at}}</td>
                          <td>{{board.updated_at}}</td>
                          <td @click="storage(board._id , board.storage)"  class="text-uppercase storage">
                            <span v-if="board.storage == false">storage</span>
                            <span v-else>Restore</span>
                          </td>
                          <td>
                              <span class="link" @click="linkToBoard(board._id)">Go to board</span>
                          </td>
                          <td>
                              <span class="link" @click="linkToLists(board._id)">Lists</span>
                          </td>
                        </tr>
                        </template>
                      </tbody>
                    </table>
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
           boards: null
        } 
      },
      created() {
        this.getBoards()
      },
      methods: {
          getBoards(){
            axios.post('api/getAllBoards',{
                  user: this.user
                }).then(response =>{
                  this.boards = response.data;
              })
          },
          linkToBoard(id) {
            this.$router.push({ name: 'board', params: { id_board: id } })
          },
         storage(id , storage){
            let mess = ''
              if(storage == false){
                  mess = "Bạn có muốn lưu trữ project này ?"
              }else{
                  mess = "Bạn có muốn khôi phục project này ?"
              }
            if (confirm(mess)) {
              axios.post("api/stogareBoards/" + id, {user: this.user}).then((response) => {
                  this.getBoards();
              });
            }
          },
          linkToLists(id){
             this.$router.push({ name: 'list', params: { id_board: id } })
          }
      }
}
</script>

<style>
  .storage {
    transition: .2s all;
  }
  .storage:hover {
      background-color: black;
      color: #fff;
  }
  .link {
    color: #6490cb
  }
  .link:hover {
    color: #3c567a
  }
</style>