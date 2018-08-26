<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="银行卡列表"></app-title>
		 <!-- 搜索 -->
        <app-search>
            <el-form :inline="true" :model="searchForm" class="customerSearch">
								<el-form-item prop="user_id">
										<el-input v-model="searchForm.user_id" placeholder="绑卡状态"></el-input>
								</el-form-item>
							 <el-select v-model="searchForm.bank_status"  clearable placeholder="请选择">
								 <el-option
									 v-for="item in searchForm.bankStatus"
									 :key="item.type"
									 :label="item.val"
									 :value="item.type">
								 </el-option>
							 </el-select>
							 <el-select v-model="searchForm.bank_code"  clearable placeholder="全部银行" @click="banks">
								 <el-option
									 v-for="item in searchForm.banks"
									  :key="item.bank_code"
									 :label="item.bank_name"
									 :value="item.bank_id">
								 </el-option>
							 </el-select>
							 <el-form-item prop="bank_account">
									 <el-input v-model="searchForm.bank_account" placeholder="银行卡号"></el-input>
							 </el-form-item>
							 <el-form-item prop="addip">
									 <el-input v-model="searchForm.addip" placeholder="addip"></el-input>
							 </el-form-item>
							 <el-form-item prop="username">
									 <el-input v-model="searchForm.username" placeholder="用户名"></el-input>
							 </el-form-item>
							 <el-form-item prop="realname">
									 <el-input v-model="searchForm.realname" placeholder="用户姓名"></el-input>
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
                <el-table-column label="操作" fixed="right" width="150px">
                    <template slot-scope="scope">
                        <el-button  type="text" size="small" @click="edit(scope)" v-hasPermission="'customer.bank.edit'">编辑</el-button>
												<el-button  type="text" size="small" @click="del(scope)" v-hasPermission="'customer.bank.del'">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>

        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
				  <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
					  <el-form-item label="银行卡号" prop="account" width="60%">
					    <el-input v-model="ruleForm.account" ></el-input>
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
	import { copy,commonReq } from '@/util/comRequest'
	import { bankData} from '@/util/comData'
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
			return bankData()
		},
		created() {
	        this.getTableData()
					this.banks()
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			 //获取全部银行
			 banks(){
				 var url ='/admin/bank/banks'
				 this.$axios({
							url: url,
							method: 'get',
							data: {}
					}).then(res => {
						if(res.errcode == 0){
							this.searchForm.banks=res.data
						}else{

						}
					})

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
			   var  url='/admin/customer/bankUpdate'
		      this.$refs[formName].validate((valid) => {
		          if (valid) {
								var data = this.ruleForm
		          	 this.$axios({
			                url: url,
			                method: 'post',
			                data: data
			            }).then(res => {
			            	if(res.errcode == 0){
			            		this.isModelShow=false;
			            		this.getTableData()
			            	}else{
			            		for(var key in res.error){
		                    	this.ruleForm[key]=''
													if(this.rules[key]!= void 0){
														this.rules[key][0].message=res.error[key]
													}
		                  }
		                 this.$refs.ruleForm.validate()
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
							 		url: '/admin/customer/bankLists',
							 		method: 'post',
							 		data: this.searchForm
							 }).then(res => {
							 		if(res.errcode==0){
							 				 this.tableData.loading = false
							 				 this.tableData.body=res.data.res;
							 				 this.searchForm.total=res.data.count
							 		}
							 })
         },
				//搜索
		  	search(){
			  	    this.getTableData()
	        },
					//删除
					del(scope){//批量删除不给他放参数也会传过来值  就是事件对象
						var url="/admin/customer/bankDel"
						var data={
							id:scope.row.id
						}
						this.$confirm('你确定要删除吗', "提示", {
			          type: "warning"
			        }).then(() => {
								commonReq(url,data).then((res)=>{
									if (res.errcode==0) {
										this.$message.success('删除成功');
										this.getTableData()
									} else {
										this.$message.error('删除失败');
										this.loading = false;
									}
								})
			      })
					},
	        //编辑
	        edit(item){
	        	this.modelTitle='下线列表修改'
	        	this.submitStatus='edit';
						var url="/admin/customer/bankEdit"
						var data = { id:item.row.user_id}
						commonReq(url,data).then((res)=>{
							if(res.errcode==0){
								res=res.data
								for(var key in res){
									this.ruleForm[key]=res[key]
								}
								this.isModelShow=true;
							}else{
								this.$message.error('编辑失败');
							}
						})
	        },

		}

	}
</script>

<style >
    .customerSearch .el-select {width:130px;}
		.customerSearch .input-with-select .el-input-group_prepend{background-color:#fff;	}
</style>
