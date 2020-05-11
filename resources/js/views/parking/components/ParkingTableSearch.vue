<template>
  <div class="app-container">
    <el-form :inline="true" >
      <el-form-item label="车位编号:">
        <el-input v-model="query.code" placeholder="请输入车位编号搜索" clearable style="width: 200px;" @change="handleFilter" class="filter-item" @keyup.enter.native="handleFilter" />
      </el-form-item>
      <el-form-item label="楼盘名称:">
        <el-input v-model="query.house_name" placeholder="请输入楼盘名称搜索" clearable style="width: 200px;" @change="handleFilter" class="filter-item" @keyup.enter.native="handleFilter" />
      </el-form-item>
      <!--<el-form-item label="车位分类:">
        <el-select v-model="query.type_id" placeholder="选择分类" clearable style="width: 90px" class="filter-item" @change="handleFilter">
          <el-option v-for="item in types" :key="item.type_id" :label="item.name" :value="item.type_id" />
        </el-select>
      </el-form-item>-->
      <el-form-item label="车位尺寸:">
        <el-select v-model="query.size_id" placeholder="选择车位尺寸" clearable style="width: 90px" class="filter-item" @change="handleFilter">
          <el-option v-for="item in sizes" :key="item.size_id" :label="item.name " :value="item.size_id" />
        </el-select>
      </el-form-item>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
    </el-form>
    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.parking_id }}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="200px" label="编号">
        <template slot-scope="{row}">
          <router-link :to="'/parking/edit/'+row.parking_id" class="link-type">
            <span>{{ row.code }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column min-width="100px" label="类型">
        <template slot-scope="{row}">
          <span>{{ row.type_name }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="100px" label="尺寸">
        <template slot-scope="{row}">
          <span>{{ row.size_name }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="100px" label="价格">
        <template slot-scope="{row}">
            <span>{{ row.price }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="100px" label="定金">
        <template slot-scope="{row}">
          <span>{{ row.handsel }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="100px" label="区域">
        <template slot-scope="{row}">
          <span>{{ row.parking_area_name }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="100px" label="楼层">
        <template slot-scope="{row}">
          <span>{{ row.parking_floor_name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="楼盘名称">
        <template slot-scope="scope">
          <span>{{ scope.row.house_name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="所属城市">
        <template slot-scope="scope">
          <span>{{ scope.row.city_name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="作者">
        <template slot-scope="scope">
          <span>{{ scope.row.author_name }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="80px" label="发布时间">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="350">
        <template slot-scope="scope">
          <el-button type="success" size="small" icon="el-icon-check" @click="handleChoose(scope.row)">
            选择
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import waves from '@/directive/waves';

import { fetchList,deleteParking } from '@/api/parking';


export default {
  name: 'ParkingTableSearch',
  components: { Pagination },
  directives: { waves },
  props: ['value'],
  data() {
    return {
      list: null,
      total: 0,
      downloading: false,
      query: {
        page: 1,
        limit: 15,
        code: '',
        house_name: '',
        type_id:20,
        size_id:'',
      },
      categories:[],
      sizes:[{size_id:10,name:'小车位'},{size_id:20,name:'大车位'}],
      types:[{'type_id':10,name:'认筹'},{'type_id':20,name:'竞拍'}],
    };
  },
  mounted() {
    this.getList();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data } = await fetchList(this.query);
      this.list = data.list;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total=data.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleChoose(item){
      this.$emit('input',item.parking_id);
      this.$emit('updateParking',item);
    }
  },
};
</script>

<style scoped>
.edit-input {
  padding-right: 100px;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
</style>
