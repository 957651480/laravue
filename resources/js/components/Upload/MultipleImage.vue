<template>
 <div>
   <el-upload
     action="/api/file/upload"
     list-type="picture-card"
     :file-list="this.fileList"
     :on-preview="handlePictureCardPreview"
     :on-success="handleSuccess"
     :headers="headers"
     :on-remove="handleRemove">
     <i class="el-icon-plus"></i>
   </el-upload>
   <el-dialog :visible.sync="dialogVisible">
     <img width="100%" :src="dialogImageUrl" alt="">
   </el-dialog>
 </div>
</template>

<script>
    import {getToken} from "@/utils/auth";
export default {
  name: 'MultipleImage',
  props: {
    fileList: {
      type:Array,
      default: [],
    },
  },
  data() {
    return {
      dialogImageUrl: '',
      dialogVisible: false,
        headers: { Authorization: 'Bearer ' + getToken() }
    };
  },
  methods: {
    handleRemove(file, fileList) {

    },
      handleSuccess(response, file, fileList){
        debugger

          this.fileList.push({name:response.name,file_id: response.file_id, url: response.url, uid: this.fileList.length});
      },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    }
  }
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import "~@/styles/mixin.scss";

</style>
