<template>
  <div class="createPost-container" style="margin-top: 20px">
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container">

      <div class="createPost-main-container">
        <el-row>
          <el-col :span="6">
            <el-form-item style="margin-bottom: 40px;" label-width="80px" label="楼盘:" prop="house_name">
              <el-input
                v-model="postForm.house_name"
                :rows="1"
                autosize
                :disabled="true"
                placeholder="请选择楼盘"
              />
            </el-form-item>
          </el-col>
          <el-col :span="6" style="margin-left: 20px">
            <el-button @click="chooseHouse">选择楼盘</el-button>
          </el-col>
          <el-col :span="1" style="margin-left: 20px">
            <el-input type="hidden" v-model="postForm.house_id" ></el-input>
          </el-col>
        </el-row>
        <el-form-item style="margin-bottom: 40px;" label-width="80px" label="车位编号:" prop="code">
          <el-input
            v-model="postForm.code"
            :rows="1"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="请输入车位编号"
          />
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

    <el-dialog :visible.sync="showHouseDialog" title="选择楼盘">
      <HouseTableSearch v-model="postForm.house_id" @updateHouse="updateHouse"></HouseTableSearch>
    </el-dialog>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import {fetchParking, createParking, updateParking} from '@/api/parking';
import UploadImage from "@/components/Upload/UploadImage";
import HouseTableSearch from "@/views/house/components/HouseTableSearch";


const defaultForm = {
  parking_id: undefined,
  code: '',
  house_id:null,
  house_name:'',
};

export default {
  name: 'ParkingDetail',
  components: {
    UploadImage,
    Tinymce,
    HouseTableSearch
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
        house_name: [{ required: true, message: '请选择楼盘', trigger: 'blur' }],
        code: [{ required: true, message: '请填写车位编号', trigger: 'blur' }],
        desc: [{ required: true, message: '简介必须', trigger: 'blur' }],
      },
      tempRoute: {},
      image_list:[],
      showHouseDialog:false,
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
      fetchParking(id)
        .then(response => {

          let data = response.data;
          this.postForm.parking_id=data.parking_id;
          this.postForm.code=data.code;
          this.postForm.house_id=data.house_id;
          this.postForm.house_name=data.house_name;

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
        title: `${title}-${this.postForm.parking_id}`,
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
          let parking_id = this.postForm.parking_id;
          updateParking(parking_id,this.postForm).then(response => {

            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
            this.$router.push({
              path: '/parking',
            });
          }).catch(() => {
          })
        }else {
          createParking(this.postForm).then(response =>
          {
            this.$message({
              message:response.msg,
              type: 'success',
              duration: 5 * 1000,
            });
            this.$router.push({
              path: '/parking',
            });
          }).catch((error) => {
              debugger
          });
        }
        this.loading = false;
      });
    },
    chooseHouse(){
        this.showHouseDialog=true;
    },
    updateHouse(item){
        this.postForm.house_name=item.name;
        this.showHouseDialog=false;
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
