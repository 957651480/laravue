<template>
<div class="house-table-search">
    <div class="filter-container">
      <el-input v-model="query.keyword" placeholder="请输入楼盘名称" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">搜索</el-button>
    </div>
    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column min-width="200px" label="楼盘名称">
        <template slot-scope="{row}">
          <router-link :to="'/house/edit/'+row.house_id" class="link-type">
            <span>{{ row.name }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column min-width="80px" label="住户数">
        <template slot-scope="scope">
          <span>{{ scope.row.household }}</span>
        </template>
      </el-table-column>

      <el-table-column width="180px" align="center" label="区域">
        <template slot-scope="scope">
          <span>{{ scope.row.region_merger_name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="350">
        <template slot-scope="scope">

          <el-button type="success" size="small" class="el-icon-check" @click="handleChoose(scope.row)">
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
import { fetchList } from '@/api/house';
export default {
    name: "HouseTableSearch",
    props: ['value'],
    components: { Pagination },
    directives: { waves },
    data() {
        return {
            list: null,
            loading:false,
            total: 0,
            query: {
                page: 1,
                limit: 15,
                keyword: '',
                category_id:''
            },
        };
    },
    mounted() {
        this.getList();
    },
    methods: {
        async getList() {
            const {limit, page} = this.query;
            this.loading = true;
            const {data} = await fetchList(this.query);
            this.list = data.list;
            this.list.forEach((element, index) => {
                element['index'] = (page - 1) * limit + index + 1;
            });
            this.total = data.total;
            this.loading = false;
        },
        handleFilter() {
            this.query.page = 1;
            this.getList();
        },
        handleChoose(item){
            this.$emit('input',item.house_id);
            this.$emit('updateHouse',item);
        },

    }
}
</script>

<style scoped>

</style>
