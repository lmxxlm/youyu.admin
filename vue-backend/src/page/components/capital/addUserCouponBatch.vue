<template>
	<div class="sys-page">
		<app-title title='發送紅包'></app-title>
				<el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="200px" class="demo-ruleForm">
					  <el-form-item label="用户名（手机号）" prop="username" >
					    <el-input v-model="ruleForm.username"></el-input>
					  </el-form-item>
						<el-form-item label="年化券名称" prop="coupon_name" >
						 <el-input v-model="ruleForm.coupon_name"></el-input>
					 </el-form-item>
						<el-form-item label="年化率" prop="coupon">
					    <el-input v-model.number="ruleForm.coupon"></el-input>
					  </el-form-item>
						<el-form-item label="过期时间" required>
					    <el-col :span="11">
					      <el-form-item >
									<el-date-picker
							      v-model="ruleForm.timeout"
							      type="date"
							      placeholder="今日之前日期禁用"
										:picker-options="pickerOptions2"
							      value-format="yyyy-MM-dd">
							    </el-date-picker>
					      </el-form-item>
					    </el-col>
					  </el-form-item>
						<el-form-item label="起头金额" prop="money_minimun_limit">
							<el-input v-model.number="ruleForm.money_minimun_limit"></el-input>才能使用（0表示无限制）
						</el-form-item>
						<el-form-item label="最大限额<=" prop="money_limit">
							<el-input v-model.number="ruleForm.money_limit"></el-input>才能使用（0表示无限制）
						</el-form-item>
						<el-form-item label="投资期限>=" prop="borrow_days">
							<el-input v-model.number="ruleForm.borrow_days"></el-input>才能使用（0表示无限制）
						</el-form-item>
						<el-form-item label="限定投资类型" prop="borrow_limit">
							<el-checkbox-group v-model="ruleForm.borrow_limit">
						    <el-checkbox :label='item.id' v-for="item in ruleForm.types">{{item.name}}</el-checkbox>
						  </el-checkbox-group>
						</el-form-item>
					  <el-form-item>
					    <el-button type="primary" @click="submitForm('ruleForm')">提交</el-button>
							<el-button @click="resetForm('ruleForm')">重置表单</el-button>
					    <el-button @click="batchUpload()" >批量上传</el-button>
					  </el-form-item>
					</el-form>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { commonReq,ruleFormError,comReqGet,goDetail} from '@/util/comRequest'
	import { addUserCouponBatch} from '@/util/comData'
	export default{
		name:'const',
		data(){
			return addUserCouponBatch()
		},
		created(){
			this.getTypes()
		},
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
						var url = '/admin/capital/addUserCoupon'
						var data = this.ruleForm
            commonReq(url,data).then(res=>{
							if(res.errcode==0){
								this.$message.success('提交成功了')
							}else{
								ruleFormError(this,res)
							}
						})
          } else {
						this.$message.error('出错了')
            return false;
          }
        });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      },
			//获取所有的标类型
			getTypes(){
				comReqGet('/admin/capital/borrowType').then(res=>{
					 if(res.errcode == 0){
						 this.ruleForm.types=res.data
					 }
				})
			},
			//批量上传
			batchUpload(){
				var val={
						id:'',
						title:'发放年化券',
						name:'发放年化券',
						meta:this.$route.meta,
						localType:'sendReward'
				}
				var url ='/components/capital/sendRewardBatch'
				goDetail(this,url,val)
			}
		}
	}
</script>
