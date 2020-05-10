
<template>
  <div class="createPost-container" style="margin-top: 20px">
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container">

      <div class="createPost-main-container">
        <el-row>
          <el-col :span="6">
            <el-form-item style="margin-bottom: 40px;" label-width="150px" label="转盘:" prop="lottery_title">
              <el-input
                v-model="postForm.lottery_title"
                :rows="1"
                autosize
                :disabled="true"
                placeholder="请选择转盘"
                style="width: 217px"
              />
            </el-form-item>
          </el-col>
          <el-col :span="6" style="margin-left: 20px">
            <el-button @click="chooseLottery">选择转盘</el-button>
          </el-col>
          <el-col :span="1" style="margin-left: 20px">
            <el-input type="hidden" v-model="postForm.lottery_id" ></el-input>
          </el-col>
        </el-row>
        <el-form-item style="margin-bottom: 40px;" label-width="150px" label="奖品名称:" prop="name">
          <el-input
            v-model="postForm.name"
            :rows="1"
            class="article-textarea"
            autosize
            placeholder="奖品名称"
          />
        </el-form-item>
        <el-form-item style="margin-bottom: 40px;" label-width="150px" label="奖品等级:" prop="grade">
          <el-input
            v-model="postForm.grade"
            :rows="1"
            class="article-textarea"
            autosize
            placeholder="奖品等级"
          />
        </el-form-item>
        <el-form-item label="奖品图片:" prop="images" label-width="150px">
          <upload-image v-model="postForm.images" :image-list="image_list" ></upload-image>
        </el-form-item>
        <el-form-item label="数量" prop="number" label-width="150px">
          <el-input-number v-model="postForm.number"></el-input-number>
        </el-form-item>
        <el-form-item label="概率" prop="probability" label-width="150px">
          <el-input v-model="postForm.probability"></el-input>
        </el-form-item>
        <el-form-item label="排序:" label-width="150px" >
          <el-input-number v-model="postForm.sort"  ></el-input-number> <span> 值越大越靠前</span>
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
    <el-dialog :visible.sync="showLotteryDialog" title="选择转盘">
      <LotteryTableSearch v-model="postForm.lottery_id" @updateLottery="updateLottery"></LotteryTableSearch>
    </el-dialog>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import {fetchLotteryPrize, createLotteryPrize, updateLotteryPrize} from '@/api/lottery-prize';
import UploadImage from "@/components/Upload/UploadImage";
import LotteryTableSearch from "@/views/lottery/components/LotteryTableSearch";


const defaultForm = {
  lottery_prize_id: undefined,
  lottery_id:null,
  lottery_title:'',
  name: '',
  grade: '',
  images:[],
  number: 1,
  probability:0,
  sort:0,
};

export default {
  name: 'LotteryPrizeDetail',
  components: {
    UploadImage,
    Tinymce,
    LotteryTableSearch
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
        lottery_id: [{ required: true, message: '请选择转盘', trigger: 'blur' }],
        name: [{ required: true, message: '名称必须', trigger: 'blur' }],
        grade: [{ required: true, message: '等级必须', trigger: 'blur' }],
        number: [{ required: true, message: '数量必须', trigger: 'blur' }],
        probability: [{ required: true, message: '概率必须', trigger: 'blur' }],
        images: [{ required: true, message: '请上传图片', trigger: 'blur' }],
      },
      tempRoute: {},
      image_list:[],
        showLotteryDialog:false
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
      fetchLotteryPrize(id)
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
        title: `${title}-${this.postForm.lottery_prize_id}`,
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
          let lottery_prize_id = this.postForm.lottery_prize_id;
          updateLotteryPrize(lottery_prize_id,this.postForm).then(response => {

            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
            this.$router.push({
              path: '/lottery_prize',
            });
          }).catch(() => {
          })
        }else {
          createLotteryPrize(this.postForm).then(response =>
          {
            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
            this.$router.push({
              path: '/lottery_prize',
            });
          }).catch((error) => {
              debugger
          });
        }
        this.loading = false;
      });
    },
      chooseLottery(){
          this.showLotteryDialog=true;
      },
      updateLottery(item){
          this.postForm.lottery_title=item.title;
          this.showLotteryDialog=false;
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
