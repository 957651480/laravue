<template>
  <el-upload
    :action=""
    list-type="picture-card"
    :on-preview="handlePictureCardPreview"
    on-success="handleSuccess"
    :on-remove="handleRemove">
    <i class="el-icon-plus"></i>
  </el-upload>
  <el-dialog :visible.sync="dialogVisible">
    <img width="100%" :src="dialogImageUrl" alt="">
  </el-dialog>
  <script>
    export default {
      data() {
        return {
          dialogImageUrl: '',
          dialogVisible: false
        };
      },
      methods: {
        handleRemove(file, fileList) {
          console.log(file, fileList);
        },
        handlePictureCardPreview(file) {
          this.dialogImageUrl = file.url;
          this.dialogVisible = true;
        },
        handleSuccess(response, file, fileList){
          fileList.push(response.url);
        }
      }
    }
  </script>
</template>

<script>
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
      dialogVisible: false
    };
  },
  methods: {
    handleRemove(file, fileList) {
      console.log(file, fileList);
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
