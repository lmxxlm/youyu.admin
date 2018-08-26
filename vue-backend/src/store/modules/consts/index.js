import Cookies from 'js-cookie'

const state = {
    title:'',	//标题
    name:'',//tab标签的名字
    id:'',
    meta:{}, //路由的meta的属性
    localType:'',
}

const mutations = {
	setParams: (state, item) => {
		state.title = item.title
    state.name = item.name
    state.id = item.id
    state.meta = item.meta
    if(item){
      localStorage.setItem(item.localType,JSON.stringify(state))
    }
	}
}

export default {
    namespaced: true,
    state,
    mutations
}
