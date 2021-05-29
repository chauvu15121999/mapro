import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import homePages from './components/home/home'
import board from './components/board/board'
import user from './components/user/user'
import profile from './components/user/profile'
import activity from './components/user/activity'
import boardOfUSer from './components/user/board'
import list from './components/user/list'
import card from './components/user/card'
const User = {
	template: '<div style="margin-top:500px;">User</div>'
}
export default new Router({
	mode: 'history',
	base: '/',
	routes: [ // bao gồm danh sách route
	{
		path: '/', ///path của route
		name: 'home', // tên route
		component: homePages // component route sử dụng
	},
	{
		  path: '/b/:id_board',
		  name: 'board',
		  component: board,
	},
	{
		path: '/user',
		name: 'user',
		component: user,
		children: [
			{
				path: 'profile',
				name: 'profile',
				component: profile,
			},
			{
				path: 'activity',
				name: 'activity',
				component: activity,
			},
			{
				path: 'boards',
				name: 'boardOfUSer',
				component: boardOfUSer,
			},
			{
				path: 'boards/lists/:id_board',
				name: 'list',
				component: list,
			},
			{
				path: 'boards/card/:id_board/:id_list',
				name: 'card',
				component: card,
			},
		]
	},
	]
  })