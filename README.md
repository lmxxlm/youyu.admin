# About
  此项目是 vue + element - ui 构建的后台管理系统

## 技术栈
   vue2 + vuex + vue-router + webpack + ES6/7 + less +element-ui
 
## 功能列表
  登陆/注销 -- 完成 
  添加用户 -- 完成
  数据展示 -- 完成
  管理用户 -- 完成
  权限验证 -- 完成
  数据导出 -- 完成
  管理员设置 -- 完成
  富文本编辑器 -- 完成
  
## 主要功能实现
   接口权限控制：通过axios进行简单的设置，增加请求拦截，为每个请求的Header信息中增加Token
   页面级访问权限控制：前端根据路由进行设置，增加permission权限，来判断该页面是否显示
   数据级别操作权限控制：根据全局自定义指令来判断页面级
   
## 部分截图
  ![Image text](https://github.com/lmxxlm/youyu.admin/blob/master/vue-backend/static/img/1%20(1).jpg) 
  ![Image text](https://github.com/lmxxlm/youyu.admin/blob/master/vue-backend/static/img/1%20(1).png) 
  ![Image text](https://github.com/lmxxlm/youyu.admin/blob/master/vue-backend/static/img/1%20(2).jpg) 
  ![Image text](https://github.com/lmxxlm/youyu.admin/blob/master/vue-backend/static/img/1%20(2).png) 
  ![Image text](https://github.com/lmxxlm/youyu.admin/blob/master/vue-backend/static/img/1%20(3).jpg) 
  ![Image text](https://github.com/lmxxlm/youyu.admin/blob/master/vue-backend/static/img/1%20(3).png) 
  ![Image text](https://github.com/lmxxlm/youyu.admin/blob/master/vue-backend/static/img/1%20(4).jpg) 
   
# 管理后台接口文档

## 【notice】每个post请求都需要post token 参数

>## 新增数据表
>* dw_user_token-admin
>* dw_menu
>* dw_rule

>### /admin/login/login			【登录接口】
>
>* params	【username,password,captcha】

>### /admin/login/verify 		【登录验证码接口】
>* method	【get】
>* response		{"url":"http:\/\/admin.youyu.com\/public\/captcha\/1531723649.25.jpg"}

>### /admin/menu/getmenu		【获取菜单】
>* response		[{"menu_id":"1","menu":"系统设置","parent_id":"0","url":"","rule":"system","orderby":"1","icon":"cog","state":"1","child":[{"menu_id":"4","menu":"常量配置","parent_id":"1","url":"system\/const","rule":"system.const","orderby":"1","icon":"cog","state":"1","child":[]},{"menu_id":"2","menu":"系统参数","parent_id":"1","url":"system\/params","rule":"system.params","orderby":"2","icon":"cog","state":"1","child":[]}]}]

>### /admin/system/params 		【系统参数列表】
>* params	【page,search,size】
>* response		
{"errcode":0,"data":{"count":86,"params":[{"id":"1","name":"网站名称1","nid":"ssssfsd","value":"ddd","type":"0","style":"1","status":"0"},{"id":"2","name":"网站关闭信息","nid":"con_closemsg","value":"系统维护,维护期间给用户带来不便，请谅解，如有问题请拨打全国统一客服热线：0571-81112615","type":"2","style":"1","status":"0"},{"id":"3","name":"网站名称","nid":"con_webname","value":"有余金服","type":"0","style":"1","status":"0"}]}}

>### /admin/system/add			【新增系统参数】
>* params	【name,nid,value,type,style,status】
>* response
	>>success:{
				errcode: 0,
				msg: "添加成功"
			}
	>> error:{
				errcode: 1,
				msg: {
						name: "系统参数名称不能为空.",
						nid: "nid不能为空.",
						value: "value不能为空.",
						type: "输入类型不能为空.",
						style: "参数分组不能为空.",
						status: "状态不能为空."
				}
			}

>### /admin/system/edit			【系统参数编辑页面数据】
>* params	【id】

>### /admin/system/update		【系统参数修改】
>* method 	【post】
>* params	【id,name,nid,value,type,style,status】

>### /admin/system/delete		【系统参数删除】
>* params	【id】

>### /admin/consts/constsType	【系统常量类型列表】
>* params	【page,search,size】

>### /admin/consts/typeAdd		【系统常量类型新增】
>* params	【name,nid,order】

>### /admin/consts/typeEdit		【系统常量类型编辑页面】
>* params	【id】

>### /admin/consts/typeUpdate	【系统常量类型修改】
>* params	【id,name,nid,order】

>### /admin/consts/constsList	【系统常量类型下的常量列表】
>* params	【id,page,search,size】

>### /admin/consts/constAdd		【常量新增】
>* params	【status,order,type_id,pid,name,value】

>### /admin/consts/constEdit	【常量编辑】
>* params	【id】

>### /admin/consts/constUpdate	【常量新增】
>* params	【id,status,order,type_id,pid,name,value】

>### /admin/consts/constDel		【常量删除】
>* params	【id】

>### /admin/manager/lists		【管理员列表】
>* params	【search,page,size】

>### /admin/manager/managerAdd	【新增管理员】
>* params	【username,password,realname,type_id,status,email】

>### /admin/manager/managerEdit	【管理员编辑页面】
>* params	【id】

>### /admin/manager/managerUpdate	【管理员修改】
>* params	【id,username,password,realname,type_id,status,email】

>### /admin/role/getAllRole		【获取用户类型列表all】
>* method	【get】

>### /admin/role/roleList		【获取用户类型列表分页】
>* params	【search,page,size】

>### /admin/role/roleAdd	【新增角色】
>* params	【name,status,summary,remark,rule】:rule为权限数组参数

>### /admin/role/roleEdit	【编辑角色】
>* params	【id】

>### /admin/role/roleUpdate	【修改角色】
>* params	【id,name,status,summary,remark,rule】:rule为权限数组参数

>### /admin/role/roleDel	【删除角色】
>* params	【id】

>### /admin/rule/getUserRules		【获取用户权限】

>### /admin/rule/getSystemRules		【权限配置所需权限数据】

>### /admin/cache/cacheDel		【缓存清理】
>* params	【name】

>### /admin/picture/lists	【图片列表】
>* params	【search,page,size】

>### /admin/picture/picType	【图片类型】


>### /admin/customer/lists	【客户列表】
>* params	【page,size,type[搜索类型:user_id,username,realname,invite_userid],search[搜索内容],start_time,end_time,st[0,1,2],bank[0,1,2]】

>### /admin/customer/export	【客户导出】
>* params	【type[搜索类型:user_id,username,realname,invite_userid],search[搜索内容],start_time,end_time,st[0,1,2],bank[0,1,2]】

>### /admin/customer/edit	【客户编辑】
>* params	【id】

>### /admin/customer/update	【客户修改】
>* params	【id,username,realname,card_id】

>### /admin/customer/del	【客户删除】
>* params	【ids】

>### /admin/customer/getInfoByUsername	【获取用户信息BY用户名】
>* params	【username】

>### /admin/customer/accountCount	【资金现状】

>### /admin/customer/accountRecord	【资金记录】
>* params	【page,size,money,id,type,start_time,end_time】

>### /admin/customer/accountLogType	【交易类型】

>### /admin/customer/checkVip	【VIP】

>### /admin/customer/company	【企业列表】
>* params	【page,size,username,realname,start_time,end_time,real_status['',0,1]】

>### /admin/customer/companyEdit		【企业编辑】
>* params	【id】

>### /admin/customer/companyUpdate		【企业修改】
>* params	【id,realname,company_workyear,company_worktime1,company_worktime2,private_employee,linkman1,relation1,tel1,company_reamrk,company_name,private_commerceid,province,city,area,address,others】

>### /admin/area/getAllArea		【获取省市区联动数据】

>### /admin/customer/downLists		【下线列表】
>* params	【page,size,type[搜索类型:username,realname,invite_username,invite_realname],search[搜索内容],start_time,end_time,st[0,1,2],bank[0,1,2]】

>###bankLists		【银行卡】
>### /admin/customer/bankLists		【银行卡】
>* params	【page,size,user_id,bank_status[0状态未定,1有效,2无效],bank_code,bank_account,username,realname】

>### /admin/customer/bankEdit		【银行卡编辑】
>* params	【id】

>### /admin/customer/bankUpdate		【银行卡修改】
>* params	【id,account】

>### /admin/customer/bankDel		【银行卡删除】
>* params	【id】

>### /admin/bank/banks		【获取银行列表】

>### /admin/capital/getAllReward	【红包列表】
>* params	【user_id,username,money,get_time_begin,get_time_end,use_time_begin,use_time_end,use_together,reward_name,use_type,page,size】

>### /admin/capital/export	【红包导出】
>* params	【user_id,username,money,get_time_begin,get_time_end,use_time_begin,use_time_end,use_together,reward_name,use_type】

>### /admin/capital/count	【红包统计】

>### /admin/capital/getRewardType	【红包名快速搜索】

>### /admin/capital/rewardUseList	【红包投资列表】
>* params	【user_id,borrow_id,tender_id,money,username,borrow_type[这个现在不弄],get_time_begin,get_time_end,use_time_begin,use_time_end,use_together,reward_name,,page,size】

>### /admin/capital/rewardUseExport	【红包投资列表导出】
>* params	【user_id,username,money,borrow_type[这个现在不弄],borrow_id,tender_id,get_time_begin,get_time_end,use_time_begin,use_time_end,use_together,reward_name】


>### /admin/capital/sendReward	【发送红包】
>* params	【use_together,username,money,timeout,reward_name,money_limit,borrow_days】

>### /admin/capital/sendRewardBatch	【批量发送红包】
>* params	【file:[name=file]】

>### /public/template/reward.zip	【批量发送红包模板】

>### /admin/capital/rechargeList	【充值列表】
>* params	【trade_no,username,realname,money,bank,recharge_time_begin,recharge_time_end,status,pay_type,page,size】
>* pay_type[{F:线下充值,M:手工返现,O:还款充值,R:其他}]

>### /admin/capital/rechargeAdd		【新增充值】
>* params	【username,pay_type,money,remark】

>### /admin/capital/rechargeExport	【充值导出】
>* params	【utrade_no,username,realname,money,bank,recharge_time_begin,recharge_time_end,status,pay_type】

>### /admin/capital/accountList		【资金账户列表】
>* params	【username,realname,condition,money,page,size】

>### /admin/capital/accountExport	【资金账户列表导出】
>* params	【username,realname,condition,money】

>### /admin/customer/getRecordCount	【资金账户月账单】
>* params	【id】

>### /admin/capital/userCouponList	【年化券列表】
>* params	【user_id,username,coupon,is_use,use_time_begin,use_time_end,get_time_bigin,get_time_end,coupon_name,tender_id,borrow_id,borrow_type,page,size】

>### /admin/capital/getCouponType	【年化券快速搜索】

>### /admin/capital/addUserCoupon	【新增年化券】
>* param	【username,coupon_name,coupon,timeout,money_minimun_limit,money_limit,borrow_days,borrow_limit】

>### /admin/capital/addUserCouponBatch	【批量新增年化券】
>* params	【file:[name=file]】

>### /public/template/coupon.zip	【批量新增年化券模板】

>### /admin/capital/borrowType		【标类型】

>### /admin/capital/borrowStyle		【还款方式】

>### /admin/capital/projectCount	【项目数据统计】
>* params	【pattern,time_begin,time_end】

>### /admin/capital/freshProjectCount	【项目数据更新】
>* params	【date:2018-08】

>### /admin/capital/rewardReport		【红包日报数据】
>* params	【time_begin,time_end】

>### /admin/capital/investList			【投资列表】
>* params	【username,money,invite_userid,name,time_begin.time_end,page,size】

>### /admin/capital/loanList			【贷款资金】
>* params	【apr:利率,borrow_type:表类型,real_status：全部状态,success_time_begin,success_time_end：开标时间,repay_time_begin：还款时间,repay_time_end,page,size】

>### /admin/capital/loanExport			【贷款资金导出】
>* params	【apr:利率,borrow_type:表类型,real_status：全部状态,success_time_begin,success_time_end：开标时间,repay_time_begin：还款时间,repay_time_end】

>### /admin/capital/rechargeCount		【充值资金】
>* params	【time_begin,time_end】

>### /admin/capital/moneyReport			【资金日报】
>* params	【time_begin,time_end】

>### /admin/capital/overview			【资金总览】

>### /admin/withdraw/withdrawList		【提现列表】
>* params	【username,realname,withdraw_no,type[>=,=,<=],money,add_time_begin,add_time_end,verify_time_begin,verify_time_end,status.sina_st,page,size】

>### /admin/withdraw/cashLook			【提现查看】
>* params	【id】

>### /admin/withdraw/cashCheck			【提现审核】
>* params	【id,status,verify_remark】

>### /admin/borrow/borrowList			【借款列表】
>* params	【username,name,real_state,money_begin,money_end,time_limit_begin,time_limit_end,apr,page,size】

>### /admin/borrow/borrowListExport		【借款列表导出】
>* params	【username,name,real_state,money_begin,money_end,time_limit_begin,time_limit_end,apr】

>### /admin/borrow/borrowAdd			【新增借款】
>* params	【username,borrow_type,new_hand,style,account,use,original_rate,discount_rate,lowest_account,most_account,tender_user,invite_user,contract_no,pawn,start_time,time_limit_day,name,sign,content,files】

>### /admin/borrow/borrowEdit			【借款编辑】
>* params	【id】

>### /admin/borrow/borrowUpdate			【借款修改】
>* params	【id,username,borrow_type,new_hand,style,account,use,original_rate,discount_rate,lowest_account,most_account,tender_user,invite_user,contract_no,pawn,start_time,time_limit_day,name,sign,content,files】

>### /admin/borrow/borrowPicDel			【借款图片删除】
>* params	【id,index】

>### /admin/borrow/borrowCheck			【借款审核】
>* params	【id,real_state,recomend_flg,success_time,verify_remark】

>### /admin/borrow/borrowDel			【借款删除】
>* params	【id】

>### /admin/borrow/repayList			【还款计划】
>* params	【username,name,status】

>### /admin/borrow/repay				【还款】
>* params	【id】

>### /admin/borrow/updateContent		【标内容控制】
>* params	【id,name,borrow_type,success_time,status,content】

>### /admin/borrow/tenderList			【投资列表】
>* params	【tender_id,borrow_id,username,realname,name,time_start,time_end,status[1,2,3,4,5],type[>=,=,<=],money,page,size】

>### /admin/active/ActiveAdd			【发布公告】
>* params	【type_id[5001,5002],title,content】

>### /admin/active/ActiveList			【公告列表】
>* params	【title,time_start,time_end,type_id,page,size】

>### /admin/operate/sms					【验证码列表】
>* params	【mobile,is_type,trackid,page,size】

>### /admin/article/siteList			【站点列表】
>* params 	【site_id,code,nid,pid,name,page,size】

>### /admin/article/siteAdd				【站点新增】
>* params 	【code,name,nid,pid,rank,url,aurl,isurl,order,status,style,litpic,content,list_name,content_name,sitedir,visit_type,index_tpl,list_tpl,content_tpl,title,keywords,description】

>### /admin/article/siteEdit				【站点编辑】
>* params	【id】

>### /admin/article/siteUpdate				【站点修改】
>* params 	【id,code,name,nid,pid,rank,url,aurl,isurl,order,status,style,litpic,content,list_name,content_name,sitedir,visit_type,index_tpl,list_tpl,content_tpl,title,keywords,description】
