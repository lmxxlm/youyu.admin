<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="常量类型列表"></app-title>
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
        	<el-button type="primary" icon="el-icon-plus" @click="add()" v-hasPermission="'system.consts.add'">新增</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}">
                <!--<el-table-column type="index" label="序号" width="64" align="center"></el-table-column>-->
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作" fixed="right">
                    <template slot-scope="scope">
                    	<!--v-if="scope.row.operation.indexOf('edit') >= 0" v-if="scope.row.operation.indexOf('delete') >= 0"-->
                        <el-button  type="text" size="small" @click="edit(scope)" v-hasPermission="'system.consts.edit'">编辑</el-button>
                        <el-button   type="text" size="small" @click="deleteRow(scope)" v-hasPermission="'system.consts.del'">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>
        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
			 <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
				  <el-form-item label="status" prop="status" width="60%">
				    <el-input v-model="ruleForm.status" ></el-input>
				  </el-form-item>
				   <el-form-item label="order" prop="order">
				    <el-input v-model="ruleForm.order" ></el-input>
				  </el-form-item>
				  <el-form-item label="type_id" prop="type_id">
				    <el-input v-model="ruleForm.type_id"  readonly></el-input>
				  </el-form-item>
					<el-form-item label="pid" prop="pid">
				    <el-input v-model="ruleForm.pid" ></el-input>
				  </el-form-item>
					<el-form-item label="name" prop="name">
				    <el-input v-model="ruleForm.name" ></el-input>
				  </el-form-item>
					<el-form-item label="value" prop="value">
				    <el-input v-model="ruleForm.value" ></el-input>
				  </el-form-item>
				  <el-form-item  >
				    <el-button type="primary" @click="submitForm('ruleForm')">确定</el-button>
				    <el-button @click="resetForm('ruleForm')">重置</el-button>
				  </el-form-item>
				</el-form>
	  </el-dialog>
	</div>
</template>

<script>
	import { mapState } from 'vuex' //通过vuex传过来的值  是好使的
	import { constPageData } from '@/util/comData'
	import { setTab } from '@/util/comRequest'
	export default{
		name:'const',
		computed:{
			...mapState({
                params: state => state.consts
            }),
		},
		data(){
			return constPageData()
		},
		created() {
      setTab(this,'consts');
			this.getTableData()
		},
		methods:{
			initForm(){
				for(var key in this.ruleForm){
            	  this.ruleForm[key]=''
            	}
			},
			getPageSize(val){
				this.searchForm.size=val
				this.getTableData()
			},
			getCurrentPage(val){
				this.searchForm.page=val
				this.getTableData()
			},
			//提交表单
			submitForm(formName) {
			   var  url=this.submitStatus=='add'?'/admin/consts/constAdd':'/admin/consts/constUpdate'
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
			            		for(var key in res.error){
		                    	  this.ruleForm[key]=''
		                    	  this.rules[key][0].message=res.msg[key]
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
							 		url: '/admin/consts/constsList',
							 		method: 'post',
							 		data: this.searchForm
							 }).then(res => {
							 		if(res.errcode==0){
										this.tableData.loading = false
										this.tableData.body=res.data.consts;
										this.searchForm.total=res.data.count
							 		}
							 }).catch(err => {
 	                console.log("出错了")
 	            });

	         },
				//搜索
		  	search(){
			  	this.getTableData()
	            console.log(`欲提交的数据    文本: ${this.searchForm.text}`)
	        },
	        //新增
	        add(){
	        	this.modelTitle='添加常量类型'
	        	this.submitStatus='add';
	        	this.isModelShow=true;
	        	this.initForm();
						this.ruleForm.type_id=this.tableData.body[0].type_id
	        },
					//删除
	        deleteRow(item){
	        	this.$confirm('确认关闭？').then(_ => {
	        		this.$axios({
		                url: '/admin/consts/constDel',
		                method: 'post',
		                data: {
										   id:item.row.id
										}
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
	        	this.modelTitle='修改常量类型'
	        	this.submitStatus='edit';
	        	this.$axios({
	                url: '/admin/consts/constEdit',
	                method: 'post',
	                data: {
									    id:item.row.id
									}
	            }).then(res => {
	            	if(res.errcode==0){
	            		for(var key in res.data){
	            			this.ruleForm[key]=res.data[key]
	            		}
	            	    this.isModelShow=true;
	            	}else{
	            		alert('编辑失败')
	            	}
	            })

	        }
		}

	}
</script>

<style>
</style>
