<template>
	<div class="sys-page">
		<!--标题-->
	 	<app-title title="缓存清理"></app-title>
    <table-mixin >
        <el-table v-loading="tableData.loading" :data="tableData.body" border  @cell-click="clearCache" >
            <el-table-column v-for="(item,index) in tableData.head" :prop="item.key" :label="item.name" sortable :key="index" ></el-table-column>
        </el-table>
    </table-mixin>
	</div>
</template>

<script>
	export default{
		name:'cache',
		data(){
			return {

			   tableData: {
	                loading: false,
	                head: [
								     {key:'title',name:'名称'},
	                ],
	                body: [
										{names:'文章',name:'site_list.cache'},
										{title:'官方公告',name:'index-p2pnews.cache'},
										{title:'媒体报道',name:'index-media_word.cache'},
										{title:'首页公告',name:'index-bulletin.cache'},
										{title:'媒体报道图片',name:'index-media_pic.cache'},
										{title:'合作伙伴',name:'index-partener.cache'},
										{title:'友情链接',name:'index-links.cache'},
										{title:'广告位图片',name:'index-image_friends.cache'},
										{title:'滚动图片WEB',name:'index-image_head.cache'},
										{title:'滚动图片WAP',name:'index-image_head_wap.cache'},
										{title:'系统设置',name:'system.cache'},
										{title:'地区缓存',name:'area.cache'},
										{title:'登录信息缓存',name:'login_info.cache'},
										{title:'抽奖缓存',name:'lottery_rate.cache'}
									]
	           },
	           modelTitle:'添加常量类型',
	           isModelShow:false,

			}
		},
		methods:{
			//清缓存
			clearCache(row){
				this.$axios({
						 url: '/admin/cache/cacheDel',
						 method: 'post',
						 data: {
							 name:row.name
						 }
				 }).then(res => {
					 if(res.errcode == 0){
					     this.$message.success('清理缓存成功');
					 }else{
               this.$message.error('清理缓存失败');
					 }
				 })
			}
		}

	}
</script>

<style>
</style>
