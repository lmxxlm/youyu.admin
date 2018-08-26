<template>
	<div class="sys-page">
		<app-title :title='curTitle'></app-title>
		<!--标题-->
		<!-- 搜索 -->
			 <app-search>
					 <el-form :inline="true" :model="searchForm" class="customerSearch">
						 <el-row>
						  <el-col :span="12">
									<el-form-item>
									 <el-date-picker
										 v-model="month"
										 type="month"
										 placeholder="选择月"
										 format="yyyy-MM"
										 value-format="yyyy-MM"
										 >
									 </el-date-picker>
									</el-form-item>
									<el-form-item >
										 <el-button type="primary" @click="update()" >刷新</el-button>
									</el-form-item>
									<el-form-item >
										 <el-button type="primary" @click="handleDownload()" >导出</el-button>
									</el-form-item>
						  </el-col>
						  <el-col :span="12">
								<el-form-item prop="pattern">
										<el-select v-model="searchForm.pattern"  clearable placeholder="选择统计">
										 <el-option
											 v-for="item in searchForm.patterns"
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
											<el-button type="primary" @click="search" >查询</el-button>
									</el-form-item>
						  </el-col>
						</el-row>
					 </el-form>
			 </app-search>
				 <!-- 表格体 -->
			 <table-mixin >
					 <el-table v-loading="tableData.loading" :data="tableData.body" border height="250">
							 <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
					 </el-table>
					 <el-table v-loading="tableData.loading" :data="tableData1.body" border >
							<el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
					</el-table>
			 </table-mixin>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,getNowFormatDate ,exportExcel,comReqGet} from '@/util/comRequest'
	import { projectCount} from '@/util/comData'
	export default{
		name:'const',
		data(){
			return projectCount();
		},
		computed:{
			...mapState({
          currentPage: state => state.pagination.currentPage,
          sizePage: state => state.pagination.sizePage,
      }),
			setTime(){
				return this.searchForm.setTime
			},

		},
		watch:{
				setTime(newVal,oldVal){
					this.searchForm.time_begin=newVal[0]
					this.searchForm.time_end=newVal[1]
				}
		},
		created() {
	        this.getTableData()
					//初始化一下时间
					this.searchForm.setTime[0]=getNowFormatDate(0)
					this.searchForm.setTime[1]=getNowFormatDate(30)
					this.searchForm.time_begin=getNowFormatDate(0)
					this.searchForm.time_end=getNowFormatDate(30)
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			 //刷新
			 update(){
				 commonReq('/admin/capital/freshProjectCount',{date:this.month}).then(res=>{
					 if(res.errcode==0){
						 this.$message.success(res.msg)
						 location.reload()
					 }else{
						 this.$message.error('刷新失败');
					 }
				 })
			 },
       getTableData() {
					 this.$axios({
					 		url: '/admin/capital/projectCount',
					 		method: 'post',
					 		data:this.searchForm
					 }).then(res => {
						 console.log(res,'当下的结果================')
					 		if(res.errcode==0){
					 				 this.tableData.loading = false
									 this.tableData.body=res.data.res
									 this.tableData1.body=[]
									 this.tableData1.body.push(res.data.sum)
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
