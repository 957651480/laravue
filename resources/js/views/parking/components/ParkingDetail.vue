<template>
  <div class="createPost-container" style="margin-top: 20px">
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container">

      <div class="createPost-main-container">
        <el-row>
          <el-col :span="6">
            <el-form-item style="margin-bottom: 40px;" label-width="150px" label="楼盘:" prop="house_name">
              <el-input
                v-model="postForm.house_name"
                :rows="1"
                autosize
                :disabled="true"
                placeholder="请选择楼盘"
                style="width: 217px"
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
        <el-row>
          <el-col :span="8" >
            <el-form-item style="margin-bottom: 40px;" label-width="150px" label="车位编号:" prop="code">
              <el-input
                v-model="postForm.code"
                placeholder="请输入车位编号" style="width: 217px"
              />
            </el-form-item>
          </el-col>
          <el-col :span="6" >
            <el-form-item style="margin-bottom: 40px;" label-width="150px" label="车位价格:" prop="price">
              <el-input-number
                :min="1"
                v-model="postForm.price"
                placeholder="请输入车位价格"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="8">
            <el-form-item style="margin-bottom: 40px;" label-width="150px" label="车位类型:" prop="type_id">
              <el-select v-model="postForm.type_id" placeholder="选择类型" >
                <el-option v-for="item in types" :key="item.type_id" :label="item.name" :value="item.type_id" />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item style="margin-bottom: 40px;" label-width="150px" label="车位尺寸:" prop="size_id">
              <el-select v-model="postForm.size_id" placeholder="选择车位尺寸" >
                <el-option v-for="item in sizes" :key="item.size_id" :label="item.name" :value="item.size_id" />
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="8">
            <el-form-item style="margin-bottom: 40px;" label-width="150px" label="车位区域:" prop="parking_area_id">
              <el-select v-model="postForm.parking_area_id" placeholder="选择车位区域" >
                <el-option v-for="item in areas" :key="item.parking_area_id" :label="item.name" :value="item.parking_area_id" />
              </el-select>
              <router-link :to="`/parking/area-list`" class="link-type">
                <span>创建车位区域</span>
              </router-link>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item style="margin-bottom: 40px;" label-width="150px" label="车位楼层:" prop="parking_floor_id">
              <el-select v-model="postForm.parking_floor_id" placeholder="选择车位楼层" >
                <el-option v-for="item in floors" :key="item.parking_floor_id" :label="item.name" :value="item.parking_floor_id" />
              </el-select>
              <router-link :to="`/parking/floor-list`" class="link-type">
                <span>创建车位楼层</span>
              </router-link>
            </el-form-item>
          </el-col>
        </el-row>

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
import {fetchList as fetchAreaList} from "@/api/parking-area";
import {fetchList as fetchFloorList} from "@/api/parking-floor";


const defaultForm = {
  parking_id: undefined,
  code: '',
  price: null,
  house_id:null,
  type_id:null,
  size_id:null,
  parking_area_id:null,
  parking_floor_id:null,
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
        price: [{ required: true, message: '请填写车位价格', trigger: 'blur' }],
        type_id: [{ required: true, message: '请填写车位类型', trigger: 'blur' }],
        size_id: [{ required: true, message: '请填写车位尺寸', trigger: 'blur' }],
          parking_area_id: [{ required: true, message: '请填写车位区域', trigger: 'blur' }],
          parking_floor_id: [{ required: true, message: '请填写车位楼层', trigger: 'blur' }],
        desc: [{ required: true, message: '简介必须', trigger: 'blur' }],
      },
      tempRoute: {},
      image_list:[],
      showHouseDialog:false,
      sizes:[{size_id:10,name:'小车位'},{size_id:20,name:'大车位'}],
      types:[{'type_id':10,name:'认筹'},{'type_id':20,name:'竞拍'}],
      floors:[],
      areas:[],
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
    this.getAreaList();
    this.getFloorList();
    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    fetchData(id) {
      fetchParking(id)
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
    },
    async getAreaList() {
        const { data } = await fetchAreaList({limit:100});
        this.areas = data.list;
    },
    async getFloorList() {
        const { data } = await fetchFloorList({limit:100});
        this.floors = data.list;
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
