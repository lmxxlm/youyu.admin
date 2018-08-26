<!--<template>
    <el-dialog
	  :title='confirmTitle'
	  :visible.sync="dialogVisible"
	  width="30%"
	  :before-close="handleClose">
	  <span>{{info}}</span>
	  <span slot="footer" class="dialog-footer">
	    <el-button @click="dialogVisible = false">取 消</el-button>
	    <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
	  </span>
	</el-dialog>
</template>

<script>
export default {
	props:{
		confirmTitle:{
			type:String,
			default:''
		},
		info:{
			type:String,
			default:''
		},
		dialogVisible:{
			type:Boolean,
			default:false
		}
	},
    data() {
      return {
        mes:''
      };
    },
    methods: {
      handleClose(done) {
        this.$confirm('确认关闭？')
          .then(_ => {
            done();
          })
          .catch(_ => {});
      }
    }
  };
</script>-->