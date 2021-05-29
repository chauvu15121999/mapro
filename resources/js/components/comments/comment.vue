<template>
  <div class="comment">
    <div class="col-12">
      <h4 style="font-weight: blod">
        <i class="mdi mdi-format-line-weight"></i> Comment
      </h4>
    </div>
    <div class="add-commnet">
      <div
        @click="onAdd = true"
        v-if="!onAdd"
        class="col-12 p-2 ml-2"
        id="add-comment"
      >
        <span class="ml-4">Add a commnet.....</span>
      </div>
      <div v-if="onAdd" class="col-12">
        <vue-editor v-model="content" id="commnet"></vue-editor>
        <button @click="Add" class="btn-sm btn-gradient-info mt-1 ml-1">Add</button>
        <button
          @click="onAdd = false"
          class="btn-sm btn-gradient-secondary mt-1"
        >Cancel</button>
      </div>
    </div>
    <div v-for="(comment) in comments" :key="comment.id" class="row mt-2">
      <div
        class="ml-3 pr-1 col-11 p-1"
        id="content-comment"
      >
        <div class="row">
          <div class="col-1">
            <img
              :src="comment.user_avatar.encoded"
              class="image_member rounded-circle ml-3"
              alt=""
            />
          </div>
          <div class="col-10">
            <div class="row">
              <div class="col-12">
                <span>{{ comment.user_name }}</span>
                <time-ago
                  :refresh="60"
                  :datetime="comment.time_spam"
                  locale="en"
                  tooltip="right"
                ></time-ago> ago    
              </div>
            </div>
            <div class="row mt-1">
              <div  :id="'content-ref-'+comment.id"  class="col-12 content-comment edit-show">
                <p v-html="comment.content"></p>
                  <a @click="editComment(comment.id)" class="mr-1">Edit</a> 
                  <a @click="deleteComment(comment.id)" class="ml-1">Delete</a> 
              </div>  
              <div :id="'edit-ref-'+comment.id" class="col-12 edit-hidden">
                <vue-editor v-model="comment.content" id="commnet"></vue-editor>
                <button @click="edit(comment)" class="btn-sm btn-gradient-info mt-1 ml-1">Add</button>
                <button
                  @click="cancleEdit(comment.id)"
                  class="btn-sm btn-gradient-secondary mt-1"
                >Cancel</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TimeAgo from "vue2-timeago";
import { VueEditor } from "vue2-editor";
export default {
  props: ["user", "card",'nofication','userReceinofication'],
  components: {
    TimeAgo,
    VueEditor
  },
  data() {
    return {
      onAdd: false,
      content: "",
      isActive: false,
      comments: [],
    };
  },
  mounted() {
    this.getAllComments();
      Echo.channel("updateC." + this.card._id).listen("updateCards", (e) => {
        this.getAllComments();
      });
  },
	computed: {
			id_board() {
			    return this.$route.params.id_board;
			}
		},
  methods: {
    Add() {
      axios
        .post("api/addCommnet/" + this.card._id, {
          content: this.content,
          user: this.user,
          avarta: this.user.avatar,
          nofication: 'add comment ' + this.nofication,
          userReceived: this.userReceinofication,
          id_board: this.id_board
        })
        .then((Response) => {
          console.log(Response.data);
          this.getAllComments();
          this.onAdd = false;
        });
    },
    getAllComments() {
      axios.get("api/getComment/" + this.card._id).then((Response) => {
        this.comments = Response.data;
      });
    },
    editComment(id){
      $("#content-ref-"+id).removeClass('edit-show');
      $("#edit-ref-"+id).removeClass('edit-hidden');
      $("#content-ref-"+id).addClass('edit-hidden');
      $("#edit-ref-"+id).addClass('edit-show');
    },
    cancleEdit(id){
      $("#content-ref-"+id).removeClass('edit-hidden');
      $("#edit-ref-"+id).removeClass('edit-show');
      $("#content-ref-"+id).addClass('edit-show');
      $("#edit-ref-"+id).addClass('edit-hidden');
    },
    edit(data){
      axios.post("api/editComment/"+this.card._id,{
        id: data.id,
        content: data.content,
        user: this.user,
        nofication: 'edit comment ' + this.nofication,
        userReceived: this.userReceinofication,
        id_board: this.id_board
      }).then(Response => {
        console.log(Response.data);
        this.getAllComments();
        this.cancleEdit(data.id);
      })
    },
    deleteComment(data){
      axios.post("api/deleteComment/"+this.card._id,{
        id: data,
        user: this.user,
        nofication: 'delete comment ' + this.nofication,
        userReceived: this.userReceinofication,
         id_board: this.id_board
      }).then(Response => {
        console.log(Response.data);
        this.getAllComments(); 
      })
    },
  },
};
</script>
<style>
#add-comment {
  border: 1px solid rgb(223, 217, 217);
  cursor: pointer;
}
#add-comment:hover {
  border: 3px solid rgb(128, 124, 124);
}
.content-comment a{
  color: rgb(128, 124, 124);
  cursor: pointer;
}
.edit-hidden{
  display: none;
}
.edit-show{
  display: inline;
}
</style>