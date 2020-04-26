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
        <el-row>
          <el-col :span="10">

            <el-form-item label-width="80px" label="分类:" class="postInfo-container-item" prop="category_id">
              <el-select
                v-model="postForm.category_id"
                placeholder="选择分类"
              >
                <el-option
                  v-for="item in categories"
                  :key="item.category_id"
                  :label="item.name"
                  :value="item.category_id"
                />
              </el-select>
              <span><router-link to="/category/list" class="link-type">去添加分类</router-link></span>
            </el-form-item>
          </el-col>
          <el-col :span="10">
            <el-form-item label-width="80px" label="教师:" class="postInfo-container-item" prop="teacher_id">
              <el-select
                v-model="postForm.teacher_id"
                placeholder="选择教师"
              >
                <el-option
                  v-for="item in teachers"
                  :key="item.teacher_id"
                  :label="item.name"
                  :value="item.teacher_id"
                />
              </el-select>
              <span><router-link to="/teacher/list" class="link-type">去添加教师</router-link></span>
            </el-form-item>
          </el-col>
        </el-row>

        <!--<el-form-item label="图片" prop="image_id">
          <el-upload
            class="avatar-uploader"
            :show-file-list="false"
            action="api/file/upload"
            :on-success="handleSuccess"
            :headers="myHeaders"
          >
            <img v-if="postForm.image_url" :src="postForm.image_url" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
          </el-upload>
        </el-form-item>-->
        <el-form-item label="授课日期:" prop="date">
          <el-date-picker
            v-model="postForm.date"
            type="date"
            placeholder="选择授课日期">
          </el-date-picker>
        </el-form-item>
        <el-row v-for="(item,key) in postForm.times" v-bind:key="key" prop="times">
          <el-col :span="2">
            <span>时间段{{key+1}}</span>
          </el-col>
          <el-col :span="5" >

            <el-form-item label="开始时间:" >
              <el-time-picker
                v-model="item.start_time"
                format="HH:mm"
                placeholder="选择时间">
              </el-time-picker>
            </el-form-item>
          </el-col>
          <el-col :span="5">
            <el-form-item label="结束时间:">
              <el-time-picker
                v-model="item.end_time"
                format="HH:mm"
                placeholder="选择时间">
              </el-time-picker>
            </el-form-item>
          </el-col>
          <i class="el-icon" @click="addTime()">添加</i>
          <i class="el-icon-delete" @click="deleteTime(key)">删除</i>
        </el-row>
        <el-form-item label="人数:" prop="number">
            <el-input-number v-model="postForm.number"></el-input-number>
        </el-form-item>
        <el-form-item style="margin-bottom: 40px;" label-width="80px" label="地点:" prop="address">
          <el-input
            v-model="postForm.address"
            :rows="1"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="请输入地点"
          />
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
          Submit
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Upload from '@/components/Upload/SingleImage';
import { fetchCourse,createCourse,updateCourse } from '@/api/course';
const categoryResource = new Resource('categories');
const teacherResource = new Resource('teachers');
import {getToken} from "@/utils/auth";
import { parseTime } from '@/utils';
import Resource from "@/api/resource";
const defaultForm = {
  course_id: undefined,
  title: '',
  desc: '',
  content: '',
  address: '',
  //image_url: '',
  //image_id: null,
  category_id:null,
  teacher_id:null,
  number:1,
  date:null,
  times: [{'start_time':null,'end_time':null}],
};

export default {
  name: 'CourseDetail',
  components: {
    Tinymce,
    Upload,
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
        category_id: [{ required: true, message: '请选择分类', trigger: 'blur' }],
        teacher_id: [{ required: true, message: '请选择教师', trigger: 'blur' }],
        //image_id: [{ required: true, message: '请选择上传图片', trigger: 'blur' }],
        date: [{ required: true, message: '请填写授课日期', trigger: 'blur' }],
        times: [{ required: true, message: '请填写授课时间段', trigger: 'blur' }],
        number: [{ required: true, message: '请填写人数', trigger: 'blur' }],
        address: [{ required: true, message: '请填写地点', trigger: 'blur' }],
        content: [{ required: true, message: '请填写课程详情', trigger: 'blur' }],
      },
      tempRoute: {},
      myHeaders: { Authorization: 'Bearer ' + getToken() },
      categories:[],
      teachers:[],
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
    this.getRemoteTeacherList();
    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    fetchData(id) {
      fetchCourse(id)
        .then(response => {
          this.postForm = response.data;
          // Set tagsview title
          this.setTagsViewTitle();
        })
        .catch(err => {
          console.log(err);
        });
    },
    /*handleSuccess(response, file, fileList){
      this.postForm.image_id = response.file_id;
      this.postForm.image_url = response.url;
    },*/
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

      let times = this.postForm.times;
      if(times.length===0){
        this.$alert('时间段至少选择一项');
        return false;
      }
      let valid=true;
      for (let i = 0; i < times.length; i++)
      {
        if(times[i].start_time===null||times[i].end_time===null){
          valid=false;
          break;
        }else{
          times[i].start_time = parseTime(times[i].start_time,'{h}:{i}');
          times[i].end_time = parseTime(times[i].end_time,'{h}:{i}');
        }
      }
      if(!valid){
          this.$alert('时间段未填写完整');
          return false;
      }
      this.postForm.times=times;
      this.postForm.date = parseTime(this.postForm.date,'{y}-{m}-{d}');
      this.$refs.postForm.validate(valid => {
        if (!valid) {
          return false;
        }
        this.loading = true;
        if(this.isEdit){
          let course_id = this.postForm.course_id;
          updateCourse(course_id,this.postForm).then(response => {

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
          createCourse(this.postForm).then(response => {

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
    async getRemoteCategoryList(){
      const { data } = await categoryResource.list({limit:100});
      this.categories = data.list;
    },
    async getRemoteTeacherList(){
        const { data } = await teacherResource.list({limit:100});
        this.teachers = data.list;
    },
    //添加时间段
    addTime(){
      this.postForm.times.push({})
    },
    //删除时间段
    deleteTime(index){
      if(index===0){
        this.$alert('不能删除第一项');
        return false;
      }
      this.postForm.times.splice(index,1);
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
<style>
.createPost-container label.el-form-item__label {
  text-align: left;
}
</style>
