<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="管理员列表"></app-title>
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
								<el-form-item >
                    <el-button type="primary" @click="handleDownload()" v-hasPermission="'customer.lists.export'">导出</el-button>
                </el-form-item>
            </el-form>
        </app-search>
         <!-- 工具条 -->
        <app-toolbar>
					<el-button type="warning" size="medium" @click="del" :disabled="disable" v-hasPermission="'customer.lists.del'">批量删除</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}"  @select-all="selectionStatus" @select="selectionStatus" @selection-change="handleSelectionChange">
                <el-table-column align="center" type="selection" width="55" fixed="left"></el-table-column>
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作" fixed="right" width="150px">
                    <template slot-scope="scope">
										  	<el-button  type="text" size="small" @click="money(scope)" v-hasPermission="'customer.lists.moneyLook'">资金</el-button>
												<el-button  type="text" size="small" @click="vip(scope)" v-hasPermission="'customer.lists.vip'">vip</el-button>
                        <el-button  type="text" size="small" @click="edit(scope)" v-hasPermission="'customer.lists.edit'">编辑</el-button>
												<el-button  type="text" size="small" @click="del(scope.row.user_id)" v-hasPermission="'customer.lists.del'">删除</el-button>
												<el-button  type="text" size="small" @click="copy(scope)">复制渠道链接</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>

        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
				  <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
					  <el-form-item label="用户名" prop="username" width="60%">
					    <el-input v-model="ruleForm.username" ></el-input>
					  </el-form-item>
					  <el-form-item label="真实姓名" prop="realname">
					    <el-input v-model="ruleForm.realname" ></el-input>
					  </el-form-item>
						<el-form-item label="身份证号" prop="card_id">
					    <el-input v-model="ruleForm.card_id" ></el-input>
					  </el-form-item>
					  <el-form-item>
					    <el-button type="primary" @click="submitForm('ruleForm')">确定</el-button>
					  </el-form-item>
					</el-form>
		  </el-dialog>

			<!-- VIP弹出框 -->
			<el-dialog title='检测vip' :visible.sync="isVIP">
					<el-form   ref="ruleFormVIP" label-width="100px"   class="demo-ruleForm"> <!--加了这个:model="ruleFormVIP"   报错-->
						<el-form-item :label="item.label"  width="60%" v-for="item in ruleFormVIP">
							<p>{{item.val}}</p>
						</el-form-item>
					</el-form>
			</el-dialog>

	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,exportExcel,goDetail,ruleFormError} from '@/util/comRequest'
	import { customerListData} from '@/util/comData'
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
			return customerListData()
		},
		created() {
	        this.getTableData()
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			 //去资金列表
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
			vip(scope){
				this.isVIP=true;
				var url="/admin/customer/checkVip"
				var data={id:scope.row.user_id}
				commonReq(url,data).then(res =>{
					if(res.errcode == 0){
						this.$message.success(res.data); //这是一个  简单的弹出提示
					}else{
						this.$message.error(res.data);
					}
				})
			},
			copy(scope){
				copy(scope.row.card_type);
			},
			//导出表格
			handleDownload() {
				var that = this
				var url='/admin/customer/export'
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
							 		url: '/admin/customer/lists',
							 		method: 'post',
							 		data: this.searchForm
							 }).then(res => {
							 		if(res.errcode==0){
										   this.$message.success('获取表格数据成功')
							 				 this.tableData.loading = false
							 				 this.tableData.body=res.data.res;
							 				 this.searchForm.total=res.data.count
							 		}else{
										this.$message.error('获取表格数据失败')
									}
							 })
         },
				//搜索
		  	search(){
			  	    this.getTableData()
	            console.log(`欲提交的数据    文本: ${this.searchForm.text}`)
	        },
	        //新增
	        add(){
	        	this.modelTitle='添加管理员'
	        	this.submitStatus='add';
	        	this.isModelShow=true;
	         	this.initForm();
	        },
					//复选框点击
			    handleSelectionChange(val) {
			      var that = this;
			      that.multipleSelection = [];
			      for (var i = 0; i < val.length; i++) {
			        that.multipleSelection.push(val[i].user_id);
			      }
			    },
			    //监控复选框的选中状态
			    selectionStatus(selection,row){
						console.log({
							'selection':selection,
							'row':row
						})
			      var that = this;
			      if(selection.length>0){
			           that.disable = false;
			      }else{
			         that.disable = true;
			      }
			    },
					//删除
					del(id){//批量删除不给他放参数也会传过来值  就是事件对象
						var text =''
						if(id && typeof id == 'string'){
							text='确认删除？'
							this.multipleSelection = [];
							this.multipleSelection.push(id);
						}else{
							text="确定批量删除？"
						}
						var url="/admin/customer/del"
						var data={
							ids:this.multipleSelection
						}
						this.$confirm(text, "提示", {
			          type: "warning"
			        }).then(() => {
								commonReq(url,data).then((res)=>{
									if (res.errcode==0) {
										this.$message.success('删除成功');
										this.getTableData()
										this.multipleSelection = [];
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
						var url="/admin/customer/edit"
						var data = { id:item.row.user_id}
						commonReq(url,data).then((res)=>{
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
