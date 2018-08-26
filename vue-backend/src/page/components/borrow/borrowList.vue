<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="管理员列表"></app-title>
		 <!-- 搜索 -->
        <app-search>
            <el-form :inline="true" :model="searchForm" class="customerSearch">
							<el-form-item prop="username">
									<el-input  v-model="searchForm.username" placeholder="借款人"></el-input>
							</el-form-item>
							<el-form-item prop="name">
									<el-input  v-model="searchForm.name" placeholder="标题"></el-input>
							</el-form-item>
							<el-select v-model="searchForm.real_state"  clearable placeholder="请选择">
								<el-option
									v-for="item in searchForm.sts"
									:key="item.name"
									:label="item.name"
									:value="item.type">
								</el-option>
							</el-select>
							<el-form-item prop="money_begin">
									<el-input  v-model="searchForm.money_begin" placeholder="借款金额起"></el-input>
							</el-form-item>
							<el-form-item prop="money_end">
									<el-input  v-model="searchForm.money_end" placeholder="借款金额止"></el-input>
							</el-form-item>
							<el-form-item prop="time_limit_begin">
									<el-input  v-model="searchForm.time_limit_begin" placeholder="借款期限起"></el-input>
							</el-form-item>
							<el-form-item prop="time_limit_end">
									<el-input  v-model="searchForm.time_limit_end" placeholder="借款期限止"></el-input>
							</el-form-item>
							<el-form-item prop="apr">
									<el-input  v-model="searchForm.apr" placeholder="利率"></el-input>
							</el-form-item>
						  <el-form-item >
                  <el-button type="primary" @click="search" >查询</el-button>
              </el-form-item>
						</el-form>
        </app-search>
         <!-- 工具条 -->
        <app-toolbar>
					<el-button type="primary" @click="handleDownload()" >导出</el-button>
					<el-button type="primary" @click="add()" >新增</el-button>
        </app-toolbar>
          <!-- 表格体 -->
        <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}" >
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作" fixed="right" width="150px">
                    <template slot-scope="scope">
										  	<el-button  type="text" size="small"  >协议书</el-button>
												<el-button  type="text" size="small" @click="verity(scope)" >审核</el-button>
                        <el-button  type="text" size="small" @click="edit(scope)" >编辑</el-button>
												<el-button  type="text" size="small" @click="del(scope)" >废除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>

        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
					<el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
						  <el-form-item label="活动名称" prop="username">
								<el-col :span="11">
									<el-input v-model="ruleForm.username" width="100px" ></el-input>
								</el-col>
								<el-col :span="11">
									<el-input width="100px" v-model="ruleForm.realname"></el-input>
								</el-col>
						  </el-form-item>
							<el-form-item label="借款类型" prop="borrow_type">
								 <el-radio-group v-model="ruleForm.borrow_type">
							     <el-radio v-for='(item,index) in ruleForm.borrow_types'  :label="item.type"  :key="item.type">{{item.name}}</el-radio>
							   </el-radio-group>
						  </el-form-item>
							<el-form-item label="标类型" prop="new_hand">
								 <el-radio-group v-model="ruleForm.new_hand">
							     <el-radio v-for='(item,index) in ruleForm.new_hands'  :label="item.type"  :key="item.type">{{item.name}}</el-radio>
							   </el-radio-group>
						  </el-form-item>
						  <el-form-item label="还款方式" prop="region">
						    <el-select v-model="ruleForm.style" placeholder="请选择">
						      <el-option :label="item.name" :value="item.type" v-for="item in ruleForm.styles"></el-option>
						    </el-select>
						  </el-form-item>

							<el-form-item label="借款总金额" prop="account">
								<el-col :span="11">
									<el-input v-model="ruleForm.account" width="100px" ></el-input>
								</el-col>
						  </el-form-item>
							<el-form-item label="借款用途" prop="use">
								<el-col :span="11">
									<el-input v-model="ruleForm.use" width="100px" ></el-input>
								</el-col>
						  </el-form-item>
							<el-form-item label="原始利率" prop="original_rate">
								<el-col :span="11">
									<el-input v-model="ruleForm.original_rate" width="100px" ></el-input>
								</el-col>
								<el-col :span="1">%</el-col>
						  </el-form-item>
							<el-form-item label="贴息利率" prop="discount_rate">
								<el-col :span="11">
									<el-input v-model="ruleForm.discount_rate" width="100px" ></el-input>
								</el-col>
								<el-col :span="11">%(选填)</el-col>
						  </el-form-item>

							<el-form-item label="最低投标金额" prop="lowest_account">
							 <el-select v-model="ruleForm.lowest_account" placeholder="请选择">
								 <el-option :label="item.name" :value="item.type" v-for="item in ruleForm.lowest_accounts"></el-option>
							 </el-select>
						  </el-form-item>
							<el-form-item label="最多投标总额" prop="most_account">
							 <el-select v-model="ruleForm.most_account" placeholder="请选择">
								 <el-option :label="item.name" :value="item.type" v-for="item in ruleForm.most_accounts"></el-option>
							 </el-select>
						  </el-form-item>
							<el-form-item label="专投手机号" prop="tender_user">
								<el-col :span="11">
									<el-input v-model="ruleForm.tender_user" width="100px" ></el-input>
								</el-col>
								<el-col :span="11">多个以英文分号(;)隔开</el-col>
							</el-form-item>
							<el-form-item label="专投渠道号" prop="invite_user">
								<el-col :span="11">
									<el-input v-model="ruleForm.invite_user" width="100px" ></el-input>
								</el-col>
								<el-col class="line" :span="2"></el-col>
								<el-col :span="11">多个以英文分号(;)隔开</el-col>
							</el-form-item>
							<el-form-item label="原合同编号" prop="contract_no">
								<el-col :span="11">
									<el-input v-model="ruleForm.contract_no" width="100px" ></el-input>
								</el-col>
							</el-form-item>
							<el-form-item label="债劵抵(质)押信息" prop="pawn">
									<el-input v-model="ruleForm.pawn" width="100px" ></el-input>
							</el-form-item>
							<el-form-item label="缩略图" prop="litpic" >
									<el-input v-model="ruleForm.litpic" width="100px" :disabled="true"></el-input>
							</el-form-item>
						  <el-form-item class="showSmallPic">
									<el-upload
									  action="/admin/borrow/borrowEdit"
									  list-type="picture-card"
										:multiple=true
										:file-list="ruleForm.imgList"
									  :on-remove="handleRemove"
										:on-change="changeUpload"
										:http-request="uploadImg"
										>
										<!-- :auto-upload=false -->
									  <i class="el-icon-plus"></i>
									</el-upload>
									<el-dialog :visible.sync="dialogVisible" v-for="item in ruleForm.litpics">
									  <img width="100%" :src="item" alt="">
									</el-dialog>
						  </el-form-item>
							<el-form-item label="标投放开始时间" required>
						    <el-col :span="11">
						      <el-form-item prop="start_time">
						        <el-date-picker type="date" placeholder="选择日期" v-model="ruleForm.start_time" style="width: 100%;" value-format="yyyy-MM-dd"></el-date-picker>
						      </el-form-item>
						    </el-col>
						  </el-form-item>
							<el-form-item label="标期" prop="time_limit_day">
								<el-col :span="11">
									<el-input v-model="ruleForm.time_limit_day" width="100px" ></el-input>
								</el-col>
								<el-col class="line" :span="2"></el-col>
								<el-col :span="11"> 天</el-col>
							</el-form-item>

							<app-title title="借款说明"></app-title>
							<el-form-item label="借款标题" prop="name" >
									<el-input v-model="ruleForm.name" width="100px" ></el-input>
							</el-form-item>
							<el-form-item label="标记" prop="sign" >
									<el-input v-model="ruleForm.sign" width="100px" ></el-input>
							</el-form-item>
							<el-form-item label="内容描述" prop="content" >
									<quill-editor ref="myTextEditor" v-model="ruleForm.content" :options="editorOption"></quill-editor>
							</el-form-item>
						  <el-form-item>
						    <el-button type="primary" @click="submitForm('ruleForm')">立即创建</el-button>
						    <el-button @click="resetForm('ruleForm')">重置</el-button>
						  </el-form-item>
						</el-form>
		  </el-dialog>

			<!-- 审核 -->
			<el-dialog title='提现审核/查看' :visible.sync="isModelShowSee">
	 			 <table class="seeTable">
	          <tr v-for="item in ruleFormList">
	          	<td>{{item.name}}</td>
	 				  	<td >
								<div class="shWrapImg" v-if="item.name=='缩略图'" >
									<img :src="itemChild" alt="" v-for="itemChild in item.val" >
								</div>
								<span v-else>{{item.val}}</span>
	 				  	</td>
	          </tr>
	 			 </table>
				 <app-title title="管理员列表"></app-title>
				 <el-form :model="ruleFormSee" :rules="rulesSee" ref="ruleFormSee" label-width="100px" class="demo-ruleForm">
					 <el-form-item label="状态" prop="real_state">
						 <el-radio-group v-model="ruleFormSee.real_state">
					    <el-radio :label="2">审核通过 </el-radio>
					    <el-radio :label="1">审核不通过</el-radio>
					  </el-radio-group>
					 </el-form-item>
					 <el-form-item label="推荐标" prop="recomend_flg">
						 <el-radio-group v-model="ruleFormSee.recomend_flg">
					    <el-radio :label="1">是 </el-radio>
					    <el-radio :label="0">否</el-radio>
					  </el-radio-group>
					 </el-form-item>
					 <el-form-item label="发布时间" prop="success_time">
						<el-date-picker
							v-model="ruleFormSee.success_time"
							type="datetime"
							value-format="yyyy-MM-dd HH:mm"
							placeholder="选择日期时间">
						</el-date-picker>
					</el-form-item>
					<el-form-item label="审核备注" prop="verify_remark">
						 <el-input v-model="ruleFormSee.verify_remark" width="100px" ></el-input>
					</el-form-item>
					<el-form-item>
						<el-button type="primary" @click="submitForm('ruleFormSee')">立即创建</el-button>
					</el-form-item>
				 </el-form>
 		 </el-dialog>
	</div>
