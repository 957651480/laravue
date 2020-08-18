<template>
<div class="single-upload">
    <el-upload
            class="avatar-uploader"
            v-model="value"
            action="/api/admin/file/upload"
            :show-file-list="false"
            :headers="myHeaders"
            :on-success="handleAvatarSuccess"
            :before-upload="beforeAvatarUpload">
        <img v-if="elFileUrl" :src.sync="elFileUrl" class="avatar">
        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
    </el-upload>
</div>

</template>

<script>
import {getToken} from "@/utils/auth";

export default {
    name: "SingleUpload",
    props:{
        value: {
            type: Number,
            default: 0
        },
        extension: {
            type: Array,
            default(){
                return []
            }
        },
        size: {
            type: Number,
            default: 0
        },
        img_url:{
            type: String,
            default: ''
        }
    },
    data() {
        return {
            elFileUrl:this.img_url,
            myHeaders: { Authorization: 'Bearer ' + getToken() },
        };
    },
    watch:{
        img_url(val){
            this.elFileUrl=this.img_url;
        }
    },
    methods: {
        handleAvatarSuccess(res, file) {
            let {data}=res;
            this.elFileUrl = data.url;
            this.$emit('input',data.id);
            this.$emit('update:img_url',data.url);

        },
        beforeAvatarUpload(file) {
            let length = this.extension.length;
            if (length) {
                let allow_extension = this.extension.some(item=>{
                    return item===file.type;
                });
                if(!allow_extension){
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                    return false;
                }
            }
            if (this.size) {
                if(file.size>this.size){
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                    return false;
                }

            }
            return true;
        },


    }
}
</script>

<style >
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }
    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
</style>
