new Vue({
  el: '#app_home',
  components: {
  	'a-team': {
		template: '#a-team',
		props: {

		}
	}
  },
  data: {
  	showModal: false,
  },
  methods: {
  	handdleShowModal(){
  		this.showModal = true;
  	},
  	addTeam(){
  		alert('test')
  	}
  }
})