</template>

<script>
  import axios from 'axios'
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,exportExcel,goDetail,ruleFormError,isEmpty} from '@/util/comRequest'
	import { borrowList} from '@/util/comData'
	import 'quill/dist/quill.core.css';
	import 'quill/dist/quill.snow.css';
	import 'quill/dist/quill.bubble.css';
	import { quillEditor } from 'vue-quill-editor';
	export default{
		name:'const',
		computed:{
			...mapState({
          currentPage: state => state.pagination.currentPage,
          sizePage: state => state.pagination.sizePage,
      }),
		},
		data(){
			return borrowList()
		},
		created() {
					//给表单初始值赋值
					if(isEmpty(this.ruleFormInit)){
						this.ruleFormInit=this.ruleForm
					}
	       this.getTableData()
	  },
		components: {
				quillEditor
		},
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			 uploadImg(f){
         this.param.append('files[]',f.file);//通过append向form对象添加数据
			 },
			 //移除图片
			 handleRemove(file, fileList) {
           var index = this.ruleForm.litpics.indexOf(file.url)
					 if(index>-1){
						 commonReq('/admin/borrow/borrowPicDel',{id:this.ruleForm.id,index:index}).then((res)=>{
							 if(res.errcode==0){
								 this.$message.success(res.msg)
							 }else{
								 this.$message.error(res.msg)
							 }
						 })
					 }

			 },
			 //放大图片
			 handlePictureCardPreview(file) {
				 this.ruleForm.litpics.push(file.url)
				 this.dialogImageUrl = file.url;
				 this.dialogVisible = true;
			 },
			 //上传
			 changeUpload(file, fileList){
				 this.ruleForm.files=fileList
			 },
				//审核
				verity(item){
					this.ruleFormSee.id=item.row.id
					commonReq('/admin/borrow/borrowEdit',{id:item.row.id}).then((res)=>{
						this.isModelShowSee=true
						if(res.errcode == 0){
							for(var item in res.data){
								for(var child of this.ruleFormList ){
									if(item==child.key){
										child.val=res.data[item]
									}
									if(child.key=='addtimeOrIp'){
										child.val=res.data.addtime+(res.data.addip!=void 0?'/'+res.data.addip:'')
									}
								}
							}
						}else{
							this.$message.error(res.data);
						}
					})
				},
				//导出表格
				handleDownload() {
					var that = this
					var url='/admin/borrow/borrowListExport'
					var data = this.searchForm
					exportExcel(that,url,data)//导出的结果
				},
			// 初始化表单
				initForm(){
					this.ruleForm=this.ruleFormInit
				},
				//新增
				add(){
					this.initForm();
					this.modelTitle='选择上传的文件'
					this.submitStatus='add';
					this.isModelShow=true;
					this.resetForm('ruleForm');//初始化表单验证   必须 放在初始化表单的前面
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
					  var url='',model ='',data ={};
						var diffData={url:'',model:'',form:'',name:''}
						if(formName=='ruleFormSee'){
							diffData.url='/admin/borrow/borrowCheck'
							diffData.model='isModelShowSee'
							diffData.form='ruleFormSee'
							diffData.name='See'
						}else{
							diffData.url=this.submitStatus=='add'?'/admin/borrow/borrowAdd	':'/admin/borrow/borrowUpdate'
							diffData.model='isModelShow'
							diffData.form='ruleForm'
						}
						for(var item in this[diffData.form]){
							this.param.append(item, this[diffData.form][item]);
						}
			      this.$refs[formName].validate((valid) => {
		          if (valid) {
								axios.post(diffData.url,this.param).then(res => {
									 	if(res.data.errcode == 0){
								  		this[diffData.model]=false;
											this.$message.success(res.data.msg)
								  		this.getTableData()
								  	}else{
											console.log(res)
											ruleFormError(this,res.data,diffData.name)
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
						if(this.$refs[formName]!=void 0){
							this.$refs[formName].resetFields();
						}
		      },
			 // 获取table数据
         getTableData() {
							 this.$axios({
							 		url: '/admin/borrow/borrowList',
							 		method: 'post',
							 		data: this.searchForm
							 }).then(res => {
							 		if(res.errcode==0){
										   this.$message.success('获取表格数据成功')
							 				 this.tableData.loading = false
											 for(var item in res.data.res){
												 res.data.res[item].time=res.data.res[item].start_time+'/'+res.data.res[item].end_time
											 }
							 				 this.tableData.body=res.data.res;
							 				 this.searchForm.total=parseInt(res.data.count)
							 		}else{
										this.$message.error('获取表格数据失败')
									}
							 })
         },
				//搜索
		    	search(){
			  	    this.getTableData()
	        },
					//删除
					del(item){//批量删除不给他放参数也会传过来值  就是事件对象
						this.$confirm('确认删除？', "提示", {
			          type: "warning"
			        }).then(() => {
								commonReq('/admin/borrow/borrowDel',{id:item.row.id}).then((res)=>{
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
	        	this.modelTitle='编辑'
	        	this.submitStatus='edit';
						this.ruleForm.id=item.row.id;
						commonReq('/admin/borrow/borrowEdit',{id:item.row.id}).then((res)=>{
							if(res.errcode==0){
								res=res.data
								for(var key in res){
									this.ruleForm[key]=res[key]
								}
								this.isModelShow=true;
								this.ruleForm.imgList=[]
								for(var item of this.ruleForm.litpics){
									this.ruleForm.imgList.push({name:'',url:item})
								}
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
		.el-upload--picture-card{width:100px;height:100px;line-height:100px;}
		.el-upload-list--picture-card .el-upload-list__item{width:100px;height:100px;}
</style>
<style scoped>
	 .seeTable{
		width:100%;
	 }
	 .seeTable tr:nth-child(2n+1){
			background:#e3e3e3;
	 }
	 .seeTable tr{padding:5px;}

	 .shWrapImg img{display:inline-block;width:20px;height:20px;border:1px solid red;margin-right:5px;}
</style>
