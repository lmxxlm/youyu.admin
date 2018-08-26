<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="图片管理"></app-title>
		 <!-- 搜索 -->
        <app-search>
            <el-form :inline="true" :model="searchForm">
                <el-form-item >
                    <el-input v-model="searchForm.username" placeholder="借款人"></el-input>
                </el-form-item>
								<el-form-item >
                    <el-input v-model="searchForm.name" placeholder="标题"></el-input>
                </el-form-item>
								<el-form-item>
									<el-select v-model="searchForm.status" clearable placeholder="选择状态">
										<el-option
											v-for="item in searchForm.sts"
											:key="item.type"
											:label="item.name"
											:value="item.type">
										</el-option>
									</el-select>
								</el-form-item>
                <el-form-item >
                    <el-button type="primary" @click="search" >查询</el-button>
                </el-form-item>
            </el-form>
        </app-search>
         <!-- 工具条 -->
        <app-toolbar>
        	<el-button type="primary" icon="el-icon-plus" >今日到期还款</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}">
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作" fixed="right">
                    <template slot-scope="scope">
                        <el-button  type="text" size="small" @click="repay(scope)">还款</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>
			</div>
</template>

<script>
	import { mapState } from 'vuex'
	import { getSystemRules,getUserRules,commonReq} from '@/util/comRequest'
	import {repayList} from '@/util/comData'
	export default{
		name:'const',
		computed:{
			...mapState({
                currentPage: state => state.pagination.currentPage,
                sizePage: state => state.pagination.sizePage,
            }),
		},
		data(){
			return repayList()
		},
		created() {
	        this.getTableData()
	    },
		methods:{
			//还款
			repay(item){
				commonReq('/admin/borrow/repay',{id:item.row.id}).then(res=>{
					this.$message.success(res.msg)
				})
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
					 		url: '/admin/borrow/repayList',
					 		method: 'post',
					 		data: this.searchForm
					 }).then(res => {
					 		if(res.errcode==0){
					 				 this.tableData.loading = false
									 for(var item in res.data){
										 res.data[item].time=res.data[item].start_time+'/'+res.data[item].end_time
									 }
					 				 this.tableData.body=res.data;
					 				 this.searchForm.total=res.data.count
					 		}
					 })
       },
				//搜索
		  	search(){
			  	this.getTableData()
	      }
		}

	}
</script>

<style >
.avatar-uploader .el-upload {
    border: 1px dashed #e3e3e3;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
	.avatar-img{
		width:100%;
		height:100%;
	}
</style>
