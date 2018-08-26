<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="管理员类型列表"></app-title>
		 <!-- 搜索 -->
        <app-search>
            <el-form :inline="true" :model="searchForm">
                <el-form-item >
                    <el-input v-model="searchForm.search" placeholder="请输入查询条件"></el-input>
                </el-form-item>
                <el-form-item >
                    <el-button type="primary" @click="search" >查询</el-button>
                </el-form-item>
            </el-form>
        </app-search>
         <!-- 工具条 -->
        <app-toolbar>
        	<el-button type="primary" icon="el-icon-plus" @click="add()" v-hasPermission="'system.role.add'">新增</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}">
                <!--<el-table-column type="index" label="序号" width="64" align="center"></el-table-column>-->
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button  type="text" size="small" @click="edit(scope)" v-hasPermission="'system.role.edit'">编辑</el-button>
												<el-button  type="text" size="small" @click="deleteRow(scope)" v-hasPermission="'system.role.del'">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>

        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
			  <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
				  <el-form-item label="名称" prop="name" width="60%">
				    <el-input v-model="ruleForm.name" ></el-input>
				  </el-form-item>

					<el-form-item label="状态" prop="status">
						<el-radio-group v-model='ruleForm.status'>
							<el-radio label="0">关闭</el-radio>  <!--label加了冒号，传过来的值是数字，不能是字符串-->
							<el-radio label="1">开通</el-radio>
						</el-radio-group>
					</el-form-item>

				   <el-form-item label="简要说明" prop="summary">
				    <el-input v-model="ruleForm.summary" type="summary"></el-input>
				  </el-form-item>
				  <el-form-item label="备注" prop="remark">
				    <el-input v-model="ruleForm.remark" ></el-input>
				  </el-form-item>

          <el-form-item label="权限选择" prop="rule" v-hasPermission="'system.role.ruleConfig'">
						<table border=1 class="rulesCheckbox" align='center'>
							<el-checkbox-group v-model="ruleForm.rule">
								<tbody v-for="item in this.systemRules" :key="item.rule_id" >
										 <tr><td  colspan="2"><el-checkbox :label="item.rule">{{item.name}}</el-checkbox></td></tr>
											<tr v-show="item.child.length>0"  v-for="son in item.child" class="son">
													<td class="left">
														 <el-checkbox :label="son.rule">{{son.name}}</el-checkbox>
													</td>
													<td class="right">
															<span class="sonChild" v-if="son.child.length>0" v-for="sonChild in son.child">
															 <el-checkbox :label="sonChild.rule">{{sonChild.name}}</el-checkbox>
															</span>
													</td>
											</tr>
								</tbody>
							</el-checkbox-group>
						</table>
					</el-form-item>

					<el-form-item>
						<el-button type="primary" @click="submitForm('ruleForm')">确定</el-button>
						<el-button @click="resetForm('ruleForm')">重置</el-button>
					</el-form-item>
				</el-form>
	  </el-dialog>
	</div>
</template>

