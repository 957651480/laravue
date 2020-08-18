<template>
<div v-bind="$attrs" class="multiple-upload">
    <el-upload
            :ref="$attrs.ref"
            v-bind="uploadAttrs"
            v-on="$listeners"
            :action="action"
            :headers="header"
            :before-upload="handleBeforeUpload"
            :on-success="handleSuccess"
            >
        <i class="el-icon-plus avatar-uploader-icon"></i>
    </el-upload>
</div>
</template>

<script>
    import {getToken} from "@/utils/auth";

    export default {
    name: "MultipleUpload",
    props:{
        action:{
            type:String,
            default:'/api/admin/file/upload'
        },
        header:{
            type:Object,
            default(){
                return { Authorization: 'Bearer ' + getToken() }
            }
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
    },

    data() {
        return {
            uploadAttrs: {},
            dialogVisible: false,
            disabled: false
        };
    },
    created(){
        this.$nextTick(() => {
            this.init()
        })
    },
    methods: {
        init() {
            this.uploadAttrs = this.$attrs;
        },
        handleSuccess(res, file,fileList) {

            let ids=[];
            let file_urls=[];
            fileList.forEach((item)=>{
                ids.push(item.response.data.id);
                file_urls.push(item.response.data.url);
            });
            this.$emit('input',ids);
            this.$emit('update:file-list',file_urls);

        },
        handleBeforeUpload(file) {
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
        handleRemove(file) {
            console.log(file);
        },
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            this.dialogVisible = true;
        },
        handleDownload(file) {
            console.log(file);
        }
    }
}
</script>

<style scoped>

</style>