<template>
  <div class="createPost-container" style="margin-top: 20px">
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container">

      <div class="createPost-main-container">
        <el-form-item style="margin-bottom: 40px;" label-width="120px" label="标题:" prop="title">
          <el-input
            v-model="postForm.title"
            :rows="1"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="请输入标题"
          />
        </el-form-item>
        <el-form-item style="margin-bottom: 40px;" label-width="120px" label="简介:" prop="desc">
          <el-input
            v-model="postForm.desc"
            :rows="1"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="请输入简介"
          />
        </el-form-item>
        <el-row>
          <el-col :span="8">
            <el-form-item style="margin-bottom: 40px;" label-width="120px" label="开始时间:" prop="start_time">
              <el-date-picker
                v-model="postForm.start_time"
                type="datetime"
                placeholder="选择开始时间"
              >
              </el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item style="margin-bottom: 40px;" label-width="120px" label="结束时间:" prop="end_time">
              <el-date-picker
                v-model="postForm.end_time"
                type="datetime"
                placeholder="选择结束时间"
              >
              </el-date-picker>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item label="图片:" prop="images" label-width="120px">
          <upload-image v-model="postForm.images" :image-list="image_list" ></upload-image>
        </el-form-item>
        <el-row>
          <el-col :span="10">
            <el-form-item label="排序:" label-width="120px" >
              <el-input-number v-model="postForm.sort"  ></el-input-number> <span> 值越大越靠前</span>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label-width="120px" label="推荐:" prop="lottery_recommend">
              <el-radio v-model="postForm.lottery_recommend" :label="10">是</el-radio>
              <el-radio v-model="postForm.lottery_recommend" :label="20">否</el-radio>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="10">
            <el-form-item label-width="120px" label="状态:" prop="status_id">
              <el-select v-model="postForm.status_id" placeholder="请选择转盘状态">
                <el-option
                  v-for="item in all_status"
                  :key="item.status_id"
                  :label="item.name"
                  :value="item.status_id">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
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
import {fetchLottery, createLottery, updateLottery} from '@/api/lottery';
import UploadImage from "@/components/Upload/UploadImage";
import {arrayColumn} from "@/utils";
const defaultForm = {
  lottery_id: undefined,
  title: '',
  desc: '',
  start_time: '',
  end_time: '',
  content: '',
  images:[],
  sort:0,
  lottery_recommend:20,
  status_id:10,
};

export default {
  name: 'LotteryDetail',
  components: {
    UploadImage,
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
        start_time: [{ required: true, message: '开始时间必须', trigger: 'blur' }],
        end_time: [{ required: true, message: '结束时间必须', trigger: 'blur' }],
        images: [{ required: true, message: '请上传图片', trigger: 'blur' }],
        content: [{ required: true, message: '请填写详情', trigger: 'blur' }],
        status_id: [{ required: true, message: '状态必填', trigger: 'blur' }],
      },
      tempRoute: {},
      image_list:[],
      all_status:[{status_id:10,name:'未开始'},{status_id:20,name:'进行中'},{status_id:30,name:'已结束'}],
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
      fetchLottery(id)
        .then(response => {

          this.postForm = response.data;
          this.image_list=response.data.image_list;
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
        title: `${title}-${this.postForm.lottery_id}`,
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
          let lottery_id = this.postForm.lottery_id;
          updateLottery(lottery_id,this.postForm).then(response => {

            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
            this.$router.push({
              path: '/lottery',
            });
          }).catch(() => {
          })
        }else {
          createLottery(this.postForm).then(response =>
          {
            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
            this.$router.push({
              path: '/lottery',
            });
          }).catch((error) => {
              debugger
          });
        }
        this.loading = false;
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
