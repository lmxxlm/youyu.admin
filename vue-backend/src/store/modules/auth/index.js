import Cookies from 'js-cookie'
import axios from '@/util/ajax'
import Auth from '@/util/auth'
import { getSystemRules,getUserRules } from '@/util/comRequest'

const state = {
    token: '',
    navList: []
}

const mutations = {
    setNavList: (state, data) => {
        state.navList = data
    },

    setToken: (state, data) => {
        if(data){
            Auth.setToken(data)
            Auth.setLoginStatus()
        } else {
            Auth.removeToken()
            Auth.removeLoginStatus()
        }
        state.token = data
    }
}

const actions = {
	//获取验证码
	getCode() {
		return new Promise((resolve) => {
			  axios({
                url: '/admin/login/verify',
                method: 'get',
                data: {},
            }).then(res => {
                resolve(res)
            })
		})
	},
    // 邮箱登录
    loginByEmail({ commit }, userInfo) {
        return new Promise((resolve) => {
            axios({
                url: '/admin/login/login',
                method: 'post',
                data: {
                    ...userInfo
                }
            }).then(res => {
               if(res.errcode==0){
               	 //commit('setToken', Cookies.get('token'))
                 commit('user/setName', res.data.username, { root: true })
               }
               resolve(res)
            })
        });
    },

    // 登出
    logout({commit}) {
        return new Promise((resolve) => {
            commit('setToken', '')
            commit('user/setName', '', { root: true })
            commit('tagNav/removeTagNav', '', {root: true})//移除token
            resolve()
        })
    },

    // 重新获取用户信息及Token
    // TODO: 这里不需要提供用户名和密码，实际中请根据接口自行修改
    relogin({dispatch, commit, state}){
        return new Promise((resolve) => {
            // 根据Token进行重新登录
            let token = Cookies.get('token'),
                userName = Cookies.get('userName')

                if(token == undefined){
                	  this.$router.push('/login');
                }
            commit('user/setName', decodeURIComponent(userName), { root: true })
            resolve()
        })
    },

    // 获取新Token
    getNewToken({commit, state}){
        return new Promise((resolve) => {
             var token = Cookies.get('token')
             if(token != undefined){//只有有token的时候才进项下一步
             	commit("setToken", token)
                resolve()
             }
             return false

        })
    },

    // 获取该用户的菜单列表
    getNavList({commit}){
        return new Promise((resolve) =>{
         getSystemRules().then((res)=>{ //所有的菜单列表都给一个isShow就好了
          getUserRules().then((result)=>{//获取当前用户的权限
             var home = {isShow:true,name:"首页",parent_id:"0",path:"/components/home",rule:"home"}
             result.push('home')
             res.unshift(home);
             var navList = res;
             function flatNavList(arr){
                   for(let v of arr){
                     v.isShow=false
                     v.path='/components/'+v.rule.split('.').join('/')
                     if(result.indexOf(v.rule)>-1){
                         v.isShow=true
                     }
                     if(v.child && v.child.length){
                         flatNavList(v.child)
                         if(v.rule.split('.').length==2){
                           v.permission=v.child
                           delete v.child
                         }
                     }
                   }
               }

              flatNavList(navList)
              commit("setNavList", navList)
              resolve(navList)
           })
         })

        })
    },

    // 将菜单列表扁平化形成权限列表
    getPermissionList({state}){
        return new Promise((resolve) =>{
            let permissionList = []
            // 将菜单数据扁平化为一级
            function flatNavList(arr){
                for(let v of arr){
                    if(v.child && v.child.length){
                        flatNavList(v.child)
                    } else{
                        permissionList.push(v)
                    }
                }
            }
            flatNavList(state.navList)
            resolve(permissionList)
        })
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}
