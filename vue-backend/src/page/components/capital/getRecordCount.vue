<template>
	<div class="sys-page">
		<!--标题-->
		<app-title title=" 客户的资金流水"></app-title>
				<!-- 表格体 -->
			<table-mixin>
					<el-table v-loading="tableAcountData.loading" :data="tableAcountData.body" border >
							<el-table-column v-for="(item,index) in tableAcountData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
					</el-table>
			</table-mixin>
				<app-title title="客户的资金月流水记录"></app-title>
          <!-- 表格体 -->
        <table-mixin >
            <el-table v-loading="tableData.loading" :data="tableData.body" border :default-sort="{prop: 'date', order: 'descending'}" >
                <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
            </el-table>
						<el-table :data="tableData1.body" >
							<el-table-column v-for="(item,index) in tableData1.head" :prop="item.key" :label="item.name" sortable :key="index"></el-table-column>
						</el-table>
        </table-mixin>
	</div>
</template>

<script>
	import { mapState } from 'vuex'
	import { commonReq ,setTab} from '@/util/comRequest'
	import { getRecordCount } from '@/util/comData'
	export default{
		name:'const',
		computed:{
			...mapState({
          params: state => state.consts
      }),
		},
		data(){
			return getRecordCount()
		},
		created() {
        setTab(this,'getRecordCount')
				this.getAccountCount()
				this.getAccountRecord()
		},
		methods:{
			getPageSize(val){
				this.searchForm.size=val
				this.getTableData()
			},
			getCurrentPage(val){
				this.searchForm.page=val
				this.getTableData()
			},
			//客户的资金流水
			getAccountCount(){
				var url = '/admin/customer/accountCount'
				var data = this.searchForm
				this.getTableData(url,data,'accountCount')
			},
			//资金记录
			getAccountRecord(){
				var url = '/admin/customer/getRecordCount'
				var data = this.searchForm
				this.getTableData(url,data,'getRecordCount')
			},
			 // 获取table数据
       getTableData(url,data,type) {
				  commonReq(url,data).then(res =>{
						if(type=='accountCount'){
							this.tableAcountData.body=[]
							this.tableAcountData.loading = false
							this.tableAcountData.body.push(res)
						}else{
							if(res.errcode==0){
								this.tableData.loading = false
								this.max=res.data.max.max
								this.cur=res.data.max.cur
								this.tableData1.body=[{title:'仅计算充值',max:this.max,cur:this.cur}]
								var list = []
								res=res.data.monthData
								for(var item in res){
									res[item].month=item
									list.push(res[item])
								}
								this.tableData.body=list;

               	this.$message.success('获得数据成功');
					 		}else{
								this.$message.success('获得数据失败');
							}
						}
					})
     },
		}
	}
</script>

<style>
</style>
