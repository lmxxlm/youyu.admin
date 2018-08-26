import Cookies from 'js-cookie'
import axios from '@/util/ajax'

//获取所有的权限
export function getSystemRules () {
    return new Promise((resolve) => {
        axios({
                url: '/admin/rule/getSystemRules',
                method: 'get',
                data: {},
            }).then(res => {
                resolve(res)
            })
    })
}

//获取当前用户的权限
export function getUserRules () {
    return new Promise((resolve) => {
        axios({
                url: '/admin/rule/getUserRules',
                method: 'get',
                data: {},
            }).then(res => {
                resolve(res)
            })
    })
}

//通用请求post
export function commonReq(url,params){
  return new Promise((resolve) => {
        axios({
            url: url,
            method: 'post',
            data:params,
        }).then(res => {
            resolve(res)
        })
  })
}
//通用请求get
export function comReqGet(url){
  return new Promise((resolve) => {
        axios({
            url: url,
            method: 'get',
            data:'',
        }).then(res => {
            resolve(res)
        })
  })
}

//复制
export function copy(text){
      var oInput = document.createElement('input');
      oInput.value = text;
      document.body.appendChild(oInput);
      oInput.select(); // 选择对象
      document.execCommand("Copy"); // 执行浏览器复制命令
      oInput.className = 'oInput';
      oInput.style.display='none';
      document.execCommand("copy");
}

//获取几天以后或者几天以前的年月日
export function getNowFormatDate(n) {
   var newData = new Date();
   var milliseconds=newData.getTime()+1000*60*60*24*n;
   var date = new Date(milliseconds);
   var seperator1 = "-";
   var seperator2 = ":";
   var month = date.getMonth() + 1;
   var strDate = date.getDate();
   if (month >= 1 && month <= 9) {
       month = "0" + month;
   }
   if (strDate >= 0 && strDate <= 9) {
       strDate = "0" + strDate;
   }
   var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate;
           // + " " + date.getHours() + seperator2 + date.getMinutes()
           // + seperator2 + date.getSeconds();
   return currentdate;
}

//格式化json
export function formatJson(filterVal, jsonData) {
 return jsonData.map(v => filterVal.map(j => {
   if (j === 'timestamp') {
     return parseTime(v[j])
   } else {
     return v[j]
   }
 }))
}

//导出表格
export function exportExcel(that,url,data) { //被用到的方法一般都放在上面
  commonReq(url,data).then(res=>{
    // console.log(res,'返回的结果=========')
    //  let data = new Blob([decodeURIComponent(encodeURI('\uFEFF'+res))],{type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'})
    // // let data = new Blob(['\uFEFF'+res],{type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'})
    //  let url = window.URL.createObjectURL(data)
		// let link = document.createElement('a')
		// link.style.display = 'none'
		// link.href = url
		// link.setAttribute('download', 'aaa.csv')
    //
		// document.body.appendChild(link)
		// link.click()

    if(res.errcode == 0){
      that.downloadLoading = true
       import('@/vendor/Export2Excel').then(excel => {
         const tHeader = res.data.headVal//标题  时间戳给有时间的选项
         const filterVal = res.data.headKey //标题的key
         const data = formatJson(filterVal, res.data.data)  //页面的table数据
         excel.export_json_to_excel({
           header: tHeader,
           data,
           filename: 'table-list'
         })
         that.downloadLoading = false
       })
    }else{
      that.$message.error('获取数据错误');
    }
  });
}


//跳到详情页
export function goDetail(that,url,data){
  that.$router.push({path:url});
  that.setParams(data)
  that.$store.commit("tagNav/addTagNav", {
      name: data.name,
      path: url,
      title: data.title
  })
}


//后台反悔报错验证信息
export function ruleFormError(that,res,name){
  var reName = ( name!=void 0) ? 'ruleForm'+name : 'ruleForm'
  var rules = ( name!=void 0) ? 'rules'+name : 'rules'
  for(var key in res.error){
      that[reName][key]=''
      if(that[rules][key]!= void 0){
        that[rules][key][0].message=res.error[key]
      }
  }
  that.$refs[reName].validate()
}


//显示头部的tab获取传过来的值
export function setTab(that,val){//val:是保存在vuex里的值
  var name = that.params.name
  var title = that.params.title
  var meta = that.params.meta
  if(!that.params.name){
      var obj=JSON.parse(localStorage.getItem(val));
      name = obj.name
      title = obj.title
      meta = obj.meta
  }

  // 如果需要缓存则必须使用组件自身的name，而不是router的name
  that.$router.getMatchedComponents()[1].name=name
  that.$route.meta.name=title
  that.$route.meta.permission=meta.permission
  that.searchForm.id=that.params.id?that.params.id:JSON.parse(localStorage.getItem(val)).id
}


//判断元素是否为空对象
export function isEmpty(obj){
  for(var prop in obj){
    if(obj.hasOwnProperty(prop)){
      return false;
    }
  }
  return true && JSON.stringify(obj) === JSON.stringify({})
}
//初始化表单===验证初始化和编辑初始化  都写出来
