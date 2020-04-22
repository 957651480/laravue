<template>
  <div class="createPost-container" style="margin-top: 20px">
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container">

      <div class="createPost-main-container">
        <el-form-item style="margin-bottom: 40px;" label-width="80px" label="标题:">
          <el-input
            v-model="postForm.title"
            :rows="1"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="请输入标题"
          />
        </el-form-item>
        <el-form-item label-width="80px" label="分类:" class="postInfo-container-item">
          <el-select
            v-model="postForm.category_id"
            placeholder="选择分类"
          >
            <el-option
              v-for="(item,index) in postForm.categories"
              :key="index"
              :label="item.name"
              :value="item.category_id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="上传图片">
          <el-upload
            action="api/file/upload"
            list-type="picture-card"
            :on-success="handleSuccess"
            :headers="myHeaders"
          >
            <i class="el-icon-plus" />
          </el-upload>
          <el-dialog :visible.sync="postForm.dialogVisible">
            <img width="100%" :src.sync="postForm.image_url" alt="">
          </el-dialog>
        </el-form-item>
        <el-row>
          <el-col :span="10">
            <el-form-item
              label-width="120px"
              label="开始时间:"
              class="postInfo-container-item"
            >
              <el-date-picker
                v-model="postForm.start_time"
                type="datetime"
                format="yyyy-MM-dd HH:mm"
                placeholder="请选择开始时间"
              />
            </el-form-item>
          </el-col>
          <el-col :span="10">
            <el-form-item
              label-width="120px"
              label="结束时间:"
              class="postInfo-container-item"
            >
              <el-date-picker
                v-model="postForm.end_time"
                type="datetime"
                format="yyyy-MM-dd HH:mm"
                placeholder="请选择结束时间"
              />
            </el-form-item>
          </el-col>
        </el-row>

        <el-form-item style="margin-bottom: 40px;" label-width="80px" label="地址:">
          <el-input
            v-model="postForm.address"
            :rows="1"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="请输入地址"
          />
        </el-form-item>

        <el-form-item prop="content" style="margin-bottom: 30px;">
          <Tinymce ref="editor" v-model="postForm.content" :height="400" />
        </el-form-item>

        <el-button
          v-loading="loading"
          style="margin-left: 10px;"
          type="success"
          @click="submitForm"
        >
          Submit
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Upload from '@/components/Upload/SingleImage';
import MDinput from '@/components/MDinput';
import Sticky from '@/components/Sticky'; // Sticky header
import { validURL } from '@/utils/validate';
import { fetchCourse,createCourse,updateCourse } from '@/api/course';
import { userSearch } from '@/api/search';
const categoryResource = new Resource('categories');
import {
  CommentDropdown,
  PlatformDropdown,
  SourceUrlDropdown,
} from './Dropdown';
import {getToken} from "@/utils/auth";
import { parseTime } from '@/utils';
import Resource from "@/api/resource";
const defaultForm = {
  course_id: undefined,
  title: '',
  content: '',
  address: '',
  image_url: '',
  image_id: 0,
  start_time:undefined,
  end_time:undefined,
  dialogVisible:false,
  category_id:'',
  categories:[]
};

export default {
  name: 'CourseDetail',
  components: {
    Tinymce,
    MDinput,
    Upload,
    Sticky,
    CommentDropdown,
    PlatformDropdown,
    SourceUrlDropdown,
  },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        this.$message({
          message: rule.field + ' is required',
          type: 'error',
        });
        callback(new Error(rule.field + ' is required'));
      } else {
        callback();
      }
    };
    const validateSourceUri = (rule, value, callback) => {
      if (value) {
        if (validURL(value)) {
          callback();
        } else {
          this.$message({
            message: 'External URL is invalid.',
            type: 'error',
          });
          callback(new Error('External URL is invalid.'));
        }
      } else {
        callback();
      }
    };
    return {
      postForm: Object.assign({}, defaultForm),
      loading: false,
      userListOptions: [],
      rules: {
        image_uri: [{ validator: validateRequire }],
        title: [{ validator: validateRequire }],
        content: [{ validator: validateRequire }],
        source_uri: [{ validator: validateSourceUri, trigger: 'blur' }],
      },
      tempRoute: {},
      dialogImageUrl: '',
      dialogVisible: false,
      myHeaders: { Authorization: 'Bearer ' + getToken() },
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
    this.getRemoteCategoryList();
    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    fetchData(id) {
      fetchCourse(id)
        .then(response => {
          this.postForm.dialogVisible=true;
          this.postForm = response.data;

          // Just for tes

          // Set tagsview title
          this.setTagsViewTitle();
        })
        .catch(err => {
          console.log(err);
        });
    },
    handleSuccess(response, file, fileList){
      this.postForm.image_id = response.file_id;
      this.postForm.image_url = response.url;
      debugger;
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
    submitForm() {

      this.postForm.start_time = parseTime(this.postForm.start_time);
      this.postForm.end_time = parseTime(this.postForm.end_time);

      this.$refs.postForm.validate(valid => {
        if (!valid) {
          return false;
        }
        this.loading = true;
        if(this.isEdit){
          updateCourse(this.postForm).then(response => {

            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
          }).catch(() => {
          })
        }else {
          createCourse(this.postForm).then(response => {

            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
          }).catch(() => {
          });
        }
        this.loading = false;
      });
    },
    async getRemoteCategoryList(){
      const { data } = await categoryResource.list({limit:100});
      this.postForm.categories = data.list;
    },
    getRemoteUserList(query) {
      userSearch(query).then(response => {
        if (!response.data.items) {
          return;
        }
        this.userListOptions = response.data.items.map(v => v.name);
      });
    },
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
