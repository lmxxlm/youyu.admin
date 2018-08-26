<template>
	<div class="sys-page">
		<app-title :title='curTitle'></app-title>
		<el-collapse v-model="activeNames" >
					<el-collapse-item :title="index=='accounts'?'账户':(index=='invests'?'投资':(index=='repays'?'还款':'红包'))" :name="index" v-for="(item,index) in counts">
						 <table border="1" class="smallTable">
								<tr>
									<th>分类</th>
									<th>金额</th>
								</tr>
								<tr v-for="(itemchild,childIndex) in item">
									<!-- <td>{{childIndex=='no_use_moneys'?'未用':(childIndex=='used'?'已使用':(childIndex=='unused'?'未使用':'已过期'))}}</td> -->
									<td>{{getVal(childIndex)}}</td>
									<td>{{itemchild}}</td>
								</tr>
						 </table>
				</el-collapse-item>
			</el-collapse>
	</div>
</template>

<script>
	import { mapState ,mapMutations} from 'vuex'
	import { copy,commonReq ,getNowFormatDate,exportExcel} from '@/util/comRequest'
	import { overview} from '@/util/comData'
	export default{
		data(){
			return overview()
		},
		created() {
	        this.getCount()
	    },
		methods:{
		 	getVal(val){
				var resVal=''
				for(var item of this.resList){
					if(item.key==val){
						resVal=item.name
					}
				}
				return resVal;
			},
			 //获得红包统计
			 getCount(){
				 this.$axios({
						url: '/admin/capital/overview',
						method: 'post',
						data:{}
				 }).then(res => {
						if(res.errcode==0){
							this.counts = res.data;
						}
				 })
			 },
		}

	}
</script>

<style >
    .customerSearch .el-select {width:130px;}
		.customerSearch .input-with-select .el-input-group_prepend{background-color:#fff;	}
		.smallTable{width:100%;}
		.smallTable td{padding-left:10px;}
		.smallTable th{font-weight:bold;}
		.smallTable tr:nth-child(even){background:#e3e3e3;}


		.sum {padding:20px 10px;}
		.sum .money{margin-left:100px;}
</style>
