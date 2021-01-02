<template>
	<div class="description">
		<div class="col-12">
            <h4 style="font-weight: blod;"><i class="mdi mdi-format-line-weight"></i> Desription</h4>
        </div>
        <div class="col-12">
              <TextareaAutosize
                class=" col-lg-12 form-group"
                v-model="card.description"
                @change="changeDescription" 
                :min-height="10"
                :max-height="350"
               />
     	</div>	
	</div>
</template>
<style>
	.form-group{
		border: transparent;
	}
	.form-group:focus {
		border: 2px solid #2D58F7;
	}
</style>
<script >

	 export default {
	 	props: ['card'],
	 	data(){
	 		return{

	 		}
	 	},
	 	updated(){
	 		this.changeDescription();
	 	},
	 	methods: {
			changeDescription(){
				axios.post('changeDescription/'+this.card._id,{
					description: this.card.description,
				});
			}
	 	}
	 }

</script>