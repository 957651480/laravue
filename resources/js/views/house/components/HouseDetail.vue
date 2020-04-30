<template>
  <div class="createPost-container" style="margin-top: 20px">
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container">

      <div class="createPost-main-container">
        <el-form-item style="margin-bottom: 40px;" label-width="80px" label="标题:" prop="title">
          <el-input
            v-model="postForm.title"
            :rows="1"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="请输入标题"
          />
        </el-form-item>
        <el-form-item style="margin-bottom: 40px;" label-width="80px" label="简介:" prop="desc">
          <el-input
            v-model="postForm.desc"
            :rows="1"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="简介"
          />
        </el-form-item>
        <el-form-item label="图片:" prop="images">
          <upload-image :image-list="postForm.images"  @updateImageList="updateImageList"></upload-image>
        </el-form-item>
        <el-form-item label="区域:" prop="region">
          <area-select :region-data="postForm.regionData" @updateRegionData="updateRegionData"></area-select>
        </el-form-item>
        <el-form-item label="住户数:" prop="household">
            <el-input-number v-model="postForm.household"></el-input-number>
        </el-form-item>
        <el-form-item prop="content" style="margin-bottom: 30px;" label="详情:">
          <Tinymce ref="editor" v-model="postForm.content" :height="400" />
        </el-form-item>

        <el-button
          v-loading="loading"
          style="margin-left: 10px;"
          type="success"
          @click="submitForm"
        >
          保存
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import {fetchHouse, createHouse, updateHouse} from '@/api/house';
import AreaSelect from "@/components/AreaSelect/index";
import UploadImage from "@/components/Upload/UploadImage";
import SingleImage from "@/components/Upload/SingleImage";
const defaultForm = {
  house_id: undefined,
  title: '',
  desc: '',
  content: '',
  household:1,
  image:{},
  images:[],
  regionData:{}
};

export default {
  name: 'HouseDetail',
  components: {
    UploadImage,
    AreaSelect,
    Tinymce,
  },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      postForm: Object.assign({}, defaultForm),
      loading: false,
      rules: {
        title: [{ required: true, message: '标题必须', trigger: 'blur' }],
        desc: [{ required: true, message: '简介必须', trigger: 'blur' }],
        region: [{ required: true, message: '请填写授课时间段', trigger: 'blur' }],
        household: [{ required: true, message: '请填写人数', trigger: 'blur' }],
        images: [{ required: true, message: '请填写地点', trigger: 'blur' }],
        content: [{ required: true, message: '请填写课程详情', trigger: 'blur' }],
      },
      tempRoute: {},
    };
  },
  computed: {

    lang() {
      return this.$store.getters.language;
    },
  },
  created() {
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      this.fetchData(id);
    } else {
      this.postForm = Object.assign({}, defaultForm);
    }
    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    fetchData(id) {
      fetchHouse(id)
        .then(response => {
          this.postForm = response.data;
          // Set tagsview title
          this.setTagsViewTitle();
        })
        .catch(err => {
          console.log(err);
        });
    },
    setTagsViewTitle() {
      const title =
        this.lang === 'zh'
          ? '编辑课程'
          : this.lang === 'vi'
            ? 'Chỉnh sửa'
            : 'Edit Article'; // Should move to i18n
      const route = Object.assign({}, this.tempRoute, {
        title: `${title}-${this.postForm.id}`,
      });
      this.$store.dispatch('updateVisitedView', route);
    },
    submitForm()
    {
      this.$refs.postForm.validate(valid => {
        if (!valid) {
          return false;
        }
        this.loading = true;
        if(this.isEdit){
          let house_id = this.postForm.house_id;
          updateHouse(house_id,this.postForm).then(response => {

            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
            this.$router.push({
              path: '/course',
            });
          }).catch(() => {
          })
        }else {
          createHouse(this.postForm).then(response =>
          {
            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
            this.$router.push({
              path: '/course',
            });
          }).catch(() => {
          });
        }
        this.loading = false;
      });
    },

    updateImageList(data){
      this.postForm.images=data;
    },
    updateRegionData(data) {
      this.postForm.regionData=data;
    }
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import "~@/styles/mixin.scss";
.createPost-container {
  position: relative;
  .createPost-main-container {
    padding: 0 45px 20px 50px;
    .postInfo-container {
      position: relative;
      @include clearfix;
      margin-bottom: 10px;
      .postInfo-container-item {
        float: left;
      }
    }
  }
  .word-counter {
    width: 40px;
    position: absolute;
    right: -10px;
    top: 0px;
  }
}
</style>
<style>
.createPost-container label.el-form-item__label {
  text-align: left;
}
</style>
