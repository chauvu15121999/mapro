<template>
	<div class="description">
		<div  class="col-12">
            <h4 style="font-weight: blod;"><i class="mdi mdi-attachment"></i> file</h4>
        </div>
        <div  class="col-12">
        	<div v-for="file in files" :key="file.file_name" class="row mt-2 ml-2 file">
        		<div class="col-3 tyoe-file">
        			<!-- Xem file loại gì -->
        			<h4 class="mt-4 ml-4" v-if="file.type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'">Docx</h4>
        			<h4 class="mt-4 ml-4" v-if="file.type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation'">Pp</h4>
        			<h4 class="mt-4 ml-4" v-if="file.type ==  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ">Excel</h4>
        			<h4 class="mt-4 ml-4" v-if="file.type ==  'image/jpeg' ">Png</h4>
        		</div>
        		<div class="col-9">
        			<p class="mt-2" style="font-weight: blod;"> {{file.file_name}}</p>
        			<p>Added <span>{{file.time}}</span> <span v-on:click ="deleteFile(file)"><u>Delete</u></span>           <span ><a 
        			:href="'getDownload/' + file.file_save + '/' + file.file_name">Dowload</a></span>
        			</p>
        		</div>
        	</div>
        	<!-- <iframe src="https://docs.google.com/gview?url=http://remote.url.tld/path/to/document.doc&embedded=true"></iframe> -->
     	</div>	
	</div>
</template>
<style>
.tyoe-file{
	background: #DDDEE3;
}
.file{
		min-height: 80px;
		cursor: pointer;
}
.file:hover {
		background: #DDDEE3;
	}
</style>
<script >
	 export default {
	 	props: ['card','files','user','nofication','userReceinofication'],
		computed: {
			id_board() {
				return this.$route.params.id_board;
			}
		},
	 	methods: {
	 		deleteFile(data){
	 			if(confirm("Bạn chắc chắn muốn xóa file ?")){
		 			axios.post('api/deleteFile/'+this.card._id,{
		 				attachment: data,
						user: this.user,
						id_board: this.id_board,
						nofication: 'delete File'+ this.nofication,
						userReceive: this.userReceinofication
		 			}).then(response =>{
		 				this.$emit('handleDeleteFile');
		 			});
	 			}

	 		}
	 	}
	 }

</script>