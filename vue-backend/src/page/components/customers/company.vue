<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title="企业列表"></app-title>
		 <!-- 搜索 -->
        <app-search>
            <el-form :inline="true" :model="searchForm" class="customerSearch">
							<el-form-item >
									<el-input v-model="searchForm.username" placeholder="用户名"></el-input>
							</el-form-item>
							<el-form-item >
									<el-input v-model="searchForm.realname" placeholder="姓名"></el-input>
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
							 <el-select v-model="searchForm.real_status"  clearable placeholder="请选择">
								 <el-option
									 v-for="item in searchForm.options"
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
        <table-mixin pagination paginationAlign="center" :paginationTotal="searchForm.total" @getSize="getPageSize" @getCurrentPage="getCurrentPage">
            <el-table v-loading="tableData.loading" :data="tableData.body" border >
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
                <el-table-column label="操作" fixed="right" width="150px">
                    <template slot-scope="scope">
                        <el-button  type="text" size="small" @click="edit(scope)" v-hasPermission="'customer.company.edit'">认证/编辑</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-mixin>
        <!--弹出框-->
        <el-dialog :title='modelTitle' :visible.sync="isModelShow">
				  <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"   class="demo-ruleForm">
						<app-title title="审核企业"></app-title>
						<el-row>
							<el-col :span="12">
								<el-form-item label="用户名" prop="id" width="60%">
							    <el-input v-model="ruleForm.id" ></el-input>
							  </el-form-item>
							</el-col>
							<el-col :span="12">
								<el-form-item label="企业名称" prop="realname">
								 <el-input v-model="ruleForm.realname" ></el-input>
							 </el-form-item>
							</el-col>
						</el-row>

            <el-row>
							<el-col :span="12">
								<el-form-item label="企业执照" prop="company_workyear">
										<img :src="ruleForm.litpic" alt="" >
								</el-form-item>
							</el-col>
							<el-col :span="12">
								<el-form-item label="税务登记证" prop="company_worktime2">
										<img :src="ruleForm.ability" alt="" >
								</el-form-item>
							</el-col>
						</el-row>
						<el-row>
							<el-col :span="12">
								<el-form-item label="上年营业收入（万元）" prop="company_workyear">
								 <el-input  v-model="ruleForm.company_workyear" ></el-input>
							 </el-form-item>
							</el-col>
							<el-col :span="12">
								<el-form-item label="上年利润总额（万元）"  prop="company_worktime1">
									<el-input  v-model="ruleForm.company_worktime1" ></el-input>
								</el-form-item>
							</el-col>
						</el-row>
						<el-row>
							<el-col :span="12">
								<el-form-item label="上年净资产(万元)" prop="company_worktime2">
									<el-input  v-model="ruleForm.company_worktime2" ></el-input>
								</el-form-item>
							</el-col>
							<el-col :span="12">
								<el-form-item label="员工人数" prop="private_employee">
		 						  <el-input v-model="ruleForm.private_employee" ></el-input>
		 					  </el-form-item>
							</el-col>

						</el-row>
						<el-row>
							<el-col :span="12">
								<el-form-item label="联系人" prop="linkman1">
									<el-input v-model="ruleForm.linkman1" ></el-input>
								</el-form-item>
							</el-col>
							<el-col :span="12">
								<el-form-item label="职位" prop="relation1">
									<el-input v-model="ruleForm.relation1" ></el-input>
								</el-form-item>
							</el-col>
						</el-row>
						<el-row>
							<el-col :span="12">
								<el-form-item label="联系电话" prop="tel1">
									<el-input v-model="ruleForm.tel1"  ></el-input>
								</el-form-item>
							</el-col>
							 <el-col :span="12">
								 <el-form-item label="企业简介" prop="company_reamrk">
		 							<el-input v-model="ruleForm.company_reamrk" ></el-input>
		 						</el-form-item>
							 </el-col>
						 </el-row>
						 <app-title title="审核员填写"></app-title>
						 <el-row>
 							 <el-col :span="12">
 								 <el-form-item label="法人代表" prop="company_name">
 		 							<el-input v-model="ruleForm.company_name" ></el-input>
 		 						</el-form-item>
 							 </el-col>
 							 <el-col :span="12">
 								 <el-form-item label="税号" prop="name">
 									 <el-input v-model="ruleForm.name" ></el-input>
 								 </el-form-item>
 							 </el-col>
 						 </el-row>
						 <el-row>
 							 <el-col :span="12">
 								 <el-form-item label="地区">
									 <el-cascader  :options="citys"  :change-on-select=false  @change="getCityVal"></el-cascader>
 		 						</el-form-item>
 							 </el-col>
 							 <el-col :span="12">
 								 <el-form-item label="地址" prop="others">
 									 <el-input v-model="ruleForm.others" ></el-input>
 								 </el-form-item>
 							 </el-col>
 						 </el-row>
						 <el-row>
 							 <el-col :span="12">
 								 <el-form-item label="备注" prop="company_workyear">
 		 							<el-input type="textarea" v-model="ruleForm.company_workyear" ></el-input>
 		 						</el-form-item>
 							 </el-col>
							 <el-col :span="12" v-if="ruleForm.real_status==0" class="statusRadio">
 								 <el-form-item label="状态" prop="status">
									 <el-radio-group v-model="ruleForm.status">
								     <el-radio :label="1">审核通过</el-radio>
								     <el-radio :label="2">审核不通过</el-radio>
								   </el-radio-group>
 		 						</el-form-item>
 							 </el-col>
 						 </el-row>
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
	import { companyListData} from '@/util/comData'
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
			return companyListData()
		},
		created() {
	        this.getTableData()
					this.getCitys()
	    },
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			 //获取选中的值
			getCityVal(val) {
				this.ruleForm.province=val[0]
				this.ruleForm.city=val[1]
				this.ruleForm.area=val[2]
			},
			 //获取城市列表
			 getCitys(){
				 this.$axios({
						url: '/admin/area/getAllArea',
						method: 'get',
						data: {}
				 }).then(res => {
					  function flatNavList(arr){
								 for(let v of arr){
									 v.label=v.name
									 v.value=v.id
									 if(v.child && v.child.length){
											 v.children=v.child
											 delete v.child
											 flatNavList(v.children)
									 }
								 }
						 }
						flatNavList(res)
					  this.citys=res
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
			   var  url='/admin/customer/companyUpdate'
		      this.$refs[formName].validate((valid) => {
		          if (valid) {
								commonReq(url,this.ruleForm).then(res=>{
									console.log(res,'====')
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
			 // 获取table数据
         getTableData() {
					     var url="/admin/customer/company"
							 var data=this.searchForm
					     commonReq(url,data).then(res=>{
									 if(res.errcode==0){
												this.tableData.loading = false
												this.tableData.body=res.data.lists
												this.searchForm.total=res.data.count
												this.$message.success('获取数据成功')
									 }else{
										 this.$message.error('获取数据失败');
									 }
							 })
         },
				//搜索
		  	 search(){ this.getTableData() },
	        //编辑
	        edit(item){
	        	this.modelTitle='企业编辑'
	        	this.submitStatus='edit';
						var url="/admin/customer/companyEdit"
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

<style scoped>
.statusRadio .el-radio + .el-radio{margin-left:0;}
</style>
