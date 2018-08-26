<template>
	<div class="sys-page">
		<app-title :title='curTitle'></app-title>
		<!--标题-->
		<el-tabs v-model="activeName" type="card" @tab-click="handleClick">
	    <el-tab-pane label="全部" name="first">
				<!-- 搜索 -->
					 <app-search>
							 <el-form :inline="true" :model="searchForm" class="customerSearch">
								 <el-form-item prop="user_id">
										 <el-input v-model="searchForm.user_id" placeholder="用户ID"></el-input>
								 </el-form-item>
								 <el-form-item prop="username">
										 <el-input v-model="searchForm.username" placeholder="手机号"></el-input>
								 </el-form-item>
								 <el-form-item prop="money">
										 <el-input v-model="searchForm.money" placeholder="红包金额"></el-input>
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
									<el-form-item>
										<el-select v-model="searchForm.use_together"  clearable placeholder="类型">
										 <el-option
											 v-for="item in searchForm.types"
											 :key="item.type"
											 :label="item.val"
											 :value="item.type">
										 </el-option>
									 </el-select>
									</el-form-item>
									<el-form-item prop="reward_name">
										<el-select v-model="searchForm.reward_name"  clearable placeholder="快速检索来源" @click="getFastSource">
										 <el-option
											 v-for="item in searchForm.sources"
											 :key="item.reward_name"
											 :label="item.reward_name"
											 :value="item.reward_name">
										 </el-option>
									 </el-select>
									</el-form-item>
								  <el-form-item prop="use_type">
										<el-select v-model="searchForm.use_type"  clearable placeholder="全部状态">
										 <el-option
											 v-for="item in searchForm.status"
											 :key="item.type"
											 :label="item.val"
											 :value="item.type">
										 </el-option>
									 </el-select>
								  </el-form-item>
									 <el-form-item >
											 <el-button type="primary" @click="search" >查询</el-button>
									 </el-form-item>
									 <el-form-item >
											 <el-button type="primary" @click="handleDownload()" v-hasPermission="'capital.reward.export'">导出</el-button>
									 </el-form-item>
							 </el-form>
					 </app-search>
						 <!-- 表格体 -->
					 <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
							 <el-table v-loading="tableData.loading" :data="tableData.body" border >
									 <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
							 </el-table>
							 <p class="sum">汇总： <span>人数：{{pNum}}</span> <span class="money">金额：{{money}}</span>	</p>
					 </table-mixin>
	    </el-tab-pane>
	    <el-tab-pane label="统计" name="second" v-hasPermission="'capital.reward.count'">
				<el-collapse v-model="activeNames" @change="handleChange">
					  <el-collapse-item :title="index=='all'?'全部红包':(index=='active'?'活动红包':'邀请红包')" :name="index" v-for="(item,index) in counts">
					       <table border="1" class="smallTable">
					       	  <tr>
					       	  	<th>分类</th>
											<th>金额</th>
					       	  </tr>
										<tr v-for="(itemchild,childIndex) in item">
											<td>{{childIndex=='all'?'未使用':(childIndex=='used'?'已使用':(childIndex=='unused'?'未使用':'已过期'))}}</td>
											<td>{{itemchild}}</td>
										</tr>
					       </table>
					  </el-collapse-item>
					</el-collapse>
	    </el-tab-pane>
	  </el-tabs>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,getNowFormatDate,exportExcel} from '@/util/comRequest'
	import { rewardData} from '@/util/comData'
	export default{
		name:'const',
		data(){
			return rewardData()
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
	        this.getTableData()
					this.getFastSource();
					//初始化一下时间
					this.searchForm.setTime[0]=getNowFormatDate(0)
					this.searchForm.setTime[1]=getNowFormatDate(30)
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			 //导出表格
			 handleDownload() {
				 var that = this
				 var url='/admin/capital/export'
				 var data = this.searchForm
				 exportExcel(that,url,data)//导出的结果
			 },
			 //点击折叠面板
			 handleChange(val) {
	        console.log(val);
	      },
			 //获得红包统计
			 getCount(){
				 this.$axios({
						url: '/admin/capital/count	',
						method: 'post',
						data:{}
				 }).then(res => {
						if(res.errcode==0){
							this.counts = res.data;
						}
				 })
			 },
			 //点击tab动态显示值
			 handleClick(tab, event) {
				 if(this.activeName=='second'){
					 this.getCount()
				 }
			 },
       getTableData() {
					 this.$axios({
					 		url: '/admin/capital/getAllReward',
					 		method: 'post',
					 		data:this.searchForm
					 }).then(res => {
					 		if(res.errcode==0){
					 				 this.tableData.loading = false
					 				 this.tableData.body=res.data.res
					 				 this.searchForm.total=parseInt(res.data.count)
									 this.pNum=res.data.sum.countUsers
									 this.money=res.data.sum.countMoney
					 		}
					 })
       },
			 //得到快速来源列表
			 getFastSource(){
				 this.$axios({
							url: '/admin/capital/getRewardType',
							method: 'get',
							data: ''
					}).then(res => {
						if(res.errcode == 0){
						  this.searchForm.sources=res.data
							this.$message.success(res.data); //这是一个  简单的弹出提示
						}else{
							this.$message.error(res.data);
						}
					})
			 },

			//搜索
	  	search(){
				  console.log(this.searchForm,'搜索参数==================')
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
