<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="下线列表"></app-title>
		 <!-- 搜索 -->
        <app-search>
            <el-form :inline="true" :model="searchForm" class="customerSearch">
							<el-form-item>
                  <el-input placeholder="请输入内容" v-model="searchForm.search" class="input-width-select">
										<el-select v-model="searchForm.type" slot="prepend" clearable placeholder="请选择">
											<el-option
												v-for="item in searchForm.options"
												:key="item.type"
												:label="item.name"
												:value="item.type">
											</el-option>
										</el-select>
									</el-input>
							</el-form-item>
						  <el-form-item >  <!--prop="searchForm.activeTime"  加了这个会报错please transfer a valid prop path to form item!"-->
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
							 <el-select v-model="searchForm.st"  clearable placeholder="请选择">
								 <el-option
									 v-for="item in searchForm.sts"
									 :key="item.name"
									 :label="item.name"
									 :value="item.type">
								 </el-option>
							 </el-select>
							 <el-select v-model="searchForm.bank"  clearable placeholder="请选择">
								 <el-option
									 v-for="item in searchForm.banks"
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
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}"  >
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作" fixed="right" width="150px">
                    <template slot-scope="scope">
										  	<el-button  type="text" size="small" @click="money(scope)" v-has="'customer.downList.money'">资金</el-button>
                        <el-button  type="text" size="small" @click="edit(scope)" v-hasPermission="'customer.downList.edit'">编辑</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>

        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
				  <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
					  <el-form-item label="上线用户ID" prop="invite_userid" width="60%">
					    <el-input v-model="ruleForm.username" ></el-input>
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
	import { copy,commonReq,goDetail } from '@/util/comRequest'
	import { downListData} from '@/util/comData'
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
			return downListData()
		},
		created() {
	        this.getTableData()
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
		  //资金
			money(item){
				var val={
						id:item.row.user_id,
						title:'资金列表',
						name:'资金列表',
						meta:this.$route.meta,
						localType:'capital'
				}
				var url ='/components/customer/capital'
				goDetail(this,url,val)
			},
		// 初始化表单
			initForm(){
				for(var key in this.ruleForm){
            	  this.ruleForm[key]=''
            	}
			},
			//获取一页多少条
			getPageSize(val){
				this.size=val
				this.getTableData()
			},
			//获取当前多少页
			getCurrentPage(val){
				this.page=val
				this.getTableData()
			},
			//提交表单
			submitForm(formName) {
			   var  url='/admin/customer/update'
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
							 		url: '/admin/customer/downLists',
							 		method: 'post',
							 		data: {
							 			page:this.page,
							 			size:this.size,
										type:this.searchForm.type,
							 			search:this.searchForm.search,
										start_time:this.searchForm.start_time,
										end_time:this.searchForm.end_time,
										st:this.searchForm.st,
										bank:this.searchForm.bank,
							 		}
							 }).then(res => {
							 		if(res.errcode==0){
							 				 this.tableData.loading = false
							 				 this.tableData.body=res.data.res;
							 				 this.total=res.data.count
							 		}
							 })
         },
				//搜索
		  	search(){
			  	    this.getTableData()
	        },
	        //新增
	        add(){
	        	this.modelTitle='添加管理员'
	        	this.submitStatus='add';
	        	this.isModelShow=true;
	         	this.initForm();
	        },
	        //编辑
	        edit(item){
	        	this.modelTitle='下线列表修改'
	        	this.submitStatus='edit';
						var url="/admin/customer/edit"
						var data = { id:item.row.user_id}
						commonReq(url,data).then((res)=>{
							console.log(res,'编辑返回来的结果================')
							if(res.errcode==0){
								res=res.data
								for(var key in res){
									this.ruleForm[key]=res[key]
								}
								this.ruleForm['id']=item.row.user_id
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
