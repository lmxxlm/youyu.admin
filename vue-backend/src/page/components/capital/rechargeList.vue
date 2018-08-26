<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="管理员列表"></app-title>
		 <!-- 搜索 -->
        <app-search>
            <el-form :inline="true" :model="searchForm" class="customerSearch">
							<el-form-item prop="trade_no">
									<el-input v-model="searchForm.trade_no" placeholder="订单"></el-input>
							</el-form-item>
							<el-form-item prop="username">
									<el-input v-model="searchForm.username" placeholder="用户"></el-input>
							</el-form-item>
							<el-form-item prop="realname">
									<el-input v-model="searchForm.realname" placeholder="姓名"></el-input>
							</el-form-item>
							<el-form-item prop="money">
									<el-input v-model="searchForm.money" placeholder="金额"></el-input>
							</el-form-item>
							<el-form-item prop="bank">
									<el-input v-model="searchForm.bank" placeholder="银行"></el-input>
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
							<el-select v-model="searchForm.status"  clearable placeholder="请选择">
								<el-option
									v-for="item in searchForm.sts"
									:key="item.name"
									:label="item.name"
									:value="item.type">
								</el-option>
							</el-select>
							<el-select v-model="searchForm.pay_type"  clearable placeholder="请选择">
								<el-option
									v-for="item in searchForm.payTypes"
									:key="item.name"
									:label="item.name"
									:value="item.type">
								</el-option>
							</el-select>

							  <el-form-item >
                    <el-button type="primary" @click="search" >查询</el-button>
                </el-form-item>
            </el-form>
        </app-search>
         <!-- 工具条 -->
        <app-toolbar>
					<el-button type="success"  @click="add()" v-hasPermission="'capital.rechargeList.add'">新增</el-button>
					<el-button type="primary" @click="handleDownload()" v-hasPermission="'capital.rechargeList.export'">导出</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}" >
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
            </el-table>
						<div class="sum">
							<span>汇总:</span><span>{{sum}}</span>
						</div>
        </table-mixin>

        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
				  <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
					  <el-form-item label="用户名" prop="username" width="60%">
					    <el-input v-model="ruleForm.username"  @blur="getUserInfo()"></el-input>
					  </el-form-item>
					  <el-form-item label="用户信息" prop="realname">
					    <p>{{ruleForm.realname}}</p>
					  </el-form-item>
						<el-form-item label="类型" prop="pay_type">
							<el-select v-model="ruleForm.pay_type"  clearable placeholder="请选择">
								<el-option
									v-for="item in ruleForm.payTypes"
									:key="item.name"
									:label="item.name"
									:value="item.type">
								</el-option>
							</el-select>
						</el-form-item>
						<el-form-item label="金额" prop="money" width="60%">
						 <el-input v-model="ruleForm.money" ></el-input>
					 </el-form-item>
						 <el-form-item label="备注" prop="remark" width="60%">
							<el-input v-model="ruleForm.remark" ></el-input>
						</el-form-item>
					  <el-form-item>
					    <el-button type="primary" @click="submitForm('ruleForm')">确定</el-button>
					  </el-form-item>
					</el-form>
		  </el-dialog>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,exportExcel,goDetail,ruleFormError} from '@/util/comRequest'
	import { rechargeList} from '@/util/comData'
	export default{
		name:'const',
		computed:{
			...mapState({
          currentPage: state => state.pagination.currentPage,
          sizePage: state => state.pagination.sizePage,
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
			return rechargeList()
		},
		created() {
	        this.getTableData()
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			getUserInfo(){
				var url='/admin/customer/getInfoByUsername'
				var data={username:this.ruleForm.username}
				commonReq(url,data).then(res=>{
					if(res.errcode==0){
						if(res.data != void 0){
							this.ruleForm.realname=res.data.realname
						}else{
							this.ruleForm.realname=res.msg
						}
					}else{
						this.ruleForm.realname='未知'
					}
				})
			},
			//导出表格
			handleDownload() {
				var that = this
				var url='/admin/capital/rechargeExport'
				var data = this.searchForm
				exportExcel(that,url,data)//导出的结果
			},
		// 初始化表单
			initForm(){
				for(var key in this.ruleForm){
            	  this.ruleForm[key]=''
            	}
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
			//提交表单
			submitForm(formName) {
			   var  url='/admin/capital/rechargeAdd'
		      this.$refs[formName].validate((valid) => {
		          if (valid) {
		          	 this.$axios({
			                url: url,
			                method: 'post',
			                data: this.ruleForm
			            }).then(res => {
			            	if(res.errcode == 0){
			            		this.isModelShow=false;
			            		this.getTableData()
			            	}else{
											ruleFormError(this,res)
			            	}
			            })
		          } else {
		            console.log('error submit!!');
		            return false;
		          }
		        });
		      },
		      //重置表单
		      resetForm(formName) {
		        this.$refs[formName].resetFields();
		      },
			 // 获取table数据
         getTableData() {
							 this.$axios({
							 		url: '/admin/capital/rechargeList',
							 		method: 'post',
							 		data: this.searchForm
							 }).then(res => {
							 		if(res.errcode==0){
										   this.$message.success('获取表格数据成功')
							 				 this.tableData.loading = false
							 				 this.tableData.body=res.data;
							 				 this.searchForm.total=parseInt(res.count)
											 this.sum=res.sum
							 		}else{
										this.$message.error('获取表格数据失败')
									}
							 })
         },
				//搜索
		  	search(){
			  	    this.getTableData()
	        },
	        //新增
	        add(){
	        	this.modelTitle='添加充值'
	        	this.submitStatus='add';
	        	this.isModelShow=true;
	         	this.initForm();
						this.$nextTick(function(){
							this.$refs['ruleForm'].resetFields();//放在next
						})
						this.ruleForm.payTypes=this.searchForm.payTypes;
	        },
		}

	}
</script>

<style >
    .customerSearch .el-select {width:130px;}
		.customerSearch .input-with-select .el-input-group_prepend{background-color:#fff;	}
</style>
