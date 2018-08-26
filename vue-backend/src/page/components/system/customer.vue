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
        	<el-button type="primary" icon="el-icon-plus" @click="add()">新增</el-button>
					<el-button type="primary"  @click="handleDownload()">导出</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}">
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button  type="text" size="small" @click="edit(scope)">编辑</el-button>
                        <el-button   type="text" size="small" @click="goConst(scope)">常量</el-button>
												<el-button   type="text" size="small" @click="copy()">复制</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>
        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
				 <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
					  <el-form-item label="name" prop="name" width="60%">
					    <el-input v-model="ruleForm.name" ></el-input>
					  </el-form-item>
					   <el-form-item label="nid" prop="nid">
					    <el-input v-model="ruleForm.nid" ></el-input>
					  </el-form-item>
					  <el-form-item label="order" prop="order">
					    <el-input v-model="ruleForm.order" ></el-input>
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
	import { mapState , mapMutations} from 'vuex'
	import { parseTime } from '@/util'
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
	                   {key:'id',name:'id'},
	                   {key:'name',name:'name'},
										 {key:'nid',name:'nid'},
	                ],
	                body: []
	           },
	           modelTitle:'添加常量类型',
	           isModelShow:false,
	           ruleForm: {
		          name: '',
		          nid: '',
		          order: '',
		        },
		        rules: {
		          name: [
		            { required: true, message: 'name 必须填写.', trigger: 'blur' },
		            { min: 0, max: 30, message: '长度在 0到30字符', trigger: 'blur' }
		          ],
		          nid: [
		            { required: true, message: '字符nid 必须填写', trigger: 'blur' },
		            { min: 0, max: 50, message: '长度在 0到50字符', trigger: 'blur' }
		          ],
		          order: [
		            { required: true, message: 'order 必须填写', trigger: 'blur' },
		            { type: 'string',message: '*',trigger: 'blur' }
		          ],
		        },
			}
		},
		created() {
	        this.getTableData()
	    },
		methods:{
		...mapMutations({
				 setParams: 'consts/setParams'
		 }),
		 //导出表格
		 handleDownload() {
			 this.downloadLoading = true
			 import('@/vendor/Export2Excel').then(excel => {
				 const tHeader = ['timestamp','id', 'name', 'nid'] //标题  时间戳给有时间的选项
				 const filterVal = ['timestamp','id', 'name', 'nid'] //标题的key
				 const data = this.formatJson(filterVal, this.tableData.body)  //页面的table数据
				 excel.export_json_to_excel({
					 header: tHeader,
					 data,
					 filename: 'table-list'
				 })
				 this.downloadLoading = false
			 })
		 },
		 formatJson(filterVal, jsonData) {
			return jsonData.map(v => filterVal.map(j => {
				if (j === 'timestamp') {
					return parseTime(v[j])
				} else {
					return v[j]
				}
			}))
		},
		 //复制
		 copy(){
		     var text = 'hsgdhgdsd';
         var oInput = document.createElement('input');
         oInput.value = text;
         document.body.appendChild(oInput);
         oInput.select(); // 选择对象
         document.execCommand("Copy"); // 执行浏览器复制命令
         oInput.className = 'oInput';
         oInput.style.display='none';
			   document.execCommand("copy");
		 },
			initForm(){
				for(var key in this.ruleForm){
            	  this.ruleForm[key]=''
            	}
			},
			getPageSize(val){
				this.size=val
				this.getTableData()
			},
			getCurrentPage(val){
				this.page=val
				this.getTableData()
			},
			//提交表单
			submitForm(formName) {
			   var  url=this.submitStatus=='add'?'/admin/consts/typeAdd':'/admin/consts/typeUpdate'
		      this.$refs[formName].validate((valid) => {
		          if (valid) {
		          	 this.$axios({
			                url: url,
			                method: 'post',
			                data: this.ruleForm
			            }).then(res => {
			            	console.log(res,'添加成功')
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
	                url: '/admin/consts/constsType',
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
	            }).catch(err => {
	                console.log("出错了")
	            })

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
	        },
	        deleteRow(item){
	        	this.$confirm('确认关闭？').then(_ => {
	        		this.$axios({
		                url: '/admin/system/delete',
		                method: 'post',
		                data: item.row.id
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
	                url: '/admin/consts/typeEdit',
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

	        },
					//去常量页面
					goConst(item){
					    this.$router.push({path:'/components/system/constPage',query:{id:item.row.id,title:'公告分类-常量列表',name:'公告分类-常量列表'}});
							var val={
							    id:item.row.id,
									title:'公告分类-常量列表',
									name:'公告分类-常量列表'
							}
							this.setParams(val)
					}
		}

	}
</script>

<style>
</style>
