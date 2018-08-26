<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="客户的资金现状"></app-title>
				<!-- 表格体 -->
			<table-mixin>
					<el-table v-loading="tableAcountData.loading" :data="tableAcountData.body" border >
							<el-table-column v-for="(item,index) in tableAcountData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
					</el-table>
			</table-mixin>
				<app-title title="客户的资金记录"></app-title>
				<app-search>
						<el-form :inline="true" :model="searchForm" class="customerSearch">
							<el-form-item label="金额" prop="money">
							 <el-input v-model="searchForm.money" ></el-input>
						 </el-form-item>
							<el-form-item >
								<el-select v-model="searchForm.type" clearable placeholder="请选择">
									<el-option
									 v-for="(item,key) in searchForm.options"
									 :key="item"
									 :label="item"
									 :value="item">
									</el-option>
								</el-select>
							</el-form-item>
							<el-form-item >
									 <el-date-picker
											v-model="searchForm.activeTime"
											type="daterange"
											range-separator="至"
											start-placeholder="开始日期"
											end-placeholder="结束日期"
											format="yyyy-MM-dd"
											value-format="yyyy-MM-dd">
										</el-date-picker>
							 </el-form-item>
								<el-form-item >
										<el-button type="primary" @click="search" >搜索</el-button>
								</el-form-item>
						</el-form>
				</app-search>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}" >
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
            </el-table>
        </table-mixin>

	</div>
</template>

<script>
	import { mapState } from 'vuex'
	import { commonReq ,setTab} from '@/util/comRequest'
	import { capitalData } from '@/util/comData'
	export default{
		name:'const',
		computed:{
			...mapState({
          params: state => state.consts
      }),
			activeTime(){
				return this.searchForm.activeTime
			}
		},
		watch:{
			activeTime(newVal,oldVal){
				this.searchForm.start_time=newVal[0]
				this.searchForm.end_time=newVal[1]
			}
		},
		data(){
			return capitalData()
		},
		created() {
        setTab(this,'capital')
        this.accountLogType()//获取全部的类型
				this.getAccountCount()
				this.getAccountRecord()
		},
		methods:{
			getPageSize(val){
				this.searchForm.size=val
				this.getTableData()
			},
			getCurrentPage(val){
				this.searchForm.page=val
				this.getTableData()
			},
			//获取全部交易类型
			accountLogType(){
				var url="/admin/customer/accountLogType"
				commonReq(url,'').then(res => {
					this.searchForm.options=res;
				})
			},
			//资金现状
			getAccountCount(){
				var url = '/admin/customer/accountCount'
				var data ={
					id:this.id
				}
      	this.getTableData(url,data,'accountCount')
			},
			//资金记录
			getAccountRecord(){
				var url = '/admin/customer/accountRecord'
				var data = this.searchForm
				this.getTableData(url,data,'accountRecord')
			},
			 // 获取table数据
       getTableData(url,data,type) {
				  commonReq(url,data).then(res =>{
						if(type=='accountCount'){
							this.tableAcountData.body=[]
							this.tableAcountData.loading = false
							this.tableAcountData.body.push(res)
						}else{
							if(res.errcode==0){
								this.tableData.loading = false
								this.tableData.body=res.data.consts;
								this.total=res.data.count
               	this.$message.success('获得数据成功');
					 		}else{
								this.$message.success('获得数据失败');
							}

						}

					})
     },
			//搜索
	  	search(){
		  	this.getAccountRecord()
     },
		}
	}
</script>

<style>
</style>
