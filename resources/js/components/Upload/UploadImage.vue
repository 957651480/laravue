<template>
 <div class="upload-image">
     <el-upload
       :action="'imagesUpload'"
       :http-request="uploadImage"
       accept="image/*"
       list-type="picture-card"
       :limit="limit"
       :on-success="handleImagesSuccess"
       :on-preview="handleImagePreview"
       :on-remove="handleImageRemove"
       :on-exceed="handleLimitTip"
       :file-list="fileList">
       <i class="el-icon-plus"></i>
     </el-upload>
     <el-dialog :visible.sync="dialogVisible" append-to-body>
       <img width="100%" :src.sync="dialogImageUrl" />
     </el-dialog>
 </div>
</template>

<script>
  import {uploadFile} from "@/api/file";

  export default {
  name: 'UploadImage',
  props: {
    imageList: {
      type:Array,
      default:function () {
          return [];
      },
    },
    limit:{
        type:String,
        default:'—'
    }
  },
  data() {
    return {
      dialogImageUrl: '',
      dialogVisible: false,
      fileList:this.imageList
    };
  },
  methods: {

    //【内容图删除事件】
    handleImageRemove: function (file, fileList) {
      this.fileList=fileList;
      this.$emit('updateImageList',this.fileList);
    },

    //【内容图片预览事件】
    handleImagePreview: function (file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleImagesSuccess(response, file, fileList)
    {
      let {data} = response;
      file = Object.assign(file,{file_id:data.file_id,name:data.name,url:data.url});
      this.fileList=fileList;
      this.$emit('updateImageList',this.fileList);
    },
    //上传内容图
    uploadImage: function (file) {
      var fd = new FormData();
      fd.append('file', file.file);
      uploadFile(fd).then((response)=>{
        file.onSuccess(response);
      }).catch(({msg})=>{
        file.onError()
      });
    },

    //内容图上传前的大小 格式的校验
    uploadImageBefore: function (file) {
      var fileType = file.type;
      var isJpg = false;
      if (fileType === 'image/jpeg' || fileType === 'image/png' || fileType === 'image/bmp') {
        isJpg = true;
      }

      if (!isJpg) {
        this.$message({
          message: '上传的图标只能是jpg、png、bmp格式!',
          type: 'warning'
        });
      }
      return isJpg;
    },
    handleLimitTip: function(files, fileList){
      this.$message({
        message: `只能上传${this.limit}张图片`,
        type: 'warning'
      });
    }
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import "~@/styles/mixin.scss";

</style>
