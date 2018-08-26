<template>
	<div class="sys-page">
		<app-title :title='title'></app-title>
		<!-- 搜索 -->
			 <app-search>
					 <el-form :inline="true" :model="searchForm" class="customerSearch">
							 <el-form-item >
								 <el-upload
										 class="upload-demo"
										 :action='actionUrl'
										 :onSuccess="uploadSuccess"
										 multiple
										 >
										 <el-button  type="primary">点击上传</el-button>
										</el-upload>
							</el-form-item>
							 <el-form-item >
									 <el-button type="primary" @click="handleDownload()" >下载模版</el-button>
							 </el-form-item>
					 </el-form>
			 </app-search>
				 <!-- 表格体 -->
			 <table-mixin >
					 <el-table v-loading="tableData.loading" :data="tableData.body" border :row-class-name="tableRowClassName">
						   <el-table-column type="index" label="序号" width="64" align="center"></el-table-column>
							 <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
					 </el-table>
			 </table-mixin>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { commonReq ,getNowFormatDate , setTab} from '@/util/comRequest'
	import { sendRewardBatchData} from '@/util/comData'
	export default{
		name:'const',
		data(){
			return sendRewardBatchData()
		},
		computed:{
			...mapState({
          currentPage: state => state.pagination.currentPage,
          sizePage: state => state.pagination.sizePage,
					params: state => state.consts
      }),
		},
		created(){
			var sendReward = JSON.parse(localStorage.getItem('sendReward'))
			this.type = sendReward.title
			setTab(this,'sendReward')
      this.getData()
		},
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			 getData(){
				 this.title=this.type
				 if(this.type=='发放红包'){
             this.actionUrl='/admin/capital/sendRewardBatch'
				 }else if(this.type=='发放年化券'){
					 this.actionUrl='/admin/capital/addUserCouponBatch'
					 this.tableData.head=[
            // username,coupon_name,coupon,timeout,money_minimun_limit,money_limit,borrow_days,borrow_limit
              {key:'username',name:'客户账户'},
              {key:'coupon_name',name:'	年化券名称'},
              {key:'coupon',name:'年化券'},
              {key:'money_minimun_limit',name:'起投金额'},
							{key:'money_limit',name:'最大限额'},
              {key:'borrow_days',name:'		投资期限'},
              {key:'timeout',name:'截止日期'},
              {key:'timeout_unix',name:'截止日期（UNIX）'},
							{key:'msg',name:'错误提示'},
           ]
				 }
			 },
			 tableRowClassName({row, rowIndex}) {
				 if (row.state == false) {
					 return 'warning-row';
				 }else{
					 return 'success-row';
				 }
			 },
			 uploadSuccess(response, file, fileList){
					this.tableData.loading = false
					if(response.errcode==1){
						this.$message.error(response.msg);
					}else if(response.errcode==0){
						this.$message.success('上传成功');
					}
					this.tableData.body=response.data
					this.searchForm.total=response.count

			 },
			 //下载模版
				handleDownload() {
					if(this.type=='发放红包'){
						window.location.href='http://admin.youyu.com/public/template/reward.zip'
					}else if(this.type=='发放年化券'){
							window.location.href='http://admin.youyu.com/public/template/coupon.zip'
					}
				},

		}

	}
</script>

<style >
.el-table .warning-row {
	background: oldlace;
	color:red;
}

.el-table .success-row {
	background: #f0f9eb;
}
</style>
