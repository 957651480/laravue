<template>
  <div class="single-image">
    <el-upload
      :action="'imageUpload'"
      :http-request="uploadImage"
      accept="image/*"
      :limit="1"
      list-type="picture-card"
      :on-success="handleImagesSuccess"
      :on-preview="handleImagePreview"
      :on-remove="handleImageRemove"
      :on-exceed="handleLimitTip"
      :file-list="[image]">
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
  name: 'SingleImage',
  props: {
    imageObject: {
      type:Object,
      default:function () {
        return {};
      },
    },
  },
  data() {
    return {
      dialogImageUrl: '',
      dialogVisible: false,
      image:this.imageObject,
    };
  },
  methods: {
    //【内容图删除事件】
    handleImageRemove: function (file, fileList) {
      this.image=file;
      this.$emit('updateImage',this.image);
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
      this.image=file;
      this.$emit('updateImage',this.image);
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
        message: '只能上传一张图片',
        type: 'warning'
      });
    }
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import "~@/styles/mixin.scss";
.upload-container {
  width: 100%;
  position: relative;
  @include clearfix;
  .image-uploader {
    width: 35%;
    float: left;
  }
  .image-preview {
    width: 200px;
    height: 200px;
    position: relative;
    border: 1px dashed #d9d9d9;
    float: left;
    margin-left: 50px;
    .image-preview-wrapper {
      position: relative;
      width: 100%;
      height: 100%;
      img {
        width: 100%;
        height: 100%;
      }
    }
    .image-preview-action {
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      cursor: default;
      text-align: center;
      color: #fff;
      opacity: 0;
      font-size: 20px;
      background-color: rgba(0, 0, 0, .5);
      transition: opacity .3s;
      cursor: pointer;
      text-align: center;
      line-height: 200px;
      .el-icon-delete {
        font-size: 36px;
      }
    }
    &:hover {
      .image-preview-action {
        opacity: 1;
      }
    }
  }
  .image-app-preview {
    width: 320px;
    height: 180px;
    position: relative;
    border: 1px dashed #d9d9d9;
    float: left;
    margin-left: 50px;
    .app-fake-conver {
      height: 44px;
      position: absolute;
      width: 100%; // background: rgba(0, 0, 0, .1);
      text-align: center;
      line-height: 64px;
      color: #fff;
    }
  }
}
</style>
