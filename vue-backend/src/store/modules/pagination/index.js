import Cookies from 'js-cookie'

const state = {
	//当前页
    currentPage:0,
    //每页多少条
    sizePage:10,
}

const mutations = {
	//当前页
	setCurrentPage: (state, data) => {
		state.currentPage = data
	},
	setSizePage: (state, data) => {
		state.sizePage = data
	},
}

export default {
    namespaced: true,
    state,
    mutations
}