<script>
	import { mapState } from 'vuex'
	import { getSystemRules,getUserRules } from '@/util/comRequest'
	export default{
		name:'const',
		computed:{
			...mapState({
                currentPage: state => state.pagination.currentPage,
                sizePage: state => state.pagination.sizePage,
            }),
		},
		data(){
			return {
		   	options: [],
				modelTitle:'添加系统参数',
				submitStatus:'add',
				size:10,//每页多少条
				page:1,//当前页
				total:0,//总共多少条
				searchForm: {
			        search: ''
			   },
			   tableData: {
	                loading: true,
	                head: [
								     {key:'name',name:'类型名称'},
										 {key:'summary',name:'简要'},
										 {key:'remark',name:'备注'},
	                   {key:'status',name:'状态'},
	                ],
	                body: []
	           },
	           modelTitle:'添加常量类型',
	           isModelShow:false,
	           ruleForm: {
			          name: '',
								status:'0',
			          summary: '',
			          remark: '',
								rule:[],
		        },
		        rules: {
		          name: [
		            { required: true, message: 'name 必须填写.', trigger: 'blur' }
		          ],
							status: [
		            { required: true, message: 'status 不能为空', trigger: 'change' }
		          ],
		          summary: [
		            { required: true, message: 'summary 必须填写', trigger: 'blur' }
		          ],
							remark: [
							 { required: true, message: 'remark 必须填写', trigger: 'blur' }
						 ],
						 rule: [
							 { required: false, message: 'rule 可选', trigger: 'change' }
						 ]
		       },
					 systemRules:[],
					 userRules:[]
			}
		},
		created() {
	        this.getTableData()
					this.getRoleList();
					this._getAllPermission();
	    },
		methods:{
		// 初始化表单
			initForm(){
				for(var key in this.ruleForm){
					var type = Object.prototype.toString.call(this.ruleForm[key]).slice(8,-1)
					    switch(type){
								  case 'Array':
									   this.ruleForm[key] = []
									break;
									case 'String':
									   this.ruleForm[key] = ''
									break;
									case 'Object':
									   this.ruleForm[key] ={}
									break;
									default:
									   this.ruleForm[key] =''
							}
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
			   var  url=this.submitStatus=='add'?'/admin/role/roleAdd':'/admin/role/roleUpdate'
		      this.$refs[formName].validate((valid) => {
		          if (valid) {
								var data = this.ruleForm
								data['id']=data['type_id']

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
		                    	this.rules[key][0].message=res.error[key]
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
					//获取角色列表
					getRoleList(){
							this.$axios({
								 url: '/admin/role/getAllRole',
								 method: 'get',
								 data: {}
							}).then(res => {
						   	this.options=res;
							})
					},
			 // 获取table数据
             getTableData() {
								 this.$axios({
								 		url: '/admin/role/roleList',
								 		method: 'post',
								 		data: {
								 			page:this.page,
								 			size:this.size,
								 			search:this.searchForm.search
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
	            console.log(`欲提交的数据    文本: ${this.searchForm.text}`)
	        },
	        //新增
	        add(){
						this.resetForm('ruleForm');//初始化表单验证   必须 放在初始化表单的前面
	        	this.modelTitle='添加管理员类型'
	        	this.submitStatus='add';
	        	this.isModelShow=true;
	         	this.initForm();
	        },
	        deleteRow(item){
	        	this.$confirm('确认关闭？').then(_ => {
	        		this.$axios({
		                url: '/admin/system/delete',
		                method: 'post',
		                data: item.row.user_id
		            }).then(res => {
		            	if(res.errcode==0){
	        	           this.tableData.body.splice(item.$index,1)
		            	}else{
		            		alert('删除失败')
		            	}
		            })
		             done();
		         }).catch(_ => {});

	        },
	        //编辑
	        edit(item){
	        	this.modelTitle='修改管理员类型'
	        	this.submitStatus='edit';
	        	this.$axios({
	                url: '/admin/role/roleEdit',
	                method: 'post',
	                data: {
									    id:item.row.type_id
									}
	            }).then(res => {
							  console.log(res,'编辑返回来的结果')
	            	if(res.errcode==0){
								  res=res.data
	            		for(var key in res){
	            			this.ruleForm[key]=res[key]
	            		}
	            	  this.isModelShow=true;
	            	}else{
	            		alert('编辑失败')
	            	}
	            })

	        },
					//获取所有的权限
         _getAllPermission(){
						 getSystemRules({}).then(res => {
						    this.systemRules=res
								console.log(res,'获取系统的规则=======111==================')
						 })

				     /*
						 getUserRules({}).then(res => {
								this.userRules=res
								console.log(res,'获取用户的规则====1111=====================')
						 })

						 */

				 },
		}

	}
</script>

<style scoped>
.rulesCheckbox{border:1px solid #e3e3e3;}
.rulesCheckbox span{padding-left:5px;}
.rulesCheckbox td{padding:0 5px;}
.rulesCheckbox td:first-child{text-align:center;}
</style>
