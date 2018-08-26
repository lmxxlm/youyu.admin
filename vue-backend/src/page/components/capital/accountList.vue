<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="测试列表"></app-title>
		 <!-- 搜索 -->
        <app-search>
            <el-form :inline="true" :model="searchForm" class="customerSearch">
							<el-form-item prop="username">
									<el-input v-model="searchForm.username" placeholder="用户名"></el-input>
							</el-form-item>
							<el-form-item prop="realname">
									<el-input v-model="searchForm.realname" placeholder="姓名"></el-input>
							</el-form-item>
							<el-select v-model="searchForm.condition"  clearable placeholder="请选择">
								<el-option
									v-for="item in searchForm.sts"
									:key="item.name"
									:label="item.name"
									:value="item.type">
								</el-option>
							</el-select>
							<el-form-item prop="money">
									<el-input v-model="searchForm.money" placeholder="金额"></el-input>
							</el-form-item>
						  <el-form-item >
                  <el-button type="primary" @click="search" >查询</el-button>
              </el-form-item>
            </el-form>
        </app-search>
         <!-- 工具条 -->
        <app-toolbar>
					<el-button type="primary" @click="handleDownload()"  v-hasPermission="'capital.accountList.export'">导出</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}" >
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
								<el-table-column label="操作" fixed="right">
                    <template slot-scope="scope">
                        <el-button   type="text" size="small" @click="goDetail(scope)">月账单</el-button>
												<el-button   type="text" size="small" @click="goTest(scope)">测试列表</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,exportExcel,goDetail,ruleFormError} from '@/util/comRequest'
	import { accountList} from '@/util/comData'
	export default{
		name:'const',
		computed:{
			...mapState({
          currentPage: state => state.pagination.currentPage,
          sizePage: state => state.pagination.sizePage,
      }),

		},
		data(){
			return accountList()
		},
		created() {
	        this.getTableData()
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			//导出表格
			handleDownload() {
				var that = this
				var url='/admin/capital/accountExport'
				var data = this.searchForm
				exportExcel(that,url,data)//导出的结果
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
			 // 获取table数据
       getTableData() {
				 this.$axios({
				 		url: '/admin/capital/accountList',
				 		method: 'post',
				 		data: this.searchForm
				 }).then(res => {
				 		if(res.errcode==0){
							   this.$message.success('获取表格数据成功')
				 				 this.tableData.loading = false
				 				 this.tableData.body=res.data.res;
				 				 this.searchForm.total=parseInt(res.data.count)
				 		}else{
							this.$message.error('获取表格数据失败')
						}
				 })
       },
				//搜索
		  	search(){
		  	    this.getTableData()
        },
				goDetail(item){
					var val={
							id:item.row.id,
							title:'月账单',
							name:'月账单',
							meta:this.$route.meta,
							localType:'getRecordCount'
					}
					var url ='/components/capital/getRecordCount'
					goDetail(this,url,val)
				},
				//去测试列表
				goTest(item){
					var val={
							id:item.row.id,
							title:'测试列表',
							name:'测试列表',
							meta:this.$route.meta,
							localType:'getRecordCount'
					}
					var url ='/components/capital/test'
					goDetail(this,url,val)
				}
		}

	}
</script>

<style >
    .customerSearch .el-select {width:130px;}
		.customerSearch .input-with-select .el-input-group_prepend{background-color:#fff;	}
</style>
