<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="管理员列表"></app-title>
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
        	<el-button type="primary" icon="el-icon-plus" @click="add()" v-hasPermission="'system.manager.add'">新增</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}">
                <!--<el-table-column type="index" label="序号" width="64" align="center"></el-table-column>-->
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button  type="text" size="small" @click="edit(scope)" v-hasPermission="'system.manager.edit'">编辑</el-button>
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
				   <el-form-item label="密码" prop="password">
				    <el-input v-model="ruleForm.password" type="password"></el-input>
				  </el-form-item>
				  <el-form-item label="真实姓名" prop="realname">
				    <el-input v-model="ruleForm.realname" ></el-input>
				  </el-form-item>
          <el-form-item label="类型" prop="type_id">
						  <el-select v-model="ruleForm.type_id" clearable placeholder="请选择">
						    <el-option
						      v-for="item in options"
						      :key="item.type_id"
						      :label="item.name"
						      :value="item.type_id">
						    </el-option>
						  </el-select>
					</el-form-item>

					<el-form-item label="状态" prop="status">
				    <el-radio-group v-model='ruleForm.status'>
				      <el-radio label="0">关闭</el-radio>  <!--label加了冒号，传过来的值是数字，不能是字符串-->
				      <el-radio label="1">开通</el-radio>
				    </el-radio-group>
				  </el-form-item>

					<el-form-item label="电子邮箱" prop="email">
				    <el-input v-model="ruleForm.email" ></el-input>
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
								     {key:'user_id',name:'ID'},
										 {key:'username',name:'管理员名'},
										 {key:'realname',name:'姓名'},
	                   {key:'addtime',name:'添加时间'},
										 {key:'status',name:'状态'},
										 {key:'role',name:'管理员类型'},
										 {key:'logintime',name:'登录次数'},
										 {key:'lasttime',name:'最后登录时间'},
	                   {key:'lastip',name:'最后登录IP'}
	                ],
	                body: []
	           },
	           modelTitle:'添加常量类型',
	           isModelShow:false,
	           ruleForm: {
			          username: '',
			          password: '',
			          realName: '',
								type_id:'',
								status:0,//不能加单引号
								email:''
		        },
		        rules: {
		          username: [
		            { required: true, message: 'name 必须填写.', trigger: 'blur' }
		          ],
							password: [
		            { required: false, message: 'password 不能为空', trigger: 'blur' }
		          ],
		          realName: [
		            { required: true, message: 'realName 必须填写', trigger: 'blur' }
		          ],
							type_id: [
							 { required: true, message: 'type_id 必须填写', trigger: 'blur' }
						 ],
						 status: [
							 { required: true, message: 'status 必须填写', trigger: 'blur' }
						 ],
						 email: [
							 { required: true, message: 'email 必须填写', trigger: 'blur' }
						 ],
		       },
			}
		},
		created() {
	        this.getTableData()
					this.getRoleList();
	    },
		methods:{
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
			   var  url=this.submitStatus=='add'?'/admin/manager/managerAdd':'/admin/manager/managerUpdate'
		      this.$refs[formName].validate((valid) => {
		          if (valid) {
								var data = this.ruleForm
								data['id']=data['user_id']

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
								 		url: '/admin/manager/lists',
								 		method: 'post',
								 		data: {
								 			page:this.page,
								 			size:this.size,
								 			search:this.searchForm.search
								 		}
								 }).then(res => {
								 		if(res.errcode==0){
								 				 this.tableData.loading = false
								 				 this.tableData.body=res.data.consts;
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
					  this.rules.password[0].required=true;
	        	this.modelTitle='添加管理员'
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
					//初始化一下密码验证
					  this.rules.password[0].required=false;
	        	this.modelTitle='修改管理类型'
	        	this.submitStatus='edit';
	        	this.$axios({
	                url: '/admin/manager/managerEdit',
	                method: 'post',
	                data: {
									    id:item.row.user_id
									}
	            }).then(res => {
	            	if(res.errcode==0){
								  res=res.data.userInfo
	            		for(var key in res){
	            			this.ruleForm[key]=res[key]
	            		}
	            	  this.isModelShow=true;
	            	}else{
	            		alert('编辑失败')
	            	}
	            })

	        },

		}

	}
</script>

<style>
</style>
