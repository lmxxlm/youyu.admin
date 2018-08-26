<template>
	<div class="sys-page">
		<app-title :title='curTitle'></app-title>
		<!--标题-->
		<!-- 搜索 -->
			 <app-search>
					 <el-form :inline="true" :model="searchForm" class="customerSearch">
						 <el-form-item prop="username">
								 <el-input v-model="searchForm.username" placeholder="用户名"></el-input>
						 </el-form-item>
						 <el-form-item prop="realname">
								<el-input v-model="searchForm.realname" placeholder="姓名"></el-input>
						</el-form-item>
						<el-form-item prop="withdraw_no">
							 <el-input v-model="searchForm.withdraw_no" placeholder="流水"></el-input>
					 </el-form-item>
					 <el-form-item prop="type">
						 <el-select v-model="searchForm.type"  clearable placeholder="类型">
							<el-option
								v-for="item in searchForm.types"
								:key="item.type"
								:label="item.val"
								:value="item.type">
							</el-option>
						</el-select>
					 </el-form-item>
					 <el-form-item prop="money">
							<el-input v-model="searchForm.money" placeholder="金额"></el-input>
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
						 <el-form-item prop="status">
							 <el-select v-model="searchForm.status"  clearable placeholder="全部状态">
								<el-option
									v-for="item in searchForm.statuss"
									:key="item.type"
									:label="item.val"
									:value="item.type">
								</el-option>
							</el-select>
						 </el-form-item>
						 <el-form-item prop="sina_st">
							 <el-select v-model="searchForm.sina_st"  clearable placeholder="全部状态">
								<el-option
									v-for="item in searchForm.sina_sts"
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
								 <el-button type="primary" @click="handleDownload()" >导出</el-button>
						 </el-form-item>
					 </el-form>
			 </app-search>
				 <!-- 表格体 -->
			 <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
					 <el-table v-loading="tableData.loading" :data="tableData.body" border >
							 <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
							 <el-table-column label="操作" fixed="right" width="150px">
									 <template slot-scope="scope">
											 <el-button  type="text" size="small" @click="verity(scope)" >审核</el-button>
										 	<el-button  type="text" size="small" @click="see(scope)">查看</el-button>
									 </template>
							 </el-table-column>
					 </el-table>
					 <div class="sum"> <div class="left" style="float:left">汇总：<span>人数：{{pNum}}</span> <span class="money">{{money}}</span>
					 </div>	 <div style="float:right"><span>{{invest_count}}</span><span  class="money">{{achive_count}}</span></div> </div>
			 </table-mixin>
			 <!--弹出框-->
			 <el-dialog :title='modelTitle' :visible.sync="isModelShow">
				 <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
					 <el-form-item label="用户名" prop="username" width="60%">
						 <el-radio-group v-model="ruleForm.status">
					    <el-radio :label="1">审核通过</el-radio>
					    <el-radio :label="2">审核不通过 </el-radio>
					    <el-radio :label="4">银行处理</el-radio>
					  </el-radio-group>
					 </el-form-item>
					 <el-form-item label="到账金额" prop="money">
						 <el-input v-model="ruleForm.money" :disabled="true"></el-input>
					 </el-form-item>
					 <el-form-item label="审核备注" prop="verify_remark">
						 <el-input v-model="ruleForm.verify_remark" ></el-input>
					 </el-form-item>
					 <el-form-item>
						 <el-button type="primary" @click="submitForm('ruleForm')">审核此提现信息</el-button>
					 </el-form-item>
				 </el-form>
		 </el-dialog>

		 <!-- 提现审核/查看 -->
		 <el-dialog title='提现审核/查看' :visible.sync="isModelShowSee">
			 <table class="seeTable">
         <tr v-for="item in ruleFormSee">
         	<td>{{item.name}}</td>
					<td>{{item.val}}</td>
         </tr>
			 </table>
		 </el-dialog>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,getNowFormatDate ,exportExcel,comReqGet,ruleFormError} from '@/util/comRequest'
	import { withdrawList} from '@/util/comData'
	export default{
		name:'const',
		data(){
			return withdrawList();
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
					this.searchForm.add_time_begin=newVal[0]
					this.searchForm.add_time_end=newVal[1]
				},
				useTime(newVal,oldVal){
					this.searchForm.verify_time_begin=newVal[0]
					this.searchForm.verify_time_end=newVal[1]
				}

		},
		created() {
	        this.getTableData()
					//初始化一下时间
					this.searchForm.useTime[0]=getNowFormatDate(0)
					this.searchForm.useTime[1]=getNowFormatDate(30)
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			 //审核
			 verity(item){
				 this.isModelShow=true;
				 this.ruleForm.id=item.row.id
				 this.ruleForm.money= item.row.total
			 },
			 //提交表单
			 submitForm(formName) {
					 this.$refs[formName].validate((valid) => {
							 if (valid) {
								 commonReq('/admin/withdraw/cashCheck',this.ruleForm).then(res=>{
									 if(res.errcode == 0){
										 this.$message.success(res.msg);
									 }else{
										 this.$message.error(res.msg);
									 }
									 this.isModelShow=false;
									 this.getTableData()
								})
							 }
						 });
					 },
			 //查看
			 see(item){
          commonReq('/admin/withdraw/cashLook',{id:item.row.id}).then(res=>{
						if(res.errcode==0){
								for(var item in res.data){
									for(var child of this.ruleFormSee ){
										if(item==child.key){
											child.val=res.data[item]
										}
										if(child.key=='addtimeOrIp'){
											child.val=res.data.addtime+(res.data.addip!=void 0?'/'+res.data.addip:'')
										}
									}
								}
								this.isModelShowSee=true
						}else{
							this.$message.error('查看失败');
						}

					})
			 },
       getTableData() {
					 this.$axios({
					 		url: '/admin/withdraw/withdrawList',
					 		method: 'post',
					 		data:this.searchForm
					 }).then(res => {
					 		if(res.errcode==0){
					 				 this.tableData.loading = false
									 this.tableData.body=res.data.res
									 this.searchForm.total=parseInt(res.data.count)
									 this.pNum=res.data.sum.num
									 this.money=res.data.sum.totals
					 		}
					 })
       },
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

<style scoped>
   .seeTable{
   	width:100%;
   }
	 .seeTable tr:nth-child(2n+1){
	 	  background:#e3e3e3;
	 }
	 .seeTable tr{padding:5px;}
</style>
