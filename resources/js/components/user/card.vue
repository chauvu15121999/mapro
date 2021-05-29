<template>
<div class="container">
              <div @click='$router.go(-1)' class="row">
                  <div class="col-2">
                      <p class="pt-1 pb-1 text-center w-50 back"><i class="mdi mdi-keyboard-return"></i></p> 
                  </div>
              </div>
  <div class="row">
        <div class="col-lg-8 col-12 offset-xl-1 grid-margin stretch-card p-0">
                <div >
                  <div class="card-body">
                    <h4 class="card-title">Projects you are managing</h4>
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col"> STT </th>
                          <th scope="col"> Name </th>
                          <th scope="col"> Created  </th>
                          <th scope="col"> Updated </th>
                          <th scope="col"> Public </th>
                          <th scope="col"> Link</th>
                        </tr>
                      </thead>
                      <tbody>
                        <template  v-for="(card,index) in cards"> 
                        <tr :key="card._id" class='nofications'>
                          <td scope="row" class="pl-4">{{index + 1}}</td>
                          <td class="py-1 pl-3 text-uppercase">
                            <span>{{card.card_name}}</span>
                          </td>
                          <td>{{card.created_at}}</td>
                          <td>{{card.updated_at}}</td>
                          <td @click="storage(card._id ,card.storage )"  class="text-uppercase storage">
                            <span v-if="card.storage == false">storage card</span>
                            <span v-else>Restore card</span>
                          </td>
                          <td>
                              <span class="link" @click="linkToBoard($route.params.id_board)">Go to board</span>
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
           cards: null
        } 
      },
      created() {
        this.getCards()
      },
      methods: {
          getCards(){
            axios.get('api/getAllCard/'+this.$route.params.id_list,{
            }).then(response => {
              this.cards = response.data;
            })
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
              axios.post("api/stogareCard/" + id, {user: this.user}).then((response) => {
                  this.getCards();
              });
            }
          },
      }
}
</script>

<style>
  .back {
    cursor: pointer;
    background: #95bef6;
    color: #fff;
    font-size: 1.235vw;
    transition: .2s all;
    font-weight: 700;
    font-family: Arial, Helvetica, sans-serif;
  }
  .back:hover {
    background: #72a8f1
  }
</style>