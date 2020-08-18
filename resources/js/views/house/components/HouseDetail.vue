<template>
  <div class="createPost-container" style="margin-top: 20px">
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container" label-width="217px">

      <div class="createPost-main-container">


        <el-form-item style="margin-bottom: 40px;" label-width="150px" label="名称:" prop="name">
          <el-input
            v-model="postForm.name"
            :rows="1"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="请输入名称"
          />
        </el-form-item>
        <el-form-item label-width="150px" label="简介:" prop="desc">
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
          <el-col :span="10">
            <el-form-item label-width="150px" label="型号:" prop="desc">
              <el-input
                      v-model="postForm.desc"
                      placeholder="请输入型号"
                      style="width:217px"
              />
            </el-form-item>
          </el-col>

          <el-col :span="6" >
            <el-form-item  label-width="150px" label="品牌:" prop="brand_id">
              <el-select v-model="postForm.brand_id" >
                <el-option
                        v-for="item in brandList"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="10">
            <el-form-item label-width="150px" label="出厂日期:" prop="date_of_manufacture">
              <el-date-picker
                      v-model="postForm.date_of_manufacture"
                      type="date"
                      placeholder="选择日期">
              </el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="6" >
            <el-form-item  label-width="150px" label="使用时长:" prop="duration_of_use">
              <el-input-number v-model="postForm.duration_of_use"></el-input-number>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item  label-width="150px" label="设备手术:" prop="operation">
          <el-input v-model="postForm.operation" style="width:217px"></el-input>
        </el-form-item>
        <el-row>
          <el-col :span="10">
            <el-form-item label-width="150px" label="图片:" prop="images">
              <upload-image v-model="postForm.images" :file_url="fileList" ></upload-image>
            </el-form-item>
          </el-col>
          <el-col :span="10">
            <el-form-item label-width="150px" label="车位分布图:" prop="parking_images">
              <upload-image v-model="postForm.parking_images" :file_url="parking_image_list" ></upload-image>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="10">
            <el-form-item  label-width="150px" label="区域" prop="region_id">
              <el-cascader
                v-model="postForm.region_id"
                :props="optionProps"
                :options="regionTrees"
              ></el-cascader>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label-width="150px" label="住户数:" prop="household">
              <el-input-number v-model="postForm.household"></el-input-number>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row v-for="(item, index) in postForm.sales" :key="index">
          <el-col :span="10" >

              <el-form-item label-width="150px"
                :label="`销售姓名`"
                :prop="'sales.' + index + '.name'"
                :rules="{
            required: true, message: '销售姓名不能为空', trigger: 'blur'
        }"
              >
                <el-input v-model="item.name" style="width:217px"></el-input>
              </el-form-item>

          </el-col>
          <el-col :span="6">
            <el-form-item label-width="150px"
              :label="`销售手机号`"
              :prop="'sales.' + index + '.phone'"
              :rules="[
             {required: true, message: '销售手机号不能为空', trigger: 'blur'},
             { pattern: /^1[34578]\d{9}$/, message: '目前只支持中国大陆的手机号码' }
          ]"
            >
              <el-input v-model="item.phone" style="width:217px"></el-input>
            </el-form-item>

          </el-col>
          <el-col :span="2" >
            <el-button type="primary" icon="el-icon-plus" @click="addSaleItem(item, index)">添加</el-button>
          </el-col>
          <el-col :span="2">
              <el-button type="danger" icon="el-icon-delete" @click="deleteSaleItem(item, index)">删除</el-button>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="10">
            <el-form-item label-width="150px" label="车位配比:" prop="rate">
              <el-input v-model="postForm.rate" style="width:217px"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label-width="150px" label="楼盘推荐:" prop="house_status">
              <el-radio v-model="postForm.house_recommend" :label="10">是</el-radio>
              <el-radio v-model="postForm.house_recommend" :label="20">否</el-radio>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="10">
            <el-form-item label-width="150px" label="楼盘状态:" prop="house_status">
              <el-radio v-model="postForm.house_status" :label="10">上架</el-radio>
              <el-radio v-model="postForm.house_status" :label="20">下架</el-radio>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="排序:" label-width="150px" >
              <el-input-number v-model="postForm.sort"  ></el-input-number> <span> 值越大越靠前</span>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item label-width="150px" label="楼盘定位:" prop="map.address">
          <el-input v-model="postForm.map.address" placeholder="请在地图上定位地址" disabled style="width:500px"></el-input>
        </el-form-item>
        <el-form-item >
          <gould-map   v-model="postForm.map" ></gould-map>
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
import {arrayColumn} from "@/utils";
import {fetchTreeList} from "@/api/region";
import {fetchList as fetchBrandList} from "@/api/brand";

