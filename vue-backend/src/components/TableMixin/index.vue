<template>
    <div class="sys-table">
        <slot></slot>
        <div class="sys-table-pagination">
            <template v-if="pagination">
                <el-pagination
                    :layout="pageLayout"
                    :total="paginationTotal"
                    :page-size="pageSize"
                    @size-change="sizeChange1"
                    @current-change="pageChange1"
                    :class="align"
                   >
                </el-pagination>
            </template>
            <template v-else>
                <slot name="pagination"></slot>
            </template>
        </div>
    </div>
</template>

<script>
import { mapMutations } from 'vuex'
export default {
    name: 'TableMixin',
    props: {
        pagination: Boolean,
        paginationAlign: String,
        pageSize:  {
            type: Number,
            default: function () {
                return 10
            }
        },
        paginationTotal: {
            type: Number,
            default: function () {
                return 10
            }
        },
        sizeChange: {
            type: Function,
            default: function(){
                return null
            }
        },
        pageChange: {
            type: Function,
            default: function(){
                return null
            }
        },
        pageLayout: {
            default: function (){
                return 'total, sizes, prev, pager, next, jumper'
            }
        }
    },
    computed: {

        align(){
            let res;
            switch(this.paginationAlign){
                case 'right':
                    res = 'textR'
                    break
                case 'center':
                    res = 'textC'
                    break
                default:
                    res = ''
            }
            return res
        }
    },
    methods:{
    	 ...mapMutations({
            setCurrentPage: 'pagination/setCurrentPage',
            setSizePage: 'pagination/setSizePage'
        }),
	    sizeChange1(val,params) {
	    	this.$emit('getPageSize',val)
	        this.setSizePage(val)
	        console.log(`每页 ${val} 条`);
	    },
	    //分页方法
	    pageChange1(val) {
	    	this.$emit('getCurrentPage',val)
	    	this.setCurrentPage(val)
	        console.log(`当前页: ${val}`);
	    }
    }
}
</script>
