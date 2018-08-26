<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="图片管理"></app-title>
		 <!-- 搜索 -->
        <app-search>
            <el-form :inline="true" :model="searchForm">
                <el-form-item >
                    <el-input v-model="searchForm.search" placeholder="文件名"></el-input>
                </el-form-item>
								<el-form-item>
									<el-select v-model="searchForm.type_id" clearable placeholder="请选择">
										<el-option
											v-for="item in picTypes"
											:key="item.const_id"
											:label="item.name"
											:value="item.const_id">
										</el-option>
									</el-select>
								</el-form-item>
                <el-form-item >
                    <el-button type="primary" @click="search" >查询</el-button>
                </el-form-item>
            </el-form>
        </app-search>
         <!-- 工具条 -->
        <app-toolbar>
        	<el-button type="primary" icon="el-icon-plus" @click="add()" v-hasPermission="'system.picture.add'">新增</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}">
                <!--<el-table-column type="index" label="序号" width="64" align="center"></el-table-column>-->
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button  type="text" size="small" @click="edit(scope)" v-hasPermission="'system.picture.update'">编辑</el-button>
												<el-button  type="text" size="small" @click="deleteRow(scope)" v-hasPermission="'system.picture.del'">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>

        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
			  <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
					<!-- v-if="submitStatus=='add'"-->
						<el-form-item label="选择图片类型" prop="type_id" >
							<el-select v-model="ruleForm.type_id" clearable placeholder="请选择">
								<el-option
									v-for="item in picTypes"
									:key="item.const_id"
									:label="item.name"
									:value="item.const_id">
								</el-option>
							</el-select>
						</el-form-item>
					 <el-form-item>
						 <el-upload  label="图片" prop="url"
								class="avatar-uploader"
								action="string"
								:http-request="upload"
								:show-file-list="false"
								>
									<div  class="avatar">
										<img v-if="ruleForm.url" :src="ruleForm.url" @click="getIdx()" class="avatar-img">
										<i v-else class="el-icon-plus avatar-uploader-icon" @click="getIdx()"></i>
									</div>
								</el-upload>
					 </el-form-item>

					 <template v-if="submitStatus=='edit'">
						 <el-form-item label="图片路径" prop="url">
  						 <el-input v-model="ruleForm.url" type="text"></el-input>
  					 </el-form-item>
						 <el-form-item label="图片名称" prop="name">
							<el-input v-model="ruleForm.name" type="text"></el-input>
						</el-form-item>
					 </template>

					 <el-form-item label="跳转路径" prop="href">
						 <el-input v-model="ruleForm.href" type="text"></el-input>
					 </el-form-item>
				 <el-form-item label="图片说明" prop="remind_name">
					 <el-input type="textarea" v-model="ruleForm.remind_name"></el-input>
				 </el-form-item>
				 <el-form-item label="排序" prop="order">
						<el-input v-model="ruleForm.order"></el-input>
				 </el-form-item>
				 <el-form-item label="活动时间" prop="activeTime">
					   <el-date-picker
					      v-model="ruleForm.activeTime"
					      type="datetimerange"
					      range-separator="至"
								start-placeholder="开始日期"
					      end-placeholder="结束日期"
									value-format="yyyy-MM-dd HH:mm:ss"
					      :default-time="['12:00:00']">
					    </el-date-picker>
				 </el-form-item>
					<el-form-item label="状态" prop="status">
						<el-radio-group v-model='ruleForm.status'>
							<el-radio label="0">有效</el-radio>  <!--label加了冒号，传过来的值是数字，不能是字符串-->
							<el-radio label="1">无效</el-radio>
						</el-radio-group>
					</el-form-item>
					<el-form-item>
						<el-button type="primary" @click="submitForm('ruleForm')">确定</el-button>
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
				imageUrl: '',
				picTypes: [],
				modelTitle:'',
				submitStatus:'add',
				size:10,//每页多少条
				page:1,//当前页
				total:0,//总共多少条
				searchForm: {
			        search: '',
							type_id:''
			   },
			   tableData: {
	                loading: true,
	                head: [
								     {key:'name',name:'id'},
										 {key:'type_name',name:'类别'},
										 {key:'url',name:'目录'},
										 {key:'href',name:'跳转路径'},
	                   {key:'name',name:'名称'},
										 {key:'size',name:'大小'},
										 {key:'show_time_begin',name:'开始显示时间'},
										 {key:'show_time_end',name:'结束显示时间'},
	                   {key:'addtime',name:'上传时间'},
										 {key:'status',name:'状态'},
										 {key:'order',name:'排序'},
										 {key:'remind_name',name:'图片说明'}
	                ],
	                body: []
	           },
	           modelTitle:'添加常量类型',
	           isModelShow:false,
	           ruleForm: {
							  showImg:'',
                url:'',
			          href: '',
								name:'',
			          remind_name: '',
								order:'',
			          show_time_begin: '',
								show_time_end:'',
								addtime:'',
								status:'0',
								type_id:'',
								activeTime:[] //活动时间
		        },
		        rules: {
		         //  time: [
		         //    { required: true, message: 'name 必须填写.', trigger: 'blur' }
		         //  ],
							// showImg: [
		         //    { required: true, message: 'status 不能为空', trigger: 'change' }
		         //  ],
		         //  href: [
		         //    { required: true, message: 'summary 必须填写', trigger: 'blur' }
		         //  ],
							// name: [
							//  { required: true, message: 'remark 必须填写', trigger: 'blur' }
						 // ],
						 // remind_name: [
							//  { required: false, message: 'rule 可选', trigger: 'change' }
						 // ]
		       },
					 systemRules:[],
					 userRules:[],

			}
		},
		created() {
	        this.getTableData()
					this.getRoleList();
					this.getPicType();
	    },
		methods:{
			getIdx(){},
			//获取图片类型
		  getPicType(){
				this.$axios({
						 url: '/admin/picture/picType',
						 method: 'post',
						 data: {}
				 }).then(res => {
					 if(res.errcode == 0){
              this.picTypes=res.data
					 }
				 })
			},
			// 自定义文件上传的方式
		  upload (item) {
				var that = this;
				  var reader=new FileReader()
					 reader.onload = function(evt){
	            var src = evt.target.result;
							that.ruleForm.url=src;
	          }
	          reader.readAsDataURL(item.file);
		  },
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
				//初始化时间
			   var  url=this.submitStatus=='add'?'/admin/picture/picAdd':'/admin/picture/picUpdate'
		      this.$refs[formName].validate((valid) => {
		          if (valid) {
								var data = this.ruleForm
								//初始化时间
								data['id']=data['id']
								data['show_time_begin'] = data['activeTime'][0]
								data['show_time_end'] = data['activeTime'][1]

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
								 		url: '/admin/picture/lists',
								 		method: 'post',
								 		data: {
								 			page:this.page,
								 			size:this.size,
								 			search:this.searchForm.search
								 		}
								 }).then(res => {
									 console.log(res,'获取图片管理列表============')
								 		if(res.errcode==0){
								 				 this.tableData.loading = false
								 				 this.tableData.body=res.data.lists;
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
						//this.resetForm('ruleForm');//初始化表单验证   必须 放在初始化表单的前面
	        	this.modelTitle='选择上传的文件'
	        	this.submitStatus='add';
	        	this.isModelShow=true;
	         	this.initForm();
	        },
	        deleteRow(item){
	        	this.$confirm('确认删除？').then(_ => {
	        		this.$axios({
		                url: '/admin/picture/picDel',
		                method: 'post',
		                data: {
							id:item.row.id
						}
		            }).then(res => {
		            	if(res.errcode==0){
	        	           this.tableData.body.splice(item.$index,1)
		            	}else{
		            		alert(res.msg)
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
	                url: '/admin/picture/picEdit',
	                method: 'post',
	                data: {
									    id:item.row.id
									}
	            }).then(res => {
							  console.log(res,'编辑返回来的结果')
	            	if(res.errcode==0){
								  res=res.data
	            		for(var key in res){
	            			this.ruleForm[key]=res[key]
	            		}
									this.ruleForm['activeTime'][0]=res['show_time_begin']
									this.ruleForm['activeTime'][1]=res['show_time_end']
	            	  this.isModelShow=true;
	            	}else{
	            		alert('编辑失败')
	            	}
	            })

	        },
		}

	}
</script>

<style >
.avatar-uploader .el-upload {
    border: 1px dashed #e3e3e3;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
	.avatar-img{
		width:100%;
		height:100%;
	}
</style>
