<template>
<div class="area-select">
  <el-form-item label="地址:" :label-width="'100px'" prop="region">
    <el-select v-model="regionData.province" placeholder="请选择省" class="address_select" @change="chooseCity(regionData.province)" size="small">
      <el-option value="0" v-show="showDefault">{{title}}</el-option>
      <el-option v-for="item in provinces" :key="item.region_id" :label="item.name" :value="item.region_id">
      </el-option>
    </el-select>
    <el-select v-model="regionData.city" placeholder="请选择市" class="address_select" @change="chooseDistrict(regionData.city)" size="small">
      <el-option value="0" v-show="showDefault">{{title}}</el-option>
      <el-option v-for="item in cities" :key="item.region_id" :label="item.name" :value="item.region_id">
      </el-option>
    </el-select>
    <el-select v-model="regionData.district" placeholder="请选择区" class="address_select" size="small">
      <el-option value="0" v-show="showDefault">{{title}}</el-option>
      <el-option v-for="item in districts" :key="item.region_id" :label="item.name" :value="item.region_id">
      </el-option>
    </el-select>
  </el-form-item>
</div>
</template>

<script>
  import {fetchList} from "@/api/region";

  export default {
  name: "AreaSelect",
  data() {
    return {
      provinces: [],
      cities: [],
      districts: [],
      query: {
        page: 1,
        limit: 100,
        parent_id: 0,
        keyword: '',
      },
    };
  },
  props: {
    showDefault:{
      type:Boolean,
      default:false
    },
    title:{
      type:String,
      default:'请选择'
    },
    regionData:{
      province:{
        type: Number,
        default: null
      },
      city:{
        type: Number,
        default: null
      },
      district:{
        type: Number,
        default: null
      },
    },
    default:Object
  },
  methods: {
    getProvince() {
      this.getRegion('provinces')
    },
    getCity(id){
      this.getRegion('cities',{parent_id:id});
    },
    getDistrict(id){
      this.getRegion('districts',{parent_id:id});
    },
    chooseCity(id) {
      this.resetCity();
      this.resetDistrict();
      if(this.showDefault){
        if(id===0)return false;
      }
      this.getCity(id);
      this.updateData();
    },
    chooseDistrict(id) {
      this.resetDistrict();
      if(this.showDefault){
        if(id===0)return false;
      }
      this.updateData();
      this.getDistrict(id)
    },
    getRegion(key,query={}){
      var _this= this;
      let param = Object.assign(this.query,query);
      fetchList(param).then((response)=>{
        let {data} = response;
        _this[key]=data.list;
      })
    },
    resetCity(){
      this.cities=[];
      this.regionData.city=null;
    },
    resetDistrict(){
      this.districts=[];
      this.regionData.district=null;
    },
    updateData(){
      this.$emit("getRegionData", this.regionData);
    },
  },
  created() {
    this.getProvince();
    if(this.regionData.province){
        this.getCity(this.regionData.province)
    }
    if(this.regionData.city){
      this.getDistrict(this.regionData.city)
    }
  },
}
</script>

<style scoped>

</style>
