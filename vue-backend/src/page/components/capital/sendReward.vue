<template>
	<div class="sys-page">
		<app-title title='發送紅包'></app-title>
				<el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="200px" class="demo-ruleForm">
						<el-form-item label="类型" prop="use_together" >
							<el-radio-group v-model="ruleForm.use_together">
								<el-radio label="1"> 红包券</el-radio>
								<el-radio label="2">现金券</el-radio>
							</el-radio-group>
						</el-form-item>
					  <el-form-item label="用户名（手机号）" prop="username" >
					    <el-input v-model="ruleForm.username"></el-input>
					  </el-form-item>
						<el-form-item label="金额" prop="money">
					    <el-input v-model.number="ruleForm.money"></el-input>
					  </el-form-item>
						<el-form-item label="红包类型" prop="name">
							 <p>活动奖励</p>
						</el-form-item>
						<el-form-item label="过期时间" required>
					    <el-col :span="11">
					      <el-form-item >
									<el-date-picker
							      v-model="ruleForm.timeout"
							      type="date"
							      placeholder="禁止选择今日之前的时间"
										:picker-options="pickerOptions2"
							      value-format="yyyy-MM-dd">
							    </el-date-picker>
					      </el-form-item>
					    </el-col>
					  </el-form-item>
						<el-form-item label="红包说明" prop="reward_name">
							<el-input v-model="ruleForm.reward_name"></el-input>
						</el-form-item>
						<el-form-item label="红包限额" prop="money_limit">
							<el-input v-model.number="ruleForm.money_limit"></el-input>
						</el-form-item>
						<el-form-item label="投资期限" prop="borrow_days">
							<el-input v-model.number="ruleForm.borrow_days"></el-input>
						</el-form-item>
					  <el-form-item>
					    <el-button type="primary" @click="submitForm('ruleForm')">提交</el-button>
							<el-button @click="resetForm('ruleForm')">重置表单</el-button>
					    <el-button @click="batchUpload()" v-hasPermission="'capital.sendReward.batch'">批量上传</el-button>
					  </el-form-item>
					</el-form>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { commonReq,ruleFormError,goDetail} from '@/util/comRequest'
	import { sendRewardData} from '@/util/comData'
	export default{
		name:'const',
		data(){
			return sendRewardData()
		},
		methods:{
			...mapMutations({
					 setParams: 'consts/setParams'
			 }),
			submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
						var url = '/admin/capital/sendReward'
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
			//批量删除
			batchUpload(){
				var val={
						id:'',
						title:'发放红包',
						name:'发放红包',
						meta:this.$route.meta,
						localType:'sendReward'
				}
				var url ='/components/capital/sendRewardBatch'
				goDetail(this,url,val)
			}
		}
	}
</script>
