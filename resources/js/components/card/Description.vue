<template>
  <div class="description">
    <div class="col-12">
      <h4 style="font-weight: blod">
        <i class="mdi mdi-format-line-weight"></i> Desription
      </h4>
    </div>
  <div @click="edit" v-if="!onEdit" class="col-12 p-1" id="content-description">
		<p v-html="card.description" class="ml-3"></p>
	</div>
    <div v-if="onEdit" class="col-12 h-auto">
		<vue-editor id="description" v-model="card.description"></vue-editor>
		<button @click="changeDescription" class="btn-sm btn-gradient-info mt-1 ml-1">Save</button>
		<button @click="onEdit = false" class="btn-sm btn-gradient-secondary mt-1">Cancel</button>
    </div>
  </div>
</template>
<style>
.form-group {
  /* border: transparent; */
  border: 1px solid rgb(78, 188, 238);
}
#content-description{
	cursor: pointer;
}
#content-description:hover{
	background-color: rgb(221, 216, 216);
}
#description:focus {
  border: 2px solid #2d58f7;
}
</style>
<script >
import { VueEditor, Quill } from "vue2-editor";
export default {
  props: ["card"],
  data() {
    return {
      onEdit: false,
    };
  },
  methods: {
    changeDescription() {
      axios.post("changeDescription/" + this.card._id, {
        description: this.card.description,
      }).then(Response =>{
		  this.onEdit = false;
	  });
    },
    edit() {
      if (!this.onEdit) {
        this.onEdit = true;	
      }
    },
  },
};
</script>
<!-- <TextareaAutosize
        class="col-lg-12 form-group"
        v-model="card.description"
        ref="description"
        v-on:click="switchAndFocus()"
        @change="changeDescription"
        :min-height="10"
        :max-height="350"
      /> -->