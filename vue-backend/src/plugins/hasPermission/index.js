const hasPermission = {
    install (Vue){
        Vue.directive('hasPermission', {
            bind(el, binding, vnode){
                var result = false
                let permissionList = vnode.context.$route.meta.permission
              
                if(!permissionList){
                    console.error(`权限判断不生效。因路由中不包含permission字段，请检查路由表设置。当前路由地址：${vnode.context.$route.path}`)
                    return
                }
                if(typeof (permissionList) != "object" || !permissionList.length){
                    console.error(`权限判断不生效。因路由中permission字段非数组类型或内容为空，请检查路由表设置。当前路由地址：${vnode.context.$route.path}`)
                    return
                }
                // if(!permissionList.includes(binding.value)){
                //     el.parentNode.removeChild(el)  //把这个按钮移除掉
                // }

                if(permissionList.length>=0){
                  for(var item of permissionList){
                    if(item.rule==binding.value && item.isShow==true){
                      result =true;
                    }
                  }
                }

              if(!result){
                 el.parentNode.removeChild(el)
              }

            }
        })
    }
}

export default hasPermission
