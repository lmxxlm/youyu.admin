<template>
	<div class="sys-page">
		<!--标题-->

		<app-title :title='curTitle'></app-title>
		<!-- 搜索 -->
			 <app-search>
					 <el-form :inline="true" :model="searchForm" class="customerSearch">
						 <el-form-item prop="apr">
								 <el-input v-model="searchForm.apr" placeholder="利率"></el-input>
						 </el-form-item>
						 <el-form-item>
							 <el-select v-model="searchForm.borrow_type"  clearable placeholder="标类型">
								<el-option
									v-for="item in searchForm.types"
									:key="item.id"
									:label="item.name"
									:value="item.id">
								</el-option>
							</el-select>
						 </el-form-item>
						 <el-form-item prop="is_use">
							 <el-select v-model="searchForm.real_status"  clearable placeholder="全部状态">
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
			 </table-mixin>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,getNowFormatDate ,exportExcel,comReqGet} from '@/util/comRequest'
	import { loanList} from '@/util/comData'
	export default{
		name:'const',
		data(){
			return loanList();
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
					this.searchForm.success_time_begin=newVal[0]
					this.searchForm.success_time_end=newVal[1]
				},
				useTime(newVal,oldVal){
					this.searchForm.repay_time_begin=newVal[0]
					this.searchForm.repay_time_end=newVal[1]
				}

		},
		created() {
		    	this.getTypes();//获取所有的标类型
	        this.getTableData()
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
					 		url: '/admin/capital/loanList',
					 		method: 'post',
					 		data:this.searchForm
					 }).then(res => {
					 		if(res.errcode==0){
					 				 this.tableData.loading = false
									 this.tableData.body=res.data.res
					 				 this.searchForm.total=parseInt(res.data.count)
									 var flag=false;
									 for(var item in res.data.sum ){
										 if(item!=null){
											  flag=true;
												break;
										 }
									 }
									 if(flag){
										 res.data.sum.name='汇总'
										 this.tableData.body.push(res.data.sum)
									 }
					 		}
					 })
       },
			 //获取所有的标类型
			 getTypes(){
				 comReqGet('/admin/capital/borrowType').then(res=>{
					  if(res.errcode == 0){
							this.searchForm.types=res.data
						}
				 })
			 },
			 //导出表格
				handleDownload() {
					var that = this
					var url='/admin/capital/loanExport'
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
