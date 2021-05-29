<template>
    <div class="container">
        <div class="col-lg-12 grid-margin stretch-card">
                <div >
                  <div class="card-body">
                    <h4 class="card-title">Nofications</h4>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th> STT </th>
                          <th> User </th>
                          <th> Content  </th>
                          <th> Time </th>
                        </tr>
                      </thead>
                      <tbody>
                        <template  v-for="(nofi, index) in orders"> 
                        <tr class='nofications' @click="linkToBoard(nofi.id_board)" :key="index">
                          <td class="pl-4">{{index + 1 }}</td>
                          <td class="py-1 p-0">
                            <img :src="nofi.user_send.avatar.encoded" class="img_member rounded-circle ml-2" />
                            <span>{{nofi.user_send.user_name}}</span>
                          </td>
                          <td> {{nofi.content}} </td>
                          <td>{{nofi.time}}</td>
                        </tr>
                        </template>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
    </div>  
</template>

<style>
  .nofications{
    cursor: pointer;
  }
</style>
<script>
import _ from 'lodash';
    export default{
      props: ['user'],
      data() {
        return {
           nofications: null
        } 
      },
      computed: {
        orders: function () {
          return _.reverse(this.nofications)
        }
      },
      created() {
        this.getNofications()
      },
      methods: {
          getNofications(){
            axios.get('api/getNofications/'+this.user._id)
                .then((Response) => {
                  this.nofications = Response.data
            })
          },
          linkToBoard(id) {
            this.$router.push({ name: 'board', params: { id_board: id } })
          }
      }
    }
</script>