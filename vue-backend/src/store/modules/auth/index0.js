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

            // 重新登录时校验Token是否存在，若不存在则获取
            /*if(!token){
                dispatch("getNewToken").then(() => {
                    commit('setToken', state.token)
                })
            } else {
                commit('setToken', token)
            }*/
            // 刷新/关闭浏览器再进入时获取用户名
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
          //弄个新的所有的侧边栏的数组
           getSystemRules().then((res)=>{
               var navList = res
                function flatNavList(arr){
     	                for(let v of arr){
                        v.path='/components/'+v.rule.split('.').join('/')
                        if(v.child && v.child.length){

                          if(v.rule && v.rule.split('.').length==2){
                             v.meta = v.child  //先赋值  再删掉
                             delete v.child
                          }else{
                             flatNavList(v.child)
                          }
                        }
     	                }
     	            }
     	            flatNavList(navList)
                  commit("setNavList", navList)
                  resolve(navList)

                  console.log(navList,'侧边栏列表')
                  console.log(res,'原始列表==============================================')
                  getUserRules().then((res)=>{
                        console.log(res,'全部的权限======================')
                  })
          })




        	// axios({
          //       url: '/admin/menu/getmenu',
          //       methods: 'get',
          //       data: {}
          //   }).then((res) => {
	        //     var home = {"menu_id":"1","menu":"首页","parent_id":"0","url":"home","rule":"system","orderby":"1","icon":"cog","state":"1"}
          //     var manager = {"menu_id":"1","menu":"管理员","parent_id":"0","url":"system/manager","rule":"system","orderby":"1","icon":"cog","state":"1"}
          //     var cust = {"menu_id":"1","menu":"客户列表","parent_id":"0","url":"system/customer","rule":"system","orderby":"1","icon":"cog","state":"1"}
          //     var managerType = {"menu_id":"1","menu":"角色设置","parent_id":"0","url":"system/managerType","rule":"system","orderby":"1","icon":"cog","state":"1"}
          //   res.unshift(home);
          //   res[1].child.push(manager)
          //   res[1].child.push(cust)
          //   res[1].child.push(managerType)
          //   console.log(res,'===========获取侧边栏的结果=====')
	        // 	function flatNavList(arr){
	        //         for(let v of arr){
	        //         	 v.name=v.menu
	        //              v.path='/components/'+v.url
	        //             if(v.child && v.child.length){
	        //                 flatNavList(v.child)
	        //             }
	        //         }
	        //     }
	        //     flatNavList(res)
	        // 	commit("setNavList", res)
	        //     resolve(res)
          //   })
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
