<template>
	<div class="sys-page">
		<app-title :title='curTitle'></app-title>
		<!--标题-->
		<!-- 搜索 -->
			 <app-search>
					 <el-form :inline="true" :model="searchForm" class="customerSearch">
						 <el-form-item prop="user_id">
								 <el-input v-model="searchForm.user_id" placeholder="用户ID"></el-input>
						 </el-form-item>
						 <el-form-item prop="username">
								<el-input v-model="searchForm.username" placeholder="手机号"></el-input>
						</el-form-item>
						<el-form-item prop="coupon">
							 <el-input v-model="searchForm.coupon" placeholder="年华券利率"></el-input>
					 </el-form-item>
					 <el-form-item prop="is_use">
						 <el-select v-model="searchForm.is_use"  clearable placeholder="全部状态">
							<el-option
								v-for="item in searchForm.status"
								:key="item.type"
								:label="item.val"
								:value="item.type">
							</el-option>
						</el-select>
					 </el-form-item>
					 <el-form-item >
								<el-date-picker
									 v-model="searchForm.setTime"
									 type="daterange"
									 range-separator="至"
									 start-placeholder="获取起始日期"
									 end-placeholder="获取截止日期"
									 format="yyyy-MM-dd"
									 value-format="yyyy-MM-dd">
								 </el-date-picker>
						</el-form-item>
						<el-form-item >
								 <el-date-picker
										v-model="searchForm.useTime"
										type="daterange"
										range-separator="至"
										start-placeholder="使用开始日期"
										end-placeholder="使用截止日期"
										format="yyyy-MM-dd"
										value-format="yyyy-MM-dd">
									</el-date-picker>
						 </el-form-item>
						 <el-form-item prop="coupon_name">
							 <el-select v-model="searchForm.coupon_name"  clearable placeholder="快速检索来源" @click="getFastSource">
								<el-option
									v-for="item in searchForm.sources"
									:key="item.coupon_name"
									:label="item.coupon_name"
									:value="item.coupon_name">
								</el-option>
							</el-select>
						 </el-form-item>
						 <el-form-item prop="tender_id">
								<el-input v-model="searchForm.tender_id" placeholder="投资ID"></el-input>
						</el-form-item>
						 <el-form-item prop="borrow_id">
								 <el-input v-model="searchForm.borrow_id" placeholder="标ID"></el-input>
						 </el-form-item>
	            <el-form-item>
								<el-select v-model="searchForm.borrow_type"  clearable placeholder="类型">
								 <el-option
									 v-for="item in searchForm.types"
									 :key="item.id"
									 :label="item.name"
									 :value="item.id">
								 </el-option>
							 </el-select>
							</el-form-item>
						 <el-form-item >
								 <el-button type="primary" @click="search" >查询</el-button>
						 </el-form-item>
						 <el-form-item >
								 <el-button type="primary" @click="handleDownload()" >导出</el-button>
						 </el-form-item>
					 </el-form>
			 </app-search>
				 <!-- 表格体 -->
			 <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
					 <el-table v-loading="tableData.loading" :data="tableData.body" border >
							 <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
					 </el-table>
					 <div class="sum"> <div class="left" style="float:left">汇总：<span>人数：{{pNum}}</span> <span class="money">{{money}}</span>
					 </div>	 <div style="float:right"><span>{{invest_count}}</span><span  class="money">{{achive_count}}</span></div> </div>
			 </table-mixin>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,getNowFormatDate ,exportExcel,comReqGet} from '@/util/comRequest'
	import { userCouponList} from '@/util/comData'
	export default{
		name:'const',
		data(){
			return userCouponList();
		},
		computed:{
			...mapState({
          currentPage: state => state.pagination.currentPage,
          sizePage: state => state.pagination.sizePage,
      }),
			setTime(){
				return this.searchForm.setTime
			},
			useTime(){
				return this.searchForm.useTime
			}
		},
		watch:{
				setTime(newVal,oldVal){
					this.searchForm.get_time_begin=newVal[0]
					this.searchForm.get_time_end=newVal[1]
				},
				useTime(newVal,oldVal){
					this.searchForm.use_time_begin=newVal[0]
					this.searchForm.use_time_end=newVal[1]
				}

		},
		created() {
		    	this.getTypes();//获取所有的标类型
	        this.getTableData()
					this.getFastSource();
					//初始化一下时间
					this.searchForm.useTime[0]=getNowFormatDate(0)
					this.searchForm.useTime[1]=getNowFormatDate(30)
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
       getTableData() {
					 this.$axios({
					 		url: '/admin/capital/userCouponList',
					 		method: 'post',
					 		data:this.searchForm
					 }).then(res => {
					 		if(res.errcode==0){
					 				 this.tableData.loading = false
									 var result= this.setRes(res.data.res)

									 this.tableData.body=result

                   console.log(result,'当下的结果================')
					 				 this.searchForm.total=parseInt(res.data.count)
									 this.pNum=res.data.sum.count_users
									 this.money=res.data.sum.tender_moneys
									 this.invest_count = res.data.sum.coupon_capitals
									 this.achive_count = res.data.sum.performances


					 		}
					 })
       },
			 setRes(result){
				 for(var i=0;i<result.length;i++){
						 if(result[i].borrow_limit.length>0){
							 for(var item in result[i].borrow_limit){
								 for(var type of this.searchForm.types){
									 if(type.id==result[i].borrow_limit[item]){
										 result[i].borrow_limit[item]=type.name+','
										 console.log(result[i].borrow_limit[item])
									 }
								 }
							 }
						 }
				 }
				 return result
			 },
			 //获取所有的标类型
			 getTypes(){
				 comReqGet('/admin/capital/borrowType').then(res=>{
					  if(res.errcode == 0){
							this.searchForm.types=res.data
						}
				 })
			 },
			 //得到快速来源列表
			 getFastSource(){
				 comReqGet('/admin/capital/getCouponType').then(res=>{
					  if(res.errcode == 0){
							this.searchForm.sources=res.data
						}
				 })
			 },
			 //导出表格
				handleDownload() {
					var that = this
					var url='/admin/capital/rewardUseExport'
					var data = this.searchForm
					exportExcel(that,url,data)//导出的结果
				},
			//搜索
	  	search(){
	  	    this.getTableData()
      },
			//获取一页多少条
			getPageSize(val){
				this.searchForm.size=val
				this.getTableData()
			},
			//获取当前多少页
			getCurrentPage(val){
				this.searchForm.page=val
				this.getTableData()
			},
		}

	}
</script>

<style >
    .customerSearch .el-select {width:130px;}
		.customerSearch .input-with-select .el-input-group_prepend{background-color:#fff;	}
		.smallTable{width:100%;}
		.smallTable td{padding-left:10px;}
		.smallTable th{font-weight:bold;}
		.smallTable tr:nth-child(even){background:#e3e3e3;}


		.sum {padding:20px 10px;}
		.sum .money{margin-left:100px;}
</style>