import GouldMap from "@/components/Map/Gould/index";
const defaultForm = {
  id: undefined,
  name: '',
  desc: '',
  model: '',
  brand_id: null,
  date_of_manufacture: null,
  duration_of_use:null,
  operation:'',
  content: '',
  household:1,
  rate:'',
  house_status:10,
  images:[],
  parking_images:[],
  region_id:null,
  sales:[{name:'',phone:''}],
  house_recommend:20,
  map:{lng:null,lat:null,address:null},
  sort:0
};

export default {
  name: 'HouseDetail',
  components: {
    GouldMap,
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
        name: [{ required: true, message: '名称必须', trigger: 'blur' }],
        desc: [{ required: true, message: '简介必须', trigger: 'blur' }],
        region_id: [{ required: true, message: '请选择地区', trigger: 'blur' }],
        household: [{ required: true, message: '请填写人数', trigger: 'blur' }],
        rate: [{ required: true, message: '请填写车位配比', trigger: 'blur' }],
        house_status: [{ required: true, message: '请选择楼盘上下架状态', trigger: 'blur' }],
        house_recommend: [{ required: true, message: '请选择是否推荐', trigger: 'blur' }],
        images: [{ required: true, message: '请上传图片', trigger: 'blur' }],
        "map.address": [{ required: true, message: '请确定定位', trigger: 'blur' }],
        parking_images: [{ required: true, message: '请上传车位分布图', trigger: 'blur' }],
        content: [{ required: true, message: '请填写详情', trigger: 'blur' }],
      },
      tempRoute: {},
      image_list:[],
      optionProps:{
          value: 'region_id',
          label: 'name',
          emitPath:false
      },
      regionTrees:[],
      fileList:[],
      parking_image_list:[],
      brandList:[]
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
    this.getRegionList();
    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    this.tempRoute = Object.assign({}, this.$route);
    this.getBrandList();
  },
  methods: {
    fetchData(id) {
      fetchHouse(id)
        .then(response => {

          let {data} = response.data;
          this.postForm=data;
          // Set tagsview title
          this.setTagsViewTitle();
        })
        .catch(err => {
          console.log(err);
        });
    },
    setTagsViewTitle() {
      const title ='编辑'
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
              path: '/house',
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
              path: '/house',
            });
          }).catch((error) => {
              debugger
          });
        }
        this.loading = false;
      });
    },

    updateImageList(data){
      this.image_list=data;
      debugger
      this.postForm.images=arrayColumn(data,'file_id');
    },
    async getRegionList() {
        const { data } = await fetchTreeList();
        this.regionTrees = this.getTreeData(data.list);
    },
    // 递归判断列表，把最后的children设为undefined
    getTreeData(data){
        for(let i=0;i<data.length;i++){
            if(data[i].children.length<1){
                // children若为空数组，则将children设为undefined
                data[i].children=undefined;
            }else {
                // children若不为空数组，则继续 递归调用 本方法
                this.getTreeData(data[i].children);
            }
        }
        return data;
    },
    addSaleItem(){
        this.postForm.sales.push({name:'',phone:''})
    },
    deleteSaleItem(item, index){
        if(index===0){
            this.$alert('不能删除第一项');
            return false;
        }
        this.postForm.sales.splice(index, 1)
    },
    async getBrandList() {
      const { data } = await fetchBrandList();
      this.brandList = data;
